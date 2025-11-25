<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\History;
use App\Models\Analyse;
use App\Models\Message;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;


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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function booking(Request $request)
    {
         // Validate the request
         $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email',
            'analysisType' => 'required|exists:analyses,id',
            'date' => 'required|date',
            'time' => 'required'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
       

        try {
           
           $patient = Patient::firstOrCreate([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'gender' => null,
            'birth_date' => null
           ]);
           

           $history = History::create([
            'patient_id' => $patient->id,
            'analyse_id' => $request->analysisType,
            'analysis_date' => $request->date,
            'time' => $request->time,
            'status' => 'pending',
            'result' => null
           ]);
           
            
            

           // Get analysis name for success message
           $analysis = Analyse::find($request->analysisType);
           $analysisName = $analysis ? $analysis->name : 'التحليل';

           return redirect()->back()->with('success', "تم حجز موعد {$analysisName} للسيد/ة {$request->name} بنجاح، سنتصل بك على الرقم {$request->phone} لتأكيد الحجز");

        } catch (\Exception $e) {
            Log::error('Booking error:', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'حدث خطأ أثناء حفظ الحجز، يرجى المحاولة مرة أخرى');
        }
    }

    public function message(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'message' => 'required|string'
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
    
            return redirect()->back()->with('success', 'تم إرسال رسالتك بنجاح وسنرد عليك في أقرب وقت');
    
        } catch (\Exception $e) {
            Log::error('Message sending error:', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'حدث خطأ أثناء إرسال الرسالة، يرجى المحاولة مرة أخرى');
        }
    }


}