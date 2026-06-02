<?php $__env->startSection('content'); ?>
<h1 style="color: var(--primary-blue); margin-bottom: 2rem;">Halo, Orang Tua <?php echo e($parent->name); ?>!</h1>

<div class="stats-grid">
    <div class="glass-card stat-card stat-card-tunggakan">
        <div style="color: #666;">Tunggakan Saat Ini</div>
        <div class="stat-value" style="color: var(--orange);" id="val-tunggakan">Rp <?php echo e(number_format($tunggakan, 0, ',', '.')); ?></div>
        <?php if($tunggakan > 0): ?>
            <?php $tunggakanInvoice = $recentInvoices->where('status', 'Tunggakan')->first(); ?>
            <?php if($tunggakanInvoice): ?>
            <button class="btn btn-orange" style="margin-top: 1rem; width: 100%;" onclick="payInvoice(<?php echo e($tunggakanInvoice->id); ?>)">Bayar Sekarang</button>
            <?php endif; ?>
        <?php endif; ?>
    </div>
    <div class="glass-card stat-card stat-card-lunas">
        <div style="color: #666;">Total Lunas</div>
        <div class="stat-value" style="color: var(--success);" id="val-lunas">Rp <?php echo e(number_format($lunas, 0, ',', '.')); ?></div>
    </div>
    <div class="glass-card stat-card stat-card-menunggu">
        <div style="color: #666;">Total Menunggu</div>
        <div class="stat-value" style="color: var(--warning);" id="val-menunggu">Rp <?php echo e(number_format($menunggu, 0, ',', '.')); ?></div>
    </div>
</div>

<div class="glass-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h3 style="margin: 0;">Tagihan Terkini</h3>
        <a href="<?php echo e(route('parent.invoices')); ?>" style="color: var(--accent-blue); text-decoration: none; font-weight: 600;">Lihat Semua &rarr;</a>
    </div>
    <div style="overflow-x: auto;">
        <table>
            <thead>
                <tr>
                    <th>Siswa</th>
                    <th>Jenis</th>
                    <th>Bulan</th>
                    <th>Jumlah</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $recentInvoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr id="row-<?php echo e($inv->id); ?>">
                    <td><?php echo e($inv->student->name); ?></td>
                    <td><?php echo e($inv->sppType->name); ?></td>
                    <td><?php echo e($inv->month); ?> <?php echo e($inv->year); ?></td>
                    <td>Rp <?php echo e(number_format($inv->total_amount, 0, ',', '.')); ?></td>
                    <td><span class="badge badge-<?php echo e(strtolower($inv->status)); ?> status-badge"><?php echo e($inv->status); ?></span></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function payInvoice(invoiceId) {
    if(!confirm('Proses pembayaran menggunakan metode default (BRIVA)?')) return;
    
    fetch('/api/pay', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ invoice_id: invoiceId, payment_method_id: 1 })
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            alert('Pembayaran Berhasil!');
            window.location.reload();
        } else {
            alert('Gagal: ' + data.message);
        }
    });
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Mytz data enginer\SPP_SYSTEM\resources\views/parent/dashboard.blade.php ENDPATH**/ ?>