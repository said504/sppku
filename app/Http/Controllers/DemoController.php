<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Invoice;
use App\Models\PaymentActivity;
use App\Models\PaymentMethod;

class DemoController extends Controller
{
    public function index()
    {
        // 1. Get Parent Data (Sarah)
        $parent = User::where('role', 'parent')->where('email', 'sarah@spp.com')->first();
        
        $invoices = Invoice::with(['sppType', 'student'])->whereHas('student', function($q) use ($parent) {
            $q->where('parent_id', $parent->id);
        })->get();

        $tunggakan = $invoices->where('status', 'Tunggakan')->sum('total_amount');
        $lunas = $invoices->where('status', 'Lunas')->sum('total_amount');
        $menunggu = $invoices->where('status', 'Menunggu')->sum('total_amount');

        $paymentMethods = PaymentMethod::all();

        // 2. Get Admin Data
        $activities = PaymentActivity::orderBy('created_at', 'desc')->take(5)->get();

        return view('demo', compact('parent', 'invoices', 'tunggakan', 'lunas', 'menunggu', 'paymentMethods', 'activities'));
    }
}
