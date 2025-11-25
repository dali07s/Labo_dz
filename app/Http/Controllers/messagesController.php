<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\Patient;
use App\Models\History;
use Illuminate\Support\Facades\Mail;


class messagesController extends Controller
{
    public function messages()
    {
        $messages = Message::orderBy('created_at', 'desc')->paginate(10);
        $patients = Patient::with('histories.analyse')->get();

        return view('Adminstration.messages', compact('messages', 'patients'));
    }

    public function sendMessage(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:10240',
        ]);

        $patient = Patient::findOrFail($validated['patient_id']);

        // Send email to patient
        try {
            $attachmentPath = null;
            if ($request->hasFile('attachment')) {
                $attachmentPath = $request->file('attachment')->store('email-attachments', 'public');
            }

            Mail::send('emails.patient-message', [
                'patient' => $patient,
                'subject' => $validated['subject'],
                'content' => $validated['message']
            ], function ($message) use ($patient, $validated, $attachmentPath) {
                $message->to($patient->email)
                        ->subject($validated['subject']);

                if ($attachmentPath) {
                    $message->attach(storage_path('app/public/' . $attachmentPath));
                }
            });

            return redirect()->route('messages')->with('success', 'تم إرسال الرسالة بنجاح إلى ' . $patient->name);

        } catch (\Exception $e) {
            return redirect()->route('messages')->with('error', 'فشل في إرسال الرسالة: ' . $e->getMessage());
        }
    }

    public function sendResult(Request $request)
    {
        $validated = $request->validate([
            'history_id' => 'required|exists:histories,id',
            'additional_notes' => 'nullable|string',
            'result_file' => 'nullable|file|mimes:pdf,jpg,png|max:10240',
        ]);

        $history = History::with(['patient', 'analyse'])->findOrFail($validated['history_id']);

        try {
            $attachmentPath = null;
            if ($request->hasFile('result_file')) {
                $attachmentPath = $request->file('result_file')->store('results', 'public');
            }

            // Update history with result
            $history->update([
                'result' => $validated['additional_notes'],
                'status' => 'completed'
            ]);

            // Send result email
            Mail::send('emails.test-result', [
                'patient' => $history->patient,
                'history' => $history,
                'additional_notes' => $validated['additional_notes']
            ], function ($message) use ($history, $attachmentPath) {
                $message->to($history->patient->email)
                        ->subject('نتيجة تحليل ' . $history->analyse->name);

                if ($attachmentPath) {
                    $message->attach(storage_path('app/public/' . $attachmentPath));
                }
            });

            return redirect()->route('messages')->with('success', 'تم إرسال نتيجة التحليل بنجاح إلى ' . $history->patient->name);

        } catch (\Exception $e) {
            return redirect()->route('messages')->with('error', 'فشل في إرسال النتيجة: ' . $e->getMessage());
        }
    }

    public function deleteMessage($id)
    {
        $message = Message::findOrFail($id);
        $message->delete();

        return redirect()->route('messages')->with('success', 'تم حذف الرسالة بنجاح');
    }

    public function markAsRead($id)
    {
        $message = Message::findOrFail($id);
        $message->update(['is_read' => true]);

        return redirect()->route('messages')->with('success', 'تم تعيين الرسالة كمقروءة بنجاح');
    }
}
