@extends('layouts.app')

@section('title', 'Pendaftar Kurir - Sobat Kurir')

@section('content')
<section class="bg-slate-50 px-5 py-10 lg:px-8">
    <div class="mx-auto max-w-7xl">
        <x-back-button url="{{ url('/admin/dashboard') }}" />

        <div class="mb-8">
            <p class="text-sm font-bold uppercase tracking-wider text-blue-600">
                Pendaftar Kurir
            </p>

            <h1 class="mt-3 text-3xl font-extrabold tracking-tight text-slate-950 sm:text-4xl">
                Approval Calon Kurir
            </h1>

            <p class="mt-3 max-w-2xl leading-7 text-slate-600">
                Setujui atau tolak calon kurir yang mendaftar dari halaman publik.
            </p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[1050px] text-left text-sm">
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50 text-slate-600">
                            <th class="px-4 py-3 font-bold">No</th>
                            <th class="px-4 py-3 font-bold">Nama</th>
                            <th class="px-4 py-3 font-bold">Email</th>
                            <th class="px-4 py-3 font-bold">No HP</th>
                            <th class="px-4 py-3 font-bold">Alamat</th>
                            <th class="px-4 py-3 font-bold">Status</th>
                            <th class="px-4 py-3 font-bold">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @forelse($pendaftar as $item)
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-4 text-slate-600">
                                    {{ $loop->iteration }}
                                </td>

                                <td class="px-4 py-4">
                                    <p class="font-extrabold text-slate-950">
                                        {{ $item->nama_lengkap }}
                                    </p>
                                    <p class="mt-1 text-xs font-semibold uppercase tracking-wide text-slate-500">
                                        Calon Kurir
                                    </p>
                                </td>

                                <td class="px-4 py-4 text-slate-600">
                                    {{ $item->email }}
                                </td>

                                <td class="px-4 py-4 text-slate-600">
                                    {{ $item->no_hp }}
                                </td>

                                <td class="px-4 py-4">
                                    <p class="max-w-[260px] text-sm leading-6 text-slate-600">
                                        {{ $item->alamat ?? '-' }}
                                    </p>
                                </td>

                                <td class="px-4 py-4">
                                    <span class="rounded-full bg-amber-50 px-3 py-1 text-xs font-bold text-amber-700">
                                        MENUNGGU
                                    </span>
                                </td>

                                <td class="px-4 py-4">
                                    <div class="flex min-w-[190px] flex-wrap gap-2">
                                        <form method="POST" action="{{ route('admin.kurir.approve', $item->id_user) }}" onsubmit="return confirm('Setujui pendaftar kurir ini?');">
                                            @csrf
                                            @method('PATCH')

                                            <button
                                                type="submit"
                                                class="inline-flex rounded-xl bg-blue-600 px-4 py-2.5 text-xs font-extrabold text-white transition hover:bg-blue-700"
                                            >
                                                Setujui
                                            </button>
                                        </form>

                                        <button
                                            type="button"
                                            data-action="{{ route('admin.kurir.reject', $item->id_user) }}"
                                            data-name="{{ $item->nama_lengkap }}"
                                            class="btn-open-reject-modal inline-flex rounded-xl bg-red-600 px-4 py-2.5 text-xs font-extrabold text-white transition hover:bg-red-700"
                                        >
                                            Tolak
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-12 text-center">
                                    <div class="mx-auto max-w-md">
                                        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-xl bg-slate-100 text-slate-500">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                 class="h-6 w-6"
                                                 fill="none"
                                                 viewBox="0 0 24 24"
                                                 stroke="currentColor"
                                                 stroke-width="1.8">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 20.25a8.25 8.25 0 1115 0" />
                                            </svg>
                                        </div>

                                        <h3 class="mt-4 font-extrabold text-slate-950">
                                            Belum ada pendaftar kurir
                                        </h3>

                                        <p class="mt-2 text-sm leading-6 text-slate-500">
                                            Calon kurir yang mendaftar akan muncul di halaman ini.
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<div
    id="rejectModal"
    class="fixed inset-0 z-[100] hidden items-center justify-center bg-slate-950/50 px-5 backdrop-blur-sm"
