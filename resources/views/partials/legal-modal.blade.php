<style>
    .legalmodal {
        position: fixed;
        inset: 0;
        z-index: 1400;
        background-color: rgba(0, 0, 0, 0.55);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 16px;
        box-sizing: border-box;
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
        transition: opacity .25s ease, visibility 0s linear .25s;
    }

    .legalmodal.is-open {
        opacity: 1;
        visibility: visible;
        pointer-events: auto;
        transition: opacity .25s ease, visibility 0s;
    }

    .legalbox {
        position: relative;
        width: 720px;
        max-width: 100%;
        max-height: 88vh;
        display: flex;
        flex-direction: column;
        background-color: #ffffff;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.25);
        transform: translateY(16px) scale(.97);
        transition: transform .25s ease;
        font-family: 'Nunito Sans', sans-serif;
    }

    .legalmodal.is-open .legalbox {
        transform: none;
    }

    .legalbox::before {
        content: "";
        position: absolute;
        top: -1px;
        left: 0;
        width: 60px;
        height: 60px;
        background-color: #672C2C;
        clip-path: polygon(0 0, 100% 0, 0 100%);
        pointer-events: none;
    }

    .legal-close {
        position: absolute;
        top: 16px;
        right: 16px;
        width: 32px;
        height: 32px;
        border: none;
        border-radius: 50%;
        background-color: transparent;
        color: #672C2C;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background-color .2s ease;
    }

    .legal-close:hover {
        background-color: #f0e8e3;
    }

    .legal-close svg {
        width: 20px;
        height: 20px;
    }

    .legalhead {
        padding: 28px 28px 0;
    }

    .legaltitle {
        margin: -1px 40px 14px 15px;
        font-family: 'Inria Serif', serif;
        font-weight: 700;
        font-size: 1.5rem;
        color: #672C2C;
    }

    .legaltabs {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-left: 15px;
        padding-bottom: 16px;
        border-bottom: 1px solid #0000000f;
    }

    .legaltab {
        height: 34px;
        padding: 0 16px;
        border-radius: 20px;
        background-color: #d9d9d960;
        border: 1px solid #00000011;
        color: #08080894;
        font-family: 'Nunito Sans', sans-serif;
        font-size: .8rem;
        font-weight: 700;
        cursor: pointer;
        transition: all .2s ease;
    }

    .legaltab:hover {
        background-color: #672c2c48;
        border-color: #672C2C;
        color: #672C2C;
    }

    .legaltab.active,
    .legaltab.active:hover {
        background-color: #672C2C;
        border-color: #672C2C;
        color: #fff;
    }

    .legalbody {
        flex: 1;
        overflow-y: auto;
        padding: 18px 28px 10px 43px;
    }

    .legalpanel[hidden] {
        display: none;
    }

    .legaldate {
        margin: 0 0 14px;
        font-size: .78rem;
        font-weight: 700;
        color: #00000080;
    }

    .legalpanel h2 {
        margin: 22px 0 6px;
        font-size: 1rem;
        font-weight: 800;
        color: #000;
    }

    .legalpanel p,
    .legalpanel li {
        font-size: .88rem;
        font-weight: 600;
        line-height: 1.6;
        color: #000000b8;
    }

    .legalpanel ul,
    .legalpanel ol {
        margin: 6px 0;
        padding-left: 22px;
    }

    .legalpanel li {
        margin-bottom: 5px;
    }

    .legalfaq {
        border-bottom: 1px solid #0000000f;
    }

    .legalfaq summary {
        cursor: pointer;
        padding: 11px 0;
        font-size: .92rem;
        font-weight: 800;
        color: #000;
    }

    .legalfaq summary:hover {
        color: #672C2C;
    }

    .legalfaq p {
        margin: 0 0 12px;
    }

    .legalfoot {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        padding: 16px 28px 22px;
        border-top: 1px solid #0000000f;
    }

    .legalbtn {
        height: 42px;
        padding: 0 28px;
        border: 1px solid #672C2C;
        border-radius: 6px;
        background-color: #672C2C;
        color: #fff;
        font-family: 'Nunito Sans', sans-serif;
        font-size: .85rem;
        font-weight: 800;
        cursor: pointer;
        transition: all .2s ease;
    }

    .legalbtn:hover {
        background-color: #672c2c00;
        color: #672C2C;
    }

    .legalbtn.ghost {
        background-color: transparent;
        color: #672C2C;
    }

     .legalbtn.ghost:hover {
        background-color: #f0e8e3;
    }

    .legalpanel[contenteditable="true"] {
        outline: 2px dashed #672C2C;
        outline-offset: 8px;
        border-radius: 4px;
    }

    .legalstatus {
        margin-right: auto;
        align-self: center;
        font-size: .85rem;
        font-weight: 700;
        color: #16a34a;
    }
