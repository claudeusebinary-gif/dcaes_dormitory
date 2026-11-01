@extends('layouts.app')
@section('title', 'Admin Dashboard')
@section('page_subtitle', $subtitle)
@section('content')
   <div class="upataapatfourboxw">
      <div class="upataapatfourboxw1" style="border-right:none; border-radius:6px 0 0 6px ;">
         <h1>Total tenants</h1>
         <h2>{{ $totalTenants }}</h2>
         <span>+{{ $newThisMonth }} this month</span>
      </div>
      <div class="upataapatfourboxw1" style="border-right:none; ">
         <h1>Occupied rooms
         </h1>
         <h2>{{ $occupied }}/{{ $totalRooms }}</h2>
         <span>{{ $pct }}% occupancy</span>
      </div>
      <div class="upataapatfourboxw1" style="border-right:none; ">
         <h1>Pending payments
         </h1>
         <h2>{{ $unpaidCount }}

         </h2>
         <span>₱{{ number_format($outstanding) }} outstanding

         </span>
      </div>
      <div class="upataapatfourboxw1" style=" border-radius:0 6px 6px 0 ;">
         <h1>Maintenance requests
         </h1>
         <h2>{{ $maintCount }}
         </h2>
         <span>{{ $maintHigh }} high urgency
         </span>
      </div>
   </div>
   <div class="pendingannounce">

      <div class="pendingannounce1">
         <h1 class="pendingapp">Pending approvals</h1>
         <div class="allpendingdocs">
            <button type="button" class="allpendingdocs1 is-on" data-f="all" data-label="All">All
               ({{ $counts['all'] }})</button>
            <button type="button" class="allpendingdocs1" data-f="new_tenant" data-label="New tenants">New tenants
               ({{ $counts['new_tenant'] }})</button>
            <button type="button" class="allpendingdocs1" data-f="payment" data-label="Payments">Payments
               ({{ $counts['payment'] }})</button>
            <button type="button" class="allpendingdocs1" data-f="document" data-label="Documents">Documents
               ({{ $counts['document'] }})</button>
         </div>
         <hr class="padiretso23175927" style="margin-top:0px;">
              <div class="scrollbaaa" id="aprList">
            @foreach($approvals as $a)
               <div class="approvalsto" data-type="{{ $a['type'] }}" data-id="{{ $a['id'] }}" data-href="{{ $a['href'] }}">
                  <div class="approvalsto23">
                     <div class="logbilogbulog">
                        <h1>{{ $a['initial'] }}</h1>
                     </div>
                     <div class="macolumbia">
                        <h1>{{ $a['label'] }}</h1>
                        <h2>{{ $a['name'] }}</h2>
                        <span>{{ $a['detail'] }}</span>
                     </div>
                  </div>
                  <div class="approvalstobutton">
                     <button type="button" class="approvalstobutton2" data-act="view"><svg style="color:#00000065;" xmlns="http://www.w3.org/2000/svg"
                           width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                           stroke-linecap="round" stroke-linejoin="round">
                           <path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7S2 12 2 12z" />
                           <circle cx="12" cy="12" r="3" />
                        </svg></button>
                     <button type="button" class="approvalstobutton2" data-act="approve" style="background-color: #227429d7"><svg style="color:#ffffff;"
                           xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                           stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                           <path d="M5 12.5l4.5 4.5L19 7.5" />
                        </svg></button>
                     <button type="button" class="approvalstobutton2" data-act="reject" style="border:1px solid #681f1f65;"><svg style="color:#681f1fb6;"
                           xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                           stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                           <path d="M6 6l12 12M18 6L6 18" />
                        </svg></button>
                  </div>
               </div>
            @endforeach

            <div class="annempty" id="aprEmpty" @if($approvals->count()) style="display:none" @endif>Nothing waiting for review.</div>
         </div>
      </div>


    
      <div class="pendingannounce12">
         <div class="pendingannounce123">
            <div class="parightsakage">
               <h1>Room map</h1>
               <div class="palogbipula">
                <h1>{{ $occupied }} of {{ $totalRooms }} occupied</h1>
               </div>
            </div>
                    <div class="mgaroomlahat18">
               @foreach($tiles->chunk(6) as $row)
                  <div class="mmgarooms18">
                     @foreach($row as $t)
                        <div class="mmgarooms181 is-{{ $t['state'] }}"
                           title="Room {{ $t['number'] }} · {{ ['occ' => 'Occupied', 'vacant' => 'Vacant', 'maint' => 'Under maintenance'][$t['state']] }}">
                           <h1>{{ $t['number'] }}</h1>
                        </div>
                     @endforeach
                  </div>
               @endforeach
            </div>
            <div class="tatlongoccupiedsakamain">
               <div class="dalawahanlang">
                  <div class="occupiedsaadmina"></div>
                  <h1>Occupied</h1>
               </div>
               <div class="dalawahanlang">
                  <div class="occupiedsaadmina" style="background-color: transparent; border:1px solid #00000065 ;"></div>
                  <h1>Vacant</h1>
               </div>
               <div class="dalawahanlang">
                  <div class="occupiedsaadmina" style="background-color: #a17413"></div>
                  <h1>Maintenance</h1>
               </div>
            </div>


         </div>
         <div class="pendingannounce124">
            <div class="parightsakage5">
               <h1>Announcement</h1>
               <h1 class="pepepending" id="annNewBtn" role="button" tabindex="0">+ New</h1>
            </div>

            <div class="anntimeline " id="annList">
               @foreach($announcements as $a)
                  <div class="annitem" data-id="{{ $a['id'] }}">
                     <div class="needtuwid">
                        <h1>{{ $a['title'] }}</h1>
                        <div class="belognapulanamyhalo">
                           <h1>{{ $a['audience'] }}</h1>
                        </div>
                     </div>
                     <h1 class="schedyle">{{ $a['message'] }}</h1>
                     <div class="needtuwid2">
                        <h1>Posted {{ $a['ago'] }}</h1>
                        <div class="editdeltet32">
                           <h1 class="editdeletetetete" data-ann="edit">Edit</h1>
                           <h1 class="editdeletetetete" data-ann="delete">Delete</h1>
                        </div>
                     </div>
                  </div>
               @endforeach

               <div class="annempty" id="annEmpty" @if($announcements->count()) style="display:none" @endif>No
                  announcements yet.</div>
            </div>

            <div class="annform" id="annForm" hidden>
               <input type="text" class="annfield" id="annTitle" placeholder="Title" maxlength="60">
               <select class="annfield" id="annAudience">
                  <option value="All tenants">All tenants</option>
                  <option value="Unpaid only">Unpaid only</option>
               </select>
               <textarea class="annfield annarea" id="annBody" placeholder="What should tenants know?"
                  maxlength="150"></textarea>
               <div class="annactions">
                  <button type="button" class="annpost" id="annPost">Post</button>
                  <button type="button" class="anncancel" id="annCancel">Cancel</button>
               </div>
            </div>


         </div>
      </div>
   </div>
   <div class="babarecentrecent">
      <div class="babarecentrecent1 rt">
         <div class="recenttenatnsa">
            <h1>Recent tenants</h1>
            <a href="{{ route('admin.tenants') }}" style="text-decoration:none;color:inherit"><span>View all
                  tenants</span></a>
         </div>

         <div class="rt-body">

            <div class="rt-row rt-head">
               <span>Tenant</span>
               <span>Room</span>
               <span>Rent status</span>
               <span></span>
            </div>
            <div class="withscrollreas">
               @forelse($recentTenants as $t)
                  <div class="rt-row">
                     <div class="approvalsto23y">
                        <div class="logbilogbulogy">
                           <h1>{{ $t['initial'] }}</h1>
                        </div>
                        <div class="macolumbiay">
                           <h1>{{ $t['name'] }}</h1>
                           <h2>Moved in {{ $t['moved'] }}</h2>
                        </div>
                     </div>
                     <span class="rt-room">{{ $t['room'] }}</span>
                     <span class="rt-status"><i class="rt-dot {{ $t['class'] }}"></i>{{ $t['status'] }}</span>
                     <a href="{{ route('admin.tenants', ['tenant' => $t['id']]) }}" class="rt-view">View</a>
                  </div>
               @empty
                  <div class="annempty">No tenants yet.</div>
               @endforelse
            </div>


         </div>
      </div>
      <div class="babarecentrecent12">
         <div class="recenttenatnsa">
            <h1>Recent complaints</h1>
            <a href="{{ route('admin.complaints') }}" style="text-decoration:none;color:inherit"><span>See all</span></a>
         </div>
         <hr class="padiretso23175927" style="margin-top:15px;">
         <div class="withpababascroll">
          @forelse($complaints as $c)
   <div class="maymessagedsa">
      <div class="approvalsto23y5">
         <div class="logbilogbulogy5">
           <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                           stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                           <path d="M10.3 3.9 1.8 18a2 2 0 0 0 1.7 3h17a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0z" />
                           <path d="M12 9v4" />
                           <circle cx="12" cy="17" r=".6" fill="currentColor" />
                        </svg>
         </div>
         <div class="macolumbiay5">
            <h1>{{ $c['name'] }}</h1>
            <h2>{{ \Illuminate\Support\Str::limit($c['message'], 60) }}</h2>
            <span>{{ $c['ago'] }}</span>
         </div>
      </div>
      <button type="button" class="pangmessagetenant"
         data-id="{{ $c['id'] }}"
         data-name="{{ $c['name'] }}"
         data-room="{{ $c['room'] }}"
         data-title="Complaint: {{ $c['type'] }}"
         data-msg="{{ \Illuminate\Support\Str::limit($c['message'], 70) }}">Message</button>
   </div>
   <hr class="padiretso23175927" style="margin-top:15px;">
