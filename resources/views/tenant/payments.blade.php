@extends('layouts.app')

@section('title', 'Payments')

@section('content')
    @section('page_title', 'Payments')
@section('page_subtitle', 'Complete your payment here')
@section('page_action')
    <button id="makePaymentBtn" class="roomlist-btn" style="border: 1px solid #672C2C; cursor: pointer;">Make a payment</button>
@endsection
    <div class="upatnaapatbox">
        <div class="upatnaapatbox1 clickcard" data-filter="overdue" role="button" tabindex="0" title="View balance due">
            <div class="currentbal">
                <h1>Current balance</h1>
                <div class="balcurrent"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        aria-hidden="true">
                        <path d="M20 7H5a2 2 0 0 1 0-4h13a2 2 0 0 1 2 2v2z" />
                        <path d="M3 5v14a2 2 0 0 0 2 2h15a1 1 0 0 0 1-1v-4" />
                        <path d="M21 12v4h-4a2 2 0 0 1 0-4h4z" />
                    </svg></div>
            </div>
            <h1 class="perapera">{{ $billing['balance_text'] }}</h1>
            <div class="duevoice"><span>{{ $billing['balance_sub'] }}</span></div>
        </div>
        <div class="upatnaapatbox1 clickcard" data-filter="overdue" role="button" tabindex="0"
            title="View overdue invoices">
            <div class="currentbal">
                <h1>Overdue</h1>
                <div class="balcurrent"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        aria-hidden="true">
                        <circle cx="12" cy="12" r="10" />
                        <line x1="12" y1="7" x2="12" y2="13" />
                        <line x1="12" y1="17" x2="12.01" y2="17" />
                    </svg>
                </div>
            </div>
            <h1 class="perapera">{{ $billing['overdue_text'] }}</h1>
            <div class="duevoice"><span>{{ $billing['overdue_sub'] }}</span></div>
        </div>
        <div class="upatnaapatbox1 clickcard" data-filter="paid" role="button" tabindex="0" title="View paid invoices">
            <div class="currentbal">
                <h1>Paid this year</h1>
                <div class="balcurrent"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        aria-hidden="true">
                        <rect x="3" y="4" width="18" height="18" rx="2" />
                        <line x1="16" y1="2" x2="16" y2="6" />
                        <line x1="8" y1="2" x2="8" y2="6" />
                        <line x1="3" y1="10" x2="21" y2="10" />
                        <polyline points="9 16 11 18 15 14" />
                    </svg></div>
            </div>
            <h1 class="perapera">{{ $billing['paid_text'] }}</h1>
            <div class="duevoice"><span>{{ $billing['paid_sub'] }}</span></div>
        </div>
        <div class="upatnaapatbox1">
            <div class="currentbal">
                <h1>Autopay</h1>
                <div class="balcurrent"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        aria-hidden="true">
                        <polyline points="17 1 21 5 17 9" />
                        <path d="M3 11V9a4 4 0 0 1 4-4h14" />
                        <polyline points="7 23 3 19 7 15" />
                        <path d="M21 13v2a4 4 0 0 1-4 4H3" />
                    </svg></div>
            </div>
            <h1 class="perapera">On</h1>
            <div class="duevoice"><span id="autopayMethod">Charges GCash on due date</span></div>
        </div>
    </div>
    <div class="invoice">
        <div class="invoice1">

            <div class="invoiceboss">
                <div class="invoss" id="invoicesHeader" data-filter="all" role="button" tabindex="0"
                    title="Show all invoices">
                    <div class="invoss1">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M4 2v20l3-2 3 2 2-2 2 2 3-2 3 2V2l-3 2-3-2-2 2-2-2-3 2z" />
                            <line x1="8" y1="9" x2="16" y2="9" />
                            <line x1="8" y1="13" x2="14" y2="13" />
                        </svg>
                    </div>
                    <div class="ibosses">
                        <h1>Invoices</h1>
                        <span>{{ $billing['room'] ? 'Room ' . $billing['room'] . ' Monthly rent' : 'No room yet' }}</span>
                    </div>
                </div>
                <div class="fourinvoice" role="tablist">
                    <div class="fourinvoice1 active" data-filter="all" role="tab" tabindex="0">
                        <h1>All</h1>
                    </div>
                    <div class="fourinvoice1" data-filter="pending" role="tab" tabindex="0">
                        <h1>Pending</h1>
                    </div>
                    <div class="fourinvoice1" data-filter="paid" role="tab" tabindex="0">
                        <h1>Paid</h1>
                    </div>
                    <div class="fourinvoice1" data-filter="overdue" role="tab" tabindex="0">
                        <h1>Overdue</h1>
                    </div>
                </div>
            </div>
            <div class="finalinvoice">
                @foreach ($billing['invoices'] as $inv)
                    <div class="ibossboss">
                        <div class="invoss3">
                            <div class="invoss13" style="{{ $inv['color'] }}">
                                @if ($inv['status'] === 'paid')
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <polyline points="20 6 9 17 4 12" />
                                    </svg>
                                @elseif ($inv['status'] === 'overdue')
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <circle cx="12" cy="12" r="10" />
                                        <line x1="12" y1="7" x2="12" y2="13" />
                                        <line x1="12" y1="17" x2="12.01" y2="17" />
                                    </svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <circle cx="12" cy="12" r="10" />
                                        <polyline points="12 6 12 12 16 14" />
                                    </svg>
                                @endif
                            </div>
                            <div class="ibosses3">
                                <h1>{{ $inv['label'] }} — Room {{ $billing['room'] }}</h1>
                                <span>· Due {{ $inv['due_text'] }}</span>
                            </div>
                        </div>
                        <div class="ungprice">
                            <h1>₱{{ number_format($inv['amount'], 2) }}</h1>
                            <div class="lubidog" style="{{ $inv['color'] }}">
                                <h1>{{ ucfirst($inv['status']) }}</h1>
                            </div>
                            <div class="lubidog1"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    aria-hidden="true">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                    <polyline points="7 10 12 15 17 10" />
                                    <line x1="12" y1="15" x2="12" y2="3" />
                                </svg></div>
                        </div>
                    </div>
                    <hr class="padiretso231">
                @endforeach

                <div class="inv-empty" id="invEmpty" hidden>No invoices to show here.</div>
            </div>
            <div class="ann-pager" id="invPager"></div>
            <a class="rightarrow" href="{{ route('tenant.settings') }}#billing">
                <h1 class="viewfullpay">View full payment history</h1>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <polyline points="9 18 15 12 9 6" />
                </svg>
            </a>


        </div>
        <div class="invoice2">
            <div class="invoss34">
                <div class="invoss134"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        aria-hidden="true">
                        <rect x="3" y="5" width="18" height="14" rx="2" />
                        <line x1="3" y1="10" x2="21" y2="10" />
                        <line x1="7" y1="15" x2="11" y2="15" />
                    </svg></div>
                <div class="ibosses34">
                    <h1>Payment method</h1>
                </div>
            </div>
            <div class="tranparentboss">
                <div class="tranparent method" data-method="GCash" role="button" tabindex="0">
                    <div class="invoss347">
                        <div class="invoss1347"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                aria-hidden="true">
                                <rect x="6" y="2" width="12" height="20" rx="2" />
                                <line x1="12" y1="18" x2="12.01" y2="18" />
                            </svg></div>
                        <div class="ibosses347">
                            <h1>GCash</h1>
                            <span>•••• 4821</span>
                        </div>

                    </div>
                    <div class="lubidog43">
                        <h1>Dafault</h1>
                    </div>
                </div>
                <div class="tranparent method" data-method="Debit / Credit" data-unavailable="true" role="button"
                    tabindex="0">
                    <div class="invoss347">
                        <div class="invoss1347"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                aria-hidden="true">
                                <rect x="3" y="5" width="18" height="14" rx="2" />
                                <line x1="3" y1="10" x2="21" y2="10" />
                                <line x1="7" y1="15" x2="11" y2="15" />
                            </svg></div>
                        <div class="ibosses347">
                            <h1>Debit / Credit</h1>
                            <span>•••• 4821</span>
                        </div>

                    </div>
                </div>
                <div class="tranparent method" data-method="Bank Transfer" data-unavailable="true" role="button"
                    tabindex="0">
                    <div class="invoss347">
                        <div class="invoss1347"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                aria-hidden="true">
                                <line x1="3" y1="21" x2="21" y2="21" />
                                <path d="M5 21V10l7-5 7 5v11" />
                                <line x1="9" y1="21" x2="9" y2="13" />
                                <line x1="15" y1="21" x2="15" y2="13" />
                            </svg></div>
                        <div class="ibosses347">
                            <h1>Bank Transfer</h1>
                            <span>•••• 4821</span>
                        </div>

                    </div>
                </div>
            </div>
            <button class="addpaymo"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    aria-hidden="true">
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
          <p><span id="unavailName">This payment method</span> is not available right now. Please use GCash for now.</p>
            <button type="button" class="unavail-btn" id="unavailClose">Okay</button>
        </div>
    </div>
    <div class="pay-overlay" id="payModal" hidden>
        <div class="pay-box" role="dialog" aria-modal="true">
            <span class="pay-corner" aria-hidden="true"></span>

            <button type="button" class="pay-close" data-pay-close aria-label="Close">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                    aria-hidden="true">
                    <path d="M6 6l12 12M18 6L6 18" />
                </svg>
            </button>

            <div class="invoss3479">
                <div class="invoss13479"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        aria-hidden="true">
                        <path d="M4 2v20l3-2 3 2 2-2 2 2 3-2 3 2V2l-3 2-3-2-2 2-2-2-3 2z" />
                        <line x1="8" y1="9" x2="16" y2="9" />
                        <line x1="8" y1="13" x2="14" y2="13" />
                    </svg></div>
                <div class="ibosses3479">
                    <h1>Select invoices</h1>
                    <span>Room 100 · Montly rent</span>
                </div>

            </div>
            <hr class="padiretso23159">
            <div class="duhakagatos">
          
   @php
    $thisMonth = now()->format('Y-m');
    $nextMonth = now()->startOfMonth()->addMonth()->format('Y-m');

    $unpaid = array_values(array_filter(
        $billing['invoices'],
        fn ($i) => $i['status'] !== 'paid'
            && in_array($i['period'], [$thisMonth, $nextMonth], true)
    ));
