<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\PaymentActivity;

class ApiController extends Controller
{
    public function payInvoice(Request $request)
    {
        $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'payment_method_id' => 'required|exists:payment_methods,id'
        ]);

        $invoice = Invoice::with('student')->findOrFail($request->invoice_id);
        
        if($invoice->status == 'Lunas') {
            return response()->json(['success' => false, 'message' => 'Invoice already paid']);
        }

        // Create Payment
        Payment::create([
            'invoice_id' => $invoice->id,
            'payment_method_id' => $request->payment_method_id,
            'amount' => $invoice->total_amount,
            'status' => 'Success',
            'payment_date' => now(),
        ]);

        // Update Invoice Status
        $invoice->update(['status' => 'Lunas']);

        // Create Activity for Admin
        PaymentActivity::create([
            'user_id' => $invoice->student->parent_id,
            'student_name' => $invoice->student->name,
            'amount' => $invoice->total_amount,
            'description' => "SPP {$invoice->month}",
            'status' => 'Lunas',
        ]);

        return response()->json(['success' => true]);
    }

    public function getAdminData()
    {
        $activities = PaymentActivity::orderBy('created_at', 'desc')->take(5)->get();
        
        $totalPemasukan = Invoice::where('status', 'Lunas')->sum('total_amount');
        $totalTunggakan = Invoice::where('status', 'Tunggakan')->sum('total_amount');
        $totalMenunggu = Invoice::where('status', 'Menunggu')->sum('total_amount');

        return response()->json([
            'activities' => $activities,
            'stats' => [
                'pemasukan' => $totalPemasukan,
                'tunggakan' => $totalTunggakan,
                'menunggu' => $totalMenunggu
            ]
        ]);
    }
}
