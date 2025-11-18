@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')
<div class="content-header">
    <div>
        <h1>Dashboard</h1>
        <p class="subtitle">Overview of your platform analytics</p>
    </div>
</div>

<!-- Service Requests Stats -->
<div style="margin-bottom: 32px;">
    <h2 style="font-size: 18px; font-weight: 600; color: #111827; margin-bottom: 16px;">Service Requests</h2>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
        <div class="stat-card">
            <div class="stat-label">Total Requests</div>
            <div class="stat-value">{{ $totalContacts }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Unread</div>
            <div class="stat-value" style="color: #f59e0b;">{{ $unreadContacts }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Today</div>
            <div class="stat-value" style="color: #3b82f6;">{{ $todayContacts }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">This Week</div>
            <div class="stat-value" style="color: #10b981;">{{ $weekContacts }}</div>
            @if($contactsGrowth != 0)
                <div class="stat-change" style="color: {{ $contactsGrowth > 0 ? '#10b981' : '#ef4444' }};">
                    {{ $contactsGrowth > 0 ? '+' : '' }}{{ $contactsGrowth }}% from last week
                </div>
            @endif
        </div>
        <div class="stat-card">
            <div class="stat-label">This Month</div>
            <div class="stat-value">{{ $monthContacts }}</div>
        </div>
    </div>
</div>

<!-- Partners Stats -->
<div style="margin-bottom: 32px;">
    <h2 style="font-size: 18px; font-weight: 600; color: #111827; margin-bottom: 16px;">Partner Applications</h2>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
        <div class="stat-card">
            <div class="stat-label">Total Partners</div>
            <div class="stat-value">{{ $totalPartners }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Pending</div>
            <div class="stat-value" style="color: #f59e0b;">{{ $pendingPartners }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Approved</div>
            <div class="stat-value" style="color: #10b981;">{{ $approvedPartners }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Rejected</div>
            <div class="stat-value" style="color: #ef4444;">{{ $rejectedPartners }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Today</div>
            <div class="stat-value" style="color: #3b82f6;">{{ $todayPartners }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">This Week</div>
            <div class="stat-value">{{ $weekPartners }}</div>
            @if($partnersGrowth != 0)
                <div class="stat-change" style="color: {{ $partnersGrowth > 0 ? '#10b981' : '#ef4444' }};">
                    {{ $partnersGrowth > 0 ? '+' : '' }}{{ $partnersGrowth }}% from last week
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Technicians Stats -->
<div style="margin-bottom: 32px;">
    <h2 style="font-size: 18px; font-weight: 600; color: #111827; margin-bottom: 16px;">Technician Applications</h2>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
        <div class="stat-card">
            <div class="stat-label">Total Technicians</div>
            <div class="stat-value">{{ $totalTechnicians }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Pending</div>
            <div class="stat-value" style="color: #f59e0b;">{{ $pendingTechnicians }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Approved</div>
            <div class="stat-value" style="color: #10b981;">{{ $approvedTechnicians }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Rejected</div>
            <div class="stat-value" style="color: #ef4444;">{{ $rejectedTechnicians }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Today</div>
            <div class="stat-value" style="color: #3b82f6;">{{ $todayTechnicians }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">This Week</div>
            <div class="stat-value">{{ $weekTechnicians }}</div>
            @if($techniciansGrowth != 0)
                <div class="stat-change" style="color: {{ $techniciansGrowth > 0 ? '#10b981' : '#ef4444' }};">
                    {{ $techniciansGrowth > 0 ? '+' : '' }}{{ $techniciansGrowth }}% from last week
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="card">
    <div style="padding: 20px; border-bottom: 1px solid #e5e7eb;">
        <h2 style="font-size: 18px; font-weight: 600; color: #111827; margin: 0;">Recent Activity</h2>
    </div>
    <div style="overflow-x: auto;">
        <table>
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentActivities as $activity)
                    <tr>
                        <td>
                            @if($activity['type'] === 'Service Request')
                                <span class="badge" style="background: #dbeafe; color: #1e40af;">{{ $activity['type'] }}</span>
                            @elseif($activity['type'] === 'Partner Application')
                                <span class="badge" style="background: #fce7f3; color: #9f1239;">{{ $activity['type'] }}</span>
                            @else
                                <span class="badge" style="background: #f3e8ff; color: #6b21a8;">{{ $activity['type'] }}</span>
                            @endif
                        </td>
                        <td>{{ $activity['description'] }}</td>
                        <td>
                            @if($activity['status'] === 'unread' || $activity['status'] === 'pending')
                                <span class="badge badge-warning">{{ ucfirst($activity['status']) }}</span>
                            @elseif($activity['status'] === 'read' || $activity['status'] === 'approved')
                                <span class="badge badge-success">{{ ucfirst($activity['status']) }}</span>
                            @else
                                <span class="badge badge-danger">{{ ucfirst($activity['status']) }}</span>
                            @endif
                        </td>
                        <td style="color: #6b7280; font-size: 14px;">
                            {{ $activity['created_at']->diffForHumans() }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align: center; color: #6b7280; padding: 40px;">
                            No recent activity
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
