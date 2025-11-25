<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Analyse;
use Illuminate\Support\Facades\Storage;
use App\Models\History;

class analysesController extends Controller
{
    public function analyses(){
        $analyses = Analyse::orderBy('created_at', 'desc')->paginate(10);
        return view('Adminstration.analyses', compact('analyses'));
       }

       public function createAnalysis(){
        return view('Adminstration.add-analysis');
       }

       public function storeAnalysis(Request $request){
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'normal_range' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|string|max:100',
            'preparation_instructions' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('analyses', 'public');
        }

        Analyse::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'normal_range' => $validated['normal_range'],
            'price' => $validated['price'],
            'duration' => $validated['duration'],
            'preparation_instructions' => $validated['preparation_instructions'],
            'image' => $imagePath,
            'availability' => true,
        ]);

        return redirect()->route('analyses')->with('success', 'تم إضافة التحليل بنجاح');
       }

       public function editAnalysis($id){
        $analysis = Analyse::findOrFail($id);
        return view('Adminstration.edit-analysis', compact('analysis'));
       }

       public function updateAnalysis(Request $request, $id){
        $analysis = Analyse::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'normal_range' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|string|max:100',
            'preparation_instructions' => 'nullable|string',
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
            'description' => $validated['description'],
            'normal_range' => $validated['normal_range'],
            'price' => $validated['price'],
            'duration' => $validated['duration'],
            'preparation_instructions' => $validated['preparation_instructions'],
            'image' => $imagePath,
        ]);

        return redirect()->route('analyses')->with('success', 'تم تحديث التحليل بنجاح');
       }

       public function destroyAnalysis($id){
        $analysis = Analyse::findOrFail($id);

        // Check if analysis has any bookings
        $hasBookings = History::where('analyse_id', $id)->exists();
        if ($hasBookings) {
            return redirect()->route('analyses')->with('error', 'لا يمكن حذف التحليل لأنه مرتبط بحجوزات سابقة');
        }

        // Delete image if exists
        if ($analysis->image) {
            Storage::disk('public')->delete($analysis->image);
        }

        $analysis->delete();

        return redirect()->route('analyses')->with('success', 'تم حذف التحليل بنجاح');
       }

       public function toggleAvailability($id){
        $analysis = Analyse::findOrFail($id);
        $analysis->update([
            'availability' => !$analysis->availability
        ]);


        $status = $analysis->availability ? 'تفعيل' : 'تعطيل';
        return redirect()->route('analyses')->with('success', "تم {$status} التحليل بنجاح");
       }
}
