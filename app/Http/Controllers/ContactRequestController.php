<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactRequest;
use Illuminate\Http\Request;

class ContactRequestController extends Controller
{
    public function index()
    {
        $contacts = ContactRequest::latest()->paginate(20);
        $unreadCount = ContactRequest::unread()->count();
        
        return view('admin.contacts.index', compact('contacts', 'unreadCount'));
    }

    public function show(ContactRequest $contact)
    {
        // Mark as read
        if (!$contact->is_read) {
            $contact->update(['is_read' => true]);
        }
        
        return view('admin.contacts.show', compact('contact'));
    }

    public function destroy(ContactRequest $contact)
    {
        $contact->delete();
        
        return redirect()->route('admin.contacts.index')
            ->with('success', 'Pesan berhasil dihapus.');
    }
}