@empty
   <div class="annempty">No complaints yet.</div>
@endforelse
          
         </div>


      </div>
   </div>
   
   <div class="ev-toast" id="dsToast"></div>
@endsection
@push('scripts')
<script>
(function () {
    var CSRF = document.querySelector('meta[name="csrf-token"]').content;
    var APPROVALS_URL = @json(url('/admin/approvals'));
    var ANN_URL = @json(route('admin.announcements.store'));
    var $ = function (id) { return document.getElementById(id); };

    var tt;
    function toast(msg, bad) {
        var t = $('dsToast');
        t.textContent = msg;
        t.className = 'ev-toast is-show' + (bad ? ' is-bad' : '');
        clearTimeout(tt);
        tt = setTimeout(function () { t.className = 'ev-toast'; }, 2400);
    }

    function api(url, method, body) {
        return fetch(url, {
            method: method,
            headers: { 'Accept': 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
            credentials: 'same-origin',
            body: body ? JSON.stringify(body) : undefined
        }).then(function (r) {
            return r.json().catch(function () { return {}; }).then(function (d) {
                if (!r.ok) {
                    var msg = d.errors ? Object.values(d.errors)[0][0] : (d.message || 'Something went wrong.');
                    if (r.status === 419) msg = 'Session expired. Refresh the page.';
                    throw new Error(msg);
                }
                return d;
            });
        });
    }

    /* ---------- approvals: filter + approve / reject ---------- */
    var tabs = document.querySelectorAll('.allpendingdocs1');
    var filter = 'all';

    function rows() { return Array.prototype.slice.call(document.querySelectorAll('#aprList .approvalsto')); }

    function recount() {
        var r = rows(), c = { all: r.length, new_tenant: 0, payment: 0, document: 0 };
        r.forEach(function (x) { if (c[x.dataset.type] !== undefined) c[x.dataset.type]++; });

        tabs.forEach(function (b) {
            b.textContent = b.dataset.label + ' (' + c[b.dataset.f] + ')';
            b.classList.toggle('is-on', b.dataset.f === filter);
        });

        var shown = 0;
        r.forEach(function (x) {
            var show = filter === 'all' || x.dataset.type === filter;
            x.style.display = show ? '' : 'none';
            if (show) shown++;
        });
        $('aprEmpty').style.display = shown ? 'none' : '';

        var sub = document.querySelector('.topbar-sub');
        if (sub) sub.textContent = sub.textContent.replace(/^\d+ approvals?/, c.all + ' approval' + (c.all === 1 ? '' : 's'));
    }

    tabs.forEach(function (b) {
        b.addEventListener('click', function () { filter = b.dataset.f; recount(); });
    });

    $('aprList').addEventListener('click', function (e) {
        var btn = e.target.closest('[data-act]');
        if (!btn) return;
        var row = btn.closest('.approvalsto'), act = btn.dataset.act;

        if (act === 'view') { window.location.href = row.dataset.href; return; }
        if (act === 'reject' && !confirm('Reject this and notify the tenant?')) return;

        btn.disabled = true;
        api(APPROVALS_URL + '/' + row.dataset.id + '/' + act, 'POST')
            .then(function () {
                row.remove();
                recount();
                toast(act === 'approve' ? 'Approved. The tenant was notified.' : 'Rejected. The tenant was notified.');
            })
            .catch(function (err) { btn.disabled = false; toast(err.message, true); });
    });
    recount();

    /* ---------- announcements: new / edit / delete ---------- */
    var list = $('annList'), form = $('annForm');
    var title = $('annTitle'), audience = $('annAudience'), body = $('annBody'), postBtn = $('annPost');
    var editId = null;

    function showForm(on) {
        form.hidden = !on;
        list.hidden = on;
        if (on) title.focus();
    }
    function reset() {
        title.value = ''; body.value = ''; audience.selectedIndex = 0;
        editId = null; postBtn.textContent = 'Post';
    }
    function checkEmpty() {
        $('annEmpty').style.display = list.querySelector('.annitem') ? 'none' : '';
    }
    function build(a) {
        var el = document.createElement('div');
        el.className = 'annitem';
        el.dataset.id = a.id;
        el.innerHTML =
            '<div class="needtuwid"><h1></h1><div class="belognapulanamyhalo"><h1></h1></div></div>' +
            '<h1 class="schedyle"></h1>' +
            '<div class="needtuwid2"><h1></h1><div class="editdeltet32">' +
            '<h1 class="editdeletetetete" data-ann="edit">Edit</h1>' +
            '<h1 class="editdeletetetete" data-ann="delete">Delete</h1></div></div>';
        el.querySelector('.needtuwid > h1').textContent = a.title;
        el.querySelector('.belognapulanamyhalo h1').textContent = a.audience;
        el.querySelector('.schedyle').textContent = a.message;
        el.querySelector('.needtuwid2 > h1').textContent = 'Posted ' + a.ago;
        return el;
    }

    $('annNewBtn').addEventListener('click', function () { reset(); showForm(true); });
    $('annCancel').addEventListener('click', function () { reset(); showForm(false); });

    postBtn.addEventListener('click', function () {
        var t = title.value.trim(), b = body.value.trim();
        if (!t) { title.focus(); return; }
        if (!b) { body.focus(); return; }

        var wasEdit = editId;
        postBtn.disabled = true;
        var payload = { title: t, audience: audience.value, message: b };
        var req = wasEdit ? api(ANN_URL + '/' + wasEdit, 'PUT', payload) : api(ANN_URL, 'POST', payload);

        req.then(function (a) {
            var el = build(a);
            if (wasEdit) {
                var old = list.querySelector('.annitem[data-id="' + wasEdit + '"]');
                if (old) old.replaceWith(el); else list.prepend(el);
            } else {
                list.prepend(el);
            }
            reset(); showForm(false); checkEmpty();
            toast(wasEdit ? 'Announcement updated.' : 'Announcement posted.');
        })
        .catch(function (err) { toast(err.message, true); })
        .then(function () { postBtn.disabled = false; });
    });

    list.addEventListener('click', function (e) {
        var t = e.target.closest('[data-ann]');
        if (!t) return;
        var item = t.closest('.annitem'), id = item.dataset.id;

        if (t.dataset.ann === 'delete') {
            if (!confirm('Delete this announcement?')) return;
            api(ANN_URL + '/' + id, 'DELETE')
                .then(function () { item.remove(); checkEmpty(); toast('Announcement deleted.'); })
                .catch(function (err) { toast(err.message, true); });
        } else {
            editId = id;
            title.value = item.querySelector('.needtuwid > h1').textContent.trim();
            audience.value = item.querySelector('.belognapulanamyhalo h1').textContent.trim();
            body.value = item.querySelector('.schedyle').textContent.trim();
            postBtn.textContent = 'Save';
            showForm(true);
        }
    });
    checkEmpty();

    /* ---------- complaints: Message opens the chatbox ---------- */
    document.addEventListener('click', function (e) {
        var b = e.target.closest('.pangmessagetenant');
        if (!b) return;
        if (!window.DaceChat) { toast('Chat is not ready. Refresh the page.', true); return; }
        window.DaceChat.open({
            kind: 'complaint', item_id: b.dataset.id, with_id: 0,
            with_name: b.dataset.name, with_room: b.dataset.room,
            item_title: b.dataset.title, item_price: b.dataset.msg, photo: ''
        });
    });
})();
</script>
@endpush