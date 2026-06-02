@extends('layouts.app')

@section('content')
<h2 style="color: var(--primary-blue); margin-bottom: 2rem;">Riwayat Pembayaran</h2>

<div class="glass-card">
    <div style="overflow-x: auto;">
        <table>
            <thead>
                <tr>
                    <th>Tanggal Bayar</th>
                    <th>Siswa</th>
                    <th>Periode Tagihan</th>
                    <th>Total Bayar</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($invoices as $inv)
                <tr>
                    <td>{{ $inv->updated_at->format('d M Y H:i') }}</td>
                    <td>{{ $inv->student->name }}</td>
                    <td>{{ $inv->month }} {{ $inv->year }}</td>
                    <td style="font-weight: 600; color: var(--success);">Rp {{ number_format($inv->total_amount, 0, ',', '.') }}</td>
                    <td><span class="badge badge-lunas">Lunas</span></td>
                </tr>
                @empty
                <tr><td colspan="5" style="text-align: center;">Belum ada riwayat pembayaran.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
