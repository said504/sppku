<?php $__env->startSection('content'); ?>
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
                <?php $__empty_1 = true; $__currentLoopData = $invoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($inv->updated_at->format('d M Y H:i')); ?></td>
                    <td><?php echo e($inv->student->name); ?></td>
                    <td><?php echo e($inv->month); ?> <?php echo e($inv->year); ?></td>
                    <td style="font-weight: 600; color: var(--success);">Rp <?php echo e(number_format($inv->total_amount, 0, ',', '.')); ?></td>
                    <td><span class="badge badge-lunas">Lunas</span></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="5" style="text-align: center;">Belum ada riwayat pembayaran.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Mytz data enginer\SPP_SYSTEM\resources\views/parent/history.blade.php ENDPATH**/ ?>