<?php

namespace App\Http\Controllers;

use App\Models\Analyse;
use App\Models\Message;
use App\Models\Patient;
use App\Models\Request_reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class Labo_dzController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $analyses = Analyse::all(); // Or use your preferred method to get the data

        return view('Labo_dz', ['analyses' => $analyses]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function booking(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email',
            'gender' => 'required|in:male,female',
            'birth_date' => 'required|date',
            'analysisTypes' => 'required|array|min:1',
            'analysisTypes.*' => 'exists:analyses,id',
            'date' => 'nullable|date',
            'time' => 'nullable',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            // Create reservation request with patient info
            $requestReservation = Request_reservation::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'analyse_id' => $request->analysisTypes[0] ?? null,
                'gender' => $request->gender,
                'birth_date' => $request->birth_date,
                'preferred_date' => $request->date,
                'preferred_time' => $request->time,
                'status' => 'pending',
            ]);

            // Attach multiple analyses
            $requestReservation->analyses()->attach($request->analysisTypes);

            // Get analysis names for success message
            $analyses = Analyse::whereIn('id', $request->analysisTypes)->get();
            $analysisNames = $analyses->pluck('name')->implode(', ');

            // Redirect with success message and trigger PDF download
            return redirect()->back()
                ->with('success', __('messages.responses.booking_request_sent', ['analyses' => $analysisNames, 'name' => $request->name, 'phone' => $request->phone]))
                ->with('download_pdf', $requestReservation->id);
        } catch (\Exception $e) {
            Log::error('Booking error:', ['error' => $e->getMessage()]);

            return redirect()->back()->with('error', __('messages.responses.booking_error'));
        }
    }

    public function message(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'message' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            Message::create([
                'name' => $request->name,
                'email' => $request->email,
                'message' => $request->message,
            ]);

            return redirect()->back()->with('success', __('messages.responses.contact_sent'));
        } catch (\Exception $e) {
            Log::error('Message sending error:', ['error' => $e->getMessage()]);

            return redirect()->back()->with('error', __('messages.responses.generic_error'));
        }
    }

    public function analysisInfo()
    {
        $analyses = Analyse::all();

        return view('analysis-info', compact('analyses'));
    }
}
