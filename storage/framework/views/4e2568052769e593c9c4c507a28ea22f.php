<?php $__env->startSection('content'); ?>
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
                <?php $__empty_1 = true; $__currentLoopData = $invoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($inv->student->name); ?><br><small><?php echo e($inv->student->nisn); ?></small></td>
                    <td><?php echo e($inv->sppType->name); ?></td>
                    <td><?php echo e($inv->month); ?> <?php echo e($inv->year); ?></td>
                    <td><?php echo e(\Carbon\Carbon::parse($inv->due_date)->format('d M Y')); ?></td>
                    <td style="font-weight: 600;">Rp <?php echo e(number_format($inv->total_amount, 0, ',', '.')); ?></td>
                    <td><span class="badge badge-<?php echo e(strtolower($inv->status)); ?>"><?php echo e($inv->status); ?></span></td>
                    <td>
                        <?php if($inv->status !== 'Lunas'): ?>
                        <button class="btn" style="padding: 0.4rem 0.8rem; font-size: 0.9rem;" onclick="payInvoice(<?php echo e($inv->id); ?>)">Bayar</button>
                        <?php else: ?>
                        <span style="color: #10B981;">✓ Selesai</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="7" style="text-align: center;">Tidak ada tagihan.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function payInvoice(invoiceId) {
    if(!confirm('Proses pembayaran tagihan ini?')) return;
    
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
        }
    });
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Mytz data enginer\SPP_SYSTEM\resources\views/parent/invoices.blade.php ENDPATH**/ ?>