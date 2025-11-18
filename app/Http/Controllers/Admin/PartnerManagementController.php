<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\Request;

class PartnerManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = Partner::query()->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $partners = $query->paginate(20);
        
        return view('admin.partners.index', compact('partners'));
    }

    public function show($id)
    {
        $partner = Partner::findOrFail($id);
        return view('admin.partners.show', compact('partner'));
    }

    public function updateStatus(Request $request, $id)
    {
        $partner = Partner::findOrFail($id);
        
        $request->validate([
            'status' => 'required|in:pending,approved,rejected'
        ]);
        
        $partner->update(['status' => $request->status]);
        
        return redirect()->back()->with('success', 'Partner application status updated successfully');
    }

    public function destroy($id)
    {
        $partner = Partner::findOrFail($id);
        $partner->delete();
        
        return redirect()->route('admin.partners.index')->with('success', 'Partner application deleted successfully');
    }
}
