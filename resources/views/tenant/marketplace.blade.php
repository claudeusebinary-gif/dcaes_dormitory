@extends('layouts.app')

@section('title', 'Marketplace')

@section('content')
@section('page_title', 'Tenant Marketplace')
@section('page_subtitle', 'Buy, sell, or give away items with other tenants in the dorm.')
    @section('page_action')
        <button id="myBtn" type="button" class="roomlist-btn mine-btn">My listings <span class="mine-count"
                id="myCount">0</span></button>
        <button id="sellBtn" class="roomlist-btn" style="border: 1px solid #672C2C; cursor: pointer;">Sell an item</button>
    @endsection
    <div class="search" style="margin-top: -10px">
        <div class="search-box" style="margin-top: 5px;">
            <svg class="search-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <circle cx="11" cy="11" r="7" />
                <path d="m20 20-3.5-3.5" />
            </svg>

            <input type="search" id="mkSearch" placeholder="Search by item..." autocomplete="off" />

            <span class="search-caret" aria-hidden="true"></span>
        </div>
        <div class="filter-wrap" style="margin-top: 5px;">
            <input type="button" class="filter" id="mkFilterBtn" value="Filter" aria-expanded="false"
                aria-controls="mkFilterPop">

            <div class="filter-pop" id="mkFilterPop" hidden>
                <label for="mkMaxPrice">Max price (₱)</label>
                <input type="number" id="mkMaxPrice" min="0" step="50" placeholder="e.g. 500">

                <label for="mkSort">Sort by</label>
                <select id="mkSort">
                    <option value="">Default</option>
                    <option value="low">Price: low to high</option>
                    <option value="high">Price: high to low</option>
                </select>

                <button type="button" class="filter-reset" id="mkReset">Reset</button>
            </div>
        </div>
    </div>
    <div class="bagongpangpile" id="mkChips">
        <button type="button" class="bagongpangpile1 is-active" data-cat="All">All</button>
        <button type="button" class="bagongpangpile1" data-cat="Furniture">Furniture</button>
        <button type="button" class="bagongpangpile1" data-cat="Electronics">Electronics</button>
        <button type="button" class="bagongpangpile1" data-cat="Appliances">Appliances</button>
        <button type="button" class="bagongpangpile1" data-cat="Others">Others</button>
        <button type="button" class="bagongpangpile1" data-cat="Books &amp; Supplies">Books &amp; Supplies</button>
        <button type="button" class="bagongpangpile1" data-cat="Food">Food</button>
        <button type="button" class="bagongpangpile1" data-cat="Clothing">Clothing</button>
        <button type="button" class="bagongpangpile1" data-cat="Free">Free</button>
    </div>
    <div class="pangsell" id="mkGrid"></div>

    <div class="mk-pager">
        <button type="button" class="mk-pg" id="mkPrev">‹ Prev</button>
        <span id="mkNums" class="mk-nums"></span>
        <button type="button" class="mk-pg" id="mkNext">Next ›</button>
        <span class="mk-info-text" id="mkInfo"></span>
    </div>


    <div class="mk-overlay" id="mkItemModal" hidden>
        <div class="mk-box" role="dialog" aria-modal="true">
            <button type="button" class="mk-close" data-mk-close aria-label="Close">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round">
                    <path d="M6 6l12 12M18 6L6 18" />
                </svg>
            </button>

            <div class="mk-red" id="mkRed">
                <h1>Photos</h1>
                <div class="imagegitna">
                    <div class="imglogoimage" id="viewMain"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M21 19V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z" />
                        </svg></div>
                </div>
                <div class="imagesall" id="viewThumbs"></div>
            </div>

            <div class="mk-white" id="mkWhite">
                <div class="autohabaa">
                    <h1 id="viewCat"></h1>
                </div>
                <h1 class="studasddsa" id="viewTitle"></h1>
                <h1 class="studasddsa1" id="viewPrice"></h1>
                <span class="studasddsa12" id="viewDesc"></span>
                <hr class="padiretso23175927" style="margin-top:20px;">
                <h1 class="studasddsa123">SELLER</h1>
                <div class="namesellernabuyer">
                    <div class="invoss933">
                        <div class="invoss1933">
                            <h1 id="viewInitial"></h1>
                        </div>
                        <div class="ibosses933">
                            <h1 class="washi" id="viewSeller"></h1>
                            <span id="viewRoom"></span>
                        </div>
                    </div>
                </div>
                <button type="button" class="messagemosiseller" id="viewMsgBtn" style="margin-top: 30px;">Message
                    seller</button>
                <p class="mk-mine" id="viewMine" hidden>This is your listing.</p>
            </div>
        </div>
    </div>

    <div class="mk-overlay" id="mkSellModal" hidden>
        <div class="mk-box" role="dialog" aria-modal="true">
            <button type="button" class="mk-close" data-mk-close aria-label="Close">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round">
                    <path d="M6 6l12 12M18 6L6 18" />
                </svg>
            </button>

            <div class="mk-red" id="sellRed">
                <h1>Photos (up to 4)</h1>
                <div class="imagegitna">
                    <div class="imglogoimage" id="sellCover" data-pick style="cursor:pointer"><svg
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="currentColor">
                            <path
                                d="M21 19V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z" />
                        </svg></div>
                </div>
                <h1 class="addroompotos3" data-pick style="cursor:pointer">Add item photos</h1>
                <h1 class="addroompotos34">Click to upload — first photo becomes the cover</h1>
                <div class="imagesall3" id="sellThumbs" style="margin-top:90px ;">
                    <div class="imagesall13" data-pick><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M12 5v14M5 12h14" />
                        </svg></div>
                </div>
                <input type="file" id="sellFile" accept="image/*" multiple hidden>
            </div>

            <div class="mk-white" id="sellWhite">
                <h1 class="postmoitem">Post an item</h1>
                <span class="listfellow">List something to sell or give away to fellow tenants.</span>

                <div class="pirsname54">
                    <h1>Item Name</h1>
                    <input type="text" id="sellTitle" class="laglaggayanfd" maxlength="60" placeholder="e.g Electric Fan">
                </div>

                <div class="pirsname295">
                    <div class="pirsname54">
                        <h1>Item Type</h1>
                        <select id="sellCategory" class="laglaggayan2">
                            <option value="">Select item type</option>
                            <option value="Furniture">Furniture</option>
                            <option value="Appliances">Appliances</option>
                            <option value="Electronics">Electronics</option>
                            <option value="Books &amp; Supplies">Books &amp; Supplies</option>
                            <option value="Food">Food</option>
                            <option value="Clothing">Clothing</option>
                            <option value="Others">Others</option>
                        </select>
                    </div>
                    <div class="pirsname54">
                        <h1 class="prce">Price (₱, or leave blank for free)</h1>
                        <input type="text" id="sellPrice" class="laglaggayanfd" inputmode="numeric" placeholder="e.g 500">
                    </div>
                </div>

                <div class="pirsname29">
                    <div class="pirsname54">
                        <h1>Description</h1>
                        <textarea id="sellDesc" class="laglaggayan22" maxlength="200"
                            placeholder="Condition, reason for selling, etc"></textarea>
                    </div>
                </div>

                <p class="mk-status" id="sellStatus" hidden></p>
                <button type="button" class="messagemosiseller" id="sellSubmit">Post Listing</button>
            </div>
        </div>
    </div>

    <div class="mk-overlay" id="mkMineModal" hidden>
        <div class="mk-box" role="dialog" aria-modal="true">
            <button type="button" class="mk-close" data-mk-close aria-label="Close">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round">
                    <path d="M6 6l12 12M18 6L6 18" />
                </svg>
            </button>
            <div class="mk-white">
                <h1 class="postmoitem">My listings</h1>
                <span class="listfellow">Items you posted. Click one to view it. Your own items don't show in the main
                    list.</span>
                <p class="mk-status" id="mineStatus" hidden></p>
                <div class="mine-list" id="mineList"></div>
            </div>
        </div>
    </div>
    <div class="mk-lightbox" id="mkLightbox" hidden>
        <button type="button" class="mk-lb-close" aria-label="Close photo">×</button>
        <button type="button" class="mk-lb-nav mk-lb-prev" id="mkLbPrev" aria-label="Previous photo">‹</button>
        <img id="mkLbImg" alt="Item photo">
        <button type="button" class="mk-lb-nav mk-lb-next" id="mkLbNext" aria-label="Next photo">›</button>
        <div class="mk-lb-count" id="mkLbCount"></div>
    </div>
