<?php

namespace App\Http\Controllers;

use App\Models\Analyse;
use App\Models\History;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class analysesController extends Controller
{
    public function analyses()
    {
        $analyses = Analyse::orderBy('created_at', 'desc')->paginate(10);

        return view('Adminstration.analyses', compact('analyses'));
    }

       public function createAnalysis()
       {
           return view('Adminstration.add-analysis');
       }

       public function storeAnalysis(Request $request)
       {
           $validated = $request->validate([
               'name' => 'required|string|max:255',
               'name_fr' => 'nullable|string|max:255',
               'description' => 'required|string',
               'description_fr' => 'nullable|string',
               'normal_range' => 'nullable|string',
               'price' => 'required|numeric|min:0',
               'duration' => 'required|string|max:100',
               'duration_fr' => 'nullable|string|max:100',
               'preparation_instructions' => 'nullable|string',
               'preparation_instructions_fr' => 'nullable|string',
               'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
           ]);

           $imagePath = null;
           if ($request->hasFile('image')) {
               $imagePath = $request->file('image')->store('analyses', 'public');
           }

           Analyse::create([
               'name' => $validated['name'],
               'name_fr' => $validated['name_fr'],
               'description' => $validated['description'],
               'description_fr' => $validated['description_fr'],
               'normal_range' => $validated['normal_range'],
               'price' => $validated['price'],
               'duration' => $validated['duration'],
               'duration_fr' => $validated['duration_fr'],
               'preparation_instructions' => $validated['preparation_instructions'],
               'preparation_instructions_fr' => $validated['preparation_instructions_fr'],
               'image' => $imagePath,
               'availability' => true,
           ]);

           return redirect()->route('analyses')->with('success', __('messages.responses.analysis_added'));
       }

       public function editAnalysis($id)
       {
           $analysis = Analyse::findOrFail($id);

           return view('Adminstration.edit-analysis', compact('analysis'));
       }

       public function updateAnalysis(Request $request, $id)
       {
           $analysis = Analyse::findOrFail($id);

           $validated = $request->validate([
               'name' => 'required|string|max:255',
               'name_fr' => 'nullable|string|max:255',
               'description' => 'required|string',
               'description_fr' => 'nullable|string',
               'normal_range' => 'nullable|string',
               'price' => 'required|numeric|min:0',
               'duration' => 'required|string|max:100',
               'duration_fr' => 'nullable|string|max:100',
               'preparation_instructions' => 'nullable|string',
               'preparation_instructions_fr' => 'nullable|string',
               'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
           ]);

           $imagePath = $analysis->image;
           if ($request->hasFile('image')) {
               // Delete old image if exists
               if ($analysis->image) {
                   Storage::disk('public')->delete($analysis->image);
               }
               $imagePath = $request->file('image')->store('analyses', 'public');
           }

           $analysis->update([
               'name' => $validated['name'],
               'name_fr' => $validated['name_fr'],
               'description' => $validated['description'],
               'description_fr' => $validated['description_fr'],
               'normal_range' => $validated['normal_range'],
               'price' => $validated['price'],
               'duration' => $validated['duration'],
               'duration_fr' => $validated['duration_fr'],
               'preparation_instructions' => $validated['preparation_instructions'],
               'preparation_instructions_fr' => $validated['preparation_instructions_fr'],
               'image' => $imagePath,
           ]);

           return redirect()->route('analyses')->with('success', __('messages.responses.analysis_updated'));
       }

       public function destroyAnalysis($id)
       {
           $analysis = Analyse::findOrFail($id);

           // Check if analysis has any bookings
           $hasBookings = History::where('analyse_id', $id)->exists();
           if ($hasBookings) {
               return redirect()->route('analyses')->with('error', __('messages.responses.analysis_delete_restricted'));
           }

           // Delete image if exists
           if ($analysis->image) {
               Storage::disk('public')->delete($analysis->image);
           }

           $analysis->delete();

           return redirect()->route('analyses')->with('success', __('messages.responses.analysis_deleted'));
       }

       public function toggleAvailability($id)
       {
           $analysis = Analyse::findOrFail($id);
           $analysis->update([
               'availability' => ! $analysis->availability,
           ]);

           $status = $analysis->availability ? __('messages.activated') : __('messages.deactivated');

           return redirect()->route('analyses')->with('success', __('messages.responses.analysis_status_changed', ['status' => $status]));
       }
}
