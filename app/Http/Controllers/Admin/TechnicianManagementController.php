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
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $technicians = $query->paginate(20);
        
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
