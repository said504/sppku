@extends('layouts.app')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <h2 style="color: var(--primary-blue); margin: 0;">Data Siswa</h2>
    <button class="btn" onclick="openModal('studentModal')">+ Tambah Siswa</button>
</div>

@if(session('success'))
    <div id="successToast" style="padding: 1rem; background: var(--success); color: white; border-radius: 8px; margin-bottom: 1rem; display: flex; justify-content: space-between;">
        {{ session('success') }}
        <span style="cursor:pointer;" onclick="this.parentElement.style.display='none'">&times;</span>
    </div>
@endif

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
                @forelse($students as $student)
                <tr>
                    <td style="font-family: monospace;">{{ $student->nisn }}</td>
                    <td style="font-weight: 600;">{{ $student->name }}</td>
                    <td>{{ $student->class_name }}</td>
                    <td>{{ $student->parent->name ?? '-' }}</td>
                    <td>
                        @if($student->anak_guru)
                            <span class="badge" style="background: #e0e7ff; color: #4338ca;">Anak Guru</span>
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        <div style="display: flex; gap: 0.5rem;">
                            <!-- Edit Button -->
                            <button type="button" class="btn" style="padding: 0.3rem 0.6rem; font-size: 0.8rem; background: var(--warning);" 
                                onclick="openEditModal({{ $student->id }}, '{{ $student->name }}', '{{ $student->nisn }}', '{{ $student->class_name }}', '{{ $student->parent_id }}', {{ $student->anak_guru ? 'true' : 'false' }})">Edit</button>
                            
                            <!-- Delete Button -->
                            <button type="button" class="btn btn-danger" style="padding: 0.3rem 0.6rem; font-size: 0.8rem;" onclick="openDeleteModal('{{ route('admin.students.delete', $student->id) }}', '{{ $student->name }}')">Hapus</button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" style="text-align: center;">Belum ada data siswa.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top: 1rem;">
        {{ $students->links('pagination::simple-tailwind') }}
    </div>
</div>

<!-- Modal Background (Shared) -->
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

<!-- Modal Tambah Siswa -->
<div id="studentModal" class="modal-overlay">
    <div class="modal-content glass-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h3 style="margin: 0; color: var(--primary-blue);">Tambah Siswa Baru</h3>
            <button onclick="closeModal('studentModal')" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: #666;">&times;</button>
        </div>
        
        <form action="{{ route('admin.students.store') }}" method="POST">
            @csrf
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
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Orang Tua</label>
                <select name="parent_id" required style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 8px;">
                    <option value="">-- Pilih Orang Tua --</option>
                    @foreach($parents as $parent)
                        <option value="{{ $parent->id }}">{{ $parent->name }} ({{ $parent->email }})</option>
                    @endforeach
                </select>
            </div>
            
            <div style="margin-bottom: 1.5rem;">
                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                    <input type="checkbox" name="anak_guru" value="1">
                    <span style="font-weight: 600;">Status Anak Guru (Berhak Diskon)</span>
                </label>
            </div>
            
            <div style="display: flex; justify-content: flex-end; gap: 1rem;">
                <button type="button" class="btn" style="background: #e5e7eb; color: #374151;" onclick="closeModal('studentModal')">Batal</button>
                <button type="submit" class="btn">Simpan Siswa</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Siswa -->
<div id="editStudentModal" class="modal-overlay">
    <div class="modal-content glass-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h3 style="margin: 0; color: var(--warning);">Edit Data Siswa</h3>
            <button onclick="closeModal('editStudentModal')" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: #666;">&times;</button>
        </div>
        
        <form id="editStudentForm" method="POST">
            @csrf
            @method('PUT')
            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Nama Lengkap</label>
                <input type="text" id="edit_name" name="name" required style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 8px;">
            </div>
            
            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">NISN</label>
                <input type="text" id="edit_nisn" name="nisn" required style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 8px;">
            </div>
            
            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Kelas</label>
                <input type="text" id="edit_class_name" name="class_name" required style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 8px;">
            </div>
            
            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Orang Tua</label>
                <select id="edit_parent_id" name="parent_id" required style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 8px;">
                    @foreach($parents as $parent)
                        <option value="{{ $parent->id }}">{{ $parent->name }} ({{ $parent->email }})</option>
                    @endforeach
                </select>
            </div>
            
            <div style="margin-bottom: 1.5rem;">
                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                    <input type="checkbox" id="edit_anak_guru" name="anak_guru" value="1">
                    <span style="font-weight: 600;">Status Anak Guru (Berhak Diskon)</span>
                </label>
            </div>
            
            <div style="display: flex; justify-content: flex-end; gap: 1rem;">
                <button type="button" class="btn" style="background: #e5e7eb; color: #374151;" onclick="closeModal('editStudentModal')">Batal</button>
                <button type="submit" class="btn" style="background: var(--warning);">Update Data</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Delete Confimation -->
<div id="deleteModal" class="modal-overlay">
    <div class="modal-content glass-card" style="max-width: 400px; text-align: center;">
        <svg style="width: 60px; height: 60px; color: #dc2626; margin: 0 auto 1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        <h3 style="margin-top: 0;">Konfirmasi Hapus</h3>
        <p style="color: #666; margin-bottom: 1.5rem;">Apakah Anda yakin ingin menghapus data siswa <strong id="delStudentName"></strong>?</p>
        <form id="deleteForm" method="POST">
            @csrf
            @method('DELETE')
            <div style="display: flex; justify-content: center; gap: 1rem;">
                <button type="button" class="btn" style="background: #e5e7eb; color: #374151;" onclick="closeModal('deleteModal')">Batal</button>
                <button type="submit" class="btn btn-danger">Ya, Hapus</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function openModal(id) {
        document.getElementById(id).style.display = 'flex';
    }
    function closeModal(id) {
        document.getElementById(id).style.display = 'none';
    }
    
    function openEditModal(id, name, nisn, className, parentId, anakGuru) {
        document.getElementById('editStudentForm').action = '/admin/students/' + id;
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_nisn').value = nisn;
        document.getElementById('edit_class_name').value = className;
        document.getElementById('edit_parent_id').value = parentId;
        document.getElementById('edit_anak_guru').checked = anakGuru;
        openModal('editStudentModal');
    }

    function openDeleteModal(url, name) {
        document.getElementById('delStudentName').innerText = name;
        document.getElementById('deleteForm').action = url;
        openModal('deleteModal');
    }
</script>
@endpush
