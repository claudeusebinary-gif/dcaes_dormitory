@extends('layouts.app')

@section('title', 'Payments')

@section('content')
<button id="makePaymentBtn" class="roomlist-btn" style="border: 1px solid #672C2C; cursor: pointer;">Make a payment</button>
    <h1 class="tit">Payments</h1>
     <h1 class="ba">Complete your payment here</h1>
     <div class="upatnaapatbox">
    <div class="upatnaapatbox1 clickcard" data-filter="overdue" role="button" tabindex="0" title="View balance due">
            <div class="currentbal">
                <h1>Current balance</h1>
                <div class="balcurrent"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
    <path d="M20 7H5a2 2 0 0 1 0-4h13a2 2 0 0 1 2 2v2z" />
    <path d="M3 5v14a2 2 0 0 0 2 2h15a1 1 0 0 0 1-1v-4" />
    <path d="M21 12v4h-4a2 2 0 0 1 0-4h4z" />
</svg></div>
            </div>
            <h1 class="perapera">{{ $billing['balance_text'] }}</h1>
            <div class="duevoice"><span>{{ $billing['balance_sub'] }}</span></div>
        </div>
       <div class="upatnaapatbox1 clickcard" data-filter="overdue" role="button" tabindex="0" title="View overdue invoices"> <div class="currentbal">
                <h1>Overdue</h1>
                <div class="balcurrent"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
    <circle cx="12" cy="12" r="10" />
    <line x1="12" y1="7" x2="12" y2="13" />
    <line x1="12" y1="17" x2="12.01" y2="17" />
</svg>
</div>
            </div>
            <h1 class="perapera">{{ $billing['overdue_text'] }}</h1>
            <div class="duevoice"><span>{{ $billing['overdue_sub'] }}</span></div></div>
      <div class="upatnaapatbox1 clickcard" data-filter="paid" role="button" tabindex="0" title="View paid invoices"> <div class="currentbal">
                <h1>Paid this year</h1>
                <div class="balcurrent"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
    <rect x="3" y="4" width="18" height="18" rx="2" />
    <line x1="16" y1="2" x2="16" y2="6" />
    <line x1="8" y1="2" x2="8" y2="6" />
    <line x1="3" y1="10" x2="21" y2="10" />
    <polyline points="9 16 11 18 15 14" />
</svg></div>
            </div>
            <h1 class="perapera">{{ $billing['paid_text'] }}</h1>
            <div class="duevoice"><span>{{ $billing['paid_sub'] }}</span></div></div>
        <div class="upatnaapatbox1"> <div class="currentbal">
                <h1>Autopay</h1>
                <div class="balcurrent"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
    <polyline points="17 1 21 5 17 9" />
    <path d="M3 11V9a4 4 0 0 1 4-4h14" />
    <polyline points="7 23 3 19 7 15" />
    <path d="M21 13v2a4 4 0 0 1-4 4H3" />
</svg></div>
            </div>
            <h1 class="perapera">On</h1>
            <div class="duevoice"><span id="autopayMethod">Charges GCash on due date</span></div></div>
     </div>
     <div class="invoice">
<div class="invoice1">
   
   <div class="invoiceboss"><div class="invoss" id="invoicesHeader" data-filter="all" role="button" tabindex="0" title="Show all invoices">
        <div class="invoss1">
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
    <path d="M4 2v20l3-2 3 2 2-2 2 2 3-2 3 2V2l-3 2-3-2-2 2-2-2-3 2z" />
    <line x1="8" y1="9" x2="16" y2="9" />
    <line x1="8" y1="13" x2="14" y2="13" />
</svg></div>
        <div class="ibosses"><h1>Invoices</h1>
        <span>{{ $billing['room'] ? 'Room ' . $billing['room'] . ' Monthly rent' : 'No room yet' }}</span>
        </div>
    </div>
<div class="fourinvoice" role="tablist">
            <div class="fourinvoice1 active" data-filter="all" role="tab" tabindex="0"><h1>All</h1></div>
            <div class="fourinvoice1" data-filter="pending" role="tab" tabindex="0"><h1>Pending</h1></div>
            <div class="fourinvoice1" data-filter="paid" role="tab" tabindex="0"><h1>Paid</h1></div>
            <div class="fourinvoice1" data-filter="overdue" role="tab" tabindex="0"><h1>Overdue</h1></div>
        </div>
    </div>
         <div class="finalinvoice">
          @foreach ($billing['invoices'] as $inv)
