<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Student;
use App\Models\DiscountRule;
use App\Models\PaymentActivity;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $activities = PaymentActivity::orderBy('created_at', 'desc')->take(8)->get();
        
        $stats = [
            'pemasukan' => Invoice::where('status', 'Lunas')->sum('total_amount'),
            'tunggakan' => Invoice::where('status', 'Tunggakan')->sum('total_amount'),
            'siswa_aktif' => Student::count()
        ];

        return view('admin.dashboard', compact('activities', 'stats'));
    }

    public function students()
    {
        $students = Student::with('parent')->paginate(10);
        $parents = User::where('role', 'parent')->get();
        return view('admin.students', compact('students', 'parents'));
    }

    public function storeStudent(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'nisn' => 'required|string|unique:students,nisn',
            'class_name' => 'required|string|max:50',
            'parent_id' => 'required|exists:users,id',
            'anak_guru' => 'boolean'
        ]);

        $data['anak_guru'] = $request->has('anak_guru');
        Student::create($data);

        return back()->with('success', 'Siswa berhasil ditambahkan');
    }

    public function updateStudent(Request $request, $id)
    {
        $student = Student::findOrFail($id);
        
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'nisn' => 'required|string|unique:students,nisn,'.$id,
            'class_name' => 'required|string|max:50',
            'parent_id' => 'required|exists:users,id',
            'anak_guru' => 'boolean'
        ]);

        $data['anak_guru'] = $request->has('anak_guru');
        $student->update($data);

        return back()->with('success', 'Siswa berhasil diupdate');
    }

    public function deleteStudent($id)
    {
        $student = Student::findOrFail($id);
        $student->delete();
        
        return back()->with('success', 'Siswa berhasil dihapus');
    }

    public function tunggakan()
    {
        $invoices = Invoice::with(['student', 'sppType'])
            ->where('status', 'Tunggakan')
            ->orderBy('due_date', 'asc')
            ->paginate(10);
            
        return view('admin.tunggakan', compact('invoices'));
    }

    public function rules()
    {
        $rules = DiscountRule::all();
        return view('admin.rules', compact('rules'));
    }

    public function storeRule(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'condition' => 'required|string',
            'discount_percentage' => 'required|numeric|min:1|max:100',
        ]);
        
        $data['is_active'] = true;
        DiscountRule::create($data);

        return back()->with('success', 'Rule berhasil dibuat');
    }

    public function deleteRule($id)
    {
        DiscountRule::findOrFail($id)->delete();
        return back()->with('success', 'Rule berhasil dihapus');
    }
}
