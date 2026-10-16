@extends('layouts.app')

@section('title', 'Rooms')

@section('content')
    <h1 class="tit">Rooms</h1>
    <h1 class="ba">Browse all available rooms and pick the one that fits you best.</h1>
    <div class="four">
        <div class="four4">
            <h1 class="namber9">{{ $summary['open'] }}</h1><span>Rooms open right now</span>
        </div>
        <div class="four4">
            <h1 class="namber9">{{ $summary['listed'] }}</h1><span>Rooms listed</span>
        </div>
        <div class="four4">
            <h1 class="namber9">{{ $summary['full'] }}</h1><span>Rooms fully occupied</span>
        </div>
        <div class="four4">
            <h1 class="namber9">₱{{ number_format($summary['lowest'], 2) }}</h1><span>Lowest price / bed</span>
        </div>
    </div>
   <div class="search">
<div class="search-box">
    <svg class="search-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <circle cx="11" cy="11" r="7" />
        <path d="m20 20-3.5-3.5" />
    </svg>

    <input type="search" id="roomSearch" placeholder="Search rooms..." autocomplete="off" />

    <span class="search-caret" aria-hidden="true"></span>
</div>
    <div class="filter-wrap">
        <input type="button" class="filter" id="filterBtn" value="Filter" aria-expanded="false" aria-controls="filterPop">

        <div class="filter-pop" id="filterPop" hidden>
            <label for="fPrice">Max price / bed (₱)</label>
            <input type="number" id="fPrice" min="0" step="100" placeholder="e.g. 2500">

            <label for="fBed">Bed type</label>
            <select id="fBed">
                <option value="">Any</option>
                @foreach ($rooms->pluck('bed_type')->unique() as $type)
                    <option value="{{ $type }}">{{ $type }}</option>
                @endforeach
            </select>

            <label for="fFree">Free spaces</label>
            <select id="fFree">
                <option value="0">Any</option>
                <option value="1">1 or more</option>
                <option value="2">2 or more</option>
                <option value="4">4 or more</option>
                <option value="6">6 or more</option>
            </select>

            <button type="button" class="filter-reset" id="fReset">Reset</button>
        </div>
    </div>
</div>

<div class="filtering">
    <input type="button" class="fiveb active" data-filter="all" value="All rooms">
    <input type="button" class="fiveb" data-filter="vacants" value="Vacants">
    <input type="button" class="fiveb" data-filter="occupied" value="Occupied">
    <input type="button" class="fiveb" data-filter="full" value="Full">
    <input type="button" class="fiveb" data-filter="maintenance" value="Maintenance">
</div>
    <div class="notes">
        <div class="box9">
            <div class="gamayb"></div>
            <h1 class="ridds">Available bed</h1>
        </div>
        <div class="box9">
            <div class="gamayb2"></div>
            <h1 class="ridds">Occupied bed</h1>
        </div>
        <div class="box9">
            <h1 class="ridds2">Tap a room to select a bed and request it</h1>
        </div>
    </div>
   <div class="allrooms">
    @forelse ($rooms as $room)
       @php
    $inRoom   = $tenants->get($room->room_number, collect());
    $fullAt   = 2;
    $capacity = $room->total_beds * $fullAt;
    $freeBeds = max(0, $capacity - $inRoom->count());
    $tenantCount = $inRoom->count();
$split       = $tenantCount > 1;
$share       = $split ? (float) $room->price_per_bed / $tenantCount : (float) $room->price_per_bed;
@endphp

        <div class="roomstart"
     data-search="{{ strtolower('room ' . $room->room_number . ' ' . $room->sharing_type . ' ' . $room->bed_type) }}"
     data-tenants="{{ $inRoom->count() }}"
     data-free="{{ $freeBeds }}"
    data-price="{{ round($share) }}"
     data-bedtype="{{ $room->bed_type }}"
     data-maintenance="{{ $room->under_maintenance ? 1 : 0 }}">
            <div class="saleft">
                @if ($room->photo)
                    <img src="{{ asset($room->photo) }}" alt="Room {{ $room->room_number }}" class="roomimg">
                @else
                    <div class="bedicon">
                        <svg viewBox="0 0 24 24">
                            <path d="M3 5v14" />
                            <circle cx="7.5" cy="10.5" r="2" />
                            <path d="M11.5 10h7.5a2 2 0 0 1 2 2v7" />
                            <path d="M3 16h18" />
                        </svg>
                    </div>
                    <h1 class="roomputo">Room photo</h1>
                @endif
            </div>

            <div class="saright">
                <div class="butanganan">
                    <h1 class="numberh1">Room {{ $room->room_number }}<br></h1><span class="twinny">{{ $room->sharing_type }}</span>
                </div>

                <div class="sixboxesulit">
    @for ($b = 1; $b <= $room->total_beds; $b++)
        @php
           $bedPeople = $inRoom->where('bed_number', $b);
