@extends('layouts.app')

@section('title', 'Dorm Information')

@section('content')
    <a href="{{ route('tenant.rooms') }}" class="roomlist-btn">View room list</a>
    <h1 class="tit">Dorm</h1>
    <h1 class="ba">Click a building or room on the map to view details.</h1>
    <div class="up">
        <h1 class="over">OVERALL</h1>
        <h1 class="t">{{ $overview['total'] }} <span>Total Rooms</span></h1>
        <hr class="ver">
        <h1 class="t">{{ $overview['available'] }} <span>Available</span></h1>
        <hr class="ver">
        <h1 class="t">{{ $overview['limited'] }} <span>Limited</span></h1>
        <hr class="ver">
        <h1 class="t">{{ $overview['full'] }} <span>Full</span></h1>
        <h1 class="percint">{{ $overview['percent'] }}% of rooms have open beds</h1>
    </div>
    <div class="mains">
        <div class="leftsidebox">
            <div class="ruw">
                <div class="mainhouse">
                    <h1 class="huse">MAIN HOUSE<br><span>Owner’s residence</span></h1>
                </div>
                <div class="mainhouse2">
                    <svg viewBox="0 0 24 24">
                        <path d="M13 4h3a2 2 0 0 1 2 2v14" />
                        <path d="M2 20h3" />
                        <path d="M13 20h9" />
                        <path d="M10 12v.01" />
                        <path
                            d="M13 4.562v16.157a1 1 0 0 1-1.242.97L5 20V5.562a2 2 0 0 1 1.515-1.94l4-1A2 2 0 0 1 13 4.561Z" />
                    </svg>
                    <span class="huse21">Main Entrance</span>
                </div>
                <div class="mainhouse">
                    <h1 class="huse">SALON<br><span>Business place</span></h1>
                </div>
            </div>
            <div class="hati"></div>
            <div class="dalawas">
                <div class="group1">
                    <div class="roms7">
                        <div class="siven">
                            <div class="dot {{ $rooms[100]['status'] }}"></div>
                            <h1 class="ruums">Room 100</h1>
                            <div class="leet">
                                <h1 class="overss">{{ $rooms[100]['occupied'] }}/{{ $rooms[100]['capacity'] }}</h1>
                            </div>
                        </div>
                        <div class="siven">
                            <div class="dot {{ $rooms[101]['status'] }}"></div>
                            <h1 class="ruums">Room 101</h1>
                            <div class="leet">
                                <h1 class="overss">{{ $rooms[101]['occupied'] }}/{{ $rooms[101]['capacity'] }}</h1>
                            </div>
                        </div>
                        <div class="siven">
                            <div class="dot {{ $rooms[102]['status'] }}"></div>
                            <h1 class="ruums">Room 102</h1>
                            <div class="leet">
                                <h1 class="overss">{{ $rooms[102]['occupied'] }}/{{ $rooms[102]['capacity'] }}</h1>
                            </div>
                        </div>
                        <div class="siven">
                            <div class="dot {{ $rooms[103]['status'] }}"></div>
                            <h1 class="ruums">Room 103</h1>
                            <div class="leet">
                                <h1 class="overss">{{ $rooms[103]['occupied'] }}/{{ $rooms[103]['capacity'] }}</h1>
                            </div>
                        </div>
                        <div class="siven">
                            <div class="dot {{ $rooms[104]['status'] }}"></div>
                            <h1 class="ruums">Room 104</h1>
                            <div class="leet">
                                <h1 class="overss">{{ $rooms[104]['occupied'] }}/{{ $rooms[104]['capacity'] }}</h1>
                            </div>
                        </div>
                        <div class="siven">
                            <div class="dot {{ $rooms[105]['status'] }}"></div>
                            <h1 class="ruums">Room 105</h1>
                            <div class="leet">
                                <h1 class="overss">{{ $rooms[105]['occupied'] }}/{{ $rooms[105]['capacity'] }}</h1>
                            </div>
                        </div>
                        <div class="siven4">
                            <div class="dot {{ $rooms[106]['status'] }}"></div>
                            <h1 class="ruums">Room 106</h1>
                            <div class="leet">
                                <h1 class="overss">{{ $rooms[106]['occupied'] }}/{{ $rooms[106]['capacity'] }}</h1>
                            </div>
                        </div>
                    </div>
                    <div class="hatee">
                        <h1 class="parking">Parking space</h1>
                        <h1 class="parking">Parking space</h1>
                        <h1 class="parking">Parking space</h1>
                        <h1 class="parking">Parking space</h1>
                        <h1 class="parking">Parking space</h1>
                        <h1 class="parking">Parking space</h1>
                        <h1 class="parking">Parking space</h1>
                    </div>
                </div>

                <div class="inthemiddle">
                    <div class="sidehate">
                        <h1 class="parking">Parking space</h1>
                        <h1 class="parking">Parking space</h1>
                    </div>
                    <div class="upatss">
                        <div class="upatbx">
                            <div class="dot {{ $rooms[107]['status'] }}"></div>
                            <h1 class="ruums">Room 107</h1>
                            <div class="leet">
                                <h1 class="overss">{{ $rooms[107]['occupied'] }}/{{ $rooms[107]['capacity'] }}</h1>
                            </div>
                        </div>
                        <div class="upatbx">
                            <div class="dot {{ $rooms[108]['status'] }}"></div>
                            <h1 class="ruums">Room 108</h1>
                            <div class="leet">
                                <h1 class="overss">{{ $rooms[108]['occupied'] }}/{{ $rooms[108]['capacity'] }}</h1>
                            </div>
                        </div>
                        <div class="upatbx">
                            <div class="dot {{ $rooms[109]['status'] }}"></div>
                            <h1 class="ruums">Room 109</h1>
                            <div class="leet">
                                <h1 class="overss">{{ $rooms[109]['occupied'] }}/{{ $rooms[109]['capacity'] }}</h1>
                            </div>
                        </div>
                        <div class="upatbx">
                            <div class="dot {{ $rooms[110]['status'] }}"></div>
                            <h1 class="ruums">Room 110</h1>
                            <div class="leet">
                                <h1 class="overss">{{ $rooms[110]['occupied'] }}/{{ $rooms[110]['capacity'] }}</h1>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="group2">
                    <div class="hatee2">
                        <h1 class="parking">Parking space</h1>
                        <h1 class="parking">Parking space</h1>
                        <h1 class="parking">Parking space</h1>
                        <h1 class="parking">Parking space</h1>
                        <h1 class="parking">Parking space</h1>
                    </div>
                    <div class="roms7">
                        <div class="siven2">
                            <svg viewBox="0 0 24 24">
                                <path d="M13 4h3a2 2 0 0 1 2 2v14" />
                                <path d="M2 20h3" />
                                <path d="M13 20h9" />
                                <path d="M10 12v.01" />
                                <path
                                    d="M13 4.562v16.157a1 1 0 0 1-1.242.97L5 20V5.562a2 2 0 0 1 1.515-1.94l4-1A2 2 0 0 1 13 4.561Z" />
                            </svg>
                            <span class="huse21">Entrance</span>
                        </div>
                        <div class="siven">
                            <div class="dot {{ $rooms[116]['status'] }}"></div>
                            <h1 class="ruums">Room 116</h1>
                            <div class="leet">
                                <h1 class="overss">{{ $rooms[116]['occupied'] }}/{{ $rooms[116]['capacity'] }}</h1>
                            </div>
                        </div>
                        <div class="siven">
                            <div class="dot {{ $rooms[115]['status'] }}"></div>
                            <h1 class="ruums">Room 115</h1>
                            <div class="leet">
                                <h1 class="overss">{{ $rooms[115]['occupied'] }}/{{ $rooms[115]['capacity'] }}</h1>
                            </div>
                        </div>
                        <div class="siven">
                            <div class="dot {{ $rooms[114]['status'] }}"></div>
                            <h1 class="ruums">Room 114</h1>
                            <div class="leet">
                                <h1 class="overss">{{ $rooms[114]['occupied'] }}/{{ $rooms[114]['capacity'] }}</h1>
                            </div>
                        </div>
                        <div class="siven">
                            <div class="dot {{ $rooms[113]['status'] }}"></div>
                            <h1 class="ruums">Room 113</h1>
                            <div class="leet">
                                <h1 class="overss">{{ $rooms[113]['occupied'] }}/{{ $rooms[113]['capacity'] }}</h1>
                            </div>
                        </div>
                        <div class="siven">
                            <div class="dot {{ $rooms[112]['status'] }}"></div>
                            <h1 class="ruums">Room 112</h1>
                            <div class="leet">
                                <h1 class="overss">{{ $rooms[112]['occupied'] }}/{{ $rooms[112]['capacity'] }}</h1>
                            </div>
                        </div>
                        <div class="siven4">
                            <div class="dot {{ $rooms[111]['status'] }}"></div>
                            <h1 class="ruums">Room 111</h1>
                            <div class="leet">
                                <h1 class="overss">{{ $rooms[111]['occupied'] }}/{{ $rooms[111]['capacity'] }}</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="rightcol">
            <div class="tulooo">
                <h1 class="legend">MAP LEGEND</h1>
                <div class="lista">
                    <div class="lagayns">
                        <div class="leetss"></div>
                        <h1 class="uhner">Owner's House / Salon</h1>
                    </div>
                    <div class="lagayns">
                        <div class="leetss" style="background-color:#3F8F5F; "></div>
                        <h1 class="uhner">Available</h1>
                    </div>
                    <div class="lagayns">
                        <div class="leetss" style="background-color:#C98A2C; "></div>
                        <h1 class="uhner">Limited slots</h1>
                    </div>
                    <div class="lagayns">
                        <div class="leetss" style="background-color:#FF0000; "></div>
                        <h1 class="uhner">Full</h1>
                    </div>
                </div>
            </div>
            <div class="tulooo" id="roomInfo">
                <h1 class="legend1" id="riTitle">SELECT A ROOM</h1>

                <span class="tap" id="riHint">Tap any room on the map to see its
                    occupancy and open a full detail
                    view.
                </span>

                <div class="ri-detail" id="riDetail" hidden>
                    <p class="ri-beds">
                        <svg viewBox="0 0 24 24">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                            <path d="M16 3.128a4 4 0 0 1 0 7.744" />
                            <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
                            <circle cx="9" cy="7" r="4" />
                        </svg>
                        <span id="riBeds"></span>
                    </p>
                    <span class="ri-pill" id="riPill"></span>
                    <p class="ri-link">Click the box to view full details</p>
                </div>
            </div>
          <div class="tulooo">
    <h1 class="legend12">
        <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><path d="M12 17h.01"/></svg>
        NEED HELP?
    </h1>
    <span class="tap3">Can't find your room or something
        looks off on the map? Reach out and
        we'll sort it out.
    </span>

    <div class="toobt">
        <a href="#" target="_blank" class="askk">
            <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><path d="M12 17h.01"/></svg>
            Ask Admin
        </a>
        <a href="#" target="_blank" class="call">
            <svg viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
            Call Admin
        </a>
    </div>
