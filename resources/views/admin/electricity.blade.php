@extends('layouts.app')

@section('title', 'Electricity bills')
@section('page_title', 'Electricity bills')
@section('page_subtitle', "Enter each room's monthly electricity bill. It is split equally between the tenants in that room.")

@section('page_action')
    <a href="{{ route('admin.payments') }}" class="addroombtn" style="text-decoration:none;">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
            stroke-linejoin="round" aria-hidden="true">
            <path d="M19 12H5M12 19l-7-7 7-7" />
        </svg>Back to payments
    </a>
@endsection

@section('content')
    @php
        $thisMonth   = now()->format('Y-m');
        $monthBills  = $bills->where('period', $thisMonth);
        $totalMonth  = (float) $monthBills->sum('amount');
        $billedCount = $monthBills->count();
        $missing     = max(0, $rooms->count() - $billedCount);
        $avgShare    = $billedCount
            ? $monthBills->avg(fn ($b) => $b->amount / max(1, $counts[$b->room_number] ?? 0))
            : 0;
    @endphp

    {{-- Summary cards --}}
    <div class="collectedthismonth">
        <div class="collectedthismonth1">
            <div class="collectedbreak">
                <h1 class="collectpay">₱{{ number_format($totalMonth, 2) }}</h1>
                <div class="boxbreakdowncollected">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2 3 14h9l-1 8 10-12h-9l1-8z" /></svg>
                </div>
            </div>
            <h1 class="collectedthismonthbreakkssk">BILLED THIS MONTH</h1>
        </div>
        <div class="collectedthismonth1">
            <div class="collectedbreak">
                <h1 class="collectpay">{{ $billedCount }}</h1>
                <div class="boxbreakdowncollected">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5" /></svg>
                </div>
            </div>
            <h1 class="collectedthismonthbreakkssk">ROOMS BILLED</h1>
        </div>
        <div class="collectedthismonth1">
            <div class="collectedbreak">
                <h1 class="collectpay">{{ $missing }}</h1>
                <div class="boxbreakdowncollected">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10" /><polyline points="12 6 12 12 16 14" /></svg>
                </div>
            </div>
            <h1 class="collectedthismonthbreakkssk">ROOMS NOT YET BILLED</h1>
        </div>
        <div class="collectedthismonth1">
            <div class="collectedbreak">
                <h1 class="collectpay">₱{{ number_format($avgShare, 2) }}</h1>
                <div class="boxbreakdowncollected">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" /><circle cx="9" cy="7" r="4" /><path d="M22 21v-2a4 4 0 0 0-3-3.9M16 3.1a4 4 0 0 1 0 7.8" /></svg>
                </div>
            </div>
            <h1 class="collectedthismonthbreakkssk">AVG. SHARE PER TENANT</h1>
        </div>
    </div>

    <div class="elec-layout">

        {{-- Form --}}
        <form method="POST" action="{{ route('admin.electricity.store') }}" enctype="multipart/form-data" class="elec-form">
            @csrf
            <h2 class="pamagatnamodal">Post a bill</h2>
            <p class="modalsub">Pick the room, month and total shown on the Meralco bill.</p>

            @if (session('status'))
                <p class="elec-ok">{{ session('status') }}</p>
            @endif
            @if ($errors->any())
                <p class="annmali" style="margin-top:0;">{{ $errors->first() }}</p>
            @endif

            <label class="labelnaann" for="elecRoom">Room</label>
            <select class="kahonnaann" name="room_number" id="elecRoom" required>
                @foreach ($rooms as $r)
                    <option value="{{ $r->room_number }}" data-count="{{ $counts[$r->room_number] ?? 0 }}">
                        Room {{ $r->room_number }} ({{ $counts[$r->room_number] ?? 0 }} tenants)
                    </option>
                @endforeach
            </select>

            <label class="labelnaann" for="elecMonth">Month</label>
            <input class="kahonnaann" type="month" name="period" id="elecMonth" value="{{ now()->format('Y-m') }}" required>

            <label class="labelnaann" for="elecAmount">Total electricity bill</label>
            <div class="pesowrap">
                <span>₱</span>
                <input class="kahonnaann" type="number" name="amount" id="elecAmount" step="0.01" min="1" placeholder="3,500" required>
            </div>
            <p class="paynote" id="elecShare" hidden></p>

            <label class="labelnaann">Photo of the bill</label>
            <label class="elec-drop" for="elecPhoto">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" /><polyline points="17 8 12 3 7 8" /><line x1="12" y1="3" x2="12" y2="15" /></svg>
                <span id="elecFileName">Click to upload (PNG or JPG)</span>
            </label>
            <input type="file" name="photo" id="elecPhoto" accept="image/png,image/jpeg" required hidden>

            <div class="butonsaibaba" style="margin-top:26px;">
                <button type="submit" class="ipostann" style="width:100%;">Save electricity bill</button>
            </div>
        </form>

        {{-- Recent bills --}}
        <div>
            <h3 class="elec-title">Recent bills</h3>
            <div class="tickercollected elec-grid">
                @forelse ($bills as $b)
                    @php $n = max(1, $counts[$b->room_number] ?? 0); @endphp
                    <div class="tickercollected1" style="cursor:default;">
                        <div class="roomjuelmont">
                            <h1>{{ \Illuminate\Support\Carbon::parse($b->period . '-01')->format('F Y') }}</h1>
                            <h2>Room {{ $b->room_number }}</h2>
                            <span>{{ $n }} {{ $n === 1 ? 'tenant' : 'tenants' }} sharing</span>
                        </div>
                        <div class="ticket-divider"><hr class="dashed-line"></div>
                        <h1 class="amountoaudjue">TOTAL BILL</h1>
                        <h1 class="perajue">₱{{ number_format($b->amount, 2) }}</h1>
                        <h1 class="duejue">₱{{ number_format($b->amount / $n, 2) }} per tenant</h1>
                        <div class="buttonpaidjue">
                            <a href="{{ route('admin.electricity.photo', $b->id) }}" target="_blank"
                               class="buttonpaidjue2 full" style="text-decoration:none;">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" /><circle cx="12" cy="12" r="3" /></svg>View bill photo
                            </a>
                        </div>
                    </div>
                @empty
                    <p class="no-rooms" style="grid-column:1/-1;">No electricity bills yet.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        (function () {
            const room = document.getElementById('elecRoom');
            const amount = document.getElementById('elecAmount');
            const note = document.getElementById('elecShare');
            const photo = document.getElementById('elecPhoto');
            const fname = document.getElementById('elecFileName');

            function share() {
                const n = Math.max(1, +room.selectedOptions[0].dataset.count || 0);
                const a = parseFloat(amount.value);
                if (!a) { note.hidden = true; return; }
                note.hidden = false;
                note.textContent = 'Each tenant pays ₱' + (a / n).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' (' + n + ' tenant' + (n > 1 ? 's' : '') + ')';
            }
            room.addEventListener('change', share);
            amount.addEventListener('input', share);
            photo.addEventListener('change', () => {
                fname.textContent = photo.files[0] ? photo.files[0].name : 'Click to upload (PNG or JPG)';
            });
        })();
    </script>
@endpush