@endsection
@push('scripts')
    <script>
        (function () {
            var PER_PAGE = 6;
            var items = @json($items);
            var ME = @json($me);
            var STORE_URL = @json(route('tenant.marketplace.store'));
            var DEL_URL = @json(route('tenant.marketplace.destroy', ['id' => '__ID__']));
            var CSRF = document.querySelector('meta[name="csrf-token"]').content;
            var colors = ['#672c2c', '#672c2cb9', '#843939', '#A87C5F', '#6E7A5D', '#8a5a44'];
            var IMG_SVG = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M21 19V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/></svg>';
            var PLUS_SVG = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>';
            var state = { q: '', cat: 'All', max: '', sort: '', page: 1 };
            var current = null;
            var photos = [];

            var $ = function (id) { return document.getElementById(id); };
            var grid = $('mkGrid'), info = $('mkInfo'), prevBtn = $('mkPrev'), nextBtn = $('mkNext'), nums = $('mkNums');

            function esc(s) { var d = document.createElement('div'); d.textContent = s == null ? '' : s; return d.innerHTML; }
            function peso(p) { return Number(p) === 0 ? 'Free' : '₱' + Number(p).toLocaleString(); }

            function filtered() {
                var q = state.q.trim().toLowerCase();
                var list = items.filter(function (it) {
                    if (it.seller_id === ME.id) return false;
                    if (state.cat === 'Free' && Number(it.price) !== 0) return false;
                    if (state.cat !== 'All' && state.cat !== 'Free' && it.category !== state.cat) return false;
                    if (state.max !== '' && Number(it.price) > Number(state.max)) return false;
                    if (q && (it.title + ' ' + it.desc + ' ' + it.category + ' ' + it.seller).toLowerCase().indexOf(q) === -1) return false;
                    return true;
                });
                if (state.sort === 'low') list.sort(function (a, b) { return a.price - b.price; });
                if (state.sort === 'high') list.sort(function (a, b) { return b.price - a.price; });
                return list;
            }

            function card(it) {
                var bg = colors[it.id % colors.length];
                var cover = (it.photos && it.photos.length)
                    ? '<div class="taasmuna" style="background:#eee url(\'' + it.photos[0] + '\') center/cover no-repeat"></div>'
                    : '<div class="taasmuna" style="background-color:' + bg + '"><div class="siranapiche">' + IMG_SVG + '</div></div>';
                var action = it.seller_id === ME.id
                    ? '<span class="mk-yours">Your listing</span>'
                    : '<button type="button" class="chatlang" data-chat="1">Message</button>';

                return '<div class="pangsell1" data-id="' + it.id + '">'
                    + cover
                    + '<div class="babamuna">'
                    + '<div class="typesoffur"><h1>' + esc(it.category) + '</h1></div>'
                    + '<div class="persoge97"><div class="hihihiwalay"><h1>' + esc(it.title) + '</h1><span class="faybe">' + peso(it.price) + '</span></div>'
                    + '<span>' + esc(it.desc) + '</span></div>'
                    + '<hr class="padiretso23175927" style="margin-top:15px;">'
                    + '<div class="chatmosya"><div class="namesakaroom"><div class="logbibilogs"><h1>' + esc(it.initial) + '</h1></div>'
                    + '<div class="persoge977"><h1>' + esc(it.seller) + '</h1><span class="faybe">' + esc(it.room) + '</span></div></div>'
                    + action + '</div>'
                    + '</div></div>';
            }

            function render() {
                var list = filtered();
                var pages = Math.max(1, Math.ceil(list.length / PER_PAGE));
                if (state.page > pages) state.page = pages;
                var from = (state.page - 1) * PER_PAGE;
                var slice = list.slice(from, from + PER_PAGE);

                grid.innerHTML = slice.length ? slice.map(card).join('') : '<p class="mk-empty">No items match your search.</p>';
                info.textContent = list.length ? 'Showing ' + (from + 1) + '–' + (from + slice.length) + ' of ' + list.length : 'Showing 0 of 0';

                nums.innerHTML = '';
                for (var p = 1; p <= pages; p++) {
                    var b = document.createElement('button');
                    b.type = 'button';
                    b.className = 'mk-pg' + (p === state.page ? ' is-active' : '');
                    b.textContent = p;
                    b.dataset.page = p;
                    nums.appendChild(b);
                }
                prevBtn.disabled = state.page <= 1;
                nextBtn.disabled = state.page >= pages;
            }

            function update() { state.page = 1; render(); }
            render();

            prevBtn.addEventListener('click', function () { state.page--; render(); });
            nextBtn.addEventListener('click', function () { state.page++; render(); });
            nums.addEventListener('click', function (e) {
                var b = e.target.closest('[data-page]');
                if (b) { state.page = Number(b.dataset.page); render(); }
            });

            $('mkSearch').addEventListener('input', function (e) { state.q = e.target.value; update(); });

            $('mkChips').addEventListener('click', function (e) {
                var b = e.target.closest('[data-cat]');
                if (!b) return;
                state.cat = b.dataset.cat;
                document.querySelectorAll('#mkChips .bagongpangpile1').forEach(function (x) {
                    x.classList.toggle('is-active', x === b);
                });
                update();
            });

            var fBtn = $('mkFilterBtn'), fPop = $('mkFilterPop');
            fBtn.addEventListener('click', function (e) {
                e.stopPropagation();
                fPop.hidden = !fPop.hidden;
                fBtn.setAttribute('aria-expanded', String(!fPop.hidden));
            });
            document.addEventListener('click', function (e) {
                if (!fPop.hidden && !fPop.contains(e.target) && e.target !== fBtn) {
                    fPop.hidden = true;
                    fBtn.setAttribute('aria-expanded', 'false');
                }
            });
            $('mkMaxPrice').addEventListener('input', function (e) { state.max = e.target.value; update(); });
            $('mkSort').addEventListener('change', function (e) { state.sort = e.target.value; update(); });

            function resetFilters() {
                state.max = ''; state.sort = ''; state.cat = 'All'; state.q = '';
                $('mkMaxPrice').value = ''; $('mkSort').value = ''; $('mkSearch').value = '';
                document.querySelectorAll('#mkChips .bagongpangpile1').forEach(function (x) {
                    x.classList.toggle('is-active', x.dataset.cat === 'All');
                });
                update();
            }
            $('mkReset').addEventListener('click', resetFilters);


            function openModal(m) { m.hidden = false; document.body.style.overflow = 'hidden'; }
            function closeModals() {
                document.querySelectorAll('.mk-overlay').forEach(function (m) { m.hidden = true; });
                document.body.style.overflow = '';
            }
            document.querySelectorAll('.mk-overlay').forEach(function (m) {
                m.addEventListener('click', function (e) {
                    if (e.target === m || e.target.closest('[data-mk-close]')) closeModals();
                });
            });
            document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closeModals(); });


            function chatWith(it) {
                if (!window.DaceChat) return;
                window.DaceChat.open({
                    item_id: it.id, item_title: it.title, item_price: peso(it.price),
                    photo: (it.photos && it.photos[0]) || '',
                    with_id: it.seller_id, with_name: it.seller, with_room: it.room
                });
            }


            function setGallery(urls) {
                var main = $('viewMain'), thumbs = $('viewThumbs');
                main.style.backgroundImage = urls.length ? 'url("' + urls[0] + '")' : '';
                gIndex = 0;
                main.classList.toggle('has-photo', urls.length > 0);
                thumbs.innerHTML = '';
                for (var i = 0; i < 4; i++) {
                    var t = document.createElement('div');
                    t.className = 'imagesall1';
                    if (urls[i]) {
                        t.style.backgroundImage = 'url("' + urls[i] + '")';
                        t.classList.add('has-photo');
                        t.dataset.idx = i;
                        t.style.cursor = 'pointer';
                    } else {
                        t.innerHTML = IMG_SVG;
                    }
                    thumbs.appendChild(t);
                }
            }

            function openItem(it) {
                current = it;
                $('viewCat').textContent = it.category;
                $('viewTitle').textContent = it.title;
                $('viewPrice').textContent = peso(it.price);
                $('viewDesc').textContent = it.desc;
                $('viewInitial').textContent = it.initial;
                $('viewSeller').textContent = it.seller;
                $('viewRoom').textContent = it.room;
                var mine = it.seller_id === ME.id;
                $('viewMsgBtn').hidden = mine;
                $('viewMine').hidden = !mine;
                setGallery(it.photos || []);
                openModal($('mkItemModal'));
            }

            $('viewThumbs').addEventListener('click', function (e) {
                var t = e.target.closest('[data-idx]');
                if (t && current) {
                    gIndex = Number(t.dataset.idx);
                    $('viewMain').style.backgroundImage = 'url("' + current.photos[gIndex] + '")';
                }
            });
            $('viewMsgBtn').addEventListener('click', function () {
                if (!current) return;
                closeModals();
                chatWith(current);
            });

            grid.addEventListener('click', function (e) {
                var c = e.target.closest('.pangsell1');
                if (!c) return;
                var it = items.filter(function (x) { return x.id === Number(c.dataset.id); })[0];
                if (!it) return;
                if (e.target.closest('[data-chat]')) { chatWith(it); return; }
                openItem(it);
            });


            var gIndex = 0;
            var lb = $('mkLightbox'), lbImg = $('mkLbImg'), lbPrev = $('mkLbPrev'), lbNext = $('mkLbNext'), lbCount = $('mkLbCount');

            function lbShow() {
                var list = (current && current.photos) || [];
                if (!list.length) return;
                gIndex = (gIndex + list.length) % list.length;
                lbImg.src = list[gIndex];
                lbCount.textContent = (gIndex + 1) + ' / ' + list.length;
                var many = list.length > 1;
                lbPrev.hidden = !many; lbNext.hidden = !many; lbCount.hidden = !many;
            }
            function lbOpen() {
                if (!current || !(current.photos || []).length) return;
                lb.hidden = false;
                lbShow();
            }
            function lbClose() {
                lb.hidden = true;
                lbImg.removeAttribute('src');
            }

            $('viewMain').addEventListener('click', lbOpen);
            lbPrev.addEventListener('click', function (e) { e.stopPropagation(); gIndex--; lbShow(); });
            lbNext.addEventListener('click', function (e) { e.stopPropagation(); gIndex++; lbShow(); });
            lb.addEventListener('click', function (e) { if (e.target !== lbImg) lbClose(); });


            document.addEventListener('keydown', function (e) {
                if (lb.hidden) return;
                if (e.key === 'Escape') { e.stopImmediatePropagation(); lbClose(); }
                if (e.key === 'ArrowLeft') { gIndex--; lbShow(); }
                if (e.key === 'ArrowRight') { gIndex++; lbShow(); }
            }, true);

            function sayMine(text, good) {
                var s = $('mineStatus');
                s.hidden = !text;
                s.textContent = text || '';
                s.className = 'mk-status ' + (good ? 'is-good' : 'is-bad');
            }

            function mineRow(it) {
                var thumb = (it.photos && it.photos.length)
                    ? '<div class="mine-thumb" style="background-image:url(\'' + it.photos[0] + '\')"></div>'
                    : '<div class="mine-thumb">' + IMG_SVG + '</div>';
                return '<div class="mine-row" data-id="' + it.id + '">' + thumb
                    + '<div class="mine-info"><b>' + esc(it.title) + '</b><span>' + esc(it.category) + ' · ' + peso(it.price) + '</span></div>'
                    + '<button type="button" class="mine-del" data-del="' + it.id + '">Delete</button></div>';
            }

            function renderMine() {
                var list = items.filter(function (x) { return x.seller_id === ME.id; });
                $('mineList').innerHTML = list.length
                    ? list.map(mineRow).join('')
                    : '<p class="mk-empty">You haven\'t posted anything yet.</p>';
                $('myCount').textContent = list.length;
            }

            $('myBtn').addEventListener('click', function () {
                sayMine('');
                renderMine();
                openModal($('mkMineModal'));
            });

            $('mineList').addEventListener('click', async function (e) {
                var del = e.target.closest('[data-del]');
                var row = e.target.closest('.mine-row');

                if (del) {
                    var id = Number(del.dataset.del);
                    if (!confirm('Delete this listing? Any chat messages about it will be removed too.')) return;
                    del.disabled = true; del.textContent = 'Deleting...';
                    try {
                        var res = await fetch(DEL_URL.replace('__ID__', id), {
                            method: 'DELETE',
                            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF },
                            credentials: 'same-origin'
                        });
                        var data = await res.json().catch(function () { return {}; });
                        if (res.status === 419) throw new Error('Your session expired. Refresh the page and try again.');
                        if (!res.ok && res.status !== 404) throw new Error(data.message || 'Could not delete. Please try again.');
                        items = items.filter(function (x) { return x.id !== id; });
                        renderMine();
                        render();
                        sayMine('Listing deleted.', true);
                    } catch (err) {
                        del.disabled = false; del.textContent = 'Delete';
                        sayMine(err instanceof TypeError ? 'No connection. Check your internet and try again.' : err.message, false);
                    }
                    return;
                }

                if (row) {
                    var it = items.filter(function (x) { return x.id === Number(row.dataset.id); })[0];
                    if (it) { closeModals(); openItem(it); }
                }
            });

            renderMine();
            function sayStatus(text, good) {
                var s = $('sellStatus');
                s.hidden = !text;
                s.textContent = text || '';
                s.className = 'mk-status ' + (good ? 'is-good' : 'is-bad');
            }

            function renderSellPhotos() {
                var cover = $('sellCover');
                cover.style.backgroundImage = photos.length ? 'url("' + photos[0].url + '")' : '';
                cover.classList.toggle('has-photo', photos.length > 0);

                var row = $('sellThumbs');
                row.innerHTML = '';
                photos.forEach(function (p, i) {
                    var t = document.createElement('div');
                    t.className = 'sell-thumb';
                    t.style.backgroundImage = 'url("' + p.url + '")';
                    t.innerHTML = '<button type="button" data-remove="' + i + '" aria-label="Remove photo">×</button>';
                    row.appendChild(t);
                });
                if (photos.length < 4) {
                    var add = document.createElement('div');
                    add.className = 'imagesall13';
                    add.setAttribute('data-pick', '');
                    add.innerHTML = PLUS_SVG;
                    row.appendChild(add);
                }
            }


            function shrink(file) {
                return new Promise(function (resolve) {
                    var img = new Image(), url = URL.createObjectURL(file);
                    img.onload = function () {
                        var s = Math.min(1, 1200 / Math.max(img.width, img.height));
                        var c = document.createElement('canvas');
                        c.width = Math.round(img.width * s);
                        c.height = Math.round(img.height * s);
                        c.getContext('2d').drawImage(img, 0, 0, c.width, c.height);
                        URL.revokeObjectURL(url);
                        c.toBlob(function (b) { resolve(b || file); }, 'image/jpeg', 0.82);
                    };
                    img.onerror = function () { URL.revokeObjectURL(url); resolve(file); };
                    img.src = url;
                });
            }

            var fileInput = $('sellFile');
            $('sellRed').addEventListener('click', function (e) {
                var rm = e.target.closest('[data-remove]');
                if (rm) {
                    var gone = photos.splice(Number(rm.dataset.remove), 1)[0];
                    if (gone) URL.revokeObjectURL(gone.url);
                    renderSellPhotos();
                    return;
                }
                if (e.target.closest('[data-pick]') && photos.length < 4) fileInput.click();
            });
            fileInput.addEventListener('change', async function () {
                var files = Array.prototype.slice.call(fileInput.files);
                fileInput.value = '';
                for (var i = 0; i < files.length && photos.length < 4; i++) {
                    if (!/^image\//.test(files[i].type)) continue;
                    var blob = await shrink(files[i]);
                    photos.push({ blob: blob, url: URL.createObjectURL(blob) });
                }
                renderSellPhotos();
            });

            function resetSellForm() {
                $('sellTitle').value = ''; $('sellCategory').value = ''; $('sellPrice').value = ''; $('sellDesc').value = '';
                photos.forEach(function (p) { URL.revokeObjectURL(p.url); });
                photos = [];
                renderSellPhotos();
                sayStatus('');
            }

            $('sellBtn').addEventListener('click', function () { sayStatus(''); openModal($('mkSellModal')); });

            $('sellSubmit').addEventListener('click', async function () {
                var btn = this;
                var title = $('sellTitle').value.trim();
                var category = $('sellCategory').value;
                var price = $('sellPrice').value.replace(/[^\d]/g, '');

                if (title.length < 2) return sayStatus('Please enter an item name.');
                if (!category) return sayStatus('Please choose an item type.');

                var fd = new FormData();
                fd.append('title', title);
                fd.append('category', category);
                fd.append('price', price === '' ? '0' : price);
                fd.append('description', $('sellDesc').value.trim());
                photos.forEach(function (p, i) { fd.append('photos[]', p.blob, 'photo' + (i + 1) + '.jpg'); });

                var label = btn.textContent;
                btn.disabled = true; btn.textContent = 'Posting...'; sayStatus('');

                try {
                    var res = await fetch(STORE_URL, {
                        method: 'POST',
                        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF },
                        credentials: 'same-origin',
                        body: fd
                    });
                    var data = await res.json().catch(function () { return {}; });

                    if (res.status === 419) throw new Error('Your session expired. Refresh the page and try again.');
                    if (res.status === 429) throw new Error('You are posting too fast. Please wait a moment.');
                    if (res.status === 422 && data.errors) throw new Error(Object.values(data.errors)[0][0]);
                    if (!res.ok) throw new Error(data.message || 'Could not post your item. Please try again.');

                    items.unshift(data);
                    resetSellForm();
                    closeModals();
                    render();
                    renderMine();
                    openModal($('mkMineModal'));
                    sayMine('Your item was posted.', true);
                } catch (err) {
                    sayStatus(err instanceof TypeError ? 'No connection. Check your internet and try again.' : err.message);
                } finally {
                    btn.disabled = false; btn.textContent = label;
                }
            });
        })();
    </script>
@endpush