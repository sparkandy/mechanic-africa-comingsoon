@extends('admin.layout')

@section('title', 'Contact Details')

@section('content')
<div class="content-header">
    <div>
        <h1>Contact Details</h1>
        <p class="subtitle">View and manage contact submission</p>
    </div>
    <div>
        <a href="{{ route('admin.contacts.index') }}" class="btn-secondary">← Back to Contacts</a>
    </div>
</div>

@if(session('success'))
    <div style="background: #d1fae5; border: 1px solid #6ee7b7; color: #065f46; padding: 12px 16px; border-radius: 8px; margin-bottom: 24px;">
        {{ session('success') }}
    </div>
@endif

<div class="card">
    <div style="padding: 24px; border-bottom: 1px solid #e5e7eb;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h2 style="font-size: 20px; font-weight: 600; color: #111827; margin: 0 0 8px 0;">
                    {{ $contact->name }}
                </h2>
                <div style="display: flex; gap: 16px; align-items: center;">
                    @if($contact->status === 'unread')
                        <span class="badge badge-warning">Unread</span>
                    @elseif($contact->status === 'read')
                        <span class="badge badge-success">Read</span>
                    @else
                        <span class="badge badge-secondary">Archived</span>
                    @endif
                    <span style="color: #6b7280; font-size: 14px;">
                        Submitted {{ $contact->created_at->diffForHumans() }}
                    </span>
                </div>
            </div>
            <div style="display: flex; gap: 12px;">
                @if($contact->status !== 'unread')
                    <form action="{{ route('admin.contacts.status', $contact->id) }}" method="POST" style="display: inline;">
                        @csrf
                        <input type="hidden" name="status" value="unread">
                        <button type="submit" class="btn-secondary">Mark as Unread</button>
                    </form>
                @endif
                
                @if($contact->status !== 'read')
                    <form action="{{ route('admin.contacts.status', $contact->id) }}" method="POST" style="display: inline;">
                        @csrf
                        <input type="hidden" name="status" value="read">
                        <button type="submit" class="btn-primary">Mark as Read</button>
                    </form>
                @endif
                
                @if($contact->status !== 'archived')
                    <form action="{{ route('admin.contacts.status', $contact->id) }}" method="POST" style="display: inline;">
                        @csrf
                        <input type="hidden" name="status" value="archived">
                        <button type="submit" class="btn-secondary">Archive</button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <div style="padding: 24px;">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 32px;">
            <div>
                <label style="display: block; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">
                    Full Name
                </label>
                <p style="font-size: 16px; color: #111827;">{{ $contact->name }}</p>
            </div>
            
            <div>
                <label style="display: block; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">
                    Email Address
                </label>
                <p style="font-size: 16px; color: #111827;">
                    <a href="mailto:{{ $contact->email }}" style="color: #7c3aed; text-decoration: none;">
                        {{ $contact->email }}
                    </a>
                </p>
            </div>
            
            <div>
                <label style="display: block; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">
                    Phone Number
                </label>
                <p style="font-size: 16px; color: #111827;">
                    <a href="tel:{{ $contact->phone }}" style="color: #7c3aed; text-decoration: none;">
                        {{ $contact->phone }}
                    </a>
                </p>
            </div>
            
            <div>
                <label style="display: block; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">
                    Submission Date
                </label>
                <p style="font-size: 16px; color: #111827;">{{ $contact->created_at->format('M d, Y h:i A') }}</p>
            </div>
        </div>

        <div>
            <label style="display: block; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">
                Message
            </label>
            <div style="background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: 16px; font-size: 15px; color: #111827; line-height: 1.6;">
                {{ $contact->message }}
            </div>
        </div>
    </div>

    <div style="padding: 24px; border-top: 1px solid #e5e7eb; background: #f9fafb;">
        <form action="{{ route('admin.contacts.destroy', $contact->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this contact? This action cannot be undone.');">
            @csrf
            @method('DELETE')
            <button type="submit" style="background: #dc2626; color: white; padding: 10px 20px; border: none; border-radius: 6px; font-weight: 500; cursor: pointer; font-size: 14px;">
                Delete Contact
            </button>
        </form>
    </div>
</div>
@endsection
