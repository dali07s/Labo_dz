<?php

namespace App\Http\Controllers;

use App\Models\History;
use App\Models\Request_reservation;
use App\Models\Patient;
use App\Models\Reminder;

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

    public function filterReservations(Request $request)
    {
        // This method handles the filter form submission
        // It redirects back to reservations with query parameters
        return $this->reservations($request);
    }

    // Add method to update booking status
    public function updateBookingStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,completed'
        ]);

        $booking = History::findOrFail($id);
        $booking->update(['status' => $request->status]);

        return redirect()->route('reservations')->with('success', 'تم تحديث حالة الحجز بنجاح');
    }

    // Show reservation requests
    public function reservationRequests(Request $request)
    {
        $query = Request_reservation::with(['analyse']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search by name or phone
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('phone', 'like', "%$search%");
            });
        }

        $requests = $query->orderByDesc('created_at')->paginate(10);

        return view('Adminstration.reservation-requests', [
            'requests' => $requests,
        ]);
    }

    // Confirm reservation request
    public function confirmRequest(Request $request, $id)
    {
        $reservationRequest = Request_reservation::findOrFail($id);

        if ($reservationRequest->status !== 'pending') {
            return redirect()->back()->with('error', 'هذا الطلب تمت معالجته بالفعل');
        }

        $request->validate([
            'analysis_date' => 'required|date',
            'time' => 'required',
            'admin_notes' => 'nullable|string'
        ]);

        try {
            // Create patient record
            $patient = Patient::create([
                'name' => $reservationRequest->name,
                'email' => $reservationRequest->email,
                'phone' => $reservationRequest->phone,
                'gender' => $reservationRequest->gender,
                'birth_date' => $reservationRequest->birth_date
            ]);

            // Create separate history records for each analysis
            $histories = [];
            foreach ($reservationRequest->analyses as $analyse) {
                $history = History::create([
                    'patient_id' => $patient->id,
                    'analyse_id' => $analyse->id,
                    'analysis_date' => $request->analysis_date,
                    'time' => $request->time,
                    'status' => 'confirmed',
                    'result' => null
                ]);
                $histories[] = $history;

                // Create reminder for each analysis
                Reminder::create([
                    'history_id' => $history->id,
                    'patient_id' => $patient->id,
                    'analyse_id' => $analyse->id,
                    'scheduled_for' => \Carbon\Carbon::parse($request->analysis_date)->subDay(), // 24 hours before
                    'is_sent' => false
                ]);
            }

            // Update reservation request
            $reservationRequest->update([
                'status' => 'confirmed',
                'patient_id' => $patient->id,
                'history_id' => $history->id,
                'admin_notes' => $request->admin_notes
            ]);

            return redirect()->route('reservation.requests')->with('success', 'تم تأكيد الطلب وإنشاء الحجز بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء تأكيد الطلب: ' . $e->getMessage());
        }
    }

    // Reject reservation request
    public function rejectRequest(Request $request, $id)
    {
        $reservationRequest = Request_reservation::findOrFail($id);

        if ($reservationRequest->status !== 'pending') {
            return redirect()->back()->with('error', 'هذا الطلب تمت معالجته بالفعل');
        }

        $request->validate([
            'admin_notes' => 'required|string'
        ]);

        $reservationRequest->update([
            'status' => 'rejected',
            'admin_notes' => $request->admin_notes
        ]);

        return redirect()->route('reservation.requests')->with('success', 'تم رفض الطلب بنجاح');
    }
}