<div class="ibossboss">  <div class="invoss3">
        <div class="invoss13" style="{{ $inv['color'] }}">
            @if ($inv['status'] === 'paid')
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="20 6 9 17 4 12" /></svg>
            @elseif ($inv['status'] === 'overdue')
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10" /><line x1="12" y1="7" x2="12" y2="13" /><line x1="12" y1="17" x2="12.01" y2="17" /></svg>
            @else
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10" /><polyline points="12 6 12 12 16 14" /></svg>
            @endif
        </div>
        <div class="ibosses3"><h1>{{ $inv['label'] }} — Room {{ $billing['room'] }}</h1>
        <span>· Due {{ $inv['due_text'] }}</span>
        </div>
    </div>
    <div class="ungprice">
        <h1>₱{{ number_format($inv['amount'], 2) }}</h1>
        <div class="lubidog" style="{{ $inv['color'] }}">
            <h1>{{ ucfirst($inv['status']) }}</h1>
        </div>
        <div class="lubidog1"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
            <polyline points="7 10 12 15 17 10" />
            <line x1="12" y1="15" x2="12" y2="3" />
        </svg></div>
    </div></div>
   <hr class="padiretso231">
@endforeach
  
                 <div class="inv-empty" id="invEmpty" hidden>No invoices to show here.</div>
         </div>
         <a class="rightarrow" href="{{ route('tenant.settings') }}#payments">
            <h1 class="viewfullpay">View full payment history</h1>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
    <polyline points="9 18 15 12 9 6" />
</svg>
         </a>
       
   
</div>  
<div class="invoice2">
    <div class="invoss34">
        <div class="invoss134"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
    <rect x="3" y="5" width="18" height="14" rx="2" />
    <line x1="3" y1="10" x2="21" y2="10" />
    <line x1="7" y1="15" x2="11" y2="15" />
</svg></div>
        <div class="ibosses34"><h1>Payment method</h1>
        </div>
    </div>
    <div class="tranparentboss">  <div class="tranparent method" data-method="GCash" role="button" tabindex="0">
        <div class="invoss347">
        <div class="invoss1347"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
    <rect x="6" y="2" width="12" height="20" rx="2" />
    <line x1="12" y1="18" x2="12.01" y2="18" />
</svg></div>
        <div class="ibosses347"><h1>GCash</h1>
        <span>•••• 4821</span>
        </div>
        
    </div>
    <div class="lubidog43">
            <h1>Dafault</h1>
        </div>
    </div>
 <div class="tranparent method" data-method="Debit / Credit" data-unavailable="true" role="button" tabindex="0">
        <div class="invoss347">
        <div class="invoss1347"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
    <rect x="3" y="5" width="18" height="14" rx="2" />
    <line x1="3" y1="10" x2="21" y2="10" />
    <line x1="7" y1="15" x2="11" y2="15" />
</svg></div>
        <div class="ibosses347"><h1>Debit / Credit</h1>
        <span>•••• 4821</span>
        </div>
        
    </div>
    </div>
 <div class="tranparent method" data-method="Bank Transfer" data-unavailable="true" role="button" tabindex="0">
        <div class="invoss347">
        <div class="invoss1347"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
    <line x1="3" y1="21" x2="21" y2="21" />
    <path d="M5 21V10l7-5 7 5v11" />
    <line x1="9" y1="21" x2="9" y2="13" />
    <line x1="15" y1="21" x2="15" y2="13" />
</svg></div>
        <div class="ibosses347"><h1>Bank Transfer</h1>
        <span>•••• 4821</span>
        </div>
        
    </div>
    </div></div>
  <button class="addpaymo"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
    <line x1="12" y1="5" x2="12" y2="19" />
    <line x1="5" y1="12" x2="19" y2="12" />
</svg>Add payment method</button>
    
   <div class="autopayrow">
    <div class="ibosses39">
        <h1>Autopay</h1>
        <span>Auto charge default method on due date</span>
    </div>

    <label class="toggle39">
        <input type="checkbox" id="autopayToggle" checked>
        <span class="toggle39-track" aria-hidden="true"></span>
        <span class="sr-only39">Autopay</span>
    </label>
</div>
</div>
     </div>
     <div class="unavail-overlay" id="unavailModal" hidden>
    <div class="unavail-box" role="dialog" aria-modal="true" aria-labelledby="unavailTitle">
        <div class="unavail-icon">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <circle cx="12" cy="12" r="10" />
                <line x1="12" y1="7" x2="12" y2="13" />
                <line x1="12" y1="17" x2="12.01" y2="17" />
            </svg>
        </div>
        <h2 id="unavailTitle">Not available yet</h2>
        <p><span id="unavailName">This payment method</span> is not available yet. Please use GCash for now.</p>
        <button type="button" class="unavail-btn" id="unavailClose">Okay</button>
    </div>
</div>
<div class="pay-overlay" id="payModal" hidden>
    <div class="pay-box" role="dialog" aria-modal="true">
        <span class="pay-corner" aria-hidden="true"></span>

        <button type="button" class="pay-close" data-pay-close aria-label="Close">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" aria-hidden="true">
                <path d="M6 6l12 12M18 6L6 18" />
            </svg>
        </button>

      <div class="invoss3479">
        <div class="invoss13479"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
    <path d="M4 2v20l3-2 3 2 2-2 2 2 3-2 3 2V2l-3 2-3-2-2 2-2-2-3 2z" />
    <line x1="8" y1="9" x2="16" y2="9" />
    <line x1="8" y1="13" x2="14" y2="13" />
