<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Technician;
use Illuminate\Http\Request;

class TechnicianManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = Technician::query()->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('date_range')) {
            switch ($request->date_range) {
                case 'today':
                    $query->whereDate('created_at', today());
                    break;
                case 'week':
                    $query->where('created_at', '>=', now()->startOfWeek());
                    break;
                case 'month':
                    $query->where('created_at', '>=', now()->startOfMonth());
                    break;
                case 'year':
                    $query->where('created_at', '>=', now()->startOfYear());
                    break;
            }
        }

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhere('specialization', 'like', "%{$search}%");
            });
        }

        $technicians = $query->paginate(20)->withQueryString();
        
        return view('admin.technicians.index', compact('technicians'));
    }

    public function show($id)
    {
        $technician = Technician::findOrFail($id);
        return view('admin.technicians.show', compact('technician'));
    }

    public function updateStatus(Request $request, $id)
    {
        $technician = Technician::findOrFail($id);
        
        $request->validate([
            'status' => 'required|in:pending,approved,rejected'
        ]);
        
        $technician->update(['status' => $request->status]);
        
        return redirect()->back()->with('success', 'Technician application status updated successfully');
    }

    public function destroy($id)
    {
        $technician = Technician::findOrFail($id);
        $technician->delete();
        
        return redirect()->route('admin.technicians.index')->with('success', 'Technician application deleted successfully');
    }
}
