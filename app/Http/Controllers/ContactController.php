<?php

namespace App\Http\Controllers;

use App\Models\ContactRequest;
use App\Models\AboutContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactFormMail;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:2000'],
        ], [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'subject.required' => 'Subjek wajib diisi.',
            'message.required' => 'Pesan wajib diisi.',
            'message.max' => 'Pesan maksimal 2000 karakter.',
        ]);

        // Save to database
        $contactRequest = ContactRequest::create($validated);

        // Send email to admin
        $about = AboutContent::first();
        if ($about && $about->contact_email) {
            try {
                Mail::to($about->contact_email)->send(new ContactFormMail($contactRequest));
            } catch (\Exception $e) {
                \Log::error('Failed to send contact email: ' . $e->getMessage());
            }
        }

        return back()->with('success', 'Pesan Anda berhasil dikirim! Kami akan segera menghubungi Anda.');
    }
}