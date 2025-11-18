<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Partner;
use App\Models\Technician;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', 'month'); // day, week, month, year
        
        // Generate date ranges for the chart
        $dates = $this->getDateRange($period);
        
        // Service Requests data
        $contactsData = [];
        foreach ($dates as $date) {
            $contactsData[] = Contact::whereDate('created_at', $date)->count();
        }
        
        // Partners data
        $partnersData = [];
        foreach ($dates as $date) {
            $partnersData[] = Partner::whereDate('created_at', $date)->count();
        }
        
        // Technicians data
        $techniciansData = [];
        foreach ($dates as $date) {
            $techniciansData[] = Technician::whereDate('created_at', $date)->count();
        }
        
        // Overall statistics
        $totalStats = [
            'contacts' => Contact::count(),
            'partners' => Partner::count(),
            'technicians' => Technician::count(),
            'unread_contacts' => Contact::where('status', 'unread')->count(),
            'pending_partners' => Partner::where('status', 'pending')->count(),
            'pending_technicians' => Technician::where('status', 'pending')->count(),
        ];
        
        // Status breakdown
        $contactsByStatus = [
            'unread' => Contact::where('status', 'unread')->count(),
            'read' => Contact::where('status', 'read')->count(),
            'archived' => Contact::where('status', 'archived')->count(),
        ];
        
        $partnersByStatus = [
            'pending' => Partner::where('status', 'pending')->count(),
            'approved' => Partner::where('status', 'approved')->count(),
            'rejected' => Partner::where('status', 'rejected')->count(),
        ];
        
        $techniciansByStatus = [
            'pending' => Technician::where('status', 'pending')->count(),
            'approved' => Technician::where('status', 'approved')->count(),
            'rejected' => Technician::where('status', 'rejected')->count(),
        ];
        
        // Monthly trends (last 12 months)
        $monthlyTrends = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthlyTrends[] = [
                'month' => $month->format('M Y'),
                'contacts' => Contact::whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)->count(),
                'partners' => Partner::whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)->count(),
                'technicians' => Technician::whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)->count(),
            ];
        }
        
        return view('admin.reports', compact(
            'dates', 'contactsData', 'partnersData', 'techniciansData',
            'totalStats', 'contactsByStatus', 'partnersByStatus', 'techniciansByStatus',
            'monthlyTrends', 'period'
        ));
    }
    
    private function getDateRange($period)
    {
        $dates = [];
        
        switch ($period) {
            case 'day':
                // Last 24 hours
                for ($i = 23; $i >= 0; $i--) {
                    $dates[] = Carbon::now()->subHours($i)->format('Y-m-d H:00:00');
                }
                break;
            case 'week':
                // Last 7 days
                for ($i = 6; $i >= 0; $i--) {
                    $dates[] = Carbon::now()->subDays($i)->format('Y-m-d');
                }
                break;
            case 'month':
                // Last 30 days
                for ($i = 29; $i >= 0; $i--) {
                    $dates[] = Carbon::now()->subDays($i)->format('Y-m-d');
                }
                break;
            case 'year':
                // Last 12 months
                for ($i = 11; $i >= 0; $i--) {
                    $dates[] = Carbon::now()->subMonths($i)->format('Y-m');
                }
                break;
        }
        
        return $dates;
    }
}
