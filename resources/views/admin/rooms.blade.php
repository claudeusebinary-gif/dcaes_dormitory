@extends('layouts.app')

@section('title', 'Rooms')
@section('page_title', 'Rooms')
@section('page_subtitle', 'Manage every room, bed, and rate in the building — occupancy at a glance, edits in two clicks.')

@section('page_action')
    <button type="button" class="addroombtn" id="addRoomBtn">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
            stroke-linejoin="round" aria-hidden="true">
            <line x1="12" y1="5" x2="12" y2="19" />
            <line x1="5" y1="12" x2="19" y2="12" />
        </svg>Add new room
    </button>
@endsection

@section('content')
    <div class="adminrooms">

        <div class="four">
            <div class="four4">
                <h1 class="namber9">{{ $summary['total'] }}</h1>
                <div class="four4ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                        stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                        <polyline points="9 22 9 12 15 12 15 22" />
                    </svg></div>
                <span>Total rooms</span>
            </div>
            <div class="four4">
                <h1 class="namber9">{{ $summary['occupied_beds'] }}</h1>
                <div class="four4ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                        stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M4 3v18" />
                        <path d="M20 3v18" />
                        <path d="M4 10h16" />
                        <path d="M4 17h16" />
                    </svg></div>
                <span>Occupied beds</span>
            </div>
            <div class="four4">
                <h1 class="namber9">{{ $summary['vacant'] }}</h1>
                <div class="four4ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                        stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                        <polyline points="9 22 9 12 15 12 15 22" />
                    </svg></div>
                <span>Vacant rooms</span>
            </div>
            <div class="four4">
                <h1 class="namber9">{{ $summary['full'] }}</h1>
                <div class="four4ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                        stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                        <polyline points="9 22 9 12 15 12 15 22" />
                    </svg></div>
                <span>Full rooms</span>
            </div>
            <div class="four4">
                <h1 class="namber9">₱{{ number_format($summary['revenue']) }}</h1>
                <div class="four4ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                        stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <rect x="1" y="4" width="22" height="16" rx="2" />
                        <line x1="1" y1="10" x2="23" y2="10" />
                    </svg></div>
                <span>Monthly revenue</span>
            </div>
        </div>

        <div class="search">
            <div class="search-box">
                <svg class="search-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                    stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="11" cy="11" r="7" />
                    <path d="m20 20-3.5-3.5" />
                </svg>
                <input type="search" id="roomSearch" placeholder="Search rooms..." autocomplete="off" />
                <span class="search-caret" aria-hidden="true"></span>
            </div>
            <div class="filter-wrap">
                <input type="button" class="filter" id="filterBtn" value="Filter" aria-expanded="false"
                    aria-controls="filterPop">

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
                <h1 class="ridds2">Click "Edit room details" to change a room</h1>
            </div>
        </div>

        <div class="allrooms">
            @forelse ($rooms as $room)
                @php
                    $inRoom = $tenants->get($room->room_number, collect());
                    $fullAt = 2;
                    $capacity = $room->total_beds * $fullAt;
                    $freeBeds = max(0, $capacity - $inRoom->count());
                    $paths = $room->photo_paths;
                    $urls = array_map(fn($p) => asset($p), $paths);
                @endphp

                <div class="roomstart"
                    data-search="{{ strtolower('room ' . $room->room_number . ' ' . $room->sharing_type . ' ' . $room->bed_type) }}"
                    data-tenants="{{ $inRoom->count() }}" data-free="{{ $freeBeds }}"
                    data-price="{{ round($room->price_per_bed) }}" data-bedtype="{{ $room->bed_type }}"
                    data-maintenance="{{ $room->under_maintenance ? 1 : 0 }}">
                    <div class="saleft">
                        @if ($paths)
                            <img src="{{ $urls[0] }}" alt="Room {{ $room->room_number }}" class="roomimg">
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
                            <h1 class="numberh1">Room {{ $room->room_number }}<br></h1><span
                                class="twinny">{{ $room->sharing_type }}</span>
                        </div>

                        <div class="sixboxesulit">
                            @for ($b = 1; $b <= $room->total_beds; $b++)
                                @php
                                    $bedPeople = $inRoom->where('bed_number', $b);
                                    $taken = $bedPeople->count();
                                    $occupied = $taken >= $fullAt;
                                    $bedTitle = \App\Support\DormStats::bedTitle($bedPeople);
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
                                    <span class="okyupayd"
                                        title="{{ $taken }} of 2 decks taken">{{ $occupied ? 'Occupied' : 'Available' }}</span>
                                </div>
                            @endfor
                        </div>
                        <div class="tulo99">
                            <div class="butanganan2">
                                <h1 class="numberh12">BED TYPE<br></h1><span class="twinny2">{{ $room->bed_type }}</span>
                            </div>
                            <div class="butanganan2">
                                <h1 class="numberh12">TOTAL BEDS<br></h1><span class="twinny2">{{ $room->total_beds }}
                                    Beds</span>
                            </div>
                            <div class="butanganan2">
                                <h1 class="numberh12">PRICE / BED<br></h1>
                                <span class="twinny2" style="color: #672C2C">₱{{ number_format($room->price_per_bed) }} /
                                    mo</span>
                            </div>
                        </div>

                        <input type="button" class="detalye" value="Edit room details" data-id="{{ $room->id }}"
                            data-room="{{ $room->room_number }}" data-price="{{ (float) $room->price_per_bed }}"
                            data-sharing="{{ $room->sharing_type }}" data-bedtype="{{ $room->bed_type }}"
                            data-total-beds="{{ $room->total_beds }}" data-photos='@json($urls)' data-paths='@json($paths)'
                            data-amenities='@json($room->amenities)' data-maintenance="{{ $room->under_maintenance ? 1 : 0 }}"
                            data-availability="{{ $room->under_maintenance ? 'Under maintenance' : ($freeBeds > 0 ? $freeBeds . ' ' . \Illuminate\Support\Str::plural('space', $freeBeds) . ' open' : 'Fully occupied') }}"
                            data-availability-color="{{ $room->under_maintenance ? '#b26a00' : ($freeBeds > 0 ? '#1b7219' : '#b3261e') }}"
                            data-update-url="{{ route('admin.rooms.update', $room->id) }}"
                            data-maint-url="{{ route('admin.rooms.maintenance', $room->id) }}"
                            data-delete-url="{{ route('admin.rooms.destroy', $room->id) }}">
                    </div>
                </div>
            @empty
                <p class="ba">No rooms yet. Click "Add new room" to create one.</p>
            @endforelse
        </div>
        <p class="no-rooms" id="noRooms" hidden>No rooms match your search.</p>
        <div class="roommodals" id="roomModal" role="dialog" aria-modal="true" aria-hidden="true">
            <div class="wala">
                <button type="button" class="goback" data-close-modal>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                        aria-hidden="true">
                        <line x1="19" y1="12" x2="5" y2="12" />
                        <polyline points="12 19 5 12 12 5" />
                    </svg>
                    <span>Back to Rooms</span>
                </button>

                <div class="tuowala">
                    <svg class="chevron" data-prev xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" aria-hidden="true">
                        <polyline points="15 18 9 12 15 6" />
                    </svg>
                    <div class="bilog999 is-empty" data-cover>
                        <div class="coverempty" data-empty>
                            <div class="circ"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                    stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <rect x="3" y="3" width="18" height="18" rx="2" />
                                    <circle cx="8.5" cy="8.5" r="1.5" />
                                    <polyline points="21 15 16 10 5 21" />
                                </svg></div>
                            <b>Add room photos</b>
                            <span>Click to upload — first photo becomes the cover</span>
                        </div>
                        <img data-img alt="" hidden>
                    </div>
                    <svg class="chevron" data-next xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" aria-hidden="true">
                        <polyline points="9 18 15 12 9 6" />
                    </svg>
                </div>

                <div class="namber999">
                    <h1 data-caption>Room</h1>
                    <div class="putifour" data-strip></div>
                </div>
                <input type="file" data-file accept="image/jpeg,image/png" multiple hidden>
            </div>

            <div class="tuo">
                <div class="pagsamasama">
                    <div class="pagsama">
                        <span class="crumb">Room / <span class="crumb-now">Room <span data-room-number></span></span></span>
                        <div class="titlerow">
                            <h1 class="roomtitleee">Room <span data-room-number></span></h1>
                            <button type="button" class="editpill" id="editPill">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M12 20h9" />
                                    <path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4z" />
                                </svg>Edit
                            </button>
                        </div>
                        <span class="nite" data-fill="subtitle"></span>
                        <div class="typeedit" id="typeEdit" hidden>
                            <select id="editSharing" class="laglaggayan2" aria-label="Room type"></select>
                            <select id="editBedType" class="laglaggayan2" aria-label="Bed type"></select>
                        </div>
                    </div>
                    <div class="pagsama2">
                        <div class="priceedit">
                            <span class="pesoo">₱</span>
                            <input type="number" id="priceInput" class="priceinput" min="0" step="50">
                        </div>
                        <span class="nite2">per bed / month</span>
                    </div>
                </div>
                <hr class="saw1">
                <div class="tulo999">
                    <div class="butanganan22">
                        <h1 class="numberh122">BED TYPE<br></h1><span class="twinny22" data-fill="bedtype"></span>
                    </div>
                    <div class="butanganan22">
                        <h1 class="numberh122">TOTAL BEDS<br></h1><span class="twinny22" data-fill="totalbeds"></span>
                    </div>
                    <div class="butanganan22">
                        <h1 class="numberh122">AVAILABILITY<br></h1><span class="twinny22" data-fill="availability"></span>
                    </div>
                </div>
                <hr class="saw12">
                <div class="sixboxesulit" data-beds></div>
                <div class="notes notes--modal">
                    <div class="box9">
                        <div class="gamayb22"></div>
                        <h1 class="ridds">Available</h1>
                    </div>
                    <div class="box9">
                        <div class="gamayb222"></div>
                        <h1 class="ridds">Occupied</h1>
                    </div>
                </div>
                <h1 class="ameties">Amenities</h1>
                <div class="amemitiesbox" data-chips></div>
                <div class="amenadd">
                    <button type="button" class="addameni" data-add-amen>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <line x1="12" y1="5" x2="12" y2="19" />
                            <line x1="5" y1="12" x2="19" y2="12" />
                        </svg>Add amenity
                    </button>
                    <input type="text" class="amenin" data-amen-input maxlength="40" placeholder="e.g. Free Wi-Fi" hidden>
                </div>

                <p class="req-status" id="roomStatus" hidden></p>

                <div class="adminme">
                    <button type="button" class="bookthis" id="saveRoom" disabled>Save changes</button>
                    <button type="button" class="messageadmin" id="maintBtn">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path
                                d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z" />
                        </svg><span>Mark as under maintenance</span>
                    </button>
                    <button type="button" class="deleteroom" id="deleteRoom">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <polyline points="3 6 5 6 21 6" />
                            <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" />
                            <path d="M10 11v6" />
                            <path d="M14 11v6" />
                            <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2" />
                        </svg><span>Delete room</span>
                    </button>
                </div>
            </div>
        </div>
        <div class="roommodals" id="addModal" role="dialog" aria-modal="true" aria-hidden="true"
            data-url="{{ route('admin.rooms.store') }}">
            <div class="wala">
                <button type="button" class="goback" data-close-modal>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                        aria-hidden="true">
                        <line x1="19" y1="12" x2="5" y2="12" />
                        <polyline points="12 19 5 12 12 5" />
                    </svg>
                    <span>Back to Rooms</span>
                </button>

                <div class="tuowala">
                    <svg class="chevron" data-prev xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" aria-hidden="true">
                        <polyline points="15 18 9 12 15 6" />
                    </svg>
                    <div class="bilog999 is-empty" data-cover>
                        <div class="coverempty" data-empty>
                            <div class="circ"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                    stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <rect x="3" y="3" width="18" height="18" rx="2" />
                                    <circle cx="8.5" cy="8.5" r="1.5" />
                                    <polyline points="21 15 16 10 5 21" />
                                </svg></div>
                            <b>Add room photos</b>
                            <span>Click to upload — first photo becomes the cover</span>
                        </div>
                        <img data-img alt="" hidden>
                    </div>
                    <svg class="chevron" data-next xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" aria-hidden="true">
                        <polyline points="9 18 15 12 9 6" />
                    </svg>
                </div>

                <div class="namber999">
                    <h1 data-caption>New room</h1>
                    <div class="putifour" data-strip></div>
                </div>
                <input type="file" data-file accept="image/jpeg,image/png" multiple hidden>
            </div>

            <div class="tuo">
                <span class="crumb">Room / <span class="crumb-now">New room</span></span>
                <h1 class="roomtitleee">Add a room</h1>
                <span class="nite">Fill in the details tenants will see, plus how many beds are up for booking.</span>

                <div class="addgrid">
                    <div class="pirsname44">
                        <h1>ROOM NUMBER / NAME</h1>
                        <input type="text" id="arRoomNo" class="laglaggayan" maxlength="20" placeholder="e.g. 118"
                            autocomplete="off">
                    </div>
                    <div class="pirsname44">
                        <h1>PRICE PER BED / MONTH</h1>
                        <div class="pesofield">
                            <span>₱</span>
                            <input type="number" id="arPrice" class="laglaggayan" min="0" step="50" value="2000">
                        </div>
                    </div>
                    <div class="pirsname44">
                        <h1>ROOM TYPE</h1>
                        <select id="arSharing" class="laglaggayan2"></select>
                    </div>
                    <div class="pirsname44">
                        <h1>BED TYPE</h1>
                        <select id="arBedType" class="laglaggayan2"></select>
                    </div>
                </div>

                <div class="bedshead">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                        stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M4 3v18" />
                        <path d="M20 3v18" />
                        <path d="M4 10h16" />
                        <path d="M4 17h16" />
                    </svg>
                    <h1 class="ameties">Beds</h1>
                </div>
                <span class="bedsnote" id="arBedsNote"></span>
                <div class="sixboxesulit" id="arBeds"></div>

                <h1 class="ameties" style="margin-top:22px">Amenities</h1>
                <span class="bedsnote">Tap to include on the tenant-facing listing</span>
                <div class="amemitiesbox" data-chips></div>
                <div class="amenadd">
                    <button type="button" class="addameni" data-add-amen>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <line x1="12" y1="5" x2="12" y2="19" />
                            <line x1="5" y1="12" x2="19" y2="12" />
                        </svg>Custom amenity
                    </button>
                    <input type="text" class="amenin" data-amen-input maxlength="40" placeholder="e.g. Free Wi-Fi" hidden>
                </div>

                <p class="req-status" id="addStatus" hidden></p>

                <div class="addfoot">
                    <button type="button" class="draftbtn" id="arDraft">Save as draft</button>
                    <button type="button" class="publishbtn" id="arPublish">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                            <polyline points="17 21 17 13 7 13 7 21" />
                            <polyline points="7 3 7 8 15 8" />
                        </svg><span>Publish room</span>
                    </button>
                </div>
            </div>
        </div>

    </div>