@endphp

@forelse ($unpaid as $inv)
    <label class="duhakagatos1 invoice-pick" data-amount="{{ $inv['amount'] }}">
        <div class="tempo">
            <input type="checkbox" class="radi invoice-check" value="{{ $inv['period'] }}">
            <div class="ibosses34791">
                <h1>{{ $inv['label'] }}</h1>
                <span>Due {{ $inv['paid_on'] }}</span>
            </div>
        </div>
        <div class="samodapid">
            <div class="pidbayad {{ $inv['status'] }}">
                <h1>{{ ucfirst($inv['status']) }}</h1>
            </div>
            <h1 class="samodal">₱{{ number_format($inv['amount'], 2) }}</h1>
        </div>
    </label>
@empty
    <p style="text-align:center;font-weight:700;color:#00000086;">No unpaid invoices.</p>
@endforelse

<hr class="padiretso231591">
<div class="totalinvoice12">
    <h1 id="totalLabel">Total (0 invoices)</h1>
    <span id="totalAmount">₱0.00</span>
</div>
<button id="contopay" class="contopay" disabled>Continue to payment</button>
            </div>
        </div>

    </div>
    <div class="pay-overlay" id="payModal2" hidden>
    <div class="pay-box" role="dialog" aria-modal="true" style="min-width: 720px; min-height: 555px;">
        <span class="pay-corner" aria-hidden="true"></span>

        <button type="button" class="pay-back" data-pay2-close aria-label="Go back">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M15 18l-6-6 6-6" />
            </svg>
        </button>

        <button type="button" class="pay-close" data-pay2-close aria-label="Close">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" aria-hidden="true">
                <path d="M6 6l12 12M18 6L6 18" />
            </svg>
        </button>
