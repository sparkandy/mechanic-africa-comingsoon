@extends('admin.layout')

@section('title', 'Waitlist Management')

@section('content')
<div class="content-header">
    <div>
        <h1>Waitlist Management</h1>
        <p class="subtitle">Manage your waitlist subscribers</p>
    </div>
    <div style="display: flex; gap: 12px;">
        <a href="{{ route('admin.waitlist.export') }}" class="btn-secondary">
            <svg style="width: 18px; height: 18px; margin-right: 8px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
            </svg>
            Export CSV
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-error">
        {{ session('error') }}
    </div>
@endif

<!-- Stats -->
<div style="margin-bottom: 32px;">
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
        <div class="stat-card">
            <div class="stat-label">Total Subscribers</div>
            <div class="stat-value">{{ $waitlists->total() }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">This Week</div>
            <div class="stat-value" style="color: #10b981;">{{ $waitlists->where('created_at', '>=', now()->startOfWeek())->count() }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">This Month</div>
            <div class="stat-value" style="color: #3b82f6;">{{ $waitlists->where('created_at', '>=', now()->startOfMonth())->count() }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Today</div>
            <div class="stat-value" style="color: #f59e0b;">{{ $waitlists->where('created_at', '>=', now()->startOfDay())->count() }}</div>
        </div>
    </div>
</div>

<!-- Waitlist Table -->
<div class="card">
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>City</th>
                    <th>Joined Date</th>
                    <th>IP Address</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($waitlists as $waitlist)
                    <tr>
                        <td>{{ $waitlist->id }}</td>
                        <td>
                            <div style="font-weight: 500; color: #111827;">{{ $waitlist->name }}</div>
                        </td>
                        <td>
                            <a href="mailto:{{ $waitlist->email }}" style="color: #3b82f6; text-decoration: none;">
                                {{ $waitlist->email }}
                            </a>
                        </td>
                        <td>
                            @if($waitlist->phone)
                                <a href="tel:{{ $waitlist->phone }}" style="color: #3b82f6; text-decoration: none;">
                                    {{ $waitlist->phone }}
                                </a>
                            @else
                                <span style="color: #9ca3af;">—</span>
                            @endif
                        </td>
                        <td>
                            @if($waitlist->city)
                                <span class="badge badge-info">{{ $waitlist->city }}</span>
                            @else
                                <span style="color: #9ca3af;">—</span>
                            @endif
                        </td>
                        <td>
                            <div style="font-size: 13px; color: #6b7280;">
                                {{ $waitlist->created_at->format('M d, Y') }}
                                <div style="font-size: 12px; color: #9ca3af; margin-top: 2px;">
                                    {{ $waitlist->created_at->format('h:i A') }}
                                </div>
                            </div>
                        </td>
                        <td>
                            <span style="font-size: 12px; color: #6b7280; font-family: monospace;">
                                {{ $waitlist->ip_address ?? '—' }}
                            </span>
                        </td>
                        <td>
                            <div style="display: flex; gap: 8px;">
                                <form action="{{ route('admin.waitlist.destroy', $waitlist->id) }}" method="POST" 
                                      onsubmit="return confirm('Are you sure you want to delete this waitlist entry?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-icon btn-danger" title="Delete">
                                        <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 48px; color: #9ca3af;">
                            <svg style="width: 48px; height: 48px; margin: 0 auto 16px; opacity: 0.5;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                            </svg>
                            <div style="font-size: 14px;">No waitlist entries found</div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($waitlists->hasPages())
        <div class="pagination-container">
            {{ $waitlists->links() }}
        </div>
    @endif
</div>
@endsection
