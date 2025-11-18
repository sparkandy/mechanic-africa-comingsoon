<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Partner;
use App\Models\Technician;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_contacts' => Contact::count(),
            'unread_contacts' => Contact::where('status', 'unread')->count(),
            'total_partners' => Partner::count(),
            'pending_partners' => Partner::where('status', 'pending')->count(),
            'total_technicians' => Technician::count(),
            'pending_technicians' => Technician::where('status', 'pending')->count(),
            'this_week' => Contact::where('created_at', '>=', Carbon::now()->startOfWeek())->count() +
                          Partner::where('created_at', '>=', Carbon::now()->startOfWeek())->count() +
                          Technician::where('created_at', '>=', Carbon::now()->startOfWeek())->count(),
        ];

        // Get recent activities
        $recent_activities = collect();

        // Add recent contacts
        Contact::latest()->take(5)->get()->each(function($contact) use ($recent_activities) {
            $recent_activities->push([
                'type' => 'contact',
                'name' => $contact->name,
                'email' => $contact->email,
                'status' => $contact->status,
                'date' => $contact->created_at->diffForHumans(),
                'view_url' => route('admin.contacts.show', $contact->id),
            ]);
        });

        // Add recent partners
        Partner::latest()->take(5)->get()->each(function($partner) use ($recent_activities) {
            $recent_activities->push([
                'type' => 'partner',
                'name' => $partner->company_name,
                'email' => $partner->email,
                'status' => $partner->status,
                'date' => $partner->created_at->diffForHumans(),
                'view_url' => route('admin.partners.show', $partner->id),
            ]);
        });

        // Add recent technicians
        Technician::latest()->take(5)->get()->each(function($technician) use ($recent_activities) {
            $recent_activities->push([
                'type' => 'technician',
                'name' => $technician->full_name,
                'email' => $technician->email,
                'status' => $technician->status,
                'date' => $technician->created_at->diffForHumans(),
                'view_url' => route('admin.technicians.show', $technician->id),
            ]);
        });

        // Sort by date and take top 10
        $recent_activities = $recent_activities->sortByDesc(function($activity) {
            return strtotime($activity['date']);
        })->take(10);

        return view('admin.dashboard', compact('stats', 'recent_activities'));
    }
}
