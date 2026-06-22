<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;

class ContactController extends Controller
{
    /**
     * Display a listing of contact messages.
     */
    public function index()
    {
        $contacts = Contact::latest()->paginate(15);
        return view('admin.contacts.index', compact('contacts'));
    }

    /**
     * Mark the specified contact message as read.
     */
    public function markAsRead(Contact $contact)
    {
        $contact->update(['is_read' => true]);
        
        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'unread_count' => Contact::where('is_read', false)->count()
            ]);
        }
        
        return back()->with('success', 'Message marked as read.');
    }

    /**
     * Mark the specified contact message as unread.
     */
    public function markAsUnread(Contact $contact)
    {
        $contact->update(['is_read' => false]);
        return back()->with('success', 'Message marked as unread.');
    }

    /**
     * Remove the specified contact message from storage.
     */
    public function destroy(Contact $contact)
    {
        $contact->delete();
        return redirect()->route('admin.contacts.index')->with('success', 'Message deleted successfully.');
    }
}
