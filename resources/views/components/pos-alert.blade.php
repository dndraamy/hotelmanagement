{{--
    Komponen Flash Message / Alert untuk POS Restoran
    Mendukung tipe: success, error, info, warning
    Penggunaan: @include('components.pos-alert')
--}}

@if (session('success') || session('error') || session('info') || session('warning'))
    @php
        $type    = session('success') ? 'success' : (session('error') ? 'error' : (session('info') ? 'info' : 'warning'));
        $message = session($type);

        $styles = [
            'success' => [
                'bg'      => '#F0FDF4',
                'border'  => '#16A34A',
                'text'    => '#15803D',
                'icon'    => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />',
            ],
            'error' => [
                'bg'      => '#FEF2F2',
                'border'  => '#DC2626',
                'text'    => '#B91C1C',
                'icon'    => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />',
            ],
            'info' => [
                'bg'      => '#EFF6FF',
                'border'  => '#2563EB',
                'text'    => '#1D4ED8',
                'icon'    => '<path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />',
            ],
            'warning' => [
                'bg'      => '#FFFBEB',
                'border'  => '#D97706',
                'text'    => '#B45309',
                'icon'    => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />',
            ],
        ];

        $s = $styles[$type];
    @endphp

    <div
        id="pos-alert"
        role="alert"
        style="
            background-color: {{ $s['bg'] }};
            border: 1.5px solid {{ $s['border'] }};
            color: {{ $s['text'] }};
            border-radius: 12px;
            padding: 14px 18px;
            margin-bottom: 20px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
            font-family: 'Montserrat', sans-serif;
            font-size: 13.5px;
            font-weight: 500;
            line-height: 1.5;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            animation: slideInAlert 0.35s cubic-bezier(0.16, 1, 0.3, 1);
        "
    >
        {{-- Icon --}}
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
             stroke-width="1.8" stroke="currentColor"
             style="width:20px;height:20px;flex-shrink:0;margin-top:1px;">
            {!! $s['icon'] !!}
        </svg>

        {{-- Message --}}
        <span style="flex:1">{{ $message }}</span>

        {{-- Close Button --}}
        <button
            type="button"
            onclick="document.getElementById('pos-alert').remove()"
            aria-label="Tutup"
            style="
                background: none;
                border: none;
                cursor: pointer;
                color: inherit;
                opacity: 0.6;
                padding: 0;
                flex-shrink: 0;
                transition: opacity 0.2s;
            "
            onmouseover="this.style.opacity='1'"
            onmouseout="this.style.opacity='0.6'"
        >
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                 stroke-width="2" stroke="currentColor" style="width:16px;height:16px">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <style>
        @keyframes slideInAlert {
            from { opacity: 0; transform: translateY(-8px); }
            to   { opacity: 1; transform: translateY(0);    }
        }
    </style>
@endif
