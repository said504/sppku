<?php $__env->startSection('content'); ?>
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <h2 style="color: var(--primary-blue); margin: 0;">Data Siswa</h2>
    <button class="btn" onclick="openModal()">+ Tambah Siswa</button>
</div>

<?php if(session('success')): ?>
    <div style="padding: 1rem; background: var(--success); color: white; border-radius: 8px; margin-bottom: 1rem;">
        <?php echo e(session('success')); ?>

    </div>
<?php endif; ?>

<div class="glass-card">
    <div style="overflow-x: auto;">
        <table>
            <thead>
                <tr>
                    <th>NISN</th>
                    <th>Nama Lengkap</th>
                    <th>Kelas</th>
                    <th>Nama Orang Tua</th>
                    <th>Status Khusus</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td style="font-family: monospace;"><?php echo e($student->nisn); ?></td>
                    <td style="font-weight: 600;"><?php echo e($student->name); ?></td>
                    <td><?php echo e($student->class_name); ?></td>
                    <td><?php echo e($student->parent->name ?? '-'); ?></td>
                    <td>
                        <?php if($student->anak_guru): ?>
                            <span class="badge" style="background: #e0e7ff; color: #4338ca;">Anak Guru</span>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                    <td>
                        <div style="display: flex; gap: 0.5rem;">
                            <!-- For simplicity, Edit just opens a stub alert, full edit form can be similar to create -->
                            <button class="btn" style="padding: 0.3rem 0.6rem; font-size: 0.8rem; background: var(--warning);" onclick="alert('Fitur edit detail dalam pengembangan')">Edit</button>
                            
                            <form action="<?php echo e(route('admin.students.delete', $student->id)); ?>" method="POST" onsubmit="return confirm('Yakin ingin menghapus siswa ini?');">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-danger" style="padding: 0.3rem 0.6rem; font-size: 0.8rem;">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="6" style="text-align: center;">Belum ada data siswa.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div style="margin-top: 1rem;">
        <?php echo e($students->links('pagination::simple-tailwind')); ?>

    </div>
</div>

<!-- Modal Tambah Siswa -->
<div id="studentModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; justify-content: center; align-items: center;">
    <div class="glass-card" style="background: white; width: 100%; max-width: 500px; padding: 2rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h3 style="margin: 0;">Tambah Siswa Baru</h3>
            <button onclick="closeModal()" style="background: none; border: none; font-size: 1.5rem; cursor: pointer;">&times;</button>
        </div>
        
        <form action="<?php echo e(route('admin.students.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Nama Lengkap</label>
                <input type="text" name="name" required style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 8px;">
            </div>
            
            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">NISN</label>
                <input type="text" name="nisn" required style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 8px;">
            </div>
            
            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Kelas</label>
                <input type="text" name="class_name" required style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 8px;">
            </div>
            
            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Orang Tua (User Account)</label>
                <select name="parent_id" required style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 8px;">
                    <option value="">-- Pilih Orang Tua --</option>
                    <?php $__currentLoopData = $parents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($parent->id); ?>"><?php echo e($parent->name); ?> (<?php echo e($parent->email); ?>)</option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            
            <div style="margin-bottom: 1.5rem;">
                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                    <input type="checkbox" name="anak_guru" value="1">
                    <span style="font-weight: 600;">Status Anak Guru (Berhak Diskon)</span>
                </label>
            </div>
            
            <div style="display: flex; justify-content: flex-end; gap: 1rem;">
                <button type="button" class="btn" style="background: #e5e7eb; color: #374151;" onclick="closeModal()">Batal</button>
                <button type="submit" class="btn">Simpan Siswa</button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    function openModal() {
        document.getElementById('studentModal').style.display = 'flex';
    }
    function closeModal() {
        document.getElementById('studentModal').style.display = 'none';
    }
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Mytz data enginer\SPP_SYSTEM\resources\views/admin/students.blade.php ENDPATH**/ ?>