@extends('layouts.app')

@section('title', 'Rooms')

@section('content')
@section('page_title', 'Rooms')
@section('page_subtitle', 'Browse all available rooms and pick the one that fits you best.')
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
            <h1 class="ridds2">Tap a room to select a bed and request it</h1>
        </div>
    </div>
    <div class="allrooms">
        @forelse ($rooms as $room)
            @php
                $inRoom = $tenants->get($room->room_number, collect());
                $fullAt = 2;
                $capacity = $room->total_beds * $fullAt;
                $freeBeds = max(0, $capacity - $inRoom->count());
                $tenantCount = $inRoom->count();
                $split = $tenantCount > 1;
                $share = $split ? (float) $room->price_per_bed / $tenantCount : (float) $room->price_per_bed;
                $photoList = $room->photo
                    ? [asset($room->photo)]
                    : [
                        asset('images/rooms/sample-room-1.svg'),
                        asset('images/rooms/sample-room-2.svg'),
                        asset('images/rooms/sample-room-3.svg'),
                        asset('images/rooms/sample-room-4.svg'),
                    ];
            @endphp

            <div class="roomstart"
                data-search="{{ strtolower('room ' . $room->room_number . ' ' . $room->sharing_type . ' ' . $room->bed_type) }}"
                data-tenants="{{ $inRoom->count() }}" data-free="{{ $freeBeds }}" data-price="{{ round($share) }}"
                data-bedtype="{{ $room->bed_type }}" data-maintenance="{{ $room->under_maintenance ? 1 : 0 }}">
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
                            <h1 class="numberh12">{{ $split ? 'PRICE / TENANT' : 'PRICE / BED' }}<br></h1>
                            <span class="twinny2" style="color: #672C2C">₱{{ number_format(round($share)) }} / mo</span>
                        </div>
                    </div>

                    <input type="button" class="detalye" value="View room details" data-room="{{ $room->room_number }}"
                        data-price="{{ number_format(round($share)) }}"
                        data-price-label="{{ $split ? 'per tenant / month' : 'per bed / month' }}"
                        data-sharing="{{ $room->sharing_type }}" data-bedtype="{{ $room->bed_type }}"
                        data-total-beds="{{ $room->total_beds }}" data-photos='@json($photoList)'
                        data-availability="{{ $room->under_maintenance ? 'Under maintenance' : ($freeBeds > 0 ? $freeBeds . ' ' . \Illuminate\Support\Str::plural('space', $freeBeds) . ' open' : 'Fully occupied') }}"
                        data-availability-color="{{ $room->under_maintenance ? '#b26a00' : ($freeBeds > 0 ? '#1b7219' : '#b3261e') }}">
                </div>
            </div>
        @empty
            <p class="ba">No rooms yet.</p>
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
                <svg class="chevron" data-prev xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    aria-hidden="true">
                    <polyline points="15 18 9 12 15 6" />
                </svg>
                <div class="bilog999">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                        stroke-linejoin="round" aria-hidden="true">
                        <path d="M4 3v18" />
                        <path d="M20 3v18" />
                        <path d="M4 10h16" />
                        <path d="M4 17h16" />
                        <path d="M7 7.5h3" />
                        <path d="M7 14.5h3" />
                    </svg>
                    <button type="button" class="expand" data-expand aria-label="View full size">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" aria-hidden="true">
                            <polyline points="15 3 21 3 21 9" />
                            <polyline points="9 21 3 21 3 15" />
                            <line x1="21" y1="3" x2="14" y2="10" />
                            <line x1="3" y1="21" x2="10" y2="14" />
                        </svg>
                    </button>
                </div>
                <svg class="chevron" data-next xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    aria-hidden="true">
                    <polyline points="9 18 15 12 9 6" />
                </svg>
            </div>

            <div class="namber999">
                <h1 data-fill="caption">Room 100 • Photo 1 of 4</h1>
                <div class="putifour">
                    <div class="fourputi"></div>
                    <div class="fourputi"></div>
                    <div class="fourputi"></div>
                    <div class="fourputi"></div>
                </div>
            </div>
        </div>
        <div class="tuo">
            <div class="pagsamasama">
                <div class="pagsama">
                    <span class="crumb">Room / <span class="crumb-now">Room <span data-room-number>100</span></span></span>
                    <h1 class="roomtitleee">Room <span data-room-number>100</span></h1>
                    <span class="nite" data-fill="subtitle">Twin Sharing · Double Deck Beds</span>
                </div>
                <div class="pagsama2">
                    <h1 class="roomtitleee2" data-fill="price">₱2,000 </h1>
                    <span class="nite2" data-fill="price-label">per bed / month</span>
                </div>
            </div>
            <hr class="saw1">
            <div class="tulo999">
                <div class="butanganan22">
                    <h1 class="numberh122">BED TYPE<br></h1><span class="twinny22" data-fill="bedtype">Double Deck</span>
                </div>
                <div class="butanganan22">
                    <h1 class="numberh122">TOTAL BEDS<br></h1><span class="twinny22" data-fill="totalbeds">6 Beds</span>
                </div>
                <div class="butanganan22">
                    <h1 class="numberh122">AVAILABILITY<br></h1>
                    <span class="twinny22" data-fill="availability" style="color: #1b7219">2 beds open</span>
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

                <div class="deckpick" id="deckPick">
                    <span>Choose deck:</span>
                    <button type="button" data-deck="Lower">Lower</button>
                    <button type="button" data-deck="Upper">Upper</button>
                </div>
            </div>
            <p class="bed-hint" id="bedHint"></p>
            <h1 class="ameties">Amenities</h1>
            <div class="amemitiesbox">
                <div class="boxame">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                        stroke-linejoin="round" aria-hidden="true">
                        <rect x="3" y="3" width="18" height="18" rx="2" />
                        <line x1="8" y1="12" x2="16" y2="12" />
                    </svg>
                    <h1>Shared cabinets</h1>
                </div>
                <div class="boxame">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                        stroke-linejoin="round" aria-hidden="true">
                        <rect x="3" y="3" width="18" height="18" rx="2" />
                        <line x1="12" y1="3" x2="12" y2="21" />
                    </svg>
                    <h1>Own toilet and bath</h1>
                </div>
                <div class="boxame">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                        stroke-linejoin="round" aria-hidden="true">
                        <rect x="3" y="3" width="18" height="18" rx="2" />
                        <rect x="7" y="7" width="4" height="4" />
                        <rect x="13" y="13" width="4" height="4" />
                    </svg>
                    <h1>Kitchen for all</h1>
                </div>
                <div class="boxame">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                        stroke-linejoin="round" aria-hidden="true">
                        <circle cx="12" cy="12" r="9" />
                        <line x1="12" y1="8" x2="12" y2="16" />
                        <line x1="8" y1="12" x2="16" y2="12" />
                    </svg>
                    <h1>Free electricfan</h1>
                </div>
            </div>
            <div class="adminme">
                <input type="button" value="Message admin" class="messageadmin" data-open-admin>
                <input type="button" value="Book this room" class="bookthis" data-open-payment>
            </div>
        </div>
    </div>

    <div class="lightbox" id="lightbox" aria-hidden="true">
        <button type="button" class="lightbox-close" data-lightbox-close aria-label="Close">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round" aria-hidden="true">
                <line x1="18" y1="6" x2="6" y2="18" />
                <line x1="6" y1="6" x2="18" y2="18" />
            </svg>
        </button>
        <img src="" alt="" id="lightboxImg">
    </div>

    <div class="adminmodal" id="adminModal" role="dialog" aria-modal="true" aria-hidden="true">
        <div class="adminbox" id="messageModal" hidden>
            <button type="button" class="admin-close" data-close-admin aria-label="Close">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" aria-hidden="true">
                    <line x1="18" y1="6" x2="6" y2="18" />
                    <line x1="6" y1="6" x2="18" y2="18" />
                </svg>
            </button>
            <h1 class="sinta21">Send a Message<br><span>Ask the owner something directly</span></h1>
            <div class="msgfield">
                <textarea class="missagekayadmin" id="adminMsg" maxlength="1000" rows="4"
                    placeholder="Type your message to the owner..."></textarea>
                <span class="msgcount" id="msgCount">0 / 1000</span>
            </div>
            <input type="button" class="sending" id="sendMsgBtn" value="Send Message"
                data-url="{{ route('tenant.messages.send') }}">
            <p class="msg-status" id="msgStatus" hidden></p>
        </div>

        <div class="adminbox" id="complaintModal" hidden>
            <button type="button" class="admin-close" data-close-admin aria-label="Close">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" aria-hidden="true">
                    <line x1="18" y1="6" x2="6" y2="18" />
                    <line x1="6" y1="6" x2="18" y2="18" />
                </svg>
            </button>
            <h1 class="sinta21">File a Complaint<br><span>Report an issue or concerns</span></h1>

            <select name="type" id="complaintType" required class="silicta">
                <option value="" disabled selected>Select complaint type</option>
                <option value="plumbing">Plumbing</option>
                <option value="electrical">Electrical</option>
                <option value="room_furniture">Room and furniture</option>
                <option value="cleanliness">Cleanliness</option>
                <option value="pests">Pests</option>
                <option value="noise">Noise and disturbance</option>
                <option value="roommate">Roommate concern</option>
                <option value="security">Security and safety</option>
                <option value="internet_utilities">Internet and utilities</option>
                <option value="billing">Billing and payments</option>
                <option value="staff">Staff or management</option>
                <option value="other">Others</option>
            </select>

            <div class="msgfield">
                <textarea class="missagekayadmin" id="complaintMsg" maxlength="1000" rows="4"
                    placeholder="Describe the problem: what happened, where, and when..."></textarea>
                <span class="msgcount" id="complaintCount">0 / 1000</span>
            </div>

            <input type="button" class="sending" id="sendComplaintBtn" value="Send Complaint"
                data-url="{{ route('tenant.complaints.store') }}">
            <p class="msg-status" id="complaintStatus" hidden></p>
        </div>

        <div class="adminbox" id="suggestionModal" hidden>
            <button type="button" class="admin-close" data-close-admin aria-label="Close">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" aria-hidden="true">
                    <line x1="18" y1="6" x2="6" y2="18" />
                    <line x1="6" y1="6" x2="18" y2="18" />
                </svg>
            </button>
            <h1 class="sinta21">Suggestion<br><span>Tell us your suggestion</span></h1>

            <div class="msgfield">
                <textarea class="missagekayadmin" id="suggestionMsg" maxlength="1000" rows="4"
                    placeholder="Type your suggestion to the owner..."></textarea>
                <span class="msgcount" id="suggestionCount">0 / 1000</span>
            </div>

            <input type="button" class="sending" id="sendSuggestionBtn" value="Send Suggestion"
                data-url="{{ route('tenant.suggestions.store') }}">
            <p class="msg-status" id="suggestionStatus" hidden></p>
        </div>

        <div class="adminbox" id="defaultView">
            <button type="button" class="admin-close" data-close-admin-all aria-label="Close">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" aria-hidden="true">
                    <line x1="18" y1="6" x2="6" y2="18" />
                    <line x1="6" y1="6" x2="18" y2="18" />
                </svg>
            </button>

            <h1 class="mainhouse12">Main House<br><span>This is the owner's house. What would you like to do?</span></h1>
            <div class="request1">
                <div class="requestbox" data-request="message">
                    <div class="elogg">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                            stroke-linejoin="round" aria-hidden="true">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
                        </svg>
                    </div>
                    <h1 class="sinta">Send a Message<br><span>Ask the owner something directly</span></h1>
                </div>
                <div class="requestbox" data-request="complaint">
                    <div class="elogg">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                            stroke-linejoin="round" aria-hidden="true">
                            <circle cx="12" cy="12" r="10" />
                            <line x1="12" y1="8" x2="12" y2="12" />
                            <line x1="12" y1="16" x2="12.01" y2="16" />
                        </svg>
                    </div>
                    <h1 class="sinta">File a Complaint<br><span>Report an issue or concerns</span></h1>
                </div>
                <div class="requestbox" data-request="suggestion">
                    <div class="elogg">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                            stroke-linejoin="round" aria-hidden="true">
                            <path d="M9 18h6" />
                            <path d="M10 22h4" />
                            <path d="M12 2a7 7 0 0 0-4 12.7c.6.5 1 1.2 1 2V17h6v-.3c0-.8.4-1.5 1-2A7 7 0 0 0 12 2z" />
                        </svg>
                    </div>
                    <h1 class="sinta">Suggestion<br><span>Tell us your suggestion</span></h1>
                </div>
            </div>
        </div>
    </div>
    <div class="roommodals" id="paymentModal" role="dialog" aria-modal="true" aria-hidden="true">
        <div class="wala1">
            <button type="button" class="goback" data-close-payment style="background-color: transparent">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                    aria-hidden="true">
                    <line x1="19" y1="12" x2="5" y2="12" />
                    <polyline points="12 19 5 12 12 5" />
                </svg>
                <span>Back to Room <span data-room-number></span></span>
            </button>
            <div class="adjustagain">
                <div class="progress" id="bookingProgress">
                    <div class="pstep" data-step="1">
                        <div class="progressbilog">
                            <h1 class="nampad">1</h1>
                            <svg class="pcheck" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"
                                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <polyline points="20 6 9 17 4 12" />
                            </svg>
                        </div>
                        <h1 class="progressh1">Room</h1>
                    </div>
                    <hr class="padiretso">

                    <div class="pstep" data-step="2">
                        <div class="progressbilog">
                            <h1 class="nampad">2</h1>
                            <svg class="pcheck" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"
                                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <polyline points="20 6 9 17 4 12" />
                            </svg>
                        </div>
                        <h1 class="progressh1">Your details</h1>
                    </div>
                    <hr class="padiretso">

                    <div class="pstep" data-step="3">
                        <div class="progressbilog">
                            <h1 class="nampad">3</h1>
                            <svg class="pcheck" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"
                                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <polyline points="20 6 9 17 4 12" />
                            </svg>
                        </div>
                        <h1 class="progressh1">Payment</h1>
                    </div>
                    <hr class="padiretso">

                    <div class="pstep" data-step="4">
                        <div class="progressbilog">
                            <h1 class="nampad">4</h1>
                            <svg class="pcheck" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"
                                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <polyline points="20 6 9 17 4 12" />
                            </svg>
                        </div>
                        <h1 class="progressh1">Confirm</h1>
                    </div>
                </div>
            </div>

            <div class="liftfat">

                <h1 class="tintinant">New tenant registration </h1>
                <h1 class="tellme">Tell us about yourself<span>We just need a few details to set up your tenancy.</span>
                </h1>
                <div class="roomnabox">
                    <div class="higaan">
                        <div class="bedicon">
                            <svg viewBox="0 0 24 24">
                                <path d="M3 5v14" />
                                <circle cx="7.5" cy="10.5" r="2" />
                                <path d="M11.5 10h7.5a2 2 0 0 1 2 2v7" />
                                <path d="M3 16h18" />
                            </svg>
                        </div>
                    </div>
                    <div class="patongtatlo">
                        <h1 data-pay="room">Room 100</h1>
                        <span data-pay="bed">Twin Sharing · Bed 3, Upper</span>
                        <div class="medyohaba">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                                aria-hidden="true">
                                <polyline points="20 6 9 17 4 12" />
                            </svg>
                            <h1 data-pay="timer">Bed reserved for 15:00</h1>
                        </div>
                    </div>
                    <h1 class="presyo"><b data-pay="amount" style="font:inherit">₱2,000</b> <span data-pay="per">/
                            month</span>
                    </h1>
                </div>
                <div class="mgainfos">
                    <div class="mgainfo">
                        <div class="mgainfo1"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
                                <circle cx="12" cy="7" r="4" />
                            </svg></div>
                        <h1 class="mgainfo2">Personal & contact info<span>Within 24 hours of booking
                            </span></h1>
                    </div>
                    <div class="mgainfo">
                        <div class="mgainfo1"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                <circle cx="9" cy="7" r="4" />
                                <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
                                <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                            </svg></div>
                        <h1 class="mgainfo2">Emergency contact<span>not-required by dormitory policy
                            </span></h1>
                    </div>
                    <div class="mgainfo">
                        <div class="mgainfo1"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <rect x="8" y="2" width="8" height="4" rx="1" ry="1" />
                                <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2" />
                            </svg></div>
                        <h1 class="mgainfo2">Valid ID upload<span>Any government-issued ID
                            </span></h1>
                    </div>
                </div>
            </div>

            <div class="sababasawala">
                <hr class="padiretso42">
                <h1 class="needmee">Need help with this form?<span>Message Admin</span></h1>
            </div>

        </div>
        <form id="detailsForm">
            <div class="tuo1">
                <h1>Tenant Information<span>Fields marked with an asterisk (*) are not-required.
                    </span></h1>
                <div class="personalkoto">
                    <div class="personalbabaw">
                        <div class="lagayanicon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
                                <circle cx="12" cy="7" r="4" />
                            </svg></div>
                        <h1>Personal Information<span>Your legal name and basic details</span></h1>


                    </div>
                    <hr class="padiretso123">
                    <div class="kabuuan">
                        <div class="firstnamelast">
                            <div class="lastfirst">
                                <span>First name *</span>
                                <input type="text" name="first_name" class="firtsnime" required>
                            </div>
                            <div class="lastfirst">
                                <span>Last name *</span>
                                <input type="text" name="last_name" class="firtsnime" required>
                            </div>
                        </div>
                        <div class="firstnamelast">
                            <div class="lastfirst">
                                <span>Date of birth *</span>
                                <input type="date" name="date_of_birth" class="firtsnimedate" required>
                            </div>
                            <div class="lastfirst">
                                <span>Sex *</span>
                                <select name="sex" class="firtsnimesex" not-required>
                                    <option value="">Select...</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                            </div>
                            <div class="lastfirst">
                                <span>Civil status *</span>
                                <select name="civil_status" class="firtsnimestatus" not-required>
                                    <option value="">Select...</option>
                                    <option value="Single">Single</option>
                                    <option value="Married">Married</option>
                                </select>
                            </div>
                        </div>
                        <div class="firstnamelast">
                            <div class="lastfirst">
                                <span>Occupation *</span>
                                <input type="radio" class="pilera" name="occupation" id="occ-student" value="Student"
                                    required>
                                <label for="occ-student" class="firtsnimepile">Student</label>
                            </div>

                            <div class="lastfirst">
                                <span style="opacity:0%;">Occupation *</span>
                                <input type="radio" class="pilera" name="occupation" id="occ-employed" value="Employed">
                                <label for="occ-employed" class="firtsnimepile">Employed</label>
                            </div>

                            <div class="lastfirst">
                                <span style="opacity:0%;">Occupation *</span>
                                <input type="radio" class="pilera" name="occupation" id="occ-other" value="Other">
                                <label for="occ-other" class="firtsnimepile">Other</label>
                            </div>
                        </div>
                        <div class="firstnamelast">
                            <div class="lastfirst">
                                <span>School / employer name *</span>
                                <input type="text" class="firtsnime" required placeholder="e.g. Nazaret"
                                    name="school_employer">
                            </div>
                        </div>
                    </div>


                </div>
                <div class="laglagyan">
                    <div class="personalkoto2">
                        <div class="personalbabaw">
                            <div class="lagayanicon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    aria-hidden="true">
                                    <path
                                        d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
                                </svg></div>
                            <h1>Contact details<span>How we'll reach you</span></h1>


                        </div>
                        <hr class="padiretso123">
                        <div class="kabuuan">
                            <div class="firstnamelast">
                                <div class="lastfirst">
                                    <span>Mobile number *</span>
                                    <input type="tel" name="mobile" class="firtsnime" required
                                        placeholder="e.g. +63 912 345 6789" pattern="(\+63|0)\s?9\d{2}\s?\d{3}\s?\d{4}"
                                        title="Use a PH number like +63 912 345 6789 or 0912 345 6789">
                                </div>
                            </div>
                            <div class="firstnamelast">
                                <div class="lastfirst">
                                    <span>Email address *</span>
                                    <input type="email" name="email" class="firtsnime" required
                                        placeholder="e.g. you@gmail.com">
                                </div>
                            </div>
                            <div class="firstnamelast">
                                <div class="lastfirst">
                                    <span>Home address *</span>
                                    <input type="text" class="firtsnime" required
                                        placeholder="Street, Barangay, City, Province" name="home_address">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="personalkoto2">
                        <div class="personalbabaw">
                            <div class="lagayanicon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    aria-hidden="true">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                    <circle cx="9" cy="7" r="4" />
                                    <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                </svg></div>
                            <h1>Emergency contact<span>In case we need to reach someone</span></h1>


                        </div>
                        <hr class="padiretso123">
                        <div class="kabuuan">
                            <div class="firstnamelast">
                                <div class="lastfirst">
                                    <span>Full name *</span>
                                    <input type="text" class="firtsnime" required name="ec_full_name">
                                </div>
                            </div>
                            <div class="firstnamelast">
                                <div class="lastfirst">
                                    <span>Relationship *</span>
                                    <input type="text" class="firtsnime" required placeholder="e.g. Mother"
                                        name="ec_relationship">
                                </div>
                            </div>
                            <div class="firstnamelast">
                                <div class="lastfirst">
                                    <span>Contact number *</span>
                                    <input type="tel" name="ec_contact_number" class="firtsnime" required
                                        placeholder="e.g. +63 912 345 6789" pattern="(\+63|0)\s?9\d{2}\s?\d{3}\s?\d{4}"
                                        title="Use a PH number like +63 912 345 6789 or 0912 345 6789">
                                </div>
                            </div>
                            <div class="firstnamelast">
                                <div class="lastfirst">
                                    <span>Home address *</span>
                                    <input type="text" name="ec_address" class="firtsnime" required
                                        placeholder="Street, Barangay, City, Province" maxlength="500">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="laglagyan">
                    <div class="personalkoto2">
                        <div class="personalbabaw">
                            <div class="lagayanicon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    aria-hidden="true">
                                    <rect x="2" y="4" width="20" height="16" rx="2" />
                                    <circle cx="9" cy="11" r="2.5" />
                                    <path d="M5.5 17a3.5 3.5 0 0 1 7 0" />
                                    <path d="M15 9.5h4" />
                                    <path d="M15 13.5h4" />
                                </svg></div>
                            <h1>Valid ID<span>One goverment-issued ID</span></h1>


                        </div>
                        <hr class="padiretso123">
                        <div class="kabuuan">
                            <div class="lastfirst">
                                <span>ID type *</span>
                                <select name="id_type" class="firtsnimesex" required>
                                    <option value="Philipine National ID">Philipine National ID</option>
                                    <option value="Drivers License">Drivers License</option>
                                </select>
                            </div>
                            <div class="firstnamelast">
                                <div class="lastfirst">
                                    <span>ID number *</span>
                                    <input type="text" class="firtsnime" required placeholder="e.g. 1234-5678-9012"
                                        name="id_number">
                                </div>
                            </div>
                            <div class="firstnamelast">
                                <div class="lastfirst">
                                    <label class="upload" data-upload>
                                        <input type="file" class="upload-input" name="id_photo" title=""
                                            accept="image/png, image/jpeg" required>

                                        <div class="upload-empty">
                                            <svg viewBox="0 0 24 24">
                                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                                <polyline points="17 8 12 3 7 8" />
                                                <line x1="12" y1="3" x2="12" y2="15" />
                                            </svg>
                                            <p class="upload-title">Click to upload</p>
                                            <p class="upload-hint">JPG or PNG, up to 5MB</p>
                                        </div>

                                        <div class="upload-file" hidden>
                                            <img class="upload-thumb" alt="">
                                            <div class="upload-info">
                                                <p class="upload-name"></p>
                                                <p class="upload-size"></p>
                                            </div>
                                            <button type="button" class="upload-remove" aria-label="Remove file">
                                                <svg viewBox="0 0 24 24">
                                                    <line x1="18" y1="6" x2="6" y2="18" />
                                                    <line x1="6" y1="6" x2="18" y2="18" />
                                                </svg>
                                            </button>
                                        </div>
                                    </label>
                                    <p class="upload-error" hidden></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="personalkoto2">
                        <div class="personalbabaw">
                            <div class="lagayanicon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    aria-hidden="true">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                                    <path d="M16 2v4" />
                                    <path d="M8 2v4" />
                                    <path d="M3 10h18" />
                                </svg></div>
                            <h1>Move-in details<span>When you're moving in</span></h1>


                        </div>
                        <hr class="padiretso123">
                        <div class="kabuuan">
                            <div class="firstnamelast">
                                <div class="lastfirst">
                                    <span>Preferred move-in date *</span>
                                    <input type="date" class="firtsnimedate" required name="move_in_date">
                                </div>
                            </div>
                            <div class="firstnamelast">
                                <div class="lastfirst">
                                    <span>Expected length of stay *</span>
                                    <select name="length_of_stay" class="firtsnimesex" required>
                                        <option value="">Select...</option>
                                        <option value="1 month">1 month</option>
                                        <option value="3 months">3 months</option>
                                        <option value="6 months">6 months</option>
                                        <option value="1 year">1 year</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="agreekaba">
                    <input type="checkbox" name="agree" id="agree" class="radi" required>
                    <h1>I certify that the information provided is true and correct, and I agree to the dormitory's house
                        rules and terms of stay</h1>
                </div>
                <button type="submit" class="continuetopays">Continue to payment</button>
                <div class="yours12">
                    <h1>Your bed stays reserved while you complete this form.</h1>
                    <div class="linyavable2"></div>
                </div>
            </div>
        </form>

    </div>
    </div>
    <div class="roommodals" id="paymentStepModal" role="dialog" aria-modal="true" aria-hidden="true">
        <div class="wala1">
            <button type="button" class="goback" data-back-to-details style="background-color: transparent">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                    aria-hidden="true">
                    <line x1="19" y1="12" x2="5" y2="12" />
                    <polyline points="12 19 5 12 12 5" />
                </svg>
                <span>Back to details</span>
            </button>
            <div class="progress progress--payment" id="bookingProgress">
                <div class="pstep" data-step="1">
                    <div class="progressbilog">
                        <h1 class="nampad">1</h1>
                        <svg class="pcheck" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"
                            stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <polyline points="20 6 9 17 4 12" />
                        </svg>
                    </div>
                    <h1 class="progressh1">Room</h1>
                </div>
                <hr class="padiretso">
                <div class="pstep" data-step="2">
                    <div class="progressbilog">
                        <h1 class="nampad">2</h1>
                        <svg class="pcheck" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"
                            stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <polyline points="20 6 9 17 4 12" />
                        </svg>
                    </div>
                    <h1 class="progressh1">Your details</h1>
                </div>
                <hr class="padiretso">
                <div class="pstep" data-step="3">
                    <div class="progressbilog">
                        <h1 class="nampad">3</h1>
                        <svg class="pcheck" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"
                            stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <polyline points="20 6 9 17 4 12" />
                        </svg>
                    </div>
                    <h1 class="progressh1">Payment</h1>
                </div>
                <hr class="padiretso">
                <div class="pstep" data-step="4">
                    <div class="progressbilog">
                        <h1 class="nampad">4</h1>
                        <svg class="pcheck" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"
                            stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <polyline points="20 6 9 17 4 12" />
                        </svg>
                    </div>
                    <h1 class="progressh1">Confirm</h1>
                </div>
            </div>
            <div class="liftfat">
                <h1 class="tintinant">Booking Summary </h1>
                <h1 class="tellme">Complete your booking<span>reserve your bed at D'aces Dormitory.</span></h1>
                <div class="roomnabox">
                    <div class="higaan">
                        <div class="bedicon">
                            <svg viewBox="0 0 24 24">
                                <path d="M3 5v14" />
                                <circle cx="7.5" cy="10.5" r="2" />
                                <path d="M11.5 10h7.5a2 2 0 0 1 2 2v7" />
                                <path d="M3 16h18" />
                            </svg>
                        </div>
                    </div>
                    <div class="patongtatlo">
                        <h1 data-pay="room">Room 100</h1>
                        <span data-pay="bed">Twin Sharing · Bed 3, Upper</span>
                        <div class="medyohaba">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                                aria-hidden="true">
                                <polyline points="20 6 9 17 4 12" />
                            </svg>
                            <h1 data-pay="timer">Bed reserved for 15:00</h1>
                        </div>
                    </div>
                    <h1 class="presyo"><b data-pay="amount" style="font:inherit">₱2,000</b> <span data-pay="per">/
                            month</span>
                    </h1>
                </div>
                <div class="bayarinmo">
                    <div class="feetas">
                        <div class="feebox"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <polygon points="12 2 2 7 12 12 22 7 12 2" />
                                <polyline points="2 12 12 17 22 12" />
                            </svg></div>
                        <h1>Reservation Fee</h1>
                        <h1 class="pera23" data-fee="{{ $fees['reservation'] }}">
                            ₱{{ number_format($fees['reservation'], 2) }}</h1>
                    </div>
                    <hr class="padiretso1234">
                    <div class="feetas">
                        <div class="feebox"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M8 2v4" />
                                <path d="M16 2v4" />
                                <rect x="3" y="4" width="18" height="18" rx="2" />
                                <path d="M3 10h18" />
                            </svg></div>
                        <h1>Security Fee</h1>
                        <h1 class="pera23" data-fee="{{ $fees['security'] }}">₱{{ number_format($fees['security'], 2) }}
                        </h1>
                    </div>
                    <hr class="padiretso1234">
                    <div class="feetas">
                        <div class="feebox"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="m16 22-1-4" />
                                <path
                                    d="M19 13.99a1 1 0 0 0 1-1V12a2 2 0 0 0-2-2h-3a1 1 0 0 1-1-1V4a2 2 0 0 0-4 0v5a1 1 0 0 1-1 1H6a2 2 0 0 0-2 2v.99a1 1 0 0 0 1 1" />
                                <path d="M5 14h14l1.973 6.767A1 1 0 0 1 20 22H4a1 1 0 0 1-.973-1.233z" />
                                <path d="m8 22 1-4" />
                            </svg></div>
                        <h1>Clean fee</h1>
                        <h1 class="pera23" data-fee="{{ $fees['clean'] }}">₱{{ number_format($fees['clean'], 2) }}</h1>
                    </div>
                    <hr class="padiretso1234">
                    <div class="feetas">
                        <h1 class="tutalinss">Total due today</h1>
                        <h1 class="pera233" data-pay="total">₱{{ number_format(array_sum($fees), 2) }}</h1>
                    </div>
                    <div class="sababasawala">
                        <hr class="padiretso42">
                        <h1 class="needmee">Need help with this form?<span>Message Admin</span></h1>
                    </div>
                </div>
            </div>

        </div>

        <div class="tuo1">
            <h1>Payment Method<span>Choose how you'd like to pay today.
                </span></h1>
            <div class="personalkoto64">
                <div class="personalbabaw">
                    <div class="lagayanicon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            aria-hidden="true">
                            <rect x="2" y="5" width="20" height="14" rx="2" />
                            <line x1="2" y1="10" x2="22" y2="10" />
                            <line x1="6" y1="15" x2="10" y2="15" />
                        </svg></div>
                    <h1>Choose a payment option<span>Select how you'd like to complete this booking</span></h1>


                </div>
                <hr class="padiretso1236">
                <div class="pangbayad">
                    <div class="pipilian" data-method="gcash"
                        style="border: 1px solid #672C2C; background-color: #672c2c21;"><svg
                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <rect x="6" y="2" width="12" height="20" rx="2.5" />
                            <line x1="11" y1="18.5" x2="13" y2="18.5" />
                        </svg>
                        <h1>Gcash</h1>
                    </div>
                    <div class="pipilian" data-method="card"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" aria-hidden="true">
                            <rect x="2" y="5" width="20" height="14" rx="2" />
                            <line x1="2" y1="10" x2="22" y2="10" />
                            <line x1="6" y1="15" x2="10" y2="15" />
                        </svg>
                        <h1>Debit / Credit</h1>
                    </div>
                    <div class="pipilian" data-method="bank">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M3 10 12 4l9 6" />
                            <line x1="4" y1="20" x2="20" y2="20" />
                            <line x1="6" y1="10" x2="6" y2="17" />
                            <line x1="10" y1="10" x2="10" y2="17" />
                            <line x1="14" y1="10" x2="14" y2="17" />
                            <line x1="18" y1="10" x2="18" y2="17" />
                        </svg>
                        <h1>Bank Transfer</h1>
                    </div>

                </div>
                <div class="lipatkolang">
                    <div class="namecashg">
                        <h1>GCash mobile number</h1>
                        <div class="gcash-field">
                            <svg class="gcash-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                                aria-hidden="true">
                                <rect x="6" y="2" width="12" height="20" rx="2.5" />
                                <line x1="11" y1="18.5" x2="13" y2="18.5" />
                            </svg>
                            <input type="text" class="gcashku" placeholder="09XX XXX XXXX">
                        </div>
                    </div>
                    <div class="namecashg4">
                        <h1>Account name</h1>
                        <input type="text" class="gcashku" placeholder="Admin*p*r">
                    </div>
                </div>

            </div>
            <div class="agreekaba3">
                <input type="checkbox" name="agree" id="agree" class="radi" required>
                <h1>Save this payment method for future rent payments</h1>
            </div>
            <div class="agreekaba3">
                <input type="checkbox" name="agree" id="agree" class="radi" required>
                <h1>I agree to the dormitory's term of stay and confirm the reservation fee is non-refundable.</h1>
            </div>
            <button class="lahatbayad">Pay ₱2,120.00 & confirm booking</button>
            <div class="yours12">
                <h1>Your bed stays reserved while you complete this form.</h1>
                <div class="linyavable"></div>
            </div>

        </div>
    </div>
    <div class="unavail-modal" id="unavailModal" role="dialog" aria-modal="true" aria-hidden="true">
        <div class="unavail-box">
            <h2 class="unavail-title">Not available</h2>
            <p class="unavail-text"><b id="unavailMethod"></b> is not available at the moment. Please use GCash instead.</p>
            <button type="button" class="unavail-btn" id="unavailClose">Okay</button>
        </div>
    </div>
    <div class="unavail-modal" id="hasRoomModal" role="dialog" aria-modal="true" aria-hidden="true">
        <div class="unavail-box">
            <h2 class="unavail-title">You already have a room</h2>
            <p class="unavail-text">You're already booked in <b id="hasRoomLabel"></b>. Contact the dormitory admin if you
                need to transfer.</p>
            <button type="button" class="unavail-btn" id="hasRoomClose">Okay</button>
        </div>
    </div>
    <div class="roommodals" id="confirmModal" role="dialog" aria-modal="true" aria-hidden="true">
        <div class="wala1">
            <button type="button" class="goback" data-back-to-payment style="background-color: transparent">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                    aria-hidden="true">
                    <line x1="19" y1="12" x2="5" y2="12" />
                    <polyline points="12 19 5 12 12 5" />
                </svg>
                <span>Back to payment</span>
            </button>
            <div class="adjust">
                <div class="progress progress--payment">
                    <div class="pstep" data-step="1">
                        <div class="progressbilog">
                            <h1 class="nampad">1</h1>
                            <svg class="pcheck" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"
                                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <polyline points="20 6 9 17 4 12" />
                            </svg>
                        </div>
                        <h1 class="progressh1">Room</h1>
                    </div>
                    <hr class="padiretso">
                    <div class="pstep" data-step="2">
                        <div class="progressbilog">
                            <h1 class="nampad">2</h1>
                            <svg class="pcheck" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"
                                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <polyline points="20 6 9 17 4 12" />
                            </svg>
                        </div>
                        <h1 class="progressh1">Your details</h1>
                    </div>
                    <hr class="padiretso">
                    <div class="pstep" data-step="3">
                        <div class="progressbilog">
                            <h1 class="nampad">3</h1>
                            <svg class="pcheck" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"
                                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <polyline points="20 6 9 17 4 12" />
                            </svg>
                        </div>
                        <h1 class="progressh1">Payment</h1>
                    </div>
                    <hr class="padiretso">
                    <div class="pstep" data-step="4">
                        <div class="progressbilog">
                            <h1 class="nampad">4</h1>
                            <svg class="pcheck" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"
                                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <polyline points="20 6 9 17 4 12" />
                            </svg>
                        </div>
                        <h1 class="progressh1">Confirm</h1>
                    </div>

                </div>
            </div>

            <div class="liftfat">
                <h1 class="tintinant">Booking Complete </h1>
                <h1 class="tellme">Welcome to D'aces Dormitory <span>Your bed is officially reserved. See you on move-in
                        day!</span></h1>
                <div class="roomnabox">
                    <div class="higaan">
                        <div class="bedicon">
                            <svg viewBox="0 0 24 24">
                                <path d="M3 5v14" />
                                <circle cx="7.5" cy="10.5" r="2" />
                                <path d="M11.5 10h7.5a2 2 0 0 1 2 2v7" />
                                <path d="M3 16h18" />
                            </svg>
                        </div>
                    </div>
                    <div class="patongtatlo">
                        <h1 data-pay="room">Room 100</h1>
                        <span data-pay="bed">Twin Sharing · Bed 3, Upper</span>
                        <div class="medyohaba" style="background-color: #1e471d">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                                aria-hidden="true">
                                <polyline points="20 6 9 17 4 12" />
                            </svg>
                            <h1>Bed confirmed</h1>
                        </div>
                    </div>
                    <h1 class="presyo"><b data-pay="amount" style="font:inherit">₱2,000</b> <span data-pay="per">/
                            month</span>
                    </h1>
                </div>
                <div class="mgainfos2">
                    <div class="mgainfo">
                        <div class="mgainfo1">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path
                                    d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
                            </svg>
                        </div>
                        <h1 class="mgainfo2">Admin will call to verify<span>Within 24 hours of booking</span></h1>
                    </div>
                    <div class="mgainfo">
                        <div class="mgainfo1">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <rect x="2" y="4" width="20" height="16" rx="2" />
                                <path d="m22 7-10 6L2 7" />
                            </svg>
                        </div>
                        <h1 class="mgainfo2">Receipt sent to your email<span>Keep it for records</span></h1>
                    </div>
                    <div class="mgainfo">
                        <div class="mgainfo1">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <rect x="3" y="4" width="18" height="18" rx="2" />
                                <line x1="16" y1="2" x2="16" y2="6" />
                                <line x1="8" y1="2" x2="8" y2="6" />
                                <line x1="3" y1="10" x2="21" y2="10" />
                            </svg>
                        </div>
                        <h1 class="mgainfo2">Move in your chosen date<span>Bring a valid ID and this receipt</span></h1>
                    </div>
                </div>
            </div>

            <div class="sababasawala">
                <hr class="padiretso42">
                <h1 class="needmee">Need help with this form?<span>Message Admin</span></h1>
            </div>
        </div>

        <div class="tuo1">
            <div class="bagongtuo">
                <div class="chik"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        aria-hidden="true">
                        <polyline points="20 6 9 17 4 12" />
                    </svg></div>
                <h1>Booking Confirm!</h1>
                <span data-conf="headline">Room 100 is now reserved under your name. A copy of this receipt has been sent to
                    your email.</span>
                <div class="dateofdating">

                    <span><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <rect x="3" y="4" width="18" height="18" rx="2" />
                            <line x1="16" y1="2" x2="16" y2="6" />
                            <line x1="8" y1="2" x2="8" y2="6" />
                            <line x1="3" y1="10" x2="21" y2="10" />
                        </svg>&nbsp; Confirmed on <span data-conf="confirmed-at"
                            style="font-weight:700; color: #000000;">Aug 2, 2026, 2:41 PM</span></span>
                </div>
                <div class="hatiinnatinmain">
                    <div class="hatiinnatin">
                        <div class="kakanan">
                            <div class="receipt-title">
                                <h1>Payment receipt</h1>
                                <span data-conf="ref">Booking ref. #DAC-2026-0842</span>

                            </div>
                            <div class="lagaw">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"
                                    aria-hidden="true">
                                    <polyline points="20 6 9 17 4 12" />
                                </svg>
                                <h1 class="pidsa">Paid</h1>
                            </div>

                        </div>
                        <div class="kakanan1">
                            <h1>Tenant name</h1>
                            <span data-conf="tenant">Juelle Villarosa</span>
                        </div>
                        <hr class="padiretso54">
                        <div class="kakanan1">
                            <h1>Room</h1>
                            <span data-conf="room-bed">Room 100 · Bed 3, Upper</span>
                        </div>
                        <hr class="padiretso54">
                        <div class="kakanan1">
                            <h1>Payment method</h1>
                            <span data-conf="method">GCash · •••• 4821</span>
                        </div>
                        <hr class="padiretso54">
                        <div class="kakanan1">
                            <h1>Move-in date</h1>
                            <span data-conf="move-in">July 18, 2026</span>
                        </div>
                        <hr class="padiretso545">
                        <div class="redbg">
                            <div class="kakanan12">
                                <h1>Total paid</h1>
                                <span data-conf="total">₱2,000</span>
                            </div>
                        </div>
                        <div class="qrr">
                            <img class="qer" src="{{ asset('images/qr.png') }}" alt="QR code">
                            <h1>Scan at the front desk on move-in day for <span>instant check-in verification.</span></h1>
                        </div>


                    </div>
                    <div class="hatiinnatin2">
                        <h1><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <circle cx="12" cy="12" r="10" />
                                <polyline points="12 6 12 12 16 14" />
                            </svg>What happens next</h1>
                        <div class="pabelow">
                            <div class="stepby">
                                <div class="stepby1">
                                    <h1>1</h1>
                                </div>
                                <span>Our admin team will verify your details and call or message you within 24
                                    hours.</span>
                            </div>
                            <div class="stepby">
                                <div class="stepby1">
                                    <h1>2</h1>
                                </div>
                                <span>Bring a valid ID and this receipt on your move-in date.</span>
                            </div>
                            <div class="stepby">
                                <div class="stepby1">
                                    <h1>3</h1>
                                </div>
                                <span>Your next rent payment will be due 30 days after move-in.</span>
                            </div>
                        </div>

                    </div>

                </div>
                <div class="akingbutton">
                    <button class="akingbutton1"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            aria-hidden="true">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                            <polyline points="7 10 12 15 17 10" />
                            <line x1="12" y1="15" x2="12" y2="3" />
                        </svg>Download receipt</button>
                    <button class="akingbutton2" onclick="window.location='{{ route('tenant.home') }}'">Go to my
                        dashboard<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            aria-hidden="true">
                            <line x1="5" y1="12" x2="19" y2="12" />
                            <polyline points="12 5 19 12 12 19" />
                        </svg></button>
                </div>
                <div class="addmove4">
                    <div class="addmove">
                        <h1><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <rect x="3" y="4" width="18" height="18" rx="2" />
                                <line x1="16" y1="2" x2="16" y2="6" />
                                <line x1="8" y1="2" x2="8" y2="6" />
                                <line x1="3" y1="10" x2="21" y2="10" />
                            </svg>Add to move-in to calendar</h1>
                    </div>
                    <div class="addmove">
                        <h1><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <circle cx="18" cy="5" r="3" />
                                <circle cx="6" cy="12" r="3" />
                                <circle cx="18" cy="19" r="3" />
                                <line x1="8.59" y1="13.51" x2="15.42" y2="17.49" />
                                <line x1="15.41" y1="6.51" x2="8.59" y2="10.49" />
                            </svg>Share booking</h1>
                    </div>
                </div>
                <div class="needtooo">
                    <h1 class="needtao">Need to make changes? <span>Contact dormitory admin</span></h1>
                </div>


            </div>
        </div>
    </div>
    </div>
