<?php

namespace App\Http\Controllers;

use App\Models\Request_reservation;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PdfController extends Controller
{
    /**
     * Generate PDF for a reservation request
     *
     * @param int $id Reservation request ID
     * @return \Illuminate\Http\Response
     */
    public function generateReservationPdf($id)
    {
        // Load reservation with related analyses
        $reservation = Request_reservation::with('analyses')->findOrFail($id);

        // Generate PDF
        $pdf = Pdf::loadView('reservation-pdf', compact('reservation'));

        // Set paper size and orientation
        $pdf->setPaper('A4', 'portrait');

        // Generate filename
        $filename = 'reservation_' . $reservation->name . '_' . now()->format('YmdHis') . '.pdf';

        // Return PDF download
        return $pdf->download($filename);
    }
}