</style>

<div class="legalmodal" id="legalModal" role="dialog" aria-modal="true" aria-hidden="true">
    <div class="legalbox">
        <button type="button" class="legal-close" data-legal-close aria-label="Close">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round" aria-hidden="true">
                <line x1="18" y1="6" x2="6" y2="18" />
                <line x1="6" y1="6" x2="18" y2="18" />
            </svg>
        </button>

        <div class="legalhead">
            <h1 class="legaltitle" id="legalTitle">Terms of Service</h1>
            <div class="legaltabs">
                <button type="button" class="legaltab active" data-tab="terms">Terms of Service</button>
                <button type="button" class="legaltab" data-tab="privacy">Privacy Policy</button>
                <button type="button" class="legaltab" data-tab="faqs">FAQs</button>
                <button type="button" class="legaltab" data-tab="rules">House Rules</button>
            </div>
        </div>

        <div class="legalbody" id="legalBody">

            <div class="legalpanel" data-panel="terms">
                <p class="legaldate">Terms of stay &middot; Last updated November 2, 2026</p>

                <h2>1. Acceptance of these terms</h2>
                <p>By creating an account, booking a bed, or staying at D'aces Dormitory, you agree to these Terms of
                    Service and to our Privacy Policy and House Rules. If you do not agree, please do not use the
                    website or book a bed.</p>

                <h2>2. Your account</h2>
                <ul>
                    <li>You must give true and complete information, including your name, contact details, and a valid
                        government-issued ID.</li>
                    <li>You are responsible for keeping your password private and for everything done through your
                        account.</li>
                    <li>Guests may browse the home page, dorm layout, rooms, and location, but only signed-in tenants
                        can book or manage a stay.</li>
                </ul>

                <h2>3. Booking and admin approval</h2>
                <ul>
                    <li>When you pick a bed, we hold it for you for 15 minutes while you complete the booking form.</li>
                    <li>A submitted booking is <b>not final</b>. The dormitory admin must review and approve it first.
                        Until then your booking shows as "waiting for admin approval" and the room does not appear in
                        your tenant details.</li>
                    <li>We may decline a booking, for example if the bed is no longer available or the information is
                        incomplete. If declined, the bed is released and you may book again.</li>
                </ul>

                <h2>4. Rent and fees</h2>
                <ul>
                    <li>Rent is charged monthly per bed at the rate shown for the room. Your next rent payment is due 30
                        days after your move-in date.</li>
                    <li>The reservation fee is <b>non-refundable</b>. The security fee and clean fee are shown in your
                        booking summary before you pay.</li>
                    <li>Late or unpaid rent may lead to reminders, restricted access to some services, and, if it
                        continues, ending of the stay.</li>
                </ul>

                <h2>5. Length of stay, transfers, and move-out</h2>
                <ul>
                    <li>You choose an expected length of stay when you book (for example 1 month, 3 months, 6 months, or
                        1 year).</li>
                    <li>Room or bed transfers are subject to availability and admin approval.</li>
                    <li>To leave, submit a move-out request from your Settings page and settle any unpaid balance before
                        your move-out date.</li>
                </ul>

                <h2>6. Using the room and shared areas</h2>
                <p>Each bed is for one tenant only. You may not sublet your bed, let others stay overnight without
                    approval, or use the room for any business. Please follow the House Rules at all times.</p>

                <h2>7. Damages and lost items</h2>
                <p>You are responsible for damage you or your visitors cause to the room, furniture, or shared
                    facilities, and the cost of repair may be charged to you. The dormitory is not responsible for lost,
                    stolen, or damaged personal belongings. Keep valuables locked.</p>

                <h2>8. Ending a stay</h2>
                <p>We may end a stay with written notice for serious or repeated violations of these terms or the House
                    Rules, non-payment, or conduct that puts other tenants at risk. You may end your stay by following
                    the move-out steps above.</p>

                <h2>9. Changes to these terms</h2>
                <p>We may update these terms from time to time. The "last updated" date above shows the latest version.
                    Continuing to use the website or stay after a change means you accept it.</p>

                <h2>10. Contact us</h2>
                <p>Questions about these terms? Use the <b>Message admin</b> button in your account, or the contact
                    details in the website footer.</p>
            </div>

            <div class="legalpanel" data-panel="privacy" hidden>
                <p class="legaldate">Last updated November 2, 2026</p>

                <p>D'aces Dormitory respects your privacy. This policy explains what personal information we collect,
                    why we collect it, and how we protect it, in line with the Philippine Data Privacy Act of 2012
                    (Republic Act No. 10173).</p>

                <h2>1. Information we collect</h2>
                <ul>
                    <li><b>Account details:</b> full name, email address, and password (stored in a scrambled, hashed
                        form).</li>
                    <li><b>Booking and tenant details:</b> date of birth, sex, civil status, occupation, school or
                        employer, mobile number, and home address.</li>
                    <li><b>Emergency contact:</b> the name, relationship, phone number, and address of the person we may
                        contact for you.</li>
                    <li><b>Identity document:</b> your ID type, ID number, and a photo of your valid ID.</li>
                    <li><b>Stay and payment records:</b> your room and bed, move-in date, rent, payments made, and
                        billing email.</li>
                    <li><b>Messages and requests:</b> chats, complaints, suggestions, and maintenance requests you send
                        us.</li>
                </ul>

                <h2>2. Why we collect it</h2>
                <ul>
                    <li>To create your account and process, approve, and manage your booking.</li>
                    <li>To verify your identity and keep the dormitory safe.</li>
                    <li>To collect rent, record payments, and send receipts and reminders.</li>
                    <li>To respond to your messages, complaints, and repair requests.</li>
                    <li>To contact your emergency contact if something happens to you.</li>
                </ul>

                <h2>3. Who can see your information</h2>
                <p>Only the dormitory admin and authorized staff can see your tenant records. Other tenants only see
                    what you choose to share, such as your name and room in the marketplace and chat. We do not sell
                    your personal information. We share it only with payment providers needed to process your payments,
                    or when the law requires it.</p>

                <h2>4. How long we keep it</h2>
                <p>We keep your information while you are a tenant and for a reasonable period afterwards for billing,
                    legal, and security purposes, then delete or anonymize it.</p>

                <h2>5. How we protect it</h2>
                <ul>
                    <li>Passwords are hashed, never stored as plain text.</li>
                    <li>Access to tenant records is limited to authorized admins.</li>
                    <li>Our forms are protected against common web attacks, and sessions end when you log out.</li>
                </ul>

                <h2>6. Cookies</h2>
                <p>We use only the cookies needed to keep you signed in and to protect your forms. We do not use
                    advertising cookies.</p>

                <h2>7. Your rights</h2>
                <p>You have the right to be informed, to access and correct your information, to object to or withdraw
                    your consent, and to ask us to delete your information where the law allows. You can update most
                    details in your Profile and Settings pages, or message the admin for anything else.</p>

                <h2>8. Changes and contact</h2>
                <p>We may update this policy and will change the date above when we do. For privacy questions, use
                    <b>Message admin</b> in your account or the contact details in the footer.</p>
            </div>

            <div class="legalpanel" data-panel="faqs" hidden>
                <p class="legaldate">Quick answers about staying at D'aces Dormitory</p>

                <div class="legalfaq">
                    <details>
                        <summary>How do I book a bed?</summary>
                        <p>Create an account and log in, open <b>Rooms</b>, pick a room, choose an available bed and
                            deck (lower or upper), and click <b>Book this room</b>. Fill in your details, choose a
                            payment method, and confirm.</p>
                    </details>
                </div>

                <div class="legalfaq">
                    <details>
                        <summary>Why does my booking say "waiting for admin approval"?</summary>
                        <p>Every new booking is reviewed by the dormitory admin first. Your bed is held for you in the
                            meantime, and the room appears in your details once the admin approves it. You will get a
                            notification either way.</p>
                    </details>
                </div>

                <div class="legalfaq">
                    <details>
                        <summary>What do I need to book?</summary>
                        <p>Your personal and contact details, an emergency contact, one valid government-issued ID (JPG
                            or PNG, up to 5MB), and your preferred move-in date.</p>
                    </details>
                </div>

                <div class="legalfaq">
                    <details>
                        <summary>What payment methods can I use?</summary>
                        <p>GCash is available now. Debit/credit card and bank transfer are not available yet.</p>
                    </details>
                </div>

                <div class="legalfaq">
                    <details>
                        <summary>When is my rent due?</summary>
                        <p>Your next rent payment is due 30 days after your move-in date. You can see what you owe and
                            your payment history on the Payments page.</p>
                    </details>
                </div>

                <div class="legalfaq">
                    <details>
                        <summary>Is the reservation fee refundable?</summary>
                        <p>No. The reservation fee is non-refundable, as stated before you pay.</p>
                    </details>
                </div>

                <div class="legalfaq">
                    <details>
                        <summary>What is included in the room?</summary>
                        <p>Free Wi-Fi, a study area, 24/7 water and electricity, CCTV security, and a gate and fence.
                            Rooms have shared cabinets, their own toilet and bath, a kitchen for everyone, and a free
                            electric fan.</p>
                    </details>
                </div>

                <div class="legalfaq">
                    <details>
                        <summary>Something in my room is broken. What do I do?</summary>
                        <p>Go to your Profile and click <b>Request Maintenance</b>, choose a category and urgency, and
                            describe the problem. The dorm team will follow up.</p>
                    </details>
                </div>

                <div class="legalfaq">
                    <details>
                        <summary>Can I change rooms or leave early?</summary>
                        <p>Yes. Use the room transfer or move-out request in your Settings page. Transfers depend on
                            availability and admin approval.</p>
                    </details>
                </div>

                <div class="legalfaq">
                    <details>
                        <summary>How do I contact the admin?</summary>
                        <p>Use the <b>Ask Admin</b> button on the Dorm page or <b>Message admin</b> on the Rooms page to
                            chat with the dormitory admin directly, or use the contact details in the footer.</p>
                    </details>
                </div>

                <div class="legalfaq">
                    <details>
                        <summary>Can I look around without an account?</summary>
                        <p>Yes. Choose <b>Continue as Guest</b> to view the home page, dorm layout, rooms, and location.
                            You need an account to book or use other features.</p>
                    </details>
                </div>
            </div>

            <div class="legalpanel" data-panel="rules" hidden>
                <p class="legaldate">Please read and follow these rules so everyone can live comfortably</p>

                <h2>Respect and quiet</h2>
                <ol>
                    <li>Be respectful to your roommates, other tenants, staff, and neighbors.</li>
                    <li>Quiet hours are from 10:00 PM to 6:00 AM. Keep music, calls, and gatherings low during this
                        time.</li>
                    <li>Settle disagreements calmly. If you cannot, ask the admin to help.</li>
                </ol>

                <h2>Visitors and security</h2>
                <ol start="4">
                    <li>Visitors are allowed only in common areas from 8:00 AM to 9:00 PM and must be accompanied by the
                        tenant.</li>
                    <li>No overnight guests without the admin's approval.</li>
                    <li>The gate closes at 11:00 PM. Tell the admin ahead of time if you will come home late.</li>
                    <li>Never prop the gate or doors open, and do not share your key or access with anyone.</li>
                    <li>Report lost keys, suspicious people, or anything unsafe to the admin right away.</li>
                </ol>

                <h2>Cleanliness</h2>
                <ol start="9">
                    <li>Keep your bed, cabinet, and area clean and tidy.</li>
                    <li>Wash your dishes and clean the kitchen after you use it. Do not leave food out.</li>
                    <li>Segregate your trash and throw it in the proper bins.</li>
                    <li>Leave the shared toilet and bath clean for the next person.</li>
                </ol>

                <h2>Safety and use of the room</h2>
                <ol start="13">
                    <li>No smoking or vaping inside the building.</li>
                    <li>No illegal drugs, weapons, or open flames. No cooking inside the sleeping rooms.</li>
                    <li>Do not bring heaters, extra air conditioners, or other high-power appliances without approval.
                    </li>
                    <li>Turn off lights, fans, and appliances when you leave the room.</li>
                    <li>No pets unless the admin has approved it.</li>
                    <li>Do not move, remove, or damage furniture and fixtures. Report repairs through <b>Request
                            Maintenance</b>.</li>
                </ol>

                <h2>Payments</h2>
                <ol start="19">
                    <li>Pay your rent on or before the due date.</li>
                    <li>Tell the admin early if you will have trouble paying.</li>
                </ol>

                <h2>If a rule is broken</h2>
                <p>A first violation usually gets a verbal reminder, then a written warning. Serious or repeated
                    violations may lead to fees for damages or the ending of the stay, as explained in the Terms of
                    Service.</p>
            </div>

        </div>

             <div class="legalfoot">
            @if ($adminEdit ?? false)
                <span class="legalstatus" id="legalStatus" hidden></span>
                <button type="button" class="legalbtn ghost" data-legal-close>Close</button>
                <button type="button" class="legalbtn ghost" id="legalEdit">Edit</button>
                <button type="button" class="legalbtn" id="legalSave" hidden>Save Changes</button>
            @elseif ($withAgree ?? false)
                <button type="button" class="legalbtn ghost" data-legal-close>Close</button>
                <button type="button" class="legalbtn" id="legalAgree">I agree</button>
            @else
                <button type="button" class="legalbtn" data-legal-close>Close</button>
            @endif
        </div>
    </div>
