<?php

namespace App\Http\Controllers;

use App\Models\Administrator;
use App\Models\Message;
use App\Models\Analyse;
use App\Models\History;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
class dashboradController extends Controller
{
   public function dashboard(){
       $totalBookings = History::count();
       $totalPatients = Patient::count();
       $pendingBookings = History::where('status', 'pending')->count();
       $availableAnalyses = Analyse::where('availability', 1)->count();

       return view('Adminstration.dashboard', [
           'totalBookings' => $totalBookings,
           'pendingBookings' => $pendingBookings,
           'totalPatients' => $totalPatients,
           'availableAnalyses' => $availableAnalyses,
       ]);
   }





  





      

}