</svg></div>
        <div class="ibosses3479"><h1>Select invoices</h1>
        <span>Room 100 · Montly rent</span>
        </div>
        
    </div>
    <hr class="padiretso23159">
    </div>
</div>
@endsection
@push('scripts')

<script>
document.addEventListener('DOMContentLoaded', function () {
    var txt = function (el) { return (el.textContent || '').trim().toLowerCase(); };

    var tabs = Array.prototype.slice.call(document.querySelectorAll('.fourinvoice1'));
    var rows = Array.prototype.slice.call(document.querySelectorAll('.ibossboss'));
    var list = document.querySelector('.finalinvoice');

    var empty = document.createElement('div');
    empty.textContent = 'No invoices to show here.';
    empty.style.cssText = 'display:none;text-align:center;padding:50px 0;font-weight:700;color:#00000086;font-family:Nunito Sans,sans-serif';
    if (list) list.appendChild(empty);

    function statusOf(row) {
        var badge = row.querySelector('.lubidog h1');
        return badge ? txt(badge) : '';
    }

    function applyFilter(filter) {
        var shown = 0;
        rows.forEach(function (row) {
            var show = (filter === 'all') || (filter === 'unpaid' && statusOf(row) !== 'paid') || statusOf(row) === filter;
            var hr = row.nextElementSibling;
            row.style.display = show ? '' : 'none';
            if (hr && hr.tagName === 'HR') hr.style.display = show ? '' : 'none';
            if (show) shown++;
        });
        tabs.forEach(function (t) {
            var on = txt(t) === filter;
            t.style.border          = on ? '1px solid #672C2C' : '';
            t.style.backgroundColor = on ? '#672c2c2f' : '';
        });
        empty.style.display = shown ? 'none' : 'block';
    }

    function bind(el, filter, scroll) {
        el.style.cursor = 'pointer';
        el.addEventListener('click', function () {
            applyFilter(filter);
            if (scroll) {
                var box = document.querySelector('.invoice1');
                if (box && box.scrollIntoView) box.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }
        });
    }

    tabs.forEach(function (t) { bind(t, txt(t), false); });

    document.querySelectorAll('.upatnaapatbox1').forEach(function (card) {
        var h = card.querySelector('.currentbal h1');
        if (!h) return;
        var name = txt(h);
        if (name === 'current balance') bind(card, 'unpaid', true);
        if (name === 'overdue')         bind(card, 'overdue', true);
        if (name === 'paid this year') bind(card, 'paid', true);
    });

    var header = document.querySelector('.invoss');
    if (header) bind(header, 'all', false);

    applyFilter('all');

   
    var overlay = document.createElement('div');
    overlay.style.cssText = 'display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,.45);align-items:center;justify-content:center;padding:20px;';
    overlay.innerHTML =
        '<div style="background:#fff;border-radius:12px;padding:30px;width:100%;max-width:360px;text-align:center;font-family:Nunito Sans,sans-serif;box-shadow:0 10px 40px rgba(0,0,0,.25)">' +
          '<h2 style="margin:0 0 8px;font-size:1.2rem;font-weight:800;color:#000">Not available yet</h2>' +
          '<p style="margin:0 0 20px;font-size:.95rem;font-weight:600;color:#00000099;line-height:1.4"><span id="naName"></span> is not available yet. Please use GCash for now.</p>' +
          '<button type="button" id="naClose" style="background:#672C2C;color:#fff;border:0;border-radius:6px;padding:10px 28px;font-size:.95rem;font-weight:800;cursor:pointer">Okay</button>' +
        '</div>';
    document.body.appendChild(overlay);

    function closePopup() { overlay.style.display = 'none'; }
    overlay.addEventListener('click', function (e) { if (e.target === overlay) closePopup(); });
    overlay.querySelector('#naClose').addEventListener('click', closePopup);
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closePopup(); });

    document.querySelectorAll('.tranparent').forEach(function (box) {
        var h = box.querySelector('.ibosses347 h1');
        if (!h) return;
        var name = h.textContent.trim();
        box.style.cursor = 'pointer';
        box.addEventListener('click', function () {
            if (txt(h) === 'gcash') return;         
            overlay.querySelector('#naName').textContent = name;
            overlay.style.display = 'flex';
        });
    });
        
    var payModal = document.getElementById('payModal');
    var payBtn   = document.getElementById('makePaymentBtn');

    function openPay()  { payModal.hidden = false; }
    function closePay() { payModal.hidden = true; }

    payBtn.addEventListener('click', openPay);

    payModal.addEventListener('click', function (e) {
        if (e.target === payModal || e.target.closest('[data-pay-close]')) closePay();
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && !payModal.hidden) closePay();
    });
});
</script>
@endpush