</div>

<script>
    (function () {
        var modal = document.getElementById('legalModal');
        if (!modal) return;
        document.body.appendChild(modal);   

        var titles = { terms: 'Terms of Service', privacy: 'Privacy Policy', faqs: 'FAQs', rules: 'House Rules' };
        var tabs = modal.querySelectorAll('.legaltab');
        var panels = modal.querySelectorAll('.legalpanel');

        function show(key) {
            if (!titles[key]) key = 'terms';
            tabs.forEach(function (t) { t.classList.toggle('active', t.dataset.tab === key); });
            panels.forEach(function (p) { p.hidden = (p.dataset.panel !== key); });
            document.getElementById('legalTitle').textContent = titles[key];
            document.getElementById('legalBody').scrollTop = 0;
        }

        function openLegal(key) {
            show(key);
            modal.classList.add('is-open');
            modal.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
        }

        function closeLegal() {
            modal.classList.remove('is-open');
            modal.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';
        }

        document.addEventListener('click', function (e) {
            var link = e.target.closest('[data-legal]');
            if (link) { e.preventDefault(); openLegal(link.dataset.legal); }
        });

        tabs.forEach(function (t) { t.addEventListener('click', function () { show(t.dataset.tab); }); });

        modal.addEventListener('click', function (e) {
            if (e.target === modal || e.target.closest('[data-legal-close]')) closeLegal();
        });
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && modal.classList.contains('is-open')) closeLegal();
        });

        var agreeBtn = document.getElementById('legalAgree');
        if (agreeBtn) {
            agreeBtn.addEventListener('click', function () {
                var box = document.getElementById('agree');
                if (box) box.checked = true;
                closeLegal();
            });
        }

        var editBtn = document.getElementById('legalEdit');
        var saveBtn = document.getElementById('legalSave');
        var statusEl = document.getElementById('legalStatus');
        if (editBtn && saveBtn) {
            function say(text) { statusEl.textContent = text; statusEl.hidden = !text; }
            function stopEdit() {
                panels.forEach(function (p) { p.removeAttribute('contenteditable'); });
                editBtn.hidden = false;
                saveBtn.hidden = true;
            }

            editBtn.addEventListener('click', function () {
                var p = modal.querySelector('.legalpanel:not([hidden])');
                p.querySelectorAll('details').forEach(function (d) { d.open = true; });
                p.setAttribute('contenteditable', 'true');
                p.focus();
                editBtn.hidden = true;
                saveBtn.hidden = false;
                say('');
            });

            saveBtn.addEventListener('click', function () {
                stopEdit();
                say('Saved.');
            });

            tabs.forEach(function (t) {
                t.addEventListener('click', function () { stopEdit(); say(''); });
            });

            modal.addEventListener('click', function (e) {
                if (e.target === modal || e.target.closest('[data-legal-close]')) { stopEdit(); say(''); }
                if (e.target.closest('.legalpanel[contenteditable] summary')) e.preventDefault();
            });
        }
    })();
</script>