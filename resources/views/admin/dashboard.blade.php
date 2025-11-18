@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')
<div class="page-header">
    <h1 class="page-title">Dashboard</h1>
    <p class="page-subtitle">Overview of your admin panel</p>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">Total Contacts</div>
        <div class="stat-value">{{ $stats['total_contacts'] }}</div>
        <div class="stat-change">{{ $stats['unread_contacts'] }} unread</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Partner Applications</div>
        <div class="stat-value">{{ $stats['total_partners'] }}</div>
        <div class="stat-change">{{ $stats['pending_partners'] }} pending</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Technician Applications</div>
        <div class="stat-value">{{ $stats['total_technicians'] }}</div>
        <div class="stat-change">{{ $stats['pending_technicians'] }} pending</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">This Week</div>
        <div class="stat-value">{{ $stats['this_week'] }}</div>
        <div class="stat-change">New submissions</div>
    </div>
</div>

<div class="content-card">
    <h3 style="font-size: 18px; font-weight: 700; margin-bottom: 16px;">Recent Activity</h3>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Name/Company</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recent_activities as $activity)
                    <tr>
                        <td>{{ ucfirst($activity['type']) }}</td>
                        <td>{{ $activity['name'] }}</td>
                        <td>{{ $activity['email'] }}</td>
                        <td>
                            <span class="badge badge-{{ $activity['status'] }}">{{ ucfirst($activity['status']) }}</span>
                        </td>
                        <td>{{ $activity['date'] }}</td>
                        <td>
                            <a href="{{ $activity['view_url'] }}" class="btn btn-sm btn-primary">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: #6B7280;">No recent activity</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
