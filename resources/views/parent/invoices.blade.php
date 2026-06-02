@extends('layouts.app')

@section('content')
<h2 style="color: var(--primary-blue); margin-bottom: 2rem;">Tagihan Saya</h2>

<div class="glass-card">
    <div style="overflow-x: auto;">
        <table>
            <thead>
                <tr>
                    <th>Siswa</th>
                    <th>Jenis Tagihan</th>
                    <th>Periode</th>
                    <th>Jatuh Tempo</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($invoices as $inv)
                <tr>
                    <td>{{ $inv->student->name }}<br><small style="color: #666; font-family: monospace;">{{ $inv->student->nisn }}</small></td>
                    <td>{{ $inv->sppType->name }}</td>
                    <td>{{ $inv->month }} {{ $inv->year }}</td>
                    <td>{{ \Carbon\Carbon::parse($inv->due_date)->format('d M Y') }}</td>
                    <td style="font-weight: 600;">Rp {{ number_format($inv->total_amount, 0, ',', '.') }}</td>
                    <td><span class="badge badge-{{ strtolower($inv->status) }}">{{ $inv->status }}</span></td>
                    <td>
                        @if($inv->status !== 'Lunas')
                        <button class="btn btn-orange" style="padding: 0.4rem 0.8rem; font-size: 0.9rem;" 
                            onclick="openPaymentModal({{ $inv->id }}, '{{ $inv->student->name }}', '{{ $inv->month }} {{ $inv->year }}', '{{ number_format($inv->total_amount, 0, ',', '.') }}')">
                            Bayar
                        </button>
                        @else
                        <span style="color: #10B981; font-weight: 600;">✓ Selesai</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" style="text-align: center;">Tidak ada tagihan.</td></tr>
                @endforelse
            </tbody>
        </table>
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
        background: white; width: 100%; max-width: 450px; padding: 2rem;
        border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        animation: modalIn 0.3s ease-out;
    }
    @keyframes modalIn {
        from { transform: translateY(20px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
</style>

<!-- Payment Modal -->
<div id="paymentModal" class="modal-overlay">
    <div class="modal-content glass-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h3 style="margin: 0; color: var(--primary-blue);">Proses Pembayaran</h3>
            <button onclick="closeModal('paymentModal')" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: #666;">&times;</button>
        </div>
        
        <div style="background: #f8fafc; border-radius: 8px; padding: 1rem; margin-bottom: 1.5rem;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                <span style="color: #666;">Siswa:</span>
                <strong id="pay_student"></strong>
            </div>
            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                <span style="color: #666;">Periode:</span>
                <strong id="pay_month"></strong>
            </div>
            <div style="display: flex; justify-content: space-between; border-top: 1px dashed #cbd5e1; padding-top: 0.5rem; margin-top: 0.5rem;">
                <span style="color: #666;">Total Pembayaran:</span>
                <strong style="color: var(--orange); font-size: 1.2rem;">Rp <span id="pay_amount"></span></strong>
            </div>
        </div>

        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Metode Pembayaran (Virtual Account)</label>
            <select id="payment_method" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 8px;">
                @foreach($paymentMethods as $pm)
                    <option value="{{ $pm->id }}">{{ $pm->name }}</option>
                @endforeach
            </select>
        </div>
        
        <div style="display: flex; justify-content: flex-end; gap: 1rem;">
            <button type="button" class="btn" style="background: #e5e7eb; color: #374151;" onclick="closeModal('paymentModal')">Batal</button>
            <button type="button" class="btn btn-orange" id="btnConfirmPay">Bayar Sekarang</button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    let currentInvoiceId = null;

    function closeModal(id) {
        document.getElementById(id).style.display = 'none';
    }
    
    function openPaymentModal(invoiceId, studentName, month, amount) {
        currentInvoiceId = invoiceId;
        document.getElementById('pay_student').innerText = studentName;
        document.getElementById('pay_month').innerText = month;
        document.getElementById('pay_amount').innerText = amount;
        document.getElementById('paymentModal').style.display = 'flex';
    }

    document.getElementById('btnConfirmPay').addEventListener('click', function() {
        const methodId = document.getElementById('payment_method').value;
        const btn = this;
        btn.innerText = 'Memproses...';
        btn.disabled = true;

        fetch('/api/pay', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ invoice_id: currentInvoiceId, payment_method_id: methodId })
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                closeModal('paymentModal');
                
                // Show Success Toast
                const toast = document.createElement('div');
                toast.innerHTML = `<div style="position: fixed; top: 20px; right: 20px; background: var(--success); color: white; padding: 1rem 1.5rem; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); z-index: 9999; animation: slideDown 0.3s ease-out;">
                    ✓ Pembayaran Berhasil Diproses!
                </div>`;
                document.body.appendChild(toast);
                
                if (!document.getElementById('toastStyle')) {
                    const style = document.createElement('style');
                    style.id = 'toastStyle';
                    style.innerHTML = `@keyframes slideDown { from { transform: translateY(-100px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }`;
                    document.head.appendChild(style);
                }
                
                setTimeout(() => window.location.reload(), 2000);
            } else {
                alert('Gagal: ' + data.message);
                btn.innerText = 'Bayar Sekarang';
                btn.disabled = false;
            }
        });
    });
</script>
@endpush