@endsection

@push('scripts')

    <script>document.querySelectorAll('[data-upload]').forEach(box => {
            const input = box.querySelector('.upload-input');
            const empty = box.querySelector('.upload-empty');
            const filebx = box.querySelector('.upload-file');
            const thumb = box.querySelector('.upload-thumb');
            const nameEl = box.querySelector('.upload-name');
            const sizeEl = box.querySelector('.upload-size');
            const error = box.parentElement.querySelector('.upload-error');

            const MAX = 5 * 1024 * 1024;
            const OK = ['image/jpeg', 'image/png'];
            let url = null;

            const fmt = b => b < 1048576 ? Math.round(b / 1024) + ' KB' : (b / 1048576).toFixed(1) + ' MB';

            function showError(msg) {
                error.textContent = msg;
                error.hidden = !msg;
                box.classList.toggle('is-error', !!msg);
            }

            function reset() {
                if (url) URL.revokeObjectURL(url);
                url = null;
                input.value = '';
                thumb.removeAttribute('src');
                filebx.hidden = true;
                empty.hidden = false;
                box.classList.remove('has-file');
            }

            input.addEventListener('change', () => {
                const f = input.files[0];
                if (!f) { reset(); return; }

                if (!OK.includes(f.type)) { reset(); showError('Only JPG or PNG files are allowed.'); return; }
                if (f.size > MAX) { reset(); showError('That file is too big. The limit is 5MB.'); return; }

                showError('');
                if (url) URL.revokeObjectURL(url);
                url = URL.createObjectURL(f);
                thumb.src = url;
                nameEl.textContent = f.name;
                sizeEl.textContent = fmt(f.size);
                empty.hidden = true;
                filebx.hidden = false;
                box.classList.add('has-file');
            });

            box.querySelector('.upload-remove').addEventListener('click', e => {
                e.preventDefault();
                reset();
                showError('');
            });

            ['dragenter', 'dragover'].forEach(ev => box.addEventListener(ev, () => box.classList.add('is-drag')));
            ['dragleave', 'drop'].forEach(ev => box.addEventListener(ev, () => box.classList.remove('is-drag')));
        });</script>
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
                fBed.value = '';
                fFree.value = '0';
                apply();
            });

            const q = new URLSearchParams(location.search).get('room');
            if (q) search.value = q;

            apply();
        })();
    </script>


    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('roomModal');
            if (!modal) return;

            const isOpen = () => modal.classList.contains('is-open');
            const csrf = '{{ csrf_token() }}';

            function fill(key, text) {
                modal.querySelectorAll('[data-fill="' + key + '"]').forEach(el => el.textContent = text);
            }


            const bilog = modal.querySelector('.bilog999');
            const placeholderSvg = bilog.querySelector('svg');
            const expandBtn = bilog.querySelector('[data-expand]');
            const thumbs = modal.querySelectorAll('.fourputi');
            const lightbox = document.getElementById('lightbox');
            const lightboxImg = document.getElementById('lightboxImg');
            let photos = [], current = 0, roomNo = '';

            const pic = document.createElement('img');
            pic.alt = '';
            bilog.insertBefore(pic, expandBtn);

            function showPhoto(i) {
                current = (i + thumbs.length) % thumbs.length;
                const src = photos[current];

                if (src) {
                    pic.src = src;
                    pic.hidden = false;
                    placeholderSvg.style.display = 'none';
                } else {
                    pic.hidden = true;
                    placeholderSvg.style.display = '';
                }
                expandBtn.hidden = !src;

                thumbs.forEach((t, n) => {
                    t.classList.toggle('active', n === current);
                    t.style.backgroundImage = photos[n] ? 'url(' + photos[n] + ')' : '';
                });

                fill('caption', 'Room ' + roomNo + ' • Photo ' + (current + 1) + ' of ' + thumbs.length);

                if (lightbox.classList.contains('is-open') && src) lightboxImg.src = src;
            }

            function openLightbox() {
                if (!photos[current]) return;
                lightboxImg.src = photos[current];
                lightbox.classList.add('is-open');
                lightbox.setAttribute('aria-hidden', 'false');
            }

            function closeLightbox() {
                lightbox.classList.remove('is-open');
                lightbox.setAttribute('aria-hidden', 'true');
            }

            expandBtn.addEventListener('click', openLightbox);
            lightbox.addEventListener('click', e => {
                if (e.target === lightbox || e.target.closest('[data-lightbox-close]')) closeLightbox();
            });

            thumbs.forEach((t, n) => t.addEventListener('click', () => showPhoto(n)));
            modal.querySelector('[data-prev]').addEventListener('click', () => showPhoto(current - 1));
            modal.querySelector('[data-next]').addEventListener('click', () => showPhoto(current + 1));

            const adminModal = document.getElementById('adminModal');
            const adminViews = adminModal.querySelectorAll('.adminbox');

            function showAdminView(id) {
                adminViews.forEach(v => v.hidden = (v.id !== id));
                setStatus('');
                setComplaintStatus('');
                setSuggestionStatus('');
                if (id === 'messageModal') setTimeout(() => msgInput.focus(), 50);
                if (id === 'complaintModal') setTimeout(() => complaintType.focus(), 50);
                if (id === 'suggestionModal') setTimeout(() => suggestionMsg.focus(), 50);
            }

            function closeAdmin() {
                adminModal.classList.remove('is-open');
                adminModal.setAttribute('aria-hidden', 'true');
                showAdminView('defaultView');
            }


            function goBackAdmin() {
                showAdminView('defaultView');
            }

            const adminOpen = () => adminModal.classList.contains('is-open');

            modal.querySelector('[data-open-admin]').addEventListener('click', () => {
                showAdminView('defaultView');
                adminModal.classList.add('is-open');
                adminModal.setAttribute('aria-hidden', 'false');
            });

            adminModal.querySelectorAll('.requestbox').forEach(box => {
                box.addEventListener('click', () => showAdminView(box.dataset.request + 'Modal'));
            });

            adminModal.addEventListener('click', e => {
                if (e.target === adminModal || e.target.closest('[data-close-admin-all]')) {
                    closeAdmin();
                } else if (e.target.closest('[data-close-admin]')) {
                    goBackAdmin();
                }
            });

            const msgInput = document.getElementById('adminMsg');
            const sendBtn = document.getElementById('sendMsgBtn');
            const msgStatus = document.getElementById('msgStatus');
            const msgCount = document.getElementById('msgCount');

            function updateCount() {
                const n = msgInput.value.length;
                msgCount.textContent = n + ' / 1000';
                msgCount.classList.toggle('near', n >= 900 && n < 1000);
                msgCount.classList.toggle('max', n >= 1000);
            }
            msgInput.addEventListener('input', updateCount);

            function setStatus(text, type) {
                msgStatus.hidden = !text;
                msgStatus.textContent = text || '';
                msgStatus.className = 'msg-status' + (type ? ' ' + type : '');
            }

            async function sendMessage() {
                const text = msgInput.value.trim();
                if (text.length < 2) {
                    setStatus('Please type your message first.', 'error');
                    msgInput.focus();
                    return;
                }

                sendBtn.disabled = true;
                sendBtn.value = 'Sending...';
                setStatus('');

                try {
                    const res = await fetch(sendBtn.dataset.url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrf
                        },
                        body: JSON.stringify({ message: text, room: modal.dataset.room || null })
                    });

                    if (res.status === 429) throw new Error('You are sending too fast. Please wait a moment.');
                    if (res.status === 419) throw new Error('Your session expired. Refresh the page and try again.');
                    if (!res.ok) throw new Error('Could not send your message. Please try again.');

                    msgInput.value = '';
                    updateCount();
                    setStatus('Message sent! The admin will get back to you.', 'ok');
                    setTimeout(() => { setStatus(''); closeAdmin(); }, 1500);
                } catch (err) {
                    setStatus(err instanceof TypeError
                        ? 'No connection. Check your internet and try again.'
                        : err.message, 'error');
                } finally {
                    sendBtn.disabled = false;
                    sendBtn.value = 'Send Message';
                }
            }

            sendBtn.addEventListener('click', sendMessage);
            msgInput.addEventListener('keydown', e => {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    sendMessage();
                }
            });


            const complaintType = document.getElementById('complaintType');
            const complaintMsg = document.getElementById('complaintMsg');
            const complaintCount = document.getElementById('complaintCount');
            const complaintBtn = document.getElementById('sendComplaintBtn');
            const complaintStatus = document.getElementById('complaintStatus');

            function setComplaintStatus(text, type) {
                complaintStatus.hidden = !text;
                complaintStatus.textContent = text || '';
                complaintStatus.className = 'msg-status' + (type ? ' ' + type : '');
            }

            function updateComplaintCount() {
                const n = complaintMsg.value.length;
                complaintCount.textContent = n + ' / 1000';
                complaintCount.classList.toggle('near', n >= 900 && n < 1000);
                complaintCount.classList.toggle('max', n >= 1000);
            }
            complaintMsg.addEventListener('input', updateComplaintCount);

            async function sendComplaint() {
                if (!complaintType.value) {
                    setComplaintStatus('Please select a complaint type.', 'error');
                    complaintType.focus();
                    return;
                }

                const text = complaintMsg.value.trim();
                if (text.length < 5) {
                    setComplaintStatus('Please describe your complaint.', 'error');
                    complaintMsg.focus();
                    return;
                }

                complaintBtn.disabled = true;
                complaintBtn.value = 'Sending...';
                setComplaintStatus('');

                try {
                    const res = await fetch(complaintBtn.dataset.url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrf
                        },
                        body: JSON.stringify({ type: complaintType.value, message: text })
                    });

                    if (res.status === 429) throw new Error('You are sending too fast. Please wait a moment.');
                    if (res.status === 419) throw new Error('Your session expired. Refresh the page and try again.');
                    if (res.status === 422) {
                        const data = await res.json().catch(() => ({}));
                        const first = data.errors ? Object.values(data.errors)[0][0] : null;
                        throw new Error(first || 'Please check your complaint and try again.');
                    }
                    if (!res.ok) throw new Error('Could not send your complaint. Please try again.');

                    complaintType.selectedIndex = 0;
                    complaintMsg.value = '';
                    updateComplaintCount();
                    setComplaintStatus('Complaint sent! We will get back to you soon.', 'ok');
                    setTimeout(() => { setComplaintStatus(''); closeAdmin(); }, 1500);
                } catch (err) {
                    setComplaintStatus(err instanceof TypeError
                        ? 'No connection. Check your internet and try again.'
                        : err.message, 'error');
                } finally {
                    complaintBtn.disabled = false;
                    complaintBtn.value = 'Send Complaint';
                }
            }

            complaintBtn.addEventListener('click', sendComplaint);


            const suggestionMsg = document.getElementById('suggestionMsg');
            const suggestionCount = document.getElementById('suggestionCount');
            const suggestionBtn = document.getElementById('sendSuggestionBtn');
            const suggestionStatus = document.getElementById('suggestionStatus');

            function setSuggestionStatus(text, type) {
                suggestionStatus.hidden = !text;
                suggestionStatus.textContent = text || '';
                suggestionStatus.className = 'msg-status' + (type ? ' ' + type : '');
            }

            function updateSuggestionCount() {
                const n = suggestionMsg.value.length;
                suggestionCount.textContent = n + ' / 1000';
                suggestionCount.classList.toggle('near', n >= 900 && n < 1000);
                suggestionCount.classList.toggle('max', n >= 1000);
            }
            suggestionMsg.addEventListener('input', updateSuggestionCount);

            async function sendSuggestion() {
                const text = suggestionMsg.value.trim();
                if (text.length < 5) {
                    setSuggestionStatus('Please type your suggestion first.', 'error');
                    suggestionMsg.focus();
                    return;
                }

                suggestionBtn.disabled = true;
                suggestionBtn.value = 'Sending...';
                setSuggestionStatus('');

                try {
                    const res = await fetch(suggestionBtn.dataset.url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrf
                        },
                        body: JSON.stringify({ message: text })
                    });

                    if (res.status === 429) throw new Error('You are sending too fast. Please wait a moment.');
                    if (res.status === 419) throw new Error('Your session expired. Refresh the page and try again.');
                    if (res.status === 422) {
                        const data = await res.json().catch(() => ({}));
                        const first = data.errors ? Object.values(data.errors)[0][0] : null;
                        throw new Error(first || 'Please check your suggestion and try again.');
                    }
                    if (!res.ok) throw new Error('Could not send your suggestion. Please try again.');

                    suggestionMsg.value = '';
                    updateSuggestionCount();
                    setSuggestionStatus('Thank you! Your suggestion was sent.', 'ok');
                    setTimeout(() => { setSuggestionStatus(''); closeAdmin(); }, 1500);
                } catch (err) {
                    setSuggestionStatus(err instanceof TypeError
                        ? 'No connection. Check your internet and try again.'
                        : err.message, 'error');
                } finally {
                    suggestionBtn.disabled = false;
                    suggestionBtn.value = 'Send Suggestion';
                }
            }

            suggestionBtn.addEventListener('click', sendSuggestion);


            const payModal = document.getElementById('paymentModal');
            const payOpen = () => payModal.classList.contains('is-open');
            const stepModal = document.getElementById('paymentStepModal');
            const confirmModal = document.getElementById('confirmModal');
            const detailsForm = document.getElementById('detailsForm');
            let bookingData = {};
            let selectedBed = null;   
            const deckPick = document.getElementById('deckPick');
            const bedHint = document.getElementById('bedHint');

            function resetBedSelection() {
                selectedBed = null;
                deckPick.classList.remove('is-visible');
                deckPick.querySelectorAll('button').forEach(b => b.classList.remove('active'));
                bedHint.textContent = '';
                modal.querySelectorAll('[data-beds] .sixulit.selected').forEach(el => el.classList.remove('selected'));
            }


            modal.querySelector('[data-beds]').addEventListener('click', e => {
                const card = e.target.closest('.sixulit.free');
                if (!card) return;

                const m = card.querySelector('.lowerdck').textContent.match(/Bed\s+(\d+)\s*·\s*(.+)/);
                if (!m) return;

                const title = m[2].trim();
                const both = title.includes('&');

                modal.querySelectorAll('[data-beds] .sixulit.selected').forEach(el => el.classList.remove('selected'));
                card.classList.add('selected');

                selectedBed = { number: m[1], deck: both ? null : title, both: both };

                deckPick.querySelectorAll('button').forEach(b => b.classList.remove('active'));
                deckPick.classList.toggle('is-visible', both);
                bedHint.textContent = both
                    ? 'Bed ' + m[1] + ' selected. Now choose Lower or Upper.'
                    : 'Bed ' + m[1] + ' (' + title + ') selected.';
            });

          
            deckPick.addEventListener('click', e => {
                const btn = e.target.closest('button[data-deck]');
                if (!btn || !selectedBed) return;
                deckPick.querySelectorAll('button').forEach(b => b.classList.toggle('active', b === btn));
                selectedBed.deck = btn.dataset.deck;
                bedHint.textContent = 'Bed ' + selectedBed.number + ' (' + selectedBed.deck + ') selected.';
            });
            const bookUrl = @json(route('tenant.rooms.book'));
            const bookCsrf = document.querySelector('meta[name="csrf-token"]').content;
            let myRoom = @json(auth()->user()->room_number);
            let booked = false;

            const hasRoomModal = document.getElementById('hasRoomModal');
            function showHasRoom() {
                document.getElementById('hasRoomLabel').textContent = 'Room ' + myRoom;
                hasRoomModal.classList.add('is-open');
                hasRoomModal.setAttribute('aria-hidden', 'false');
            }
            function hideHasRoom() {
                hasRoomModal.classList.remove('is-open');
                hasRoomModal.setAttribute('aria-hidden', 'true');
            }
            document.getElementById('hasRoomClose').addEventListener('click', hideHasRoom);
            hasRoomModal.addEventListener('click', e => { if (e.target === hasRoomModal) hideHasRoom(); });
            const bookBtn = modal.querySelector('[data-open-payment]');

            const setPay = (key, text) => {
                document
                    .querySelectorAll(
                        '#paymentModal [data-pay="' + key + '"], ' +
                        '#paymentStepModal [data-pay="' + key + '"], ' +
                        '#confirmModal [data-pay="' + key + '"]'
                    )
                    .forEach(el => el.textContent = text);
            };
            const setConf = (key, text) => {
                confirmModal
                    .querySelectorAll('[data-conf="' + key + '"]')
                    .forEach(el => el.textContent = text);
            };
            let holdTimer = null;

            function setProgressStep(n) {
                document.querySelectorAll('.progress .pstep').forEach(step => {
                    const s = Number(step.dataset.step);
                    step.classList.toggle('is-done', s < n);
                    step.classList.toggle('is-current', s === n);
                    if (s === n) step.setAttribute('aria-current', 'step');
                    else step.removeAttribute('aria-current');

                    const line = step.nextElementSibling;
                    if (line && line.classList.contains('padiretso')) line.classList.toggle('is-done', s < n);
                });
            }


            function pickBed() {
                if (!selectedBed) {
                    bedHint.textContent = 'Please select a bed first.';
                    return null;
                }
                if (!selectedBed.deck) {
                    bedHint.textContent = 'Please choose Lower or Upper deck.';
                    return null;
                }
                return { number: selectedBed.number, deck: selectedBed.deck };
            }

            function stopHold() {
                if (holdTimer) clearInterval(holdTimer);
                holdTimer = null;
            }

            function startHold(seconds) {
                stopHold();
                const end = Date.now() + seconds * 1000;

                const tick = () => {
                    const left = Math.max(0, Math.round((end - Date.now()) / 1000));
                    const mm = Math.floor(left / 60);
                    const ss = String(left % 60).padStart(2, '0');
                    setPay('timer', 'Bed reserved for ' + mm + ':' + ss);
                    if (left === 0) {
                        closePayment();
                        alert('Your 15-minute bed reservation expired. Please pick a bed again.');
                    }
                };

                tick();
                holdTimer = setInterval(tick, 1000);
            }

            function openPayment() {
                if (myRoom) { showHasRoom(); return; }
                const bed = pickBed();
                if (!bed) return;

                const room = modal.dataset.room || '';
                payModal.dataset.room = room;
                payModal.dataset.bed = bed.number;
                payModal.dataset.deck = bed.deck;
                payModal.querySelectorAll('[data-room-number]').forEach(el => el.textContent = room);

                setPay('room', 'Room ' + room);
                setPay('bed', (modal.dataset.sharing || '') + ' · Bed ' + bed.number + ', ' + bed.deck);
                setPay('amount', '₱' + (modal.dataset.price || ''));
                const base = Number(String(modal.dataset.price || '0').replace(/,/g, '')) || 0;
                const fees = [...stepModal.querySelectorAll('[data-fee]')]
                    .reduce((sum, el) => sum + Number(el.dataset.fee), 0);
                const totalText = (base + fees).toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
                setPay('total', '₱' + totalText);
                const payBtn = stepModal.querySelector('.lahatbayad');
                if (payBtn) payBtn.textContent = 'Pay ₱' + totalText + ' & confirm booking';

                setProgressStep(2);
                startHold(15 * 60);
                payModal.classList.add('is-open');
                payModal.setAttribute('aria-hidden', 'false');
            }

            function closePayment() {
                confirmModal.classList.remove('is-open');
                confirmModal.setAttribute('aria-hidden', 'true');
                stepModal.classList.remove('is-open');
                stepModal.setAttribute('aria-hidden', 'true');
                stopHold();
                payModal.classList.remove('is-open');
                payModal.setAttribute('aria-hidden', 'true');
            }

            bookBtn.addEventListener('click', openPayment);

            const todayLocal = new Date(Date.now() - new Date().getTimezoneOffset() * 60000).toISOString().split('T')[0];
            detailsForm.querySelector('[name="date_of_birth"]').max = todayLocal;
            detailsForm.querySelector('[name="move_in_date"]').min = todayLocal;

            function openStep3() {
                bookingData = Object.fromEntries(new FormData(detailsForm));
                bookingData.room = payModal.dataset.room;
                bookingData.bed = payModal.dataset.bed;
                bookingData.deck = payModal.dataset.deck;

                setProgressStep(3);
                stepModal.classList.add('is-open');
                stepModal.setAttribute('aria-hidden', 'false');
            }

            function closeStep3() {
                stepModal.classList.remove('is-open');
                stepModal.setAttribute('aria-hidden', 'true');
                setProgressStep(2);
            }

            detailsForm.addEventListener('submit', e => {
                e.preventDefault();
                openStep3();
            });

            stepModal.addEventListener('click', e => {
                if (e.target.closest('[data-back-to-details]')) closeStep3();
            });
            function openConfirm(r) {
                const total = stepModal.querySelector('[data-pay="total"]').textContent;

                setConf('headline', 'Room ' + r.room + ' is now reserved under your name. A copy of this receipt has been sent to your email.');
                setConf('confirmed-at', r.confirmed_at);
                setConf('ref', 'Booking ref. #' + r.ref);
                setConf('tenant', r.tenant);
                setConf('room-bed', 'Room ' + r.room + ' · Bed ' + r.bed + ', ' + r.deck);
                setConf('method', 'GCash');
                setConf('move-in', r.move_in);
                setConf('total', total);


                confirmModal.querySelector('[data-back-to-payment] span').textContent = 'Close';

                setProgressStep(4);
                confirmModal.classList.add('is-open');
                confirmModal.setAttribute('aria-hidden', 'false');
            }
            function closeConfirm() {
                if (booked) { window.location.reload(); return; }
                confirmModal.classList.remove('is-open');
                confirmModal.setAttribute('aria-hidden', 'true');
                setProgressStep(3);
            }

            const payBtnEl = stepModal.querySelector('.lahatbayad');

            async function submitBooking() {
                if (booked || payBtnEl.disabled) return;

                const original = payBtnEl.textContent;
                payBtnEl.disabled = true;
                payBtnEl.textContent = 'Processing...';

                try {
                    const res = await fetch(bookUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': bookCsrf
                        },
                        body: JSON.stringify(bookingData)
                    });
                    const data = await res.json().catch(() => ({}));

                    if (res.status === 409 && data.code === 'has_room') {
                        myRoom = data.room;
                        closePayment();
                        showHasRoom();
                        return;
                    }
                    if (res.status === 422) {
                        const first = data.errors ? Object.values(data.errors)[0][0] : data.message;
                        throw new Error(first || 'Please check your details and try again.');
                    }
                    if (res.status === 419) throw new Error('Your session expired. Refresh the page and try again.');
                    if (!res.ok) throw new Error(data.message || 'Could not complete your booking. Please try again.');

                    booked = true;
                    myRoom = data.room;
                    stopHold();
                    openConfirm(data);
                } catch (err) {
                    alert(err instanceof TypeError ? 'No connection. Check your internet and try again.' : err.message);
                } finally {
                    payBtnEl.disabled = false;
                    payBtnEl.textContent = original;
                }
            }

            payBtnEl.addEventListener('click', submitBooking);

            confirmModal.addEventListener('click', e => {
                if (e.target.closest('[data-back-to-payment]')) closeConfirm();
            });
            payModal.addEventListener('click', e => {
                if (e.target === payModal || e.target.closest('[data-close-payment]')) closePayment();
            });


            function openModal(btn) {
                const room = btn.dataset.room;
                modal.dataset.room = room;

                modal.querySelectorAll('[data-room-number]').forEach(el => el.textContent = room);
                fill('subtitle', btn.dataset.sharing + ' · ' + btn.dataset.bedtype);
                fill('price', '₱' + btn.dataset.price + ' ');
                fill('price-label', btn.dataset.priceLabel);
                fill('bedtype', btn.dataset.bedtype);
                fill('totalbeds', btn.dataset.totalBeds + ' Beds');
                fill('availability', btn.dataset.availability);

                const av = modal.querySelector('[data-fill="availability"]');
                if (av) av.style.color = btn.dataset.availabilityColor;

                const bedsTarget = modal.querySelector('[data-beds]');
                const bedsSource = btn.closest('.roomstart').querySelector('.sixboxesulit');
                if (bedsTarget && bedsSource) bedsTarget.innerHTML = bedsSource.innerHTML;
                resetBedSelection();

                modal.dataset.price = btn.dataset.price;
                modal.dataset.sharing = btn.dataset.sharing;

                const card = btn.closest('.roomstart');
                const noSpace = card.dataset.maintenance === '1' || +card.dataset.free === 0;
                bookBtn.disabled = noSpace;
                bookBtn.style.opacity = noSpace ? '.5' : '';
                bookBtn.style.cursor = noSpace ? 'not-allowed' : '';
                bookBtn.value = card.dataset.maintenance === '1' ? 'Under maintenance'
                    : (+card.dataset.free === 0 ? 'Fully occupied' : 'Book this room');

                roomNo = room;
                photos = JSON.parse(btn.dataset.photos || '[]');
                showPhoto(0);

                modal.classList.add('is-open');
                modal.setAttribute('aria-hidden', 'false');
                document.body.style.overflow = 'hidden';
            }

            function closeModal() {
                if (window.AdminModal) window.AdminModal.close();
                closeAdmin();
                closePayment();
                closeLightbox();
                modal.classList.remove('is-open');
                modal.setAttribute('aria-hidden', 'true');
                document.body.style.overflow = '';
                resetBedSelection();
            }

            document.querySelectorAll('.detalye').forEach(btn => {
                btn.addEventListener('click', () => openModal(btn));
            });

            modal.addEventListener('click', e => {
                if (e.target.closest('[data-close-modal]')) closeModal();
            });

            document.addEventListener('keydown', e => {
                if (!isOpen() || adminOpen()) return;

                if (payOpen()) {
                    if (e.key === 'Escape') {
                        if (confirmModal.classList.contains('is-open')) closeConfirm();
                        else if (stepModal.classList.contains('is-open')) closeStep3();
                        else closePayment();
                    }
                    return;
                }

                if (e.key === 'Escape') {
                    if (lightbox.classList.contains('is-open')) closeLightbox();
                    else closeModal();
                    return;
                }
                if (e.key === 'ArrowLeft') showPhoto(current - 1);
                if (e.key === 'ArrowRight') showPhoto(current + 1);
            });

        });
    </script>
    <script>
        (function () {
            const popup = document.getElementById('unavailModal');
            const label = document.getElementById('unavailMethod');
            const names = { card: 'Debit / Credit', bank: 'Bank Transfer' };

            function openPopup(name) {
                label.textContent = name;
                popup.classList.add('is-open');
                popup.setAttribute('aria-hidden', 'false');
            }

            function closePopup() {
                popup.classList.remove('is-open');
                popup.setAttribute('aria-hidden', 'true');
            }

            document.querySelectorAll('.pipilian[data-method]').forEach(box => {
                box.style.cursor = 'pointer';
                box.addEventListener('click', () => {
                    const m = box.dataset.method;
                    if (names[m]) openPopup(names[m]);
                });
            });

            document.getElementById('unavailClose').addEventListener('click', closePopup);
            popup.addEventListener('click', e => { if (e.target === popup) closePopup(); });
            document.addEventListener('keydown', e => {
                if (e.key === 'Escape' && popup.classList.contains('is-open')) {
                    e.stopPropagation();
                    closePopup();
                }
            }, true);
        })();
    </script>
@endpush