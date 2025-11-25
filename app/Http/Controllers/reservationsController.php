<?php

namespace App\Http\Controllers;
use App\Models\History;

use Illuminate\Http\Request;

class reservationsController extends Controller
{
    public function reservations(Request $request)
    {
        // Start the query, eager loading patient and analyse
        $query = History::with(['patient', 'analyse']);
 
        // Apply date filters
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('analysis_date', [$request->start_date, $request->end_date]);
        } elseif ($request->filled('start_date')) {
            $query->whereDate('analysis_date', '>=', $request->start_date);
        } elseif ($request->filled('end_date')) {
            $query->whereDate('analysis_date', '<=', $request->end_date);
        }
 
        // Filter by booking status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
 
        // Search for patient by name or phone
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('patient', function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('phone', 'like', "%$search%");
            });
        }
 
        // Fetch booked histories, newest first
        $bookings = $query->orderByDesc('analysis_date')
                          ->orderByDesc('time')
                          ->paginate(10);
 
        return view('Adminstration.reservations', [
            'bookings' => $bookings,
        ]);
    }
 
    public function filterReservations(Request $request){
     // This method handles the filter form submission
     // It redirects back to reservations with query parameters
     return $this->reservations($request);
    }
 
    // Add method to update booking status
    public function updateBookingStatus(Request $request, $id){
        $request->validate([
            'status' => 'required|in:pending,confirmed,completed'
         ]);
 
         $booking = History::findOrFail($id);
         $booking->update(['status' => $request->status]);
 
         return redirect()->route('reservations')->with('success', 'تم تحديث حالة الحجز بنجاح');
     }
}
