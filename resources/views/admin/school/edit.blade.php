<x-layouts.admin title="Profil Sekolah">

<div class="page-header">
    <h2 class="page-title">Profil Sekolah</h2>
    <p class="page-subtitle">Kelola identitas dan informasi sekolah</p>
</div>

<form method="POST" action="{{ route('admin.school.update') }}" enctype="multipart/form-data">
    @csrf @method('PUT')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Logo Upload -->
        <div class="card flex flex-col items-center text-center">
            <div class="mb-4">
                @if($school->logo_path)
                    <img src="{{ Storage::url($school->logo_path) }}" id="logo-preview" alt="Logo Sekolah" class="w-32 h-32 object-contain rounded-xl border border-slate-200 bg-white">
                @else
                    <img src="" id="logo-preview" alt="Logo Sekolah" class="hidden w-32 h-32 object-contain rounded-xl border border-slate-200 bg-white">
                    <div id="logo-placeholder" class="w-32 h-32 rounded-xl bg-slate-100 border-2 border-dashed border-slate-300 flex flex-col items-center justify-center text-slate-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 mb-1" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                        <span class="text-xs">Belum ada logo</span>
                    </div>
                @endif
            </div>
            <label class="btn btn-secondary btn-sm cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
                Upload Logo
                <input type="file" name="logo" accept="image/png,image/jpeg" class="hidden" onchange="previewLogo(this)">
            </label>
            <p class="text-xs text-slate-400 mt-2">PNG/JPG, maks. 2MB</p>
        </div>

        <!-- School Info -->
        <div class="card col-span-2">
            <h3 class="card-title mb-4">Informasi Sekolah</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="form-group">
                    <label class="form-label">Nama Sekolah</label>
                    <input type="text" name="name" class="form-input" value="{{ old('name', $school->name) }}" placeholder="SDN 01 Contoh">
                </div>
                <div class="form-group">
                    <label class="form-label">NPSN</label>
                    <input type="text" name="npsn" class="form-input" value="{{ old('npsn', $school->npsn) }}" placeholder="12345678">
                </div>
                <div class="form-group md:col-span-2">
                    <label class="form-label">Alamat</label>
                    <textarea name="address" rows="2" class="form-input">{{ old('address', $school->address) }}</textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Kota</label>
                    <input type="text" name="city" class="form-input" value="{{ old('city', $school->city) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Provinsi</label>
                    <input type="text" name="province" class="form-input" value="{{ old('province', $school->province) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">No. Telepon</label>
                    <input type="text" name="phone" class="form-input" value="{{ old('phone', $school->phone) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-input" value="{{ old('email', $school->email) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Nama Kepala Sekolah</label>
                    <input type="text" name="principal_name" class="form-input" value="{{ old('principal_name', $school->principal_name) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">NIP Kepala Sekolah</label>
                    <input type="text" name="principal_nip" class="form-input" value="{{ old('principal_nip', $school->principal_nip) }}">
                </div>
            </div>
            <div class="flex justify-end mt-4">
                <button type="submit" class="btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                    Simpan Perubahan
                </button>
            </div>
        </div>
    </div>
</form>

<script>
function previewLogo(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.getElementById('logo-preview');
            const placeholder = document.getElementById('logo-placeholder');
            
            if (img) {
                img.src = e.target.result;
                img.classList.remove('hidden');
            }
            if (placeholder) {
                placeholder.classList.add('hidden');
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

</x-layouts.admin>
