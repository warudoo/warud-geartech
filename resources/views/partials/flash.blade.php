@php
    $toasts = collect();

    if (session('toast')) {
        $toasts->push(array_merge([
            'tone' => 'success',
            'title' => 'Aksi berhasil',
            'message' => null,
            'action_label' => null,
            'action_url' => null,
            'timeout' => 4500,
        ], session('toast')));
    }

    if (session('status')) {
        $toasts->push([
            'tone' => 'success',
            'title' => 'Aksi berhasil',
            'message' => session('status'),
            'action_label' => null,
            'action_url' => null,
            'timeout' => 4200,
        ]);
    }

    if (session('error')) {
        $toasts->push([
            'tone' => 'error',
            'title' => 'Terjadi kendala',
            'message' => session('error'),
            'action_label' => null,
            'action_url' => null,
            'timeout' => 6500,
        ]);
    }

    if ($errors->any()) {
        $toasts->push([
            'tone' => 'warning',
            'title' => 'Permintaan belum bisa diproses',
            'message' => null,
            'messages' => $errors->all(),
            'action_label' => null,
            'action_url' => null,
            'timeout' => 7000,
        ]);
    }
@endphp

@if ($toasts->isNotEmpty())
    <div class="pointer-events-none fixed inset-x-0 top-24 z-50 flex flex-col items-center gap-3 px-4 sm:inset-x-auto sm:right-6 sm:items-end" aria-live="polite" aria-atomic="true">
        @foreach ($toasts as $toast)
            @php
                $tone = $toast['tone'];
                $iconClasses = match ($tone) {
                    'success' => 'border-emerald-200 bg-emerald-50 text-emerald-700',
                    'error' => 'border-rose-200 bg-rose-50 text-rose-700',
                    'warning' => 'border-amber-200 bg-amber-50 text-amber-700',
                    default => 'border-slate-200 bg-slate-50 text-slate-700',
                };

                $toastClasses = match ($tone) {
                    'success' => 'border-emerald-200/80 bg-white text-slate-900 shadow-emerald-100/80',
                    'error' => 'border-rose-200/90 bg-white text-slate-900 shadow-rose-100/80',
                    'warning' => 'border-amber-200/90 bg-white text-slate-900 shadow-amber-100/80',
                    default => 'border-slate-200 bg-white text-slate-900 shadow-slate-200/80',
                };

                $accentClasses = match ($tone) {
                    'success' => 'bg-emerald-500',
                    'error' => 'bg-rose-500',
                    'warning' => 'bg-amber-500',
                    default => 'bg-slate-400',
                };
            @endphp

            <div
                class="toast pointer-events-auto relative w-full max-w-sm overflow-hidden rounded-[1.6rem] border p-4 opacity-0 shadow-xl transition duration-300 ease-out translate-y-3 sm:translate-x-6 sm:translate-y-0 {{ $toastClasses }}"
                data-toast
                data-timeout="{{ $toast['timeout'] ?? 4500 }}"
                role="status"
            >
                <div class="absolute inset-y-0 left-0 w-1 {{ $accentClasses }}"></div>

                <div class="flex items-start gap-3 pl-2">
                    <div class="mt-0.5 flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl border {{ $iconClasses }}">
                        @if ($tone === 'success')
                            <svg viewBox="0 0 20 20" fill="currentColor" class="h-5 w-5" aria-hidden="true">
                                <path fill-rule="evenodd" d="M16.704 5.29a1 1 0 010 1.42l-7.2 7.2a1 1 0 01-1.415 0l-3-3a1 1 0 111.414-1.42l2.293 2.294 6.493-6.494a1 1 0 011.415 0z" clip-rule="evenodd" />
                            </svg>
                        @elseif ($tone === 'error')
                            <svg viewBox="0 0 20 20" fill="currentColor" class="h-5 w-5" aria-hidden="true">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm2.707-10.707a1 1 0 00-1.414-1.414L10 7.586 8.707 6.293a1 1 0 00-1.414 1.414L8.586 9l-1.293 1.293a1 1 0 101.414 1.414L10 10.414l1.293 1.293a1 1 0 001.414-1.414L11.414 9l1.293-1.293z" clip-rule="evenodd" />
                            </svg>
                        @else
                            <svg viewBox="0 0 20 20" fill="currentColor" class="h-5 w-5" aria-hidden="true">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.72-1.36 3.485 0l5.58 9.92c.75 1.334-.213 2.981-1.742 2.981H4.42c-1.53 0-2.492-1.647-1.743-2.98l5.58-9.92zM11 13a1 1 0 10-2 0 1 1 0 002 0zm-1-6a1 1 0 00-1 1v3a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        @endif
                    </div>

                    <div class="min-w-0 flex-1">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="text-sm font-semibold tracking-tight text-slate-900">{{ $toast['title'] }}</p>
                                @if (!empty($toast['message']))
                                    <p class="mt-1 text-sm leading-6 text-slate-600">{{ $toast['message'] }}</p>
                                @endif
                            </div>

                            <button type="button" class="toast-close inline-flex h-8 w-8 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 transition hover:border-slate-300 hover:text-slate-700" aria-label="Tutup notifikasi">
                                <svg viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>

                        @if (!empty($toast['messages']))
                            <ul class="mt-2 space-y-1 text-sm leading-6 text-slate-600">
                                @foreach ($toast['messages'] as $message)
                                    <li>{{ $message }}</li>
                                @endforeach
                            </ul>
                        @endif

                        @if (!empty($toast['action_label']) && !empty($toast['action_url']))
                            <div class="mt-4">
                                <a href="{{ $toast['action_url'] }}" class="inline-flex items-center rounded-xl border border-slate-200 bg-slate-900 px-3.5 py-2 text-sm font-semibold text-white transition hover:bg-slate-800">
                                    {{ $toast['action_label'] }}
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