</div>
        </div>
    </div>
    @include('partials.footer')
    <script type="application/json" id="roomData">@json($rooms)</script>

<div class="rmd" id="mainnn" hidden>
    <div class="boxas" role="dialog" aria-modal="true" aria-labelledby="mainnnTitle">
        <button type="button" class="mainnn-close" id="sara" aria-label="Close">
            <svg viewBox="0 0 24 24"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
        </button>

        <h2 class="mainnntitle" id="mainnnTitle"></h2>
        <p class="mainnn-type" id="mainnnType"></p>

        <div class="mainnn-beds" id="mainnnBeds"></div>

       <div class="infos">
    <div><p class="lbl2">BED TYPE</p><p class="tas" id="mainnnBedType"></p></div>
    <div><p class="lbl2">TOTAL BEDS</p><p class="tas" id="mainnnTotal"></p></div>

    <div class="btnbaba">
        <p class="rmd-note" id="mainnnPriceNote" hidden></p>
        <a href="#" class="mainnn-btn" id="mainnnMore" data-base="{{ route('tenant.rooms') }}">View more details</a>
    </div>

    <div><p class="lbl2">AVAILABLE</p><p class="tas" id="mainnnFree"></p></div>
    <div>
        <p class="lbl2" id="mainnnPriceLbl">PRICE / BED</p>
        <p class="price" id="mainnnPrice"></p>
    </div>
