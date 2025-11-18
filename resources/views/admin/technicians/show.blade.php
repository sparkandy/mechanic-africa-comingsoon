@extends('admin.layout')

@section('title', 'Technician Application Details')

@section('content')
<div class="content-header">
    <div>
        <h1>Technician Application Details</h1>
        <p class="subtitle">Review independent technician application</p>
    </div>
    <div>
        <a href="{{ route('admin.technicians.index') }}" class="btn-secondary">← Back to Technicians</a>
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
                    {{ $technician->name }}
                </h2>
                <div style="display: flex; gap: 16px; align-items: center;">
                    @if($technician->status === 'pending')
                        <span class="badge badge-warning">Pending Review</span>
                    @elseif($technician->status === 'approved')
                        <span class="badge badge-success">Approved</span>
                    @else
                        <span class="badge badge-danger">Rejected</span>
                    @endif
                    <span style="color: #6b7280; font-size: 14px;">
                        Submitted {{ $technician->created_at->diffForHumans() }}
                    </span>
                </div>
            </div>
            <div style="display: flex; gap: 12px;">
                @if($technician->status !== 'approved')
                    <form action="{{ route('admin.technicians.status', $technician->id) }}" method="POST" style="display: inline;">
                        @csrf
                        <input type="hidden" name="status" value="approved">
                        <button type="submit" class="btn-primary">Approve Application</button>
                    </form>
                @endif
                
                @if($technician->status !== 'rejected')
                    <form action="{{ route('admin.technicians.status', $technician->id) }}" method="POST" style="display: inline;">
                        @csrf
                        <input type="hidden" name="status" value="rejected">
                        <button type="submit" style="background: #dc2626; color: white; padding: 10px 20px; border: none; border-radius: 6px; font-weight: 500; cursor: pointer; font-size: 14px;">
                            Reject Application
                        </button>
                    </form>
                @endif
                
                @if($technician->status !== 'pending')
                    <form action="{{ route('admin.technicians.status', $technician->id) }}" method="POST" style="display: inline;">
                        @csrf
                        <input type="hidden" name="status" value="pending">
                        <button type="submit" class="btn-secondary">Mark as Pending</button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <div style="padding: 24px;">
        <h3 style="font-size: 16px; font-weight: 600; color: #111827; margin: 0 0 16px 0;">Technician Information</h3>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 32px;">
            <div>
                <label style="display: block; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">
                    Full Name
                </label>
                <p style="font-size: 16px; color: #111827;">{{ $technician->name }}</p>
            </div>
            
            <div>
                <label style="display: block; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">
                    Email Address
                </label>
                <p style="font-size: 16px; color: #111827;">
                    <a href="mailto:{{ $technician->email }}" style="color: #7c3aed; text-decoration: none;">
                        {{ $technician->email }}
                    </a>
                </p>
            </div>
            
            <div>
                <label style="display: block; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">
                    Phone Number
                </label>
                <p style="font-size: 16px; color: #111827;">
                    <a href="tel:{{ $technician->phone }}" style="color: #7c3aed; text-decoration: none;">
                        {{ $technician->phone }}
                    </a>
                </p>
            </div>
            
            <div>
                <label style="display: block; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">
                    Location
                </label>
                <p style="font-size: 16px; color: #111827;">{{ $technician->location }}</p>
            </div>
            
            <div>
                <label style="display: block; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">
                    Specialization
                </label>
                <p style="font-size: 16px; color: #111827;">{{ $technician->specialization }}</p>
            </div>
            
            <div>
                <label style="display: block; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">
                    Years of Experience
                </label>
                <p style="font-size: 16px; color: #111827;">{{ $technician->years_of_experience }} years</p>
            </div>
            
            <div>
                <label style="display: block; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">
                    Certifications
                </label>
                <p style="font-size: 16px; color: #111827;">{{ $technician->certifications ?: 'None provided' }}</p>
            </div>
            
            <div>
                <label style="display: block; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">
                    Submission Date
                </label>
                <p style="font-size: 16px; color: #111827;">{{ $technician->created_at->format('M d, Y h:i A') }}</p>
            </div>
        </div>

        @if($technician->additional_info)
            <div>
                <label style="display: block; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">
                    Additional Information
                </label>
                <div style="background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: 16px; font-size: 15px; color: #111827; line-height: 1.6;">
                    {{ $technician->additional_info }}
                </div>
            </div>
        @endif
    </div>

    <div style="padding: 24px; border-top: 1px solid #e5e7eb; background: #f9fafb;">
        <form action="{{ route('admin.technicians.destroy', $technician->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this technician application? This action cannot be undone.');">
            @csrf
            @method('DELETE')
            <button type="submit" style="background: #dc2626; color: white; padding: 10px 20px; border: none; border-radius: 6px; font-weight: 500; cursor: pointer; font-size: 14px;">
                Delete Application
            </button>
        </form>
    </div>
</div>
@endsection
