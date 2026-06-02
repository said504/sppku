<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $parent = Auth::user();
        $invoices = Invoice::with(['sppType', 'student'])
            ->whereHas('student', function($q) use ($parent) {
                $q->where('parent_id', $parent->id);
            })->get();

        $tunggakan = $invoices->where('status', 'Tunggakan')->sum('total_amount');
        $lunas = $invoices->where('status', 'Lunas')->sum('total_amount');
        $menunggu = $invoices->where('status', 'Menunggu')->sum('total_amount');

        $paymentMethods = PaymentMethod::all();
        $recentInvoices = $invoices->take(5);

        return view('parent.dashboard', compact('parent', 'tunggakan', 'lunas', 'menunggu', 'paymentMethods', 'recentInvoices'));
    }

    public function invoices()
    {
        $parent = Auth::user();
        $invoices = Invoice::with(['sppType', 'student'])
            ->whereHas('student', function($q) use ($parent) {
                $q->where('parent_id', $parent->id);
            })->orderBy('due_date', 'desc')->get();

        $paymentMethods = PaymentMethod::all();
        return view('parent.invoices', compact('invoices', 'paymentMethods'));
    }

    public function history()
    {
        // For simplicity, we just fetch paid invoices
        $parent = Auth::user();
        $invoices = Invoice::with(['sppType', 'student', 'payments'])
            ->where('status', 'Lunas')
            ->whereHas('student', function($q) use ($parent) {
                $q->where('parent_id', $parent->id);
            })->orderBy('updated_at', 'desc')->get();
            
        return view('parent.history', compact('invoices'));
    }
}
