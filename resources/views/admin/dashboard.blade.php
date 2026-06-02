@extends('layouts.app')

@section('content')
<h1 style="color: var(--primary-blue); margin-bottom: 2rem;">Admin Dashboard</h1>

<div class="stats-grid">
    <div class="glass-card stat-card" style="border-color: var(--success);">
        <div style="color: #666;">Total Pemasukan</div>
        <div class="stat-value" style="color: var(--success);">Rp {{ number_format($stats['pemasukan'], 0, ',', '.') }}</div>
    </div>
    <div class="glass-card stat-card" style="border-color: var(--orange);">
        <div style="color: #666;">Total Tunggakan</div>
        <div class="stat-value" style="color: var(--orange);">Rp {{ number_format($stats['tunggakan'], 0, ',', '.') }}</div>
    </div>
    <div class="glass-card stat-card" style="border-color: var(--accent-blue);">
        <div style="color: #666;">Siswa Aktif</div>
        <div class="stat-value" style="color: var(--accent-blue);">{{ $stats['siswa_aktif'] }} Siswa</div>
    </div>
</div>

<div style="display: flex; gap: 1.5rem; flex-wrap: wrap;">
    <!-- Charts -->
    <div class="glass-card" style="flex: 2; min-width: 300px;">
        <h3 style="margin-top: 0;">Statistik Keuangan</h3>
        <div style="height: 250px;">
            <canvas id="financeChart"></canvas>
        </div>
    </div>

    <!-- Live Activity Feed -->
    <div class="glass-card" style="flex: 1; min-width: 300px;">
        <h3 style="margin-top: 0;">Live Activity Feed</h3>
        <div id="activityFeed">
            @foreach($activities as $act)
            <div style="padding: 1rem; border-left: 4px solid var(--success); background: white; margin-bottom: 0.75rem; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.02);">
                <div style="font-weight: 700;">{{ $act->student_name }}</div>
                <div style="font-size: 0.9rem; color: #666;">Membayar Rp {{ number_format($act->amount, 0, ',', '.') }} ({{ $act->description }})</div>
                <div style="font-size: 0.8rem; color: #999; margin-top: 0.25rem;">{{ $act->created_at->diffForHumans() }}</div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const ctx = document.getElementById('financeChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
        datasets: [{
            label: 'Pemasukan',
            data: [12000000, 15000000, 15000000, 15000000, 13500000, {{ $stats['pemasukan'] }}],
            backgroundColor: '#3B82F6',
            borderRadius: 4
        }]
    },
    options: { responsive: true, maintainAspectRatio: false }
});

// Polling for live activity updates (simulating real-time WebSocket)
setInterval(() => {
    fetch('/api/admin-data')
    .then(res => res.json())
    .then(data => {
        let html = '';
        data.activities.slice(0, 5).forEach(act => {
            html += `
            <div style="padding: 1rem; border-left: 4px solid var(--success); background: white; margin-bottom: 0.75rem; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.02);">
                <div style="font-weight: 700;">${act.student_name}</div>
                <div style="font-size: 0.9rem; color: #666;">Membayar Rp ${new Intl.NumberFormat('id-ID').format(act.amount)} (${act.description})</div>
            </div>
            `;
        });
        document.getElementById('activityFeed').innerHTML = html;
    });
}, 5000);
</script>
@endpush