<div class="invoss34791">
                <div class="invoss13479"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        aria-hidden="true">
                        <path d="M4 2v20l3-2 3 2 2-2 2 2 3-2 3 2V2l-3 2-3-2-2 2-2-2-3 2z" />
                        <line x1="8" y1="9" x2="16" y2="9" />
                        <line x1="8" y1="13" x2="14" y2="13" />
                    </svg></div>
                <div class="ibosses3479">
                    <h1>Choose payment method</h1>
                    <span>Room 100 · Montly rent</span>
                </div>

            </div>
            <hr class="padiretso23159">
               <div class="duhakagatos">
               <label class="duhakagatos1 method-pick selected" data-method="GCash" data-available="true">
    <div class="tempo">
        <input type="checkbox" class="radi" checked>
        <div class="modalboxesss">
         <div class="modalboxesss">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <rect x="6" y="2" width="12" height="20" rx="2" />
        <line x1="12" y1="18" x2="12.01" y2="18" />
    </svg>
</div>
        </div>
        <div class="ibosses34791"><h1>GCash</h1><span>•••• 4821</span></div>
    </div>
    <div class="samodapid"><div class="difults"><h1>default</h1></div></div>
</label>

<label class="duhakagatos1 method-pick" data-method="Debit / Credit" data-available="false">
    <div class="tempo">
        <input type="checkbox" class="radi">
        <div class="modalboxesss">
            <div class="modalboxesss">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <rect x="3" y="5" width="18" height="14" rx="2" />
        <line x1="3" y1="10" x2="21" y2="10" />
        <line x1="7" y1="15" x2="11" y2="15" />
    </svg>
