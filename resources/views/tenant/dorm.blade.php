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
                            <div class="dot"></div>
                            <h1 class="ruums">Room 100</h1>
                            <div class="leet">
                                <h1 class="overss">2/12</h1>
                            </div>
                        </div>
                        <div class="siven">
                            <div class="dot"></div>
                            <h1 class="ruums">Room 100</h1>
                            <div class="leet">
                                <h1 class="overss">2/12</h1>
                            </div>
                        </div>
                        <div class="siven">
                            <div class="dot"></div>
                            <h1 class="ruums">Room 100</h1>
                            <div class="leet">
                                <h1 class="overss">2/12</h1>
                            </div>
                        </div>
                        <div class="siven">
                            <div class="dot"></div>
                            <h1 class="ruums">Room 100</h1>
                            <div class="leet">
                                <h1 class="overss">2/12</h1>
                            </div>
                        </div>
                        <div class="siven">
                            <div class="dot"></div>
                            <h1 class="ruums">Room 100</h1>
                            <div class="leet">
                                <h1 class="overss">2/12</h1>
                            </div>
                        </div>
                        <div class="siven">
                            <div class="dot"></div>
                            <h1 class="ruums">Room 100</h1>
                            <div class="leet">
                                <h1 class="overss">2/12</h1>
                            </div>
                        </div>
                        <div class="siven4">
                            <div class="dot"></div>
                            <h1 class="ruums">Room 100</h1>
                            <div class="leet">
                                <h1 class="overss">2/12</h1>
                            </div>
                        </div>
                    </div>
                    <div class="hatee"></div>
                </div>
                <div class="group2">
                    <div class="hatee2"></div>
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
                            <div class="dot"></div>
                            <h1 class="ruums">Room 100</h1>
                            <div class="leet">
                                <h1 class="overss">2/12</h1>
                            </div>
                        </div>
                        <div class="siven">
                            <div class="dot"></div>
                            <h1 class="ruums">Room 100</h1>
                            <div class="leet">
                                <h1 class="overss">2/12</h1>
                            </div>
                        </div>
                        <div class="siven">
                            <div class="dot"></div>
                            <h1 class="ruums">Room 100</h1>
                            <div class="leet">
                                <h1 class="overss">2/12</h1>
                            </div>
                        </div>
                        <div class="siven">
                            <div class="dot"></div>
                            <h1 class="ruums">Room 100</h1>
                            <div class="leet">
                                <h1 class="overss">2/12</h1>
                            </div>
                        </div>
                        <div class="siven">
                            <div class="dot"></div>
                            <h1 class="ruums">Room 100</h1>
                            <div class="leet">
                                <h1 class="overss">2/12</h1>
                            </div>
                        </div>
                        <div class="siven4">
                            <div class="dot"></div>
                            <h1 class="ruums">Room 100</h1>
                            <div class="leet">
                                <h1 class="overss">2/12</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



        </div>

        <div class="rightcol">
            <div class="tulooo"></div>
            <div class="tulooo"></div>
            <div class="tulooo"></div>
        </div>
    </div>
    @include('partials.footer')
@endsection