$taken     = $bedPeople->count();
$occupied  = $taken >= $fullAt;
$bedTitle  = \App\Support\DormStats::bedTitle($bedPeople);
        @endphp

        <div class="sixulit {{ $occupied ? 'occupied' : 'free' }}">
            <div class="aycon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                    stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M4 3v18" />
                    <path d="M20 3v18" />
                    <path d="M4 10h16" />
                    <path d="M4 17h16" />
                    <path d="M7 7.5h3" />
                    <path d="M7 14.5h3" />
                </svg></div>
           <h1 class="lowerdck">Bed {{ $b }} · {{ $bedTitle }}</h1>
            <span class="okyupayd" title="{{ $taken }} of 2 decks taken">{{ $occupied ? 'Occupied' : 'Available' }}</span>
        </div>
    @endfor
</div>
                <div class="tulo99">
                    <div class="butanganan2">
                        <h1 class="numberh12">BED TYPE<br></h1><span class="twinny2">{{ $room->bed_type }}</span>
                    </div>
                    <div class="butanganan2">
                        <h1 class="numberh12">TOTAL BEDS<br></h1><span class="twinny2">{{ $room->total_beds }} Beds</span>
                    </div>
                    <div class="butanganan2">
    <h1 class="numberh12">{{ $split ? 'PRICE / TENANT' : 'PRICE / BED' }}<br></h1>
    <span class="twinny2" style="color: #672C2C">₱{{ number_format(round($share)) }} / mo</span>
    @if ($split)

    @endif
</div>
                </div>

                <input type="button" class="detalye" value="View room details">
            </div>
        </div>
    @empty
        <p class="ba">No rooms yet.</p>
    @endforelse
</div>
<p class="no-rooms" id="noRooms" hidden>No rooms match your search.</p>
@endsection
@push('scripts')
<script>
(function () {
    const cards = Array.from(document.querySelectorAll('.roomstart'));
    if (!cards.length) return;

    const search = document.getElementById('roomSearch');
    const tabs   = document.querySelectorAll('.fiveb');
    const btn    = document.getElementById('filterBtn');
    const pop    = document.getElementById('filterPop');
    const fPrice = document.getElementById('fPrice');
    const fBed   = document.getElementById('fBed');
    const fFree  = document.getElementById('fFree');
    const none   = document.getElementById('noRooms');

    let status = 'all';

    const rules = {
        all:         () => true,
        vacants:     c => +c.dataset.free > 0 && c.dataset.maintenance !== '1',
        occupied:    c => +c.dataset.tenants > 0,
        full:        c => +c.dataset.free === 0,
        maintenance: c => c.dataset.maintenance === '1',
    };

    function apply() {
        const words   = search.value.toLowerCase().trim().split(/\s+/).filter(Boolean);
        const maxP    = parseFloat(fPrice.value);
        const bed     = fBed.value;
        const minFree = +fFree.value || 0;
        let shown = 0;

        cards.forEach(c => {
            const ok = rules[status](c)
                && words.every(w => c.dataset.search.includes(w))
                && (isNaN(maxP) || +c.dataset.price <= maxP)
                && (!bed || c.dataset.bedtype === bed)
                && +c.dataset.free >= minFree;

            c.classList.toggle('is-hidden', !ok);
            if (ok) shown++;
        });

        none.hidden = shown > 0;

        const active = (fPrice.value !== '' ? 1 : 0) + (bed ? 1 : 0) + (minFree ? 1 : 0);
        btn.value = active ? 'Filter (' + active + ')' : 'Filter';
    }


    search.addEventListener('input', apply);

    
    tabs.forEach(t => t.addEventListener('click', () => {
        status = t.dataset.filter;
        tabs.forEach(x => x.classList.toggle('active', x === t));
        apply();
    }));

    function setPanel(open) {
        pop.hidden = !open;
        btn.setAttribute('aria-expanded', String(open));
    }
    btn.addEventListener('click', e => { e.stopPropagation(); setPanel(pop.hidden); });
    pop.addEventListener('click', e => e.stopPropagation());
    document.addEventListener('click', () => setPanel(false));
    document.addEventListener('keydown', e => { if (e.key === 'Escape') setPanel(false); });

    [fPrice, fBed, fFree].forEach(el => el.addEventListener('input', apply));

    document.getElementById('fReset').addEventListener('click', () => {
        fPrice.value = '';
        fBed.value   = '';
        fFree.value  = '0';
        apply();
    });

    
    const q = new URLSearchParams(location.search).get('room');
    if (q) search.value = q;

    apply();
})();
</script>
@endpush