</div>
        </div>
        <div class="ibosses34791"><h1>Debit / Credit</h1><span>•••• 4821</span></div>
    </div>
</label>

<label class="duhakagatos1 method-pick" data-method="Bank Transfer" data-available="false">
    <div class="tempo">
        <input type="checkbox" class="radi">
        <div class="modalboxesss">
           <div class="modalboxesss">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <line x1="3" y1="21" x2="21" y2="21" />
        <path d="M5 21V10l7-5 7 5v11" />
        <line x1="9" y1="21" x2="9" y2="13" />
        <line x1="15" y1="21" x2="15" y2="13" />
    </svg>
</div>
        </div>
        <div class="ibosses34791"><h1>Bank Transfer</h1><span>•••• 4821</span></div>
    </div>
</label>

<div class="encri1">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <path d="M12 2 4 5v6c0 5 3.4 9.4 8 11 4.6-1.6 8-6 8-11V5l-8-3z" />
        <rect x="9" y="11" width="6" height="5" rx="1" />
        <path d="M10.5 11V9.5a1.5 1.5 0 0 1 3 0V11" />
    </svg>
    <h1 class="encri">Payments are encrypted and processed securely.</h1>
</div>

<hr class="padiretso2315912">

<div class="totalinvoice12" style="gap:490px;">
    <h1 id="methodLabel">Total (Via GCash)</h1>
    <span id="methodTotal">₱0.00</span>
</div>
<button id="payNowBtn" class="contopay">Pay ₱0.00 Now</button>
            </div>
       
    </div>
</div>
<div class="pay-overlay" id="payModal3" hidden>
    <div class="pay-box" role="dialog" aria-modal="true" style="display:flex; align-items: center; justify-content:center; min-height: 300px; flex-direction: column; gap: 5px;">
        <span class="pay-corner" aria-hidden="true"></span>


        <div class="logbi21"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
    <circle cx="12" cy="12" r="10" />
    <path d="m9 12 2 2 4-4" />
</svg></div>
<h2 class="paymentsuc">Payment Successful</h2>
<h1 class="paymentsuc1" id="successText">₱0.00 paid via GCash</h1>
<span class="paymentsuc2">A receipt has been sent to your account</span>
        <button type="button" id="doneBtn" class="contopay1">Done</button>
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
            var PER_PAGE = 4;    
var page  = 1;
var pager = document.getElementById('invPager');

function renderPager(filter, pages, count, from, shown) {
    pager.innerHTML = '';
    if (pages <= 1) return;                   

    function btn(label, target, disabled, current) {
        var b = document.createElement('button');
        b.type = 'button';
        b.textContent = label;
        if (current) b.className = 'current';
        b.disabled = !!disabled;
        b.addEventListener('click', function () { page = target; applyFilter(filter, true); });
        pager.appendChild(b);
    }

    btn('‹ Prev', page - 1, page === 1, false);
    for (var p = 1; p <= pages; p++) btn(String(p), p, false, p === page);
    btn('Next ›', page + 1, page === pages, false);

    var info = document.createElement('span');
    info.textContent = 'Showing ' + (from + 1) + '–' + (from + shown) + ' of ' + count;
    pager.appendChild(info);
}

