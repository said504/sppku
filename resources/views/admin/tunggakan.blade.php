@extends('layouts.app')

@section('content')
<h2 style="color: var(--primary-blue); margin-bottom: 2rem;">Manajemen Tunggakan</h2>

<div class="glass-card">
    <div style="overflow-x: auto;">
        <table>
            <thead>
                <tr>
                    <th>Siswa</th>
                    <th>Kelas</th>
                    <th>Jenis Tunggakan</th>
                    <th>Periode</th>
                    <th>Total</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($invoices as $inv)
                <tr>
                    <td>{{ $inv->student->name }}</td>
                    <td>{{ $inv->student->class_name }}</td>
                    <td>{{ $inv->sppType->name }}</td>
                    <td>{{ $inv->month }} {{ $inv->year }}</td>
                    <td style="font-weight: 600; color: var(--orange);">Rp {{ number_format($inv->total_amount, 0, ',', '.') }}</td>
                    <td>
                        <button class="btn" style="padding: 0.4rem 0.8rem; font-size: 0.9rem;" 
                            onclick="openReminderModal('{{ $inv->student->name }}', '{{ $inv->month }} {{ $inv->year }}', '{{ number_format($inv->total_amount, 0, ',', '.') }}')">
                            Kirim Reminder
                        </button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" style="text-align: center;">Tidak ada data tunggakan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top: 1rem;">
        {{ $invoices->links('pagination::simple-tailwind') }}
    </div>
</div>

<!-- Modal Background Shared -->
<style>
    .modal-overlay {
        display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; 
        background: rgba(0,0,0,0.5); z-index: 1000; justify-content: center; align-items: center;
        backdrop-filter: blur(4px);
    }
    .modal-content {
        background: white; width: 100%; max-width: 500px; padding: 2rem;
        border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        animation: modalIn 0.3s ease-out;
    }
    @keyframes modalIn {
        from { transform: translateY(20px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
</style>

<!-- Reminder Modal -->
<div id="reminderModal" class="modal-overlay">
    <div class="modal-content glass-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h3 style="margin: 0; color: var(--primary-blue);">Kirim Pesan Reminder</h3>
            <button onclick="closeModal('reminderModal')" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: #666;">&times;</button>
        </div>
        
        <p style="color: #666; margin-bottom: 1rem;">Draft pesan otomatis yang akan dikirimkan ke orang tua <strong id="rm_student"></strong>:</p>
        
        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 1rem; margin-bottom: 1.5rem; font-family: monospace; line-height: 1.5;">
            Yth. Orang Tua dari <span id="rm_sname" style="color:var(--accent-blue)"></span>,<br><br>
            Mengingatkan bahwa tagihan SPP periode <strong id="rm_period"></strong> sebesar <strong style="color:var(--orange)">Rp <span id="rm_amount"></span></strong> saat ini berstatus Tunggakan.<br><br>
            Mohon untuk segera melakukan pembayaran melalui Portal Orang Tua. Terima kasih.
        </div>
        
        <div style="display: flex; justify-content: flex-end; gap: 1rem;">
            <button type="button" class="btn" style="background: #e5e7eb; color: #374151;" onclick="closeModal('reminderModal')">Batal</button>
            <button type="button" class="btn" style="background: var(--success);" onclick="sendReminder()">Kirim Sekarang (Simulasi)</button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function closeModal(id) {
        document.getElementById(id).style.display = 'none';
    }
    
    function openReminderModal(studentName, period, amount) {
        document.getElementById('rm_student').innerText = studentName;
        document.getElementById('rm_sname').innerText = studentName;
        document.getElementById('rm_period').innerText = period;
        document.getElementById('rm_amount').innerText = amount;
        document.getElementById('reminderModal').style.display = 'flex';
    }

    function sendReminder() {
        closeModal('reminderModal');
        // We'll show a quick toast instead of an alert
        const toast = document.createElement('div');
        toast.innerHTML = `<div style="position: fixed; bottom: 20px; right: 20px; background: var(--success); color: white; padding: 1rem 1.5rem; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); z-index: 9999; animation: slideUp 0.3s ease-out;">
            Pesan reminder WhatsApp berhasil dikirim ke orang tua! ✓
        </div>`;
        document.body.appendChild(toast);
        
        // Add animation style if not exists
        if (!document.getElementById('toastStyle')) {
            const style = document.createElement('style');
            style.id = 'toastStyle';
            style.innerHTML = `@keyframes slideUp { from { transform: translateY(100px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }`;
            document.head.appendChild(style);
        }
        
        setTimeout(() => toast.remove(), 3000);
    }
</script>
@endpush
