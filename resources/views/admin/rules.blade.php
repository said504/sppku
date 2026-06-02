@extends('layouts.app')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <h2 style="color: var(--primary-blue); margin: 0;">Dynamic Rule Engine</h2>
    <button class="btn" onclick="openModal()">+ Buat Rule Baru</button>
</div>

@if(session('success'))
    <div style="padding: 1rem; background: var(--success); color: white; border-radius: 8px; margin-bottom: 1rem;">
        {{ session('success') }}
    </div>
@endif

<div class="glass-card">
    <p style="color: #666; margin-bottom: 1.5rem;">Rule engine digunakan untuk mengatur otomatisasi potongan biaya SPP berdasarkan kondisi spesifik siswa.</p>
    
    <div style="display: grid; gap: 1rem;">
        @foreach($rules as $rule)
        <div style="background: rgba(30,58,95,0.03); border: 1px solid var(--border-color); border-radius: 12px; padding: 1.5rem;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                <div>
                    <h4 style="margin: 0 0 0.5rem 0;">{{ $rule->name }}</h4>
                    <span class="badge {{ $rule->is_active ? 'badge-lunas' : 'badge-tunggakan' }}">{{ $rule->is_active ? 'Aktif' : 'Non-Aktif' }}</span>
                </div>
                <div style="font-size: 1.25rem; font-weight: 800; color: var(--accent-blue);">
                    -{{ (float)$rule->discount_percentage }}%
                </div>
            </div>
            
            <div style="background: #1e1e1e; color: #d4d4d4; padding: 1rem; border-radius: 8px; font-family: monospace; font-size: 0.9rem;">
                <span style="color: #569cd6;">IF</span>( <span style="color: #9cdcfe;">{{ $rule->condition }}</span> ) <br>
                <span style="color: #569cd6;">THEN</span> ( <span style="color: #9cdcfe;">Tagihan</span> * <span style="color: #ce9178;">{{ (100 - $rule->discount_percentage) / 100 }}</span> )
            </div>
            
            <div style="margin-top: 1rem; text-align: right; display: flex; justify-content: flex-end; gap: 0.5rem;">
                <form action="{{ route('admin.rules.delete', $rule->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus rule ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" style="padding: 0.4rem 0.8rem; font-size: 0.9rem;">Hapus</button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Modal Tambah Rule -->
<div id="ruleModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; justify-content: center; align-items: center;">
    <div class="glass-card" style="background: white; width: 100%; max-width: 500px; padding: 2rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h3 style="margin: 0;">Tambah Rule Diskon</h3>
            <button onclick="closeModal()" style="background: none; border: none; font-size: 1.5rem; cursor: pointer;">&times;</button>
        </div>
        
        <form action="{{ route('admin.rules.store') }}" method="POST">
            @csrf
            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Nama Rule</label>
                <input type="text" name="name" placeholder="Misal: Diskon Prestasi" required style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 8px;">
            </div>
            
            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Logika Kondisi (Mockup text)</label>
                <input type="text" name="condition" placeholder="Misal: Siswa.prestasi == True" required style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 8px; font-family: monospace;">
            </div>
            
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Persentase Diskon (%)</label>
                <input type="number" name="discount_percentage" min="1" max="100" required style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 8px;">
            </div>
            
            <div style="display: flex; justify-content: flex-end; gap: 1rem;">
                <button type="button" class="btn" style="background: #e5e7eb; color: #374151;" onclick="closeModal()">Batal</button>
                <button type="submit" class="btn">Simpan Rule</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openModal() {
        document.getElementById('ruleModal').style.display = 'flex';
    }
    function closeModal() {
        document.getElementById('ruleModal').style.display = 'none';
    }
</script>
@endpush