function applyFilter(filter, keepPage) {
    if (!keepPage) page = 1;

    var matches = rows.filter(function (row) {
        return (filter === 'all') || (filter === 'unpaid' && statusOf(row) !== 'paid') || statusOf(row) === filter;
    });

    var pages = Math.max(1, Math.ceil(matches.length / PER_PAGE));
    if (page > pages) page = pages;
    var from = (page - 1) * PER_PAGE;

    rows.forEach(function (row) {
        var i    = matches.indexOf(row);
        var show = i >= from && i < from + PER_PAGE;
        var hr   = row.nextElementSibling;
        row.style.display = show ? '' : 'none';
        if (hr && hr.tagName === 'HR') hr.style.display = show ? '' : 'none';
    });

    tabs.forEach(function (t) {
        var on = txt(t) === filter;
        t.style.border = on ? '1px solid #672C2C' : '';
        t.style.backgroundColor = on ? '#672c2c2f' : '';
    });

    empty.style.display = matches.length ? 'none' : 'block';
    renderPager(filter, pages, matches.length, from, Math.min(PER_PAGE, matches.length - from));
}
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
            if (name === 'overdue') bind(card, 'overdue', true);
            if (name === 'paid this year') bind(card, 'paid', true);
        });

        var header = document.querySelector('.invoss');
        if (header) bind(header, 'all', false);

        applyFilter('all');


        var overlay = document.createElement('div');
        overlay.style.cssText = 'display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,.45);align-items:center;justify-content:center;padding:20px;';
        overlay.innerHTML =
            '<div style="background:#fff;border-radius:12px;padding:30px;width:100%;max-width:360px;text-align:center;font-family:Nunito Sans,sans-serif;box-shadow:0 10px 40px rgba(0,0,0,.25)">' +
           '<h2 id="naTitle" style="margin:0 0 8px;font-size:1.2rem;font-weight:800;color:#000"></h2>' +
'<p id="naMsg" style="margin:0 0 20px;font-size:.95rem;font-weight:600;color:#00000099;line-height:1.4"></p>' +
            '<button type="button" id="naClose" style="background:#672C2C;color:#fff;border:0;border-radius:6px;padding:10px 28px;font-size:.95rem;font-weight:800;cursor:pointer">Okay</button>' +
            '</div>';
        document.body.appendChild(overlay);
function showNA(title, msg) {
    overlay.querySelector('#naTitle').textContent = title;
    overlay.querySelector('#naMsg').textContent = msg;
    overlay.style.display = 'flex';
}
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
               showNA('Not available yet', name + ' is not available right now. Please use GCash for now.');
            });
        });

