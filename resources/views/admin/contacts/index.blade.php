@extends('admin.layout')

@section('title', 'Service Requests Management')

@section('content')
<div class="content-header">
    <div>
        <h1>Service Requests Management</h1>
        <p class="subtitle">Manage customer service requests</p>
    </div>
</div>

@if(session('success'))
    <div style="background: #d1fae5; border: 1px solid #6ee7b7; color: #065f46; padding: 12px 16px; border-radius: 8px; margin-bottom: 24px;">
        {{ session('success') }}
    </div>
@endif

<div class="card">
    <div style="padding: 20px; border-bottom: 1px solid #e5e7eb;">
        <form action="{{ route('admin.contacts.index') }}" method="GET" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px; align-items: end;">
            <div>
                <label style="display: block; font-size: 14px; font-weight: 500; color: #374151; margin-bottom: 6px;">
                    Status
                </label>
                <select name="status" style="padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; width: 100%;">
                    <option value="">All Status</option>
                    <option value="unread" {{ request('status') === 'unread' ? 'selected' : '' }}>Unread</option>
                    <option value="read" {{ request('status') === 'read' ? 'selected' : '' }}>Read</option>
                    <option value="archived" {{ request('status') === 'archived' ? 'selected' : '' }}>Archived</option>
                </select>
            </div>
            <div>
                <label style="display: block; font-size: 14px; font-weight: 500; color: #374151; margin-bottom: 6px;">
                    Date Range
                </label>
                <select name="date_range" style="padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; width: 100%;">
                    <option value="">All Time</option>
                    <option value="today" {{ request('date_range') === 'today' ? 'selected' : '' }}>Today</option>
                    <option value="week" {{ request('date_range') === 'week' ? 'selected' : '' }}>This Week</option>
                    <option value="month" {{ request('date_range') === 'month' ? 'selected' : '' }}>This Month</option>
                    <option value="year" {{ request('date_range') === 'year' ? 'selected' : '' }}>This Year</option>
                </select>
            </div>
            <div>
                <label style="display: block; font-size: 14px; font-weight: 500; color: #374151; margin-bottom: 6px;">
                    Search
                </label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Name, email, phone..." style="padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; width: 100%;">
            </div>
            <div style="display: flex; gap: 8px;">
                <button type="submit" class="btn-primary" style="flex: 1;">Apply Filters</button>
                @if(request()->hasAny(['status', 'date_range', 'search']))
                    <a href="{{ route('admin.contacts.index') }}" class="btn-secondary">Clear</a>
                @endif
            </div>
        </form>
    </div>

    <div style="overflow-x: auto;">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Message Preview</th>
                    <th>Status</th>
                    <th>Submitted</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($contacts as $contact)
                    <tr>
                        <td style="font-weight: 500; color: #111827;">{{ $contact->name }}</td>
                        <td>{{ $contact->email }}</td>
                        <td>{{ $contact->phone }}</td>
                        <td style="max-width: 300px;">
                            <div style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                {{ Str::limit($contact->message, 50) }}
                            </div>
                        </td>
                        <td>
                            @if($contact->status === 'unread')
                                <span class="badge badge-warning">Unread</span>
                            @elseif($contact->status === 'read')
                                <span class="badge badge-success">Read</span>
                            @else
                                <span class="badge badge-secondary">Archived</span>
                            @endif
                        </td>
                        <td style="color: #6b7280; font-size: 14px;">
                            {{ $contact->created_at->format('M d, Y') }}
                        </td>
                        <td>
                            <a href="{{ route('admin.contacts.show', $contact->id) }}" class="btn-secondary" style="font-size: 13px; padding: 6px 12px;">
                                View Details
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; color: #6b7280; padding: 40px;">
                            No service requests found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($contacts->hasPages())
        <div style="padding: 20px; border-top: 1px solid #e5e7eb;">
            {{ $contacts->links() }}
        </div>
    @endif
</div>
@endsection
