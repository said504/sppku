<?php $__env->startSection('content'); ?>
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
                <?php $__empty_1 = true; $__currentLoopData = $invoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($inv->student->name); ?></td>
                    <td><?php echo e($inv->student->class_name); ?></td>
                    <td><?php echo e($inv->sppType->name); ?></td>
                    <td><?php echo e($inv->month); ?> <?php echo e($inv->year); ?></td>
                    <td style="font-weight: 600; color: var(--orange);">Rp <?php echo e(number_format($inv->total_amount, 0, ',', '.')); ?></td>
                    <td>
                        <button class="btn" style="padding: 0.4rem 0.8rem; font-size: 0.9rem;" onclick="alert('Reminder berhasil dikirim ke orang tua!')">Kirim Reminder</button>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="6" style="text-align: center;">Tidak ada data tunggakan.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div style="margin-top: 1rem;">
        <?php echo e($invoices->links('pagination::simple-tailwind')); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Mytz data enginer\SPP_SYSTEM\resources\views/admin/tunggakan.blade.php ENDPATH**/ ?>