>
    <div class="w-full max-w-lg rounded-2xl border border-slate-200 bg-white shadow-xl">
        <div class="border-b border-slate-200 px-6 py-5">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm font-bold uppercase tracking-wider text-red-600">
                        Tolak Pendaftar
                    </p>

                    <h2 class="mt-2 text-xl font-extrabold text-slate-950">
                        Konfirmasi Penolakan Kurir
                    </h2>

                    <p class="mt-2 text-sm leading-6 text-slate-500">
                        Anda akan menolak pendaftaran kurir:
                        <span id="rejectName" class="font-extrabold text-slate-900">-</span>
                    </p>
                </div>

                <button
                    type="button"
                    id="btnCloseRejectModalTop"
                    class="flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 transition hover:bg-slate-50 hover:text-slate-900"
                >
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <form id="rejectForm" method="POST" action="" class="p-6">
            @csrf
            @method('PATCH')

            <div>
                <label class="mb-2 block text-sm font-bold text-slate-700">
                    Alasan Penolakan
                </label>

                <select
                    id="alasanTolak"
                    name="alasan_tolak"
                    class="h-12 w-full rounded-xl border border-slate-200 bg-white px-4 text-sm font-semibold text-slate-700 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                    required
                >
                    <option value="">Pilih alasan</option>
                    <option value="Data kurir tidak valid">Data kurir tidak valid</option>
                    <option value="Dokumen tidak lengkap">Dokumen tidak lengkap</option>
                    <option value="Kendaraan tidak memenuhi syarat">Kendaraan tidak memenuhi syarat</option>
                    <option value="Lainnya">Lainnya</option>
                </select>
            </div>

            <div id="alasanLainnyaWrapper" class="mt-5 hidden">
                <label class="mb-2 block text-sm font-bold text-slate-700">
                    Detail Alasan
                </label>

                <textarea
                    id="alasanLainnya"
                    name="alasan_lainnya"
                    rows="4"
                    placeholder="Tulis alasan penolakan secara singkat..."
                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                ></textarea>
            </div>

            <div class="mt-6 rounded-2xl border border-red-100 bg-red-50 px-5 py-4 text-sm font-semibold leading-6 text-red-700">
                Setelah ditolak, pendaftar tidak dapat login. Namun, sistem akan mereset status email ini sehingga mereka dapat mendaftar ulang jika ingin memperbaiki data.
            </div>

            <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                <button
                    type="button"
                    id="btnCloseRejectModalBottom"
                    class="inline-flex h-12 items-center justify-center rounded-xl border border-slate-200 bg-white px-6 text-sm font-extrabold text-slate-700 transition hover:bg-slate-50"
                >
                    Batal
                </button>

                <button
                    type="submit"
                    class="inline-flex h-12 items-center justify-center rounded-xl bg-red-600 px-6 text-sm font-extrabold text-white transition hover:bg-red-700"
                >
                    Konfirmasi Tolak
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const rejectModal = document.getElementById('rejectModal');
    const rejectForm = document.getElementById('rejectForm');
    const rejectName = document.getElementById('rejectName');
    const alasanTolak = document.getElementById('alasanTolak');
    const alasanLainnyaWrapper = document.getElementById('alasanLainnyaWrapper');
    const alasanLainnya = document.getElementById('alasanLainnya');

    const openButtons = document.querySelectorAll('.btn-open-reject-modal');
    const closeTop = document.getElementById('btnCloseRejectModalTop');
    const closeBottom = document.getElementById('btnCloseRejectModalBottom');

    function openRejectModal(action, name) {
        rejectForm.action = action;
        rejectName.textContent = name;

        alasanTolak.value = '';
        alasanLainnya.value = '';
        alasanLainnya.required = false;
        alasanLainnyaWrapper.classList.add('hidden');

        rejectModal.classList.remove('hidden');
        rejectModal.classList.add('flex');
    }

    function closeRejectModal() {
        rejectModal.classList.add('hidden');
        rejectModal.classList.remove('flex');
    }

    openButtons.forEach((button) => {
        button.addEventListener('click', function () {
            openRejectModal(this.dataset.action, this.dataset.name);
        });
    });

    closeTop.addEventListener('click', closeRejectModal);
    closeBottom.addEventListener('click', closeRejectModal);

    rejectModal.addEventListener('click', function (event) {
        if (event.target === rejectModal) {
            closeRejectModal();
        }
    });

    alasanTolak.addEventListener('change', function () {
        if (this.value === 'Lainnya') {
            alasanLainnyaWrapper.classList.remove('hidden');
            alasanLainnya.required = true;
        } else {
            alasanLainnyaWrapper.classList.add('hidden');
            alasanLainnya.required = false;
            alasanLainnya.value = '';
        }
    });
</script>
@endsection
