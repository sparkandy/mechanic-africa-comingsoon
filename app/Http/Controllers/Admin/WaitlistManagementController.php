<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Waitlist;
use Illuminate\Http\Request;

class WaitlistManagementController extends Controller
{
    public function index()
    {
        $waitlists = Waitlist::orderBy('created_at', 'desc')->paginate(50);
        
        return view('admin.waitlist', compact('waitlists'));
    }

    public function destroy($id)
    {
        $waitlist = Waitlist::findOrFail($id);
        $waitlist->delete();

        return back()->with('success', 'Waitlist entry deleted successfully.');
    }

    public function export()
    {
        $waitlists = Waitlist::orderBy('created_at', 'desc')->get();
        
        $filename = 'waitlist_' . date('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($waitlists) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Name', 'Email', 'Phone', 'City', 'IP Address', 'Joined Date']);

            foreach ($waitlists as $waitlist) {
                fputcsv($file, [
                    $waitlist->id,
                    $waitlist->name,
                    $waitlist->email,
                    $waitlist->phone,
                    $waitlist->city,
                    $waitlist->ip_address,
                    $waitlist->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
