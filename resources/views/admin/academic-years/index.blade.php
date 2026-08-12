<x-layouts.admin title="Tahun Ajaran & Periode">

<div class="page-header flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h2 class="page-title">Tahun Ajaran & Periode</h2>
        <p class="page-subtitle">Kelola tahun ajaran, semester, dan periode penilaian</p>
    </div>
    <a href="{{ route('admin.academic-years.create') }}" class="btn-primary w-full md:w-auto justify-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
        Tambah Tahun Ajaran
    </a>
</div>

@forelse($years as $year)
<div class="card mb-4">
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-3">
            <h3 class="font-bold text-slate-800 text-lg">{{ $year->name }}</h3>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700 border border-indigo-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" /></svg>
                {{ $year->curriculum?->name ?? 'Belum diset' }}
            </span>
            @if($year->is_active)
                <span class="badge-success">Aktif</span>
            @else
                <span class="badge-neutral">Tidak Aktif</span>
            @endif
        </div>
        <div class="flex items-center gap-2">
            @if(!$year->is_active)
            <form method="POST" action="{{ route('admin.academic-years.activate', $year) }}">
                @csrf
                <button type="submit" class="btn btn-secondary btn-sm">Aktifkan</button>
            </form>
            <form method="POST" action="{{ route('admin.academic-years.destroy', $year) }}"
                data-confirm="Hapus tahun ajaran {{ $year->name }}? Semua data semester terkait juga akan terhapus."
                data-confirm-title="Hapus Tahun Ajaran"
                data-confirm-type="danger">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
            </form>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach($year->semesters as $semester)
        <div class="border border-slate-200 rounded-lg p-4">
            <div class="flex items-center justify-between mb-3">
                <h4 class="font-semibold text-slate-700">Semester {{ $semester->name }}</h4>
                @if($semester->is_active)<span class="badge-success text-xs">Aktif</span>@endif
            </div>
            <div class="space-y-2">
                @foreach($semester->assessmentPeriods as $period)
                <div class="flex items-center justify-between py-2 px-3 rounded-lg {{ $period->is_active ? 'bg-primary-50 border border-primary-200' : 'bg-slate-50' }}">
                    <div>
                        <div class="text-sm font-medium {{ $period->is_active ? 'text-primary-700' : 'text-slate-700' }}">{{ $period->name }}</div>
                        <div class="text-xs text-slate-400">{{ $period->code }}</div>
                        @if($period->report_place && $period->report_date)
                        <div class="text-[11px] text-slate-500 mt-1 flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" /></svg>
                            {{ $period->report_place }}, {{ $period->report_date->translatedFormat('d F Y') }}
                        </div>
                        @endif
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button" class="btn-icon" title="Atur Titimangsa"
                            onclick="editPeriod({{ $period->id }}, '{{ addslashes($period->name) }}', '{{ $period->report_place }}', '{{ $period->report_date ? $period->report_date->format('Y-m-d') : '' }}')">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125"/></svg>
                        </button>
                        @if(!$period->is_active)
                        <form method="POST" action="{{ route('admin.periods.activate', $period) }}">
                            @csrf
                            <button type="submit" class="btn btn-secondary btn-sm">Aktifkan</button>
                        </form>
                        @else
                        <span class="badge-success">Aktif</span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>
</div>
@empty
<div class="card text-center py-16">
    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
    <p class="text-slate-500">Belum ada tahun ajaran. <a href="{{ route('admin.academic-years.create') }}" class="text-primary-600 underline">Tambah sekarang</a></p>
</div>
@endforelse

<!-- Modal Edit Period -->
<div id="modal-edit-period" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="closeModal('modal-edit-period')"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md bg-white rounded-2xl shadow-xl overflow-hidden flex flex-col max-h-[90vh]">
        <div class="px-6 py-4 border-b border-slate-100 bg-gradient-to-r from-blue-50 to-indigo-50 flex items-center justify-between shrink-0">
            <h3 class="font-bold text-slate-800 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125"/></svg>
                Atur Titimangsa
            </h3>
            <button type="button" onclick="closeModal('modal-edit-period')" class="text-slate-400 hover:text-slate-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <form id="form-edit-period" method="POST" class="overflow-y-auto p-6">
            @csrf
            @method('PUT')
            <p class="text-sm text-slate-500 mb-4" id="edit_period_name_display"></p>
            <div class="space-y-4">
                <div>
                    <label class="form-label">Tempat <span class="text-xs font-normal text-slate-400">(Contoh: Jakarta)</span></label>
                    <input type="text" name="report_place" id="edit_report_place" class="form-input" placeholder="Kota penandatanganan">
                </div>
                <div>
                    <label class="form-label">Tanggal Rapor <span class="text-xs font-normal text-slate-400">(Tampil di bawah TTD Wali Kelas)</span></label>
                    <input type="date" name="report_date" id="edit_report_date" class="form-input">
                </div>
            </div>
            <div class="mt-8 flex justify-end gap-3">
                <button type="button" class="btn-secondary" onclick="closeModal('modal-edit-period')">Batal</button>
                <button type="submit" class="btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openModal(id) {
        const modal = document.getElementById(id);
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.firstElementChild.classList.add('opacity-100');
            modal.lastElementChild.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeModal(id) {
        const modal = document.getElementById(id);
        modal.firstElementChild.classList.remove('opacity-100');
        modal.lastElementChild.classList.remove('scale-100', 'opacity-100');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 200);
    }

    function editPeriod(id, name, place, date) {
        const form = document.getElementById('form-edit-period');
        form.action = `/admin/assessment-periods/${id}`;
        document.getElementById('edit_period_name_display').textContent = `Periode: ${name}`;
        document.getElementById('edit_report_place').value = place || '';
        document.getElementById('edit_report_date').value = date || '';
        openModal('modal-edit-period');
    }
</script>
@endpush

</x-layouts.admin>
