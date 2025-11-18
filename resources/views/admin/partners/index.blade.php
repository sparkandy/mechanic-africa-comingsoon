@extends('admin.layout')

@section('title', 'Partner Applications')

@section('content')
<div class="content-header">
    <div>
        <h1>Partner Applications</h1>
        <p class="subtitle">Manage workshop partnership applications</p>
    </div>
</div>

@if(session('success'))
    <div style="background: #d1fae5; border: 1px solid #6ee7b7; color: #065f46; padding: 12px 16px; border-radius: 8px; margin-bottom: 24px;">
        {{ session('success') }}
    </div>
@endif

<div class="card">
    <div style="padding: 20px; border-bottom: 1px solid #e5e7eb;">
        <form action="{{ route('admin.partners.index') }}" method="GET" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px; align-items: end;">
            <div>
                <label style="display: block; font-size: 14px; font-weight: 500; color: #374151; margin-bottom: 6px;">
                    Status
                </label>
                <select name="status" style="padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; width: 100%;">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
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
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Workshop, owner, email..." style="padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; width: 100%;">
            </div>
            <div style="display: flex; gap: 8px;">
                <button type="submit" class="btn-primary" style="flex: 1;">Apply Filters</button>
                @if(request()->hasAny(['status', 'date_range', 'search']))
                    <a href="{{ route('admin.partners.index') }}" class="btn-secondary">Clear</a>
                @endif
            </div>
        </form>
    </div>

    <div style="overflow-x: auto;">
        <table>
            <thead>
                <tr>
                    <th>Workshop Name</th>
                    <th>Owner</th>
                    <th>Contact</th>
                    <th>Location</th>
                    <th>Status</th>
                    <th>Submitted</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($partners as $partner)
                    <tr>
                        <td style="font-weight: 500; color: #111827;">{{ $partner->workshop_name }}</td>
                        <td>{{ $partner->owner_name }}</td>
                        <td>
                            <div>{{ $partner->email }}</div>
                            <div style="font-size: 13px; color: #6b7280;">{{ $partner->phone }}</div>
                        </td>
                        <td>{{ $partner->location }}</td>
                        <td>
                            @if($partner->status === 'pending')
                                <span class="badge badge-warning">Pending</span>
                            @elseif($partner->status === 'approved')
                                <span class="badge badge-success">Approved</span>
                            @else
                                <span class="badge badge-danger">Rejected</span>
                            @endif
                        </td>
                        <td style="color: #6b7280; font-size: 14px;">
                            {{ $partner->created_at->format('M d, Y') }}
                        </td>
                        <td>
                            <a href="{{ route('admin.partners.show', $partner->id) }}" class="btn-secondary" style="font-size: 13px; padding: 6px 12px;">
                                View Details
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; color: #6b7280; padding: 40px;">
                            No partner applications found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($partners->hasPages())
        <div style="padding: 20px; border-top: 1px solid #e5e7eb;">
            {{ $partners->links() }}
        </div>
    @endif
</div>
@endsection