</div>
    </div>
</div>
@endsection
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const rooms  = JSON.parse(document.getElementById('roomData').textContent);
    const boxes  = document.querySelectorAll('.siven, .siven4, .upatbx');

   
    const title  = document.getElementById('riTitle');
    const hint   = document.getElementById('riHint');
    const detail = document.getElementById('riDetail');
    const beds   = document.getElementById('riBeds');
    const pill   = document.getElementById('riPill');
    const labels = { available: 'Spaces available', limited: 'Filling up fast', full: 'Fully booked' };
    let pinned = null;

    function show(box) {
        const name = box.querySelector('.ruums').textContent.trim();
        const [occ, cap] = box.querySelector('.overss').textContent.trim().split('/');
        const dot = box.querySelector('.dot');
        const status = ['full', 'limited', 'available'].find(s => dot.classList.contains(s)) || 'available';

        title.textContent = name.toUpperCase();
        beds.textContent  = occ + ' / ' + cap + ' beds occupied';
        pill.textContent  = labels[status];
        pill.className    = 'ri-pill ' + status;
        hint.hidden = true;
        detail.hidden = false;
    }
    function reset() {
        title.textContent = 'SELECT A ROOM';
        hint.hidden = false;
        detail.hidden = true;
    }

    const modal = document.getElementById('mainnn');
    const bedIcon = '<svg viewBox="0 0 24 24"><path d="M2 20v-8a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v8"/><path d="M4 10V6a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v4"/><path d="M12 4v6"/><path d="M2 18h20"/></svg>';

    function openModal(box) {
    const n = parseInt(box.querySelector('.ruums').textContent.replace(/\D/g, ''), 10);
    const r = rooms[n];
    if (!r) return;

    document.getElementById('mainnnTitle').textContent   = 'Room ' + n;
    document.getElementById('mainnnType').textContent    = r.type;
    document.getElementById('mainnnBedType').textContent = r.bed_type;
    document.getElementById('mainnnTotal').textContent   = r.total_beds + ' Beds';

  
    const tenants = r.occupied;
    const split   = tenants > 1;
    const share   = split ? r.price / tenants : r.price;

    document.getElementById('mainnnPriceLbl').textContent = split ? 'PRICE / TENANT' : 'PRICE / BED';
    document.getElementById('mainnnPrice').textContent    = '₱' + Math.round(share).toLocaleString() + ' / mo';

    const priceNote = document.getElementById('mainnnPriceNote');
    priceNote.hidden      = !split;
    priceNote.textContent = split
        ? 'Each · ₱' + Number(r.price).toLocaleString() + ' split by ' + tenants + ' tenants'
        : '';

    const free = document.getElementById('mainnnFree');
    free.textContent = r.free > 0 ? r.free + (r.free === 1 ? ' space' : ' spaces') : 'FULL';
    free.classList.toggle('is-full', r.free === 0);

    document.getElementById('mainnnBeds').innerHTML = r.beds.map(b => {
        const note = b.status === 'occupied' ? 'Occupied'
                   : (b.taken === 1 ? '1 deck free' : 'Available');
        return '<div class="mainnn-bed ' + b.status + '">' + bedIcon +
              '<p class="mainnn-bedname">Bed ' + b.number + ' · ' + b.title.replace('&', '&amp;') + '</p>' +
               '<p class="mainnn-bedstat">' + note + '</p></div>';
    }).join('');

    const more = document.getElementById('mainnnMore');
    more.href = more.dataset.base + '?room=' + n;

    modal.hidden = false;
    document.body.style.overflow = 'hidden';
}   

    function closeModal() {
        modal.hidden = true;
        document.body.style.overflow = '';
    }

    document.getElementById('sara').addEventListener('click', closeModal);
    modal.addEventListener('click', e => { if (e.target === modal) closeModal(); });  
    document.addEventListener('keydown', e => { if (e.key === 'Escape' && !modal.hidden) closeModal(); });

 
    boxes.forEach(box => {
        box.addEventListener('mouseenter', () => show(box));
        box.addEventListener('mouseleave', () => pinned ? show(pinned) : reset());
        box.addEventListener('click', () => {
            if (pinned) pinned.classList.remove('picked');
            pinned = box;
            box.classList.add('picked');
            show(box);
            openModal(box);
        });
    });
});
</script>
@endpush