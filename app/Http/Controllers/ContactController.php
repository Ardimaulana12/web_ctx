<?php

namespace App\Http\Controllers;

use App\Mail\ContactInquiry;
use App\Models\Inquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Throwable;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:150'],
            'email' => ['required', 'email', 'max:150'],
            'company' => ['nullable', 'string', 'max:150'],
            'subject' => ['required', 'string', 'min:3', 'max:200'],
            'message' => ['required', 'string', 'min:10', 'max:5000'],
        ], [], [
            'name' => __('Full name'), 'email' => __('Email address'),
            'company' => __('Company / organization'), 'subject' => __('Subject'),
            'message' => __('Message'),
        ]);
        $inquiry = Inquiry::create($data);

        try {
            // Never report a log/array/fallback transport as a delivered email.
            if (config('mail.default') !== 'smtp') {
                throw new \RuntimeException('Contact email requires the SMTP mailer.');
            }
            Mail::to(config('mail.contact_to'))->send(new ContactInquiry($data));
        } catch (Throwable $exception) {
            // Do not put message contents or SMTP credentials in the application log.
            Log::warning('Contact email could not be sent.', [
                'inquiry_id' => $inquiry->id, 'exception' => get_class($exception),
            ]);
            return redirect()->route('contact')->with('warning',
                'Your message was saved, but the notification email could not be sent. You do not need to submit it again. For urgent requests, please contact us by email or WhatsApp.');
        }

        return redirect()->route('contact')->with('success',
            'Thank you. Your message was saved and the notification was sent to our team.');
    }
}
