<?php

namespace Tests\Feature;

use App\Models\Inquiry;
use App\Mail\ContactInquiry;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class WebsiteTest extends TestCase
{
    use DatabaseTransactions;

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
        config(['mail.default' => 'smtp']);
        Mail::fake();
        $this->post('/contact', [
            'name' => 'Website Test', 'email' => 'test@example.com',
            'company' => 'Test Organization', 'subject' => 'Research inquiry',
            'message' => 'We would like to discuss a market research project.',
        ])->assertRedirect('/contact')->assertSessionHas('success');
        $this->assertDatabaseHas('inquiries', ['email' => 'test@example.com', 'subject' => 'Research inquiry']);
        Mail::assertSent(ContactInquiry::class, fn ($mail) => $mail->hasTo('ardimaulana265@gmail.com') && $mail->inquiry['email'] === 'test@example.com');
    }

    public function test_invalid_contact_data_is_rejected(): void
    {
        $before = Inquiry::count();
        $this->post('/contact', ['email' => 'invalid'])->assertSessionHasErrors(['name', 'email', 'subject', 'message']);
        $this->assertSame($before, Inquiry::count());
    }
    public function test_log_mailer_shows_warning_instead_of_false_success(): void
    {
        config(['mail.default' => 'log']);
        Mail::fake();
        $this->post('/contact', ['name'=>'Test User', 'email'=>'test@example.com',
            'subject'=>'Research', 'message'=>'Please contact me about research.'])
            ->assertSessionHas('warning')->assertSessionMissing('success');
        Mail::assertNothingSent();
        $this->assertDatabaseHas('inquiries', ['subject'=>'Research']);
    }

    public function test_smtp_failure_preserves_the_message(): void
    {
        config(['mail.default' => 'smtp']);
        Mail::shouldReceive('to')->once()->andThrow(new \RuntimeException('Unavailable'));
        $this->post('/contact', ['name'=>'Test User', 'email'=>'test@example.com',
            'subject'=>'SMTP failure', 'message'=>'Please contact me about research.'])
            ->assertSessionHas('warning')->assertSessionMissing('success');
        $this->assertDatabaseHas('inquiries', ['subject'=>'SMTP failure']);
    }

    public function test_short_fields_are_rejected_with_indonesian_errors(): void
    {
        $this->withSession(['locale'=>'id'])->post('/contact', [
            'name'=>'A', 'email'=>'wrong', 'subject'=>'Hi', 'message'=>'short',
        ])->assertSessionHasErrors(['name','email','subject','message']);
        $this->get('/contact')->assertSee('minimal 10 karakter')->assertSee('aria-invalid="true"', false);
    }
}
