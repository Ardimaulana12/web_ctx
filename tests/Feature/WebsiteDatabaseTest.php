<?php

namespace Tests\Feature;

use App\Models\Bahasa;
use App\Models\HalamanWebsite;
use App\Models\Kontak;
use App\Models\KontenWebsite;
use App\Models\MenuNavigasi;
use Database\Seeders\WebsiteSeeder;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class WebsiteDatabaseTest extends TestCase
{
    use DatabaseTransactions;

    public function test_seed_is_repeatable_and_preserves_existing_edits_and_other_tables(): void
    {
        $language = Bahasa::where('kode_bahasa', 'en')->firstOrFail();
        $language->kontak()->firstOrFail()->update(['email' => 'edited@example.com']);
        MenuNavigasi::firstOrFail()->update(['nama_menu' => 'Edited menu']);
        $content = $language->konten()->where('judul', 'page')->firstOrFail();
        $content->update(['meta_title' => 'Edited title']);
        $tables = ['bahasa', 'halaman_website', 'konten_website', 'menu_navigasi', 'kontak', 'hubungi_kami', 'users'];
        $snapshot = fn () => collect($tables)->mapWithKeys(fn ($table) => [$table => DB::table($table)->get()->toJson()])->all();
        $before = $snapshot();
        $this->seed(WebsiteSeeder::class);
        $this->seed(WebsiteSeeder::class);
        $this->assertSame($before, $snapshot());
    }

    public function test_all_erd_relationships_resolve(): void
    {
        foreach (Bahasa::with(['konten.halaman.menu', 'kontak'])->get() as $language) {
            $this->assertCount(7, $language->konten);
            foreach ($language->konten as $content) {
                $this->assertTrue($content->bahasa->is($language));
                $this->assertTrue($content->halaman->konten->contains($content));
                foreach ($content->halaman->menu as $menu) {
                    $this->assertTrue($menu->halaman->is($content->halaman));
                }
            }
            $this->assertTrue($language->kontak->first()->bahasa->is($language));
        }
    }

    public function test_database_edits_drive_copy_lists_seo_menu_and_contacts_without_affecting_other_language(): void
    {
        $english = Bahasa::where('kode_bahasa', 'en')->firstOrFail();
        $shared = $english->konten()->where('judul', 'shared')->firstOrFail();
        $payload = $shared->isi;
        $payload['translations']['Understand your world.'] = 'Database headline';
        $payload['services'][0][0] = 'Database service';
        $shared->update(['isi' => $payload]);
        $home = HalamanWebsite::where('nama_halaman', 'home')->firstOrFail();
        $page = $home->konten()->where('id_bahasa', $english->id_bahasa)->where('judul', 'page')->firstOrFail();
        $payload = $page->isi;
        $payload['sections']['list_2'] = ['42' => 'Database figure'];
        $page->update(['isi' => $payload, 'meta_title' => 'Database SEO title', 'meta_description' => 'Database SEO description']);
        $home->menu()->firstOrFail()->update(['nama_menu' => 'Database menu']);
        $english->kontak()->firstOrFail()->update(['email' => 'database@example.com', 'alamat' => 'Database office', 'telepon' => '628123456789']);
        $this->withSession(['locale' => 'en'])->get('/')
            ->assertOk()->assertSee('Database headline')->assertSee('Database service')
            ->assertSee('Database figure')->assertSee('data-count="42"', false)
            ->assertSee('<title>Database SEO title</title>', false)->assertSee('Database SEO description')
            ->assertSee('Database menu')->assertSee('Database office')->assertSee('mailto:database@example.com', false)
            ->assertSee('https://wa.me/628123456789?', false);
        $this->withSession(['locale' => 'id'])->get('/')->assertOk()
            ->assertDontSee('Database headline')->assertDontSee('Database service')->assertDontSee('database@example.com');
    }

    public function test_inactive_pages_menus_contacts_and_contents_are_not_published(): void
    {
        $blog = HalamanWebsite::where('nama_halaman', 'blog')->firstOrFail();
        $blog->update(['status' => 'nonaktif']);
        $this->get('/blog')->assertNotFound();
        $this->get('/')->assertOk()->assertDontSee('href="http://localhost/blog"', false)->assertDontSee('href="/blog"', false);
        $about = HalamanWebsite::where('nama_halaman', 'about')->firstOrFail();
        $about->konten()->update(['status' => 'nonaktif']);
        $this->get('/about')->assertNotFound();
        MenuNavigasi::whereHas('halaman', fn ($q) => $q->where('nama_halaman', 'services'))->update(['status' => 'nonaktif']);
        $this->get('/contact')->assertOk()->assertDontSee('href="http://localhost/product-and-services"', false);
        Kontak::query()->update(['status' => 'nonaktif']);
        $this->get('/contact')->assertNotFound();
    }

    public function test_language_status_and_database_default_are_respected(): void
    {
        Bahasa::where('kode_bahasa', 'id')->firstOrFail()->update(['status' => 'nonaktif']);
        $this->withSession(['locale' => 'id'])->get('/')->assertOk()->assertSee('<html lang="en">', false)->assertDontSee('value="id"', false);
        $this->post('/language', ['locale' => 'id'])->assertSessionHasErrors('locale');
        Bahasa::where('kode_bahasa', 'en')->firstOrFail()->update(['is_default' => false]);
        Bahasa::where('kode_bahasa', 'id')->firstOrFail()->update(['status' => 'aktif', 'is_default' => true]);
        $this->withSession(['locale' => 'unknown'])->get('/')->assertOk()->assertSee('<html lang="id">', false);
    }

    public function test_duplicate_content_is_rejected_by_database_constraint(): void
    {
        $row = KontenWebsite::firstOrFail()->getAttributes();
        unset($row['id_konten']);
        $this->expectException(QueryException::class);
        DB::table('konten_website')->insert($row);
    }

    public function test_orphan_content_is_rejected_by_database_constraint(): void
    {
        $row = KontenWebsite::firstOrFail()->getAttributes();
        unset($row['id_konten']);
        $row['id_halaman'] = 4294967295;
        $this->expectException(QueryException::class);
        DB::table('konten_website')->insert($row);
    }

    public function test_referenced_language_cannot_be_deleted(): void
    {
        $this->expectException(QueryException::class);
        DB::table('bahasa')->where('kode_bahasa', 'en')->delete();
    }

    public function test_model_rejects_invalid_contact_email(): void
    {
        $this->expectException(ValidationException::class);
        Kontak::firstOrFail()->update(['email' => 'invalid']);
    }

    public function test_model_rejects_content_larger_than_erd_text_column(): void
    {
        $this->expectException(ValidationException::class);
        KontenWebsite::firstOrFail()->update(['isi' => ['text' => str_repeat('x', 65536)]]);
    }

    public function test_model_rejects_second_default_language(): void
    {
        $this->expectException(ValidationException::class);
        Bahasa::where('kode_bahasa', 'id')->firstOrFail()->update(['is_default' => true]);
    }
}
