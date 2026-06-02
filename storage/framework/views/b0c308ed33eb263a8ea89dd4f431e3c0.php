<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Website Responsif Pembayaran SPP (Interkoneksi Peran)</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary-blue: #1E3A5F;
            --accent-blue: #3B82F6;
            --orange: #F97316;
            --success: #10B981;
            --warning: #F59E0B;
            --bg-color: #F0F4FF;
            --card-bg: rgba(255, 255, 255, 0.85);
            --border-color: rgba(255, 255, 255, 0.3);
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: url('https://images.unsplash.com/photo-1522071820081-009f0129c71c?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80') center/cover fixed;
            margin: 0;
            padding: 0;
            color: #333;
            overflow-x: hidden;
        }
        .overlay {
            background: var(--bg-color);
            opacity: 0.85;
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            z-index: -1;
            backdrop-filter: blur(10px);
        }
        .grain {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: -1;
            opacity: 0.05; pointer-events: none;
            background-image: url('data:image/svg+xml;utf8,%3Csvg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg"%3E%3Cfilter id="noiseFilter"%3E%3CfeTurbulence type="fractalNoise" baseFrequency="0.65" numOctaves="3" stitchTiles="stitch"/%3E%3C/filter%3E%3Crect width="100%25" height="100%25" filter="url(%23noiseFilter)"/%3E%3C/svg%3E');
        }
        .container {
            display: flex;
            width: 100vw;
            height: 100vh;
            overflow: hidden;
        }
        
        /* Parent Panel */
        .parent-panel {
            width: 60%;
            padding: 2rem;
            display: flex;
            flex-direction: column;
            border-right: 2px solid var(--border-color);
            position: relative;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }
        h1, h2, h3 { margin: 0; color: var(--primary-blue); }
        .glass-card {
            background: var(--card-bg);
            backdrop-filter: blur(12px);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            margin-bottom: 1.5rem;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        .stat-card {
            text-align: center;
        }
        .stat-card.stat-card-tunggakan { border-top: 4px solid var(--orange); }
        .stat-card.stat-card-lunas { border-top: 4px solid var(--success); }
        .stat-card.stat-card-menunggu { border-top: 4px solid var(--warning); }
        
        .stat-value {
            font-size: 1.75rem;
            font-weight: 800;
            margin-top: 0.5rem;
        }
        .btn {
            background: var(--accent-blue);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
        }
        .btn-orange { background: var(--orange); }
        .btn-orange:hover { box-shadow: 0 4px 12px rgba(249, 115, 22, 0.4); }

        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }
        th { font-weight: 600; color: var(--primary-blue); }
        .badge {
            padding: 0.25rem 0.75rem;
            border-radius: 999px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        .badge-tunggakan { background: #fee2e2; color: #dc2626; }
        .badge-lunas { background: #d1fae5; color: #059669; }
        .badge-menunggu { background: #fef3c7; color: #d97706; }

        /* Admin Panel */
        .admin-panel {
            width: 40%;
            background: rgba(30, 58, 95, 0.03);
            display: flex;
            flex-direction: column;
            overflow-y: auto;
        }
        .admin-tablet {
            padding: 2rem;
            border-bottom: 2px solid var(--border-color);
        }
        .admin-mobile {
            padding: 2rem;
            flex-grow: 1;
            background: rgba(255,255,255,0.3);
        }
        
        /* Activity Stream */
        .activity-item {
            display: flex;
            align-items: center;
            padding: 1rem;
            background: white;
            border-radius: 12px;
            margin-bottom: 1rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.02);
            border-left: 4px solid var(--success);
            animation: slideIn 0.5s ease-out forwards;
            opacity: 0;
            transform: translateX(20px);
        }
        @keyframes slideIn {
            to { opacity: 1; transform: translateX(0); }
        }

        /* Glowing Connection Stream */
        .connection-stream {
            position: absolute;
            top: 50%;
            right: -2px; /* overlap border */
            width: 100px;
            height: 4px;
            background: linear-gradient(90deg, var(--orange), var(--success));
            border-radius: 4px;
            box-shadow: 0 0 15px var(--success);
            z-index: 10;
            opacity: 0;
            pointer-events: none;
        }
        .stream-active {
            animation: flowStream 1.5s ease-out forwards;
        }
        @keyframes flowStream {
            0% { width: 0; opacity: 1; transform: translateX(0); }
            50% { width: 150px; opacity: 1; }
            100% { width: 0; opacity: 0; transform: translateX(150px); }
        }

        /* Rule Engine */
        .rule-code {
            background: #1e1e1e;
            color: #d4d4d4;
            padding: 1rem;
            border-radius: 8px;
            font-family: monospace;
            font-size: 0.9rem;
        }
        .keyword { color: #569cd6; }
        .variable { color: #9cdcfe; }
        .string { color: #ce9178; }
        .operator { color: #d4d4d4; }
        
        /* Responsive tweaks */
        @media (max-width: 1024px) {
            .container { flex-direction: column; height: auto; }
            .parent-panel, .admin-panel { width: 100%; border-right: none; }
            .connection-stream { display: none; } /* stream only makes sense side-by-side */
        }
    </style>
</head>
<body>
    <div class="overlay"></div>
    <div class="grain"></div>
    <div class="container">
        
        <!-- PARENT PANEL (Left) -->
        <div class="parent-panel">
            <div class="connection-stream" id="dataStream"></div>
            
            <div class="header">
                <div>
                    <h1>Halo, Orang Tua <?php echo e($parent->name); ?>! 👋</h1>
                    <p>Selamat datang di Portal Orang Tua</p>
                </div>
                <div style="display: flex; gap: 1rem; align-items: center;">
                    <div style="background: white; padding: 0.5rem; border-radius: 50%;">👤</div>
                </div>
            </div>

            <div class="stats-grid">
                <div class="glass-card stat-card stat-card-tunggakan">
                    <div style="color: #666;">Tunggakan Saat Ini</div>
                    <div class="stat-value" style="color: var(--orange);" id="parentTunggakan">Rp <?php echo e(number_format($tunggakan, 0, ',', '.')); ?></div>
                    <?php if($tunggakan > 0): ?>
                        <?php
                           $tunggakanInvoice = collect($invoices)->firstWhere('status', 'Tunggakan');
                        ?>
                        <button class="btn btn-orange" style="margin-top: 1rem; width: 100%;" onclick="payInvoice(<?php echo e($tunggakanInvoice->id); ?>)">Bayar Sekarang</button>
                    <?php endif; ?>
                </div>
                <div class="glass-card stat-card stat-card-lunas">
                    <div style="color: #666;">Status Lunas</div>
                    <div class="stat-value" style="color: var(--success);" id="parentLunas">Rp <?php echo e(number_format($lunas, 0, ',', '.')); ?></div>
                </div>
                <div class="glass-card stat-card stat-card-menunggu">
                    <div style="color: #666;">Menunggu</div>
                    <div class="stat-value" style="color: var(--warning);" id="parentMenunggu">Rp <?php echo e(number_format($menunggu, 0, ',', '.')); ?></div>
                </div>
            </div>

            <div class="glass-card" style="flex-grow: 1;">
                <h3 style="margin-bottom: 1.5rem;">Metira Invoice</h3>
                <table id="invoiceTable">
                    <thead>
                        <tr>
                            <th>Siswa</th>
                            <th>Tagihan</th>
                            <th>Bulan</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $invoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr id="row-<?php echo e($inv->id); ?>">
                            <td><?php echo e($inv->student->name); ?></td>
                            <td><?php echo e($inv->sppType->name); ?></td>
                            <td><?php echo e($inv->month); ?> <?php echo e($inv->year); ?></td>
                            <td>Rp <?php echo e(number_format($inv->total_amount, 0, ',', '.')); ?></td>
                            <td>
                                <span class="badge badge-<?php echo e(strtolower($inv->status)); ?> status-badge"><?php echo e($inv->status); ?></span>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            
            <div class="glass-card">
                <h3 style="margin-bottom: 1rem;">Metode Pembayaran Terfavorit</h3>
                <div style="display: flex; gap: 1rem;">
                    <?php $__currentLoopData = $paymentMethods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div style="background: white; border-radius: 8px; padding: 0.5rem 1rem; display: flex; align-items: center; gap: 0.5rem; border: 1px solid var(--border-color);">
                        <img src="<?php echo e($pm->logo_url); ?>" alt="<?php echo e($pm->name); ?>" style="height: 20px;">
                        <span style="font-weight: 600;"><?php echo e($pm->name); ?></span>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>

        <!-- ADMIN PANEL (Right) -->
        <div class="admin-panel">
            <!-- Tablet View (Top) -->
            <div class="admin-tablet">
                <div class="header">
                    <h2>Admin Dashboard 📊</h2>
                    <button class="btn">Generate Laporan</button>
                </div>
                
                <div style="display: flex; gap: 1.5rem; margin-top: 1.5rem;">
                    <div class="glass-card" style="flex: 2;">
                        <h4>Pemasukan Bulanan</h4>
                        <canvas id="barChart" height="150"></canvas>
                    </div>
                    <div class="glass-card" style="flex: 1;">
                        <h4>Status</h4>
                        <canvas id="doughnutChart" height="150"></canvas>
                    </div>
                </div>
            </div>

            <!-- Mobile View (Bottom) -->
            <div class="admin-mobile">
                <div class="glass-card">
                    <h3 style="margin-bottom: 1rem;">Dynamic Rule Engine</h3>
                    <div class="rule-code">
                        <span class="keyword">IF</span>(<span class="variable">Siswa.anak_guru</span> <span class="operator">==</span> <span class="keyword">True</span>) <br>
                        <span class="keyword">THEN</span> (<span class="variable">Tagihan.SPP</span> <span class="operator">*</span> <span class="string">0.5</span>)
                    </div>
                </div>

                <h3 style="margin-bottom: 1rem; margin-top: 1.5rem;">Aktivitas Pembayaran Terkini</h3>
                <div id="activityFeed">
                    <?php $__currentLoopData = $activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $act): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="activity-item">
                        <div style="background: var(--success); color: white; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 1rem;">
                            ✓
                        </div>
                        <div>
                            <div style="font-weight: 700;"><?php echo e($act->student_name); ?>'s Payment</div>
                            <div style="color: #666; font-size: 0.9rem;">Rp <?php echo e(number_format($act->amount, 0, ',', '.')); ?> (<?php echo e($act->description); ?>) - <?php echo e($act->status); ?></div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Setup Chart.js
        const barCtx = document.getElementById('barChart').getContext('2d');
        const doughnutCtx = document.getElementById('doughnutChart').getContext('2d');
        
        let barChart = new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
                datasets: [{
                    label: 'Pemasukan (Juta)',
                    data: [15, 15, 15, 15, 13.5, 15],
                    backgroundColor: '#3B82F6',
                    borderRadius: 4
                }]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });

        let doughnutChart = new Chart(doughnutCtx, {
            type: 'doughnut',
            data: {
                labels: ['Lunas', 'Menunggu', 'Tunggakan'],
                datasets: [{
                    data: [<?php echo e($lunas); ?>, <?php echo e($menunggu); ?>, <?php echo e($tunggakan); ?>],
                    backgroundColor: ['#10B981', '#F59E0B', '#F97316']
                }]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });

        // Payment logic (Interkoneksi Peran)
        function payInvoice(invoiceId) {
            // Trigger animation
            const stream = document.getElementById('dataStream');
            stream.classList.remove('stream-active');
            void stream.offsetWidth; // trigger reflow
            stream.classList.add('stream-active');

            // Send AJAX Request
            fetch('/api/pay', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    invoice_id: invoiceId,
                    payment_method_id: 1 // Default to BRIVA for demo
                })
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    // Update Parent UI
                    updateParentUI(invoiceId);
                    
                    // Fetch and Update Admin UI to show real-time interconnectivity
                    setTimeout(() => {
                        fetchAdminData();
                    }, 800); // delay for dramatic animation effect
                }
            });
        }

        function updateParentUI(invoiceId) {
            // Update Stats
            let tunggakanEl = document.getElementById('parentTunggakan');
            let lunasEl = document.getElementById('parentLunas');
            
            tunggakanEl.innerHTML = "Rp 0";
            
            // Assuming we just paid 1.5m and had 0 lunas previously for demo purposes
            // Let's increment dynamically
            fetch('/api/admin-data') // reuse this endpoint to get stats
            .then(res => res.json())
            .then(data => {
                lunasEl.innerHTML = "Rp " + new Intl.NumberFormat('id-ID').format(data.stats.pemasukan);
            });

            // Hide pay button
            let btn = document.querySelector('.btn-orange');
            if(btn) btn.style.display = 'none';

            // Update Table Row
            let row = document.getElementById('row-' + invoiceId);
            if(row) {
                let badge = row.querySelector('.status-badge');
                badge.className = 'badge badge-lunas status-badge';
                badge.innerHTML = 'Lunas';
            }
        }

        function fetchAdminData() {
            fetch('/api/admin-data')
            .then(res => res.json())
            .then(data => {
                // Update Activity Feed
                const feed = document.getElementById('activityFeed');
                feed.innerHTML = '';
                data.activities.forEach(act => {
                    feed.innerHTML += `
                        <div class="activity-item">
                            <div style="background: var(--success); color: white; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 1rem;">
                                ✓
                            </div>
                            <div>
                                <div style="font-weight: 700;">${act.student_name}'s Payment</div>
                                <div style="color: #666; font-size: 0.9rem;">Rp ${new Intl.NumberFormat('id-ID').format(act.amount)} (${act.description}) - ${act.status}</div>
                            </div>
                        </div>
                    `;
                });

                // Update Doughnut Chart
                doughnutChart.data.datasets[0].data = [
                    data.stats.pemasukan, 
                    data.stats.menunggu, 
                    data.stats.tunggakan
                ];
                doughnutChart.update();
            });
        }
    </script>
</body>
</html>
<?php /**PATH C:\Users\Mytz data enginer\SPP_SYSTEM\resources\views/demo.blade.php ENDPATH**/ ?>