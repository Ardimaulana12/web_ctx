<?php

namespace Tests\Feature;

use App\Mail\ContactInquiry;
use App\Models\HubungiKami;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class WebsiteTest extends TestCase
{
    use DatabaseTransactions;

    public function test_contact_table_has_only_the_requested_indonesian_column_names(): void
    {
        $this->assertFalse(Schema::hasTable('inquiries'));
        $this->assertSame([
            'id_hubungi', 'nama', 'email', 'perusahaan', 'subjek', 'pesan', 'created_at', 'updated_at',
        ], Schema::getColumnListing('hubungi_kami'));
    }

    public function test_all_public_pages_load(): void
    {
        foreach (['/', '/product-and-services', '/case-study', '/blog', '/contact', '/about'] as $page) {
            $this->get($page)->assertOk()->assertSee('navigation');
        }
    }

    public function test_english_is_the_default_and_language_persists(): void
    {
        $this->get('/')->assertSee('<html lang="en">', false)->assertSee('Home');
        $this->post('/language', ['locale' => 'en', 'return_to' => '/about'])
            ->assertRedirect('/about')->assertSessionHas('locale', 'en');
        $this->get('/about')->assertSee('<html lang="en">', false)->assertSee('Company Profile');
        $this->get('/contact')->assertSee('Full name');
        $this->post('/language', ['locale' => 'id', 'return_to' => '/contact?subject=Research'])
            ->assertRedirect('/contact?subject=Research');
        $this->get('/contact')->assertSee('Nama lengkap');
    }

    public function test_language_selection_rejects_invalid_locale_and_external_redirect(): void
    {
        $this->post('/language', ['locale' => 'fr'])->assertSessionHasErrors('locale');
        $this->post('/language', ['locale' => 'en', 'return_to' => 'https://example.com'])
            ->assertRedirect('/');
    }

    public function test_contact_request_is_saved_in_database(): void
    {
        config(['mail.default' => 'smtp', 'mail.contact_to' => 'contact-tests@example.com']);
        Mail::fake();
        $this->post('/contact', [
            'name' => 'Website Test', 'email' => 'test@example.com',
            'company' => 'Test Organization', 'subject' => 'Research inquiry',
            'message' => 'We would like to discuss a market research project.',
        ])->assertRedirect('/contact')->assertSessionHas('success');
        $this->assertDatabaseHas('hubungi_kami', [
            'nama' => 'Website Test', 'email' => 'test@example.com',
            'perusahaan' => 'Test Organization', 'subjek' => 'Research inquiry',
            'pesan' => 'We would like to discuss a market research project.',
        ]);
        $saved = HubungiKami::where('email', 'test@example.com')->latest('id_hubungi')->firstOrFail();
        $this->assertGreaterThan(0, $saved->id_hubungi);
        $this->assertNotNull($saved->created_at);
        $this->assertNotNull($saved->updated_at);
        $this->assertTrue(HubungiKami::findOrFail($saved->id_hubungi)->is($saved));
        Mail::assertSent(ContactInquiry::class, fn ($mail) => $mail->hasTo('contact-tests@example.com') && $mail->inquiry['email'] === 'test@example.com');
    }

    public function test_invalid_contact_data_is_rejected(): void
    {
        $before = HubungiKami::count();
        $this->post('/contact', ['email' => 'invalid'])->assertSessionHasErrors(['name', 'email', 'subject', 'message']);
        $this->assertSame($before, HubungiKami::count());
    }

    public function test_log_mailer_shows_warning_instead_of_false_success(): void
    {
        config(['mail.default' => 'log']);
        Mail::fake();
        $this->post('/contact', ['name' => 'Test User', 'email' => 'test@example.com',
            'subject' => 'Research', 'message' => 'Please contact me about research.'])
            ->assertSessionHas('warning')->assertSessionMissing('success');
        Mail::assertNothingSent();
        $this->assertDatabaseHas('hubungi_kami', ['subjek' => 'Research', 'perusahaan' => null]);
    }

    public function test_smtp_failure_preserves_the_message(): void
    {
        config(['mail.default' => 'smtp']);
        Mail::shouldReceive('to')->once()->andThrow(new \RuntimeException('Unavailable'));
        $this->post('/contact', ['name' => 'Test User', 'email' => 'test@example.com',
            'subject' => 'SMTP failure', 'message' => 'Please contact me about research.'])
            ->assertSessionHas('warning')->assertSessionMissing('success');
        $this->assertDatabaseHas('hubungi_kami', ['subjek' => 'SMTP failure']);
    }

    public function test_short_fields_are_rejected_with_indonesian_errors(): void
    {
        $this->withSession(['locale' => 'id'])->post('/contact', [
            'name' => 'A', 'email' => 'wrong', 'subject' => 'Hi', 'message' => 'short',
        ])->assertSessionHasErrors(['name', 'email', 'subject', 'message']);
        $this->get('/contact')->assertSee('minimal 10 karakter')->assertSee('aria-invalid="true"', false);
    }
}
