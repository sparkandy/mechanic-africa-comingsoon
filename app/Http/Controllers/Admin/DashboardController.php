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
        // Service Requests Statistics
        $totalContacts = Contact::count();
        $unreadContacts = Contact::where('status', 'unread')->count();
        $todayContacts = Contact::whereDate('created_at', today())->count();
        $weekContacts = Contact::where('created_at', '>=', Carbon::now()->startOfWeek())->count();
        $monthContacts = Contact::where('created_at', '>=', Carbon::now()->startOfMonth())->count();
        
        // Partners Statistics
        $totalPartners = Partner::count();
        $pendingPartners = Partner::where('status', 'pending')->count();
        $approvedPartners = Partner::where('status', 'approved')->count();
        $rejectedPartners = Partner::where('status', 'rejected')->count();
        $todayPartners = Partner::whereDate('created_at', today())->count();
        $weekPartners = Partner::where('created_at', '>=', Carbon::now()->startOfWeek())->count();
        
        // Technicians Statistics
        $totalTechnicians = Technician::count();
        $pendingTechnicians = Technician::where('status', 'pending')->count();
        $approvedTechnicians = Technician::where('status', 'approved')->count();
        $rejectedTechnicians = Technician::where('status', 'rejected')->count();
        $todayTechnicians = Technician::whereDate('created_at', today())->count();
        $weekTechnicians = Technician::where('created_at', '>=', Carbon::now()->startOfWeek())->count();
        
        // Growth rates
        $lastWeekContacts = Contact::whereBetween('created_at', [
            Carbon::now()->subWeek()->startOfWeek(),
            Carbon::now()->subWeek()->endOfWeek()
        ])->count();
        $contactsGrowth = $lastWeekContacts > 0 ? round((($weekContacts - $lastWeekContacts) / $lastWeekContacts) * 100, 1) : 0;
        
        $lastWeekPartners = Partner::whereBetween('created_at', [
            Carbon::now()->subWeek()->startOfWeek(),
            Carbon::now()->subWeek()->endOfWeek()
        ])->count();
        $partnersGrowth = $lastWeekPartners > 0 ? round((($weekPartners - $lastWeekPartners) / $lastWeekPartners) * 100, 1) : 0;
        
        $lastWeekTechnicians = Technician::whereBetween('created_at', [
            Carbon::now()->subWeek()->startOfWeek(),
            Carbon::now()->subWeek()->endOfWeek()
        ])->count();
        $techniciansGrowth = $lastWeekTechnicians > 0 ? round((($weekTechnicians - $lastWeekTechnicians) / $lastWeekTechnicians) * 100, 1) : 0;
        
        // Recent activities
        $recentContacts = Contact::latest()->take(10)->get()->map(function($item) {
            return [
                'type' => 'Service Request',
                'description' => $item->name . ' submitted a service request',
                'created_at' => $item->created_at,
                'status' => $item->status
            ];
        });
        
        $recentPartners = Partner::latest()->take(10)->get()->map(function($item) {
            return [
                'type' => 'Partner Application',
                'description' => $item->workshop_name . ' applied for partnership',
                'created_at' => $item->created_at,
                'status' => $item->status
            ];
        });
        
        $recentTechnicians = Technician::latest()->take(10)->get()->map(function($item) {
            return [
                'type' => 'Technician Application',
                'description' => $item->name . ' applied as technician',
                'created_at' => $item->created_at,
                'status' => $item->status
            ];
        });
        
        $recentActivities = $recentContacts
            ->merge($recentPartners)
            ->merge($recentTechnicians)
            ->sortByDesc('created_at')
            ->take(10);

        return view('admin.dashboard', compact(
            'totalContacts', 'unreadContacts', 'todayContacts', 'weekContacts', 'monthContacts', 'contactsGrowth',
            'totalPartners', 'pendingPartners', 'approvedPartners', 'rejectedPartners', 'todayPartners', 'weekPartners', 'partnersGrowth',
            'totalTechnicians', 'pendingTechnicians', 'approvedTechnicians', 'rejectedTechnicians', 'todayTechnicians', 'weekTechnicians', 'techniciansGrowth',
            'recentActivities'
        ));
    }
}
