<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = Contact::query()->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $contacts = $query->paginate(20);
        
        return view('admin.contacts.index', compact('contacts'));
    }

    public function show($id)
    {
        $contact = Contact::findOrFail($id);
        
        // Mark as read when viewed
        if ($contact->status === 'unread') {
            $contact->update(['status' => 'read']);
        }
        
        return view('admin.contacts.show', compact('contact'));
    }

    public function updateStatus(Request $request, $id)
    {
        $contact = Contact::findOrFail($id);
        
        $request->validate([
            'status' => 'required|in:unread,read,archived'
        ]);
        
        $contact->update(['status' => $request->status]);
        
        return redirect()->back()->with('success', 'Contact status updated successfully');
    }

    public function destroy($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->delete();
        
        return redirect()->route('admin.contacts.index')->with('success', 'Contact deleted successfully');
    }
}
