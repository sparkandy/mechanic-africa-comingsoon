@extends('admin.layout')

@section('title', 'Reports & Analytics')

@section('content')
<div class="content-header">
    <div>
        <h1>Reports & Analytics</h1>
        <p class="subtitle">Comprehensive insights and data visualization</p>
    </div>
</div>

<!-- Overall Statistics -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 32px;">
    <div class="stat-card">
        <div class="stat-label">Total Service Requests</div>
        <div class="stat-value">{{ $totalStats['contacts'] }}</div>
        <div style="font-size: 12px; color: #f59e0b; margin-top: 8px;">{{ $totalStats['unread_contacts'] }} unread</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Total Partner Applications</div>
        <div class="stat-value">{{ $totalStats['partners'] }}</div>
        <div style="font-size: 12px; color: #f59e0b; margin-top: 8px;">{{ $totalStats['pending_partners'] }} pending</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Total Technician Applications</div>
        <div class="stat-value">{{ $totalStats['technicians'] }}</div>
        <div style="font-size: 12px; color: #f59e0b; margin-top: 8px;">{{ $totalStats['pending_technicians'] }} pending</div>
    </div>
</div>

<!-- Status Breakdown -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 24px; margin-bottom: 32px;">
    <!-- Service Requests Status -->
    <div class="card">
        <div style="padding: 20px; border-bottom: 1px solid #e5e7eb;">
            <h3 style="font-size: 16px; font-weight: 600; color: #111827; margin: 0;">Service Requests by Status</h3>
        </div>
        <div style="padding: 24px;">
            <div style="margin-bottom: 16px;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                    <span style="font-size: 14px; color: #6b7280;">Unread</span>
                    <span style="font-size: 14px; font-weight: 600; color: #111827;">{{ $contactsByStatus['unread'] }}</span>
                </div>
                <div style="background: #e5e7eb; border-radius: 4px; height: 8px; overflow: hidden;">
                    <div style="background: #f59e0b; height: 100%; width: {{ $totalStats['contacts'] > 0 ? ($contactsByStatus['unread'] / $totalStats['contacts']) * 100 : 0 }}%;"></div>
                </div>
            </div>
            <div style="margin-bottom: 16px;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                    <span style="font-size: 14px; color: #6b7280;">Read</span>
                    <span style="font-size: 14px; font-weight: 600; color: #111827;">{{ $contactsByStatus['read'] }}</span>
                </div>
                <div style="background: #e5e7eb; border-radius: 4px; height: 8px; overflow: hidden;">
                    <div style="background: #10b981; height: 100%; width: {{ $totalStats['contacts'] > 0 ? ($contactsByStatus['read'] / $totalStats['contacts']) * 100 : 0 }}%;"></div>
                </div>
            </div>
            <div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                    <span style="font-size: 14px; color: #6b7280;">Archived</span>
                    <span style="font-size: 14px; font-weight: 600; color: #111827;">{{ $contactsByStatus['archived'] }}</span>
                </div>
                <div style="background: #e5e7eb; border-radius: 4px; height: 8px; overflow: hidden;">
                    <div style="background: #6b7280; height: 100%; width: {{ $totalStats['contacts'] > 0 ? ($contactsByStatus['archived'] / $totalStats['contacts']) * 100 : 0 }}%;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Partners Status -->
    <div class="card">
        <div style="padding: 20px; border-bottom: 1px solid #e5e7eb;">
            <h3 style="font-size: 16px; font-weight: 600; color: #111827; margin: 0;">Partners by Status</h3>
        </div>
        <div style="padding: 24px;">
            <div style="margin-bottom: 16px;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                    <span style="font-size: 14px; color: #6b7280;">Pending</span>
                    <span style="font-size: 14px; font-weight: 600; color: #111827;">{{ $partnersByStatus['pending'] }}</span>
                </div>
                <div style="background: #e5e7eb; border-radius: 4px; height: 8px; overflow: hidden;">
                    <div style="background: #f59e0b; height: 100%; width: {{ $totalStats['partners'] > 0 ? ($partnersByStatus['pending'] / $totalStats['partners']) * 100 : 0 }}%;"></div>
                </div>
            </div>
            <div style="margin-bottom: 16px;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                    <span style="font-size: 14px; color: #6b7280;">Approved</span>
                    <span style="font-size: 14px; font-weight: 600; color: #111827;">{{ $partnersByStatus['approved'] }}</span>
                </div>
                <div style="background: #e5e7eb; border-radius: 4px; height: 8px; overflow: hidden;">
                    <div style="background: #10b981; height: 100%; width: {{ $totalStats['partners'] > 0 ? ($partnersByStatus['approved'] / $totalStats['partners']) * 100 : 0 }}%;"></div>
                </div>
            </div>
            <div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                    <span style="font-size: 14px; color: #6b7280;">Rejected</span>
                    <span style="font-size: 14px; font-weight: 600; color: #111827;">{{ $partnersByStatus['rejected'] }}</span>
                </div>
                <div style="background: #e5e7eb; border-radius: 4px; height: 8px; overflow: hidden;">
                    <div style="background: #ef4444; height: 100%; width: {{ $totalStats['partners'] > 0 ? ($partnersByStatus['rejected'] / $totalStats['partners']) * 100 : 0 }}%;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Technicians Status -->
    <div class="card">
        <div style="padding: 20px; border-bottom: 1px solid #e5e7eb;">
            <h3 style="font-size: 16px; font-weight: 600; color: #111827; margin: 0;">Technicians by Status</h3>
        </div>
        <div style="padding: 24px;">
            <div style="margin-bottom: 16px;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                    <span style="font-size: 14px; color: #6b7280;">Pending</span>
                    <span style="font-size: 14px; font-weight: 600; color: #111827;">{{ $techniciansByStatus['pending'] }}</span>
                </div>
                <div style="background: #e5e7eb; border-radius: 4px; height: 8px; overflow: hidden;">
                    <div style="background: #f59e0b; height: 100%; width: {{ $totalStats['technicians'] > 0 ? ($techniciansByStatus['pending'] / $totalStats['technicians']) * 100 : 0 }}%;"></div>
                </div>
            </div>
            <div style="margin-bottom: 16px;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                    <span style="font-size: 14px; color: #6b7280;">Approved</span>
                    <span style="font-size: 14px; font-weight: 600; color: #111827;">{{ $techniciansByStatus['approved'] }}</span>
                </div>
                <div style="background: #e5e7eb; border-radius: 4px; height: 8px; overflow: hidden;">
                    <div style="background: #10b981; height: 100%; width: {{ $totalStats['technicians'] > 0 ? ($techniciansByStatus['approved'] / $totalStats['technicians']) * 100 : 0 }}%;"></div>
                </div>
            </div>
            <div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                    <span style="font-size: 14px; color: #6b7280;">Rejected</span>
                    <span style="font-size: 14px; font-weight: 600; color: #111827;">{{ $techniciansByStatus['rejected'] }}</span>
                </div>
                <div style="background: #e5e7eb; border-radius: 4px; height: 8px; overflow: hidden;">
                    <div style="background: #ef4444; height: 100%; width: {{ $totalStats['technicians'] > 0 ? ($techniciansByStatus['rejected'] / $totalStats['technicians']) * 100 : 0 }}%;"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Monthly Trends -->
<div class="card">
    <div style="padding: 20px; border-bottom: 1px solid #e5e7eb;">
        <h3 style="font-size: 16px; font-weight: 600; color: #111827; margin: 0;">12-Month Trend Analysis</h3>
    </div>
    <div style="padding: 24px; overflow-x: auto;">
        <table>
            <thead>
                <tr>
                    <th>Month</th>
                    <th>Service Requests</th>
                    <th>Partners</th>
                    <th>Technicians</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($monthlyTrends as $trend)
                    <tr>
                        <td style="font-weight: 500;">{{ $trend['month'] }}</td>
                        <td>{{ $trend['contacts'] }}</td>
                        <td>{{ $trend['partners'] }}</td>
                        <td>{{ $trend['technicians'] }}</td>
                        <td style="font-weight: 600; color: #7c3aed;">{{ $trend['contacts'] + $trend['partners'] + $trend['technicians'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