var addBtn = document.querySelector('.addpaymo');
if (addBtn) {
    addBtn.addEventListener('click', function () {
        showNA('Cannot add right now', "You can't add a payment method right now. Please try again later.");
    });
}
      

        var money = function (n) {
            return '₱' + n.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        };

        var payModal    = document.getElementById('payModal');
        var payModal2   = document.getElementById('payModal2');
        var payModal3   = document.getElementById('payModal3');
        var payBtn      = document.getElementById('makePaymentBtn');
        var contopayBtn = document.getElementById('contopay');
        var payNowBtn   = document.getElementById('payNowBtn');
        var doneBtn     = document.getElementById('doneBtn');
        var totalLabel  = document.getElementById('totalLabel');
        var totalAmount = document.getElementById('totalAmount');
        var methodLabel = document.getElementById('methodLabel');
        var methodTotal = document.getElementById('methodTotal');
        var successText = document.getElementById('successText');
        var unavailModal = document.getElementById('unavailModal');

        var checks     = document.querySelectorAll('#payModal .invoice-check');
        var methodRows = document.querySelectorAll('#payModal2 .method-pick');
        var selectedTotal = 0, selectedCount = 0, method = 'GCash';
             
        function hideAnimated(el) {
            if (el.hidden || el.classList.contains('closing')) return;
            if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                el.hidden = true;
                return;
            }
            var finished = false;
            function end() {
                if (finished) return;
                finished = true;
                el.hidden = true;
                el.classList.remove('closing');
            }
            el.classList.add('closing');
            el.addEventListener('animationend', function h(e) {
                if (e.target !== el) return;          
                el.removeEventListener('animationend', h);
                end();
            });
            setTimeout(end, 400);                    
        }

     
        function updateTotal() {
            selectedTotal = 0;
            selectedCount = 0;
            checks.forEach(function (c) {
                var row = c.closest('.duhakagatos1');
                row.classList.toggle('selected', c.checked);
                if (c.checked) {
                    selectedCount++;
                    selectedTotal += parseFloat(row.dataset.amount);
                }
            });
            totalLabel.textContent = 'Total (' + selectedCount + ' invoice' + (selectedCount === 1 ? '' : 's') + ')';
            totalAmount.textContent = money(selectedTotal);
            contopayBtn.disabled = selectedCount === 0;   
        }
        checks.forEach(function (c) { c.addEventListener('change', updateTotal); });

        function openPay() {
            checks.forEach(function (c) { c.checked = false; });
            updateTotal();
            payModal.hidden = false;
        }
        function closePay() { payModal.hidden = true; }

        payBtn.addEventListener('click', openPay);
        payModal.addEventListener('click', function (e) {
            if (e.target === payModal || e.target.closest('[data-pay-close]')) closePay();
        });

        
        function openPay2() {
            if (selectedCount === 0) return;
            methodTotal.textContent = money(selectedTotal);
            payNowBtn.textContent = 'Pay ' + money(selectedTotal) + ' Now';
            payModal.hidden = true;
            payModal2.hidden = false;
        }
        function closePay2(goBack) {
            payModal2.hidden = true;
            if (goBack) payModal.hidden = false;
        }
        contopayBtn.addEventListener('click', openPay2);

        payModal2.addEventListener('click', function (e) {
            if (e.target === payModal2) closePay2(false);
            if (e.target.closest('.pay-back')) closePay2(true);
            if (e.target.closest('.pay-close')) closePay2(false);
        });

        methodRows.forEach(function (row) {
            var box = row.querySelector('input');
            box.addEventListener('click', function (e) {
                if (row.dataset.available !== 'true') {
                    e.preventDefault();                      
                    document.getElementById('unavailName').textContent = row.dataset.method;
                    unavailModal.hidden = false;
                    return;
                }
                method = row.dataset.method;
                methodRows.forEach(function (r) {
                    var on = (r === row);
                    r.classList.toggle('selected', on);
                    r.querySelector('input').checked = on;    
                });
                methodLabel.textContent = 'Total (Via ' + method + ')';
            });
        });
        document.getElementById('unavailClose').addEventListener('click', function () {
            unavailModal.hidden = true;
        });

        
       payNowBtn.addEventListener('click', function () {
    var periods = [];
    checks.forEach(function (c) { if (c.checked) periods.push(c.value); });

    payNowBtn.disabled = true;
    fetch("{{ route('tenant.payments.pay') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ periods: periods, method: method })
    })
    .then(function (r) { return r.json().then(function (d) { return { ok: r.ok, d: d }; }); })
    .then(function (res) {
        payNowBtn.disabled = false;
        if (!res.ok || !res.d.ok) {
            alert(res.d.message || 'Payment failed. Please try again.');
            return;
        }
        successText.textContent = money(selectedTotal) + ' paid via ' + method;
        payModal2.hidden = true;
        payModal3.hidden = false;
    })
    .catch(function () {
        payNowBtn.disabled = false;
        alert('Something went wrong. Please try again.');
    });
});
        doneBtn.addEventListener('click', function () {
            payModal3.hidden = true;
            window.location.href = "{{ route('tenant.payments') }}";
        });

       
        document.addEventListener('keydown', function (e) {
            if (e.key !== 'Escape') return;
            if (!unavailModal.hidden) unavailModal.hidden = true;
            else if (!payModal.hidden) closePay();
            else if (!payModal2.hidden) closePay2(false);
        });
    });
</script>
@endpush