@endsection
@push('scripts')
    <script>
        (function () {
            const cards = Array.from(document.querySelectorAll('.roomstart'));
            if (!cards.length) return;

            const search = document.getElementById('roomSearch');
            const tabs = document.querySelectorAll('.fiveb');
            const btn = document.getElementById('filterBtn');
            const pop = document.getElementById('filterPop');
            const fPrice = document.getElementById('fPrice');
            const fBed = document.getElementById('fBed');
            const fFree = document.getElementById('fFree');
            const none = document.getElementById('noRooms');
            let status = 'all';

            const rules = {
                all: () => true,
                vacants: c => +c.dataset.free > 0 && c.dataset.maintenance !== '1',
                occupied: c => +c.dataset.tenants > 0,
                full: c => +c.dataset.free === 0,
                maintenance: c => c.dataset.maintenance === '1',
            };

            function apply() {
                const words = search.value.toLowerCase().trim().split(/\s+/).filter(Boolean);
                const maxP = parseFloat(fPrice.value);
                const bed = fBed.value;
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

            function setPanel(open) { pop.hidden = !open; btn.setAttribute('aria-expanded', String(open)); }
            btn.addEventListener('click', e => { e.stopPropagation(); setPanel(pop.hidden); });
            pop.addEventListener('click', e => e.stopPropagation());
            document.addEventListener('click', () => setPanel(false));
            [fPrice, fBed, fFree].forEach(el => el.addEventListener('input', apply));
            document.getElementById('fReset').addEventListener('click', () => {
                fPrice.value = ''; fBed.value = ''; fFree.value = '0'; apply();
            });
            apply();
        })();
    </script>
    <script>
        (function () {
            const csrf = document.querySelector('meta[name="csrf-token"]').content;
            const $ = (sel, root) => (root || document).querySelector(sel);

            const SHARING = ['Bed Sharing', 'Twin Sharing', 'Good for 6', 'Good for 12'];
            const BEDTYPES = ['Double Deck', 'Single Bed'];
            const MAX_PHOTOS = 8, MAX_BYTES = 5 * 1024 * 1024;

            const icon = inner => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' + inner + '</svg>';
            const BED_SVG = icon('<path d="M4 3v18"/><path d="M20 3v18"/><path d="M4 10h16"/><path d="M4 17h16"/><path d="M7 7.5h3"/><path d="M7 14.5h3"/>');
            const CUSTOM_ICON = '<circle cx="12" cy="12" r="9"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/>';
            const PRESETS = [
                { name: 'Shared cabinets', icon: '<rect x="3" y="3" width="18" height="18" rx="2"/><line x1="8" y1="12" x2="16" y2="12"/>' },
                { name: 'Own toilet and bath', icon: '<rect x="3" y="3" width="18" height="18" rx="2"/><line x1="12" y1="3" x2="12" y2="21"/>' },
                { name: 'Kitchen for all', icon: '<rect x="3" y="3" width="18" height="18" rx="2"/><rect x="7" y="7" width="4" height="4"/><rect x="13" y="13" width="4" height="4"/>' },
                { name: 'Free electricfan', icon: CUSTOM_ICON },
            ];

            function fillSelect(sel, list, value) {
                const opts = list.slice();
                if (value && !opts.includes(value)) opts.unshift(value);
                sel.innerHTML = '';
                opts.forEach(o => {
                    const op = document.createElement('option');
                    op.value = op.textContent = o;
                    sel.appendChild(op);
                });
                if (value) sel.value = value;
            }

            function makeSender(statusEl) {
                function say(msg, kind) {
                    statusEl.hidden = !msg;
                    statusEl.textContent = msg || '';
                    statusEl.className = 'req-status' + (kind ? ' ' + kind : '');
                }
                async function send(url, body, method, btn, busyText) {
                    const textEl = btn.querySelector('span') || btn;
                    const label = textEl.textContent;
                    btn.disabled = true;
                    textEl.textContent = busyText;
                    say('');
                    try {
                        const res = await fetch(url, {
                            method: method,
                            headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
                            body: body
                        });
                        const data = await res.json().catch(() => ({}));
                        if (res.status === 419) throw new Error('Your session expired. Refresh the page and try again.');
                        if (res.status === 422 && data.errors) throw new Error(Object.values(data.errors)[0][0]);
                        if (!res.ok) throw new Error(data.message || 'Something went wrong. Please try again.');
                        say(data.message || 'Saved.', 'ok');
                        setTimeout(() => location.reload(), 700);
                        return true;
                    } catch (err) {
                        say(err instanceof TypeError ? 'No connection. Check your internet and try again.' : err.message, 'error');
                        btn.disabled = false;
                        textEl.textContent = label;
                        return false;
                    }
                }
                return { say, send };
            }

            function photoWidget(root, onChange, say) {
                const cover = $('[data-cover]', root), empty = $('[data-empty]', root), img = $('[data-img]', root);
                const caption = $('[data-caption]', root), strip = $('[data-strip]', root), file = $('[data-file]', root);
                let items = [], cur = 0, label = '';

                function render() {
                    if (cur >= items.length) cur = Math.max(0, items.length - 1);
                    const it = items[cur];
                    cover.classList.toggle('is-empty', !it);
                    empty.hidden = !!it;
                    img.hidden = !it;
                    if (it) img.src = it.src;
                    caption.textContent = label + (items.length ? ' • Photo ' + (cur + 1) + ' of ' + items.length : ' • No photos yet');

                    strip.innerHTML = '';
                    items.forEach((p, n) => {
                        const t = document.createElement('div');
                        t.className = 'fourputi' + (n === cur ? ' active' : '');
                        t.style.backgroundImage = 'url("' + p.src + '")';
                        t.addEventListener('click', () => { cur = n; render(); });
                        const x = document.createElement('button');
                        x.type = 'button';
                        x.className = 'photox';
                        x.setAttribute('aria-label', 'Remove photo');
                        x.textContent = '×';
                        x.addEventListener('click', e => {
                            e.stopPropagation();
                            if (p.file) URL.revokeObjectURL(p.src);
                            items.splice(n, 1);
                            render();
                            onChange();
                        });
                        t.appendChild(x);
                        strip.appendChild(t);
                    });
                    if (items.length < MAX_PHOTOS) {
                        const plus = document.createElement('div');
                        plus.className = 'fourputi plustile';
                        plus.textContent = '+';
                        plus.title = 'Add photo';
                        plus.addEventListener('click', () => file.click());
                        strip.appendChild(plus);
                    }
                }

                empty.addEventListener('click', () => file.click());
                $('[data-prev]', root).addEventListener('click', () => { if (items.length) { cur = (cur - 1 + items.length) % items.length; render(); } });
                $('[data-next]', root).addEventListener('click', () => { if (items.length) { cur = (cur + 1) % items.length; render(); } });

                file.addEventListener('change', () => {
                    let problem = '';
                    Array.from(file.files).forEach(f => {
                        if (!['image/jpeg', 'image/png'].includes(f.type)) { problem = 'Only JPG or PNG files are allowed.'; return; }
                        if (f.size > MAX_BYTES) { problem = 'A photo is too big. The limit is 5MB each.'; return; }
                        if (items.length >= MAX_PHOTOS) { problem = 'You can add up to ' + MAX_PHOTOS + ' photos.'; return; }
                        items.push({ src: URL.createObjectURL(f), file: f, path: null });
                    });
                    file.value = '';
                    cur = Math.max(0, items.length - 1);
                    say(problem, problem ? 'error' : '');
                    render();
                    onChange();
                });

                return {
                    set(urls, paths, name) {
                        items.forEach(p => { if (p.file) URL.revokeObjectURL(p.src); });
                        items = urls.map((u, i) => ({ src: u, path: paths[i], file: null }));
                        cur = 0;
                        label = name;
                        render();
                    },
                    items: () => items,
                    key: () => items.map(p => p.file ? 'new:' + p.file.name + p.file.size : p.path).join('|')
                };
            }

            function amenityWidget(root, onChange) {
                const box = $('[data-chips]', root), addBtn = $('[data-add-amen]', root), input = $('[data-amen-input]', root);
                let list = [];

                function render() {
                    box.innerHTML = '';
                    list.forEach(a => {
                        const b = document.createElement('button');
                        b.type = 'button';
                        b.className = 'boxame' + (a.on ? '' : ' is-off');
                        b.setAttribute('aria-pressed', String(a.on));
                        b.innerHTML = icon(a.icon);
                        const h = document.createElement('h1');
                        h.textContent = a.name;
                        b.appendChild(h);
                        b.addEventListener('click', () => { a.on = !a.on; render(); onChange(); });
                        box.appendChild(b);
                    });
                }

                function closeInput() { input.value = ''; input.hidden = true; addBtn.hidden = false; }
                function addCustom() {
                    const n = input.value.trim().slice(0, 40);
                    if (n && !list.some(a => a.name.toLowerCase() === n.toLowerCase())) {
                        list.push({ name: n, icon: CUSTOM_ICON, on: true });
                        render();
                        onChange();
                    }
                    closeInput();
                }

                addBtn.addEventListener('click', () => { addBtn.hidden = true; input.hidden = false; input.focus(); });
                input.addEventListener('keydown', e => {
                    if (e.key === 'Enter') { e.preventDefault(); addCustom(); }
                    if (e.key === 'Escape') { e.stopPropagation(); closeInput(); }
                });
                input.addEventListener('blur', () => { if (!input.hidden) addCustom(); });

                return {
                    set(saved) {
                        const names = saved === null ? PRESETS.map(p => p.name) : saved;
                        list = PRESETS.map(p => ({ name: p.name, icon: p.icon, on: names.includes(p.name) }));
                        names.filter(n => !PRESETS.some(p => p.name === n)).forEach(n => list.push({ name: n, icon: CUSTOM_ICON, on: true }));
                        closeInput();
                        render();
                    },
                    selected: () => list.filter(a => a.on).map(a => a.name),
                    key: () => list.map(a => a.name + (a.on ? 1 : 0)).join('|')
                };
            }

            function openModal(m) { m.classList.add('is-open'); m.setAttribute('aria-hidden', 'false'); document.body.style.overflow = 'hidden'; }
            function closeModal(m) { m.classList.remove('is-open'); m.setAttribute('aria-hidden', 'true'); document.body.style.overflow = ''; }

            const edit = document.getElementById('roomModal');
            const editSender = makeSender($('#roomStatus'));
            const priceInput = $('#priceInput'), sharingSel = $('#editSharing'), bedSel = $('#editBedType');
            const editPill = $('#editPill'), typeEdit = $('#typeEdit');
            const saveBtn = $('#saveRoom'), maintBtn = $('#maintBtn'), delBtn = $('#deleteRoom');
            let cur = null, base = '';

            const photos = photoWidget(edit, check, editSender.say);
            const amen = amenityWidget(edit, check);

            const fill = (key, text) => edit.querySelectorAll('[data-fill="' + key + '"]').forEach(el => el.textContent = text);
            const snapshot = () => JSON.stringify([priceInput.value.trim(), sharingSel.value, bedSel.value, amen.key(), photos.key()]);

            function check() {
                const price = parseFloat(priceInput.value);
                saveBtn.disabled = !(!isNaN(price) && price >= 0 && snapshot() !== base);
            }

            function openEdit(btn) {
                const d = btn.dataset;
                cur = d;
                edit.querySelectorAll('[data-room-number]').forEach(el => el.textContent = d.room);

                priceInput.value = d.price;
                fillSelect(sharingSel, SHARING, d.sharing);
                fillSelect(bedSel, BEDTYPES, d.bedtype);
                fill('subtitle', d.sharing + ' · ' + d.bedtype);
                fill('bedtype', d.bedtype);
                fill('totalbeds', d.totalBeds + ' Beds');
                fill('availability', d.availability);
                const av = edit.querySelector('[data-fill="availability"]');
                if (av) av.style.color = d.availabilityColor;

                const src = btn.closest('.roomstart').querySelector('.sixboxesulit');
                $('[data-beds]', edit).innerHTML = src ? src.innerHTML : '';

                typeEdit.hidden = true;
                editPill.classList.remove('on');
                maintBtn.disabled = delBtn.disabled = false;
                maintBtn.querySelector('span').textContent = d.maintenance === '1' ? 'Reopen room for booking' : 'Mark as under maintenance';
                delBtn.querySelector('span').textContent = 'Delete room';

                photos.set(JSON.parse(d.photos || '[]'), JSON.parse(d.paths || '[]'), 'Room ' + d.room);
                amen.set(JSON.parse(d.amenities || 'null'));
                editSender.say('');
                saveBtn.textContent = 'Save changes';
                base = snapshot();
                check();
                openModal(edit);
            }

            document.querySelectorAll('.detalye').forEach(b => b.addEventListener('click', () => openEdit(b)));

            editPill.addEventListener('click', () => {
                typeEdit.hidden = !typeEdit.hidden;
                editPill.classList.toggle('on', !typeEdit.hidden);
            });
            [sharingSel, bedSel].forEach(s => s.addEventListener('change', () => {
                fill('subtitle', sharingSel.value + ' · ' + bedSel.value);
                fill('bedtype', bedSel.value);
                check();
            }));
            priceInput.addEventListener('input', check);

            saveBtn.addEventListener('click', () => {
                if (saveBtn.disabled) return;
                const fd = new FormData();
                fd.append('_method', 'PUT');
                fd.append('sharing_type', sharingSel.value);
                fd.append('bed_type', bedSel.value);
                fd.append('price_per_bed', priceInput.value);
                amen.selected().forEach(a => fd.append('amenities[]', a));
                photos.items().forEach(p => p.file ? fd.append('new_photos[]', p.file) : fd.append('keep[]', p.path));
                editSender.send(cur.updateUrl, fd, 'POST', saveBtn, 'Saving...');
            });

            maintBtn.addEventListener('click', () => {
                editSender.send(cur.maintUrl, null, 'POST', maintBtn, 'Updating...');
            });

            delBtn.addEventListener('click', () => {
                if (!confirm('Delete Room ' + cur.room + '? This cannot be undone.')) return;
                editSender.send(cur.deleteUrl, null, 'DELETE', delBtn, 'Deleting...');
            });

            const add = document.getElementById('addModal');
            const addSender = makeSender($('#addStatus'));
            const arRoomNo = $('#arRoomNo'), arPrice = $('#arPrice'), arSharing = $('#arSharing'), arBedType = $('#arBedType');
            const arBeds = $('#arBeds'), arBedsNote = $('#arBedsNote');
            const arDraft = $('#arDraft'), arPublish = $('#arPublish');
            let bedCount = 6;

            const addPhotos = photoWidget(add, () => { }, addSender.say);
            const addAmen = amenityWidget(add, () => { });

            function renderBeds() {
                arBeds.innerHTML = '';
                for (let i = 1; i <= bedCount; i++) {
                    const c = document.createElement('div');
                    c.className = 'sixulit free';
                    c.innerHTML = '<div class="aycon">' + BED_SVG + '</div><h1 class="lowerdck">Bed ' + i + ' · Lower &amp; Upper</h1><span class="okyupayd">Available</span>';
                    if (i === bedCount && bedCount > 1) {
                        const x = document.createElement('button');
                        x.type = 'button';
                        x.className = 'bedx';
                        x.setAttribute('aria-label', 'Remove last bed');
                        x.textContent = '×';
                        x.addEventListener('click', () => { bedCount--; renderBeds(); });
                        c.appendChild(x);
                    }
                    arBeds.appendChild(c);
                }
                if (bedCount < 20) {
                    const a = document.createElement('button');
                    a.type = 'button';
                    a.className = 'addbedcard';
                    a.textContent = '+ Add bed';
                    a.addEventListener('click', () => { bedCount++; renderBeds(); });
                    arBeds.appendChild(a);
                }
                arBedsNote.textContent = bedCount + ' ' + (bedCount === 1 ? 'bed' : 'beds') + ' · all start Available';
            }

            function openAdd() {
                arRoomNo.value = '';
                arPrice.value = 2000;
                fillSelect(arSharing, SHARING, SHARING[0]);
                fillSelect(arBedType, BEDTYPES, BEDTYPES[0]);
                bedCount = 6;
                renderBeds();
                addPhotos.set([], [], 'New room');
                addAmen.set(null);
                addSender.say('');
                arDraft.disabled = arPublish.disabled = false;
                openModal(add);
                arRoomNo.focus();
            }

            function submitRoom(draft, btn, busy) {
                const no = arRoomNo.value.trim();
                const price = parseFloat(arPrice.value);
                if (!no) { addSender.say('Enter a room number or name.', 'error'); arRoomNo.focus(); return; }
                if (isNaN(price) || price < 0) { addSender.say('Enter a valid price per bed.', 'error'); arPrice.focus(); return; }

                const fd = new FormData();
                fd.append('room_number', no);
                fd.append('price_per_bed', price);
                fd.append('sharing_type', arSharing.value);
                fd.append('bed_type', arBedType.value);
                fd.append('total_beds', bedCount);
                fd.append('draft', draft ? 1 : 0);
                addAmen.selected().forEach(a => fd.append('amenities[]', a));
                addPhotos.items().forEach(p => fd.append('new_photos[]', p.file));

                arDraft.disabled = arPublish.disabled = true;
                addSender.send(add.dataset.url, fd, 'POST', btn, busy).then(ok => { if (!ok) arDraft.disabled = arPublish.disabled = false; });
            }

            $('#addRoomBtn').addEventListener('click', openAdd);
            arDraft.addEventListener('click', () => submitRoom(true, arDraft, 'Saving...'));
            arPublish.addEventListener('click', () => submitRoom(false, arPublish, 'Publishing...'));

            [edit, add].forEach(m => m.addEventListener('click', e => {
                if (e.target.closest('[data-close-modal]')) closeModal(m);
            }));
            document.addEventListener('keydown', e => {
                if (e.key !== 'Escape') return;
                [edit, add].forEach(m => { if (m.classList.contains('is-open')) closeModal(m); });
            });
        })();
    </script>
@endpush