<div class="boxes" id="featuredBoxes">
    @forelse ($featured->take(2) as $room)
        <div class="box{{ $loop->iteration }} fr-card">
            <h1 class="roomp">Room {{ $room['number'] }}</h1>&nbsp;<h1 class="roomp1">— Good For {{ $room['beds'] }}</h1>
            <h1 class="{{ $loop->first ? 'roomp2' : 'roomp22' }}">Double Bed For {{ $room['decks'] }}</h1>
            <h1 class="roomp3">₱{{ number_format($room['price']) }} / month</h1>
        </div>
    @empty
        <p class="roomp2" style="margin:0 auto;">No rooms are open right now. Please check back soon.</p>
    @endforelse
</div>
<script>window.FEATURED_ROOMS = {{ \Illuminate\Support\Js::from($featured->values()) }};</script>