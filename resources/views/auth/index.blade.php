<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log in</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}?v={{ filemtime(public_path('css/login.css')) }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inria+Serif:wght@300;400;700&family=Inter:wght@400;600&family=Nunito+Sans:wght@400;600;700&display=swap"
        rel="stylesheet">
</head>

<body>
    <div class="all">
        <div class="Main">

            <div id="loginView" class="view" {{ $view === 'signup' ? 'hidden' : '' }}>
                <form action="{{ route('login.submit') }}" method="POST">
                    @csrf
                    <div class="top">
                        <div><img src="{{ asset('images/logo.png') }}" alt="Logo" class="logo"></div>
                        <div>
                            <p class="lg">Log in</p>
                        </div>
                    </div>
                    <div>
                        <p class="h3">Welcome back! Please login to your<br> account.</p>
                    </div>

                    @if (session('error') && $view !== 'signup')
                        <p class="message error">{{ session('error') }}</p>
                    @endif
                    @if (session('success') && $view !== 'signup')
                        <p class="message success">{{ session('success') }}</p>
                    @endif

                    <div class="inpots">
                        <div class="inputs">
                            <svg class="iconsa" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="2" y="4" width="20" height="16" rx="2"></rect>
                                <path d="M2 7l10 6 10-6"></path>
                            </svg>
                            <div class="inputr">
                                <input type="email" id="email" name="email" placeholder=" " required>
                                <label for="email">Email Address</label>
                            </div>
                        </div>

                        <div class="inputs" style="margin-top: 40px;">
                            <svg class="iconsa" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="4" y="11" width="16" height="10" rx="2"></rect>
                                <path d="M8 11V7a4 4 0 0 1 8 0v4"></path>
                            </svg>
                            <div class="inputr">
                                <input type="password" id="password" name="password" placeholder=" " required>
                                <label for="password">Password</label>
                            </div>

                            <button type="button" class="hides" aria-label="Show password">
                                <svg class="iconsa eye-show" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>

                                <svg class="iconsa eye-hide" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" style="display:none;">
                                    <path
                                        d="M17.94 17.94A10.94 10.94 0 0 1 12 19c-7 0-11-7-11-7a21.4 21.4 0 0 1 5.06-5.94M9.9 4.24A10.94 10.94 0 0 1 12 5c7 0 11 7 11 7a21.4 21.4 0 0 1-2.16 3.19M14.12 14.12a3 3 0 1 1-4.24-4.24">
                                    </path>
                                    <path d="M1 1l22 22"></path>
                                </svg>
                            </button>

                        </div>

                    </div>
                    <div class="remember">
                        <input type="checkbox" name="remember" id="remember" class="radi">
                        <label for="remember">Remember me</label>
                        <label for="forgot" style="margin-left: auto;">Forgot Password?</label>
                    </div>
                    <div><input type="submit" value="Login" class="loginbo"></div>
                    <div>
                        <p class="create">New to D'Aces? &nbsp;<a href="#" class="crea" id="showSignup">Create an
                                account</a>
                        </p>
                    </div>
                    <div class="divider">
                        <hr>
                        <p class="divider-text">Or continue with</p>
                        <hr>
                    </div>
                    <div>
                        <button type="button" class="loginbo2">
                            <svg class="guestsicon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"
                                aria-hidden="true">
                                <path d="M20 21a8 8 0 0 0-16 0"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                            Continue as Guest
                        </button>
                    </div>
                    <div class="below">
                        <div class="social facebook">
                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                <path
                                    d="M13.5 22v-8h3l.5-3h-3.5V7.5c0-.9.4-1.5 1.5-1.5H17V3.1c-.3-.1-1.4-.1-2.6-.1-2.5 0-4.4 1.5-4.4 4.5V11H7v3h3.1v8h3.4z" />
                            </svg>
                            <span>Facebook</span>
                        </div>
                        <div class="social apple">
                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                <path
                                    d="M15.7 12.5c0-2.2 1.8-3.3 1.9-3.4-1-1.5-2.6-1.7-3.1-1.7-1.3-.1-2.6.8-3.3.8-.7 0-1.8-.8-3-.8-1.5 0-2.9.9-3.7 2.2-1.6 2.8-.4 6.9 1.1 9.1.8 1.1 1.6 2.3 2.8 2.2 1.1-.1 1.6-.7 3-.7s1.8.7 3 .7c1.2 0 2-.9 2.8-2 .9-1.3 1.3-2.6 1.3-2.7-.1-.1-2.4-.9-3.6-3.3zM14.4 5.9c.6-.7 1-1.7.9-2.7-.9.1-2.1.6-2.8 1.4-.6.7-1.1 1.7-1 2.7 1 .1 2.1-.5 2.9-1.4z" />
                            </svg>
                            <span>Apple</span>
                        </div>
                        <div class="social google">
                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                <path
                                    d="M21.8 12.2c0-.7-.1-1.4-.2-2.1H12v4h5.4a4.6 4.6 0 0 1-2 3.1v2.6h3.3c1.9-1.7 3.1-4.3 3.1-7.6z"
                                    fill="#4285F4" />
                                <path
                                    d="M12 22c2.7 0 5-.9 6.7-2.4l-3.3-2.6c-.9.6-2.1 1-3.4 1-2.6 0-4.8-1.8-5.6-4.1H.7v2.7A10 10 0 0 0 12 22z"
                                    fill="#34A853" />
                                <path
                                    d="M6.4 18.9c-.7-1.3-1.1-2.7-1.1-4.2s.4-2.9 1.1-4.2V7.8H2.9A10 10 0 0 0 1 12c0 1.6.4 3.2 1 4.6l4.4-2.7z"
                                    fill="#FBBC05" />
                                <path
                                    d="M12 4.8c1.4 0 2.7.5 3.7 1.5l2.7-2.7A10 10 0 0 0 2.9 7.8l3.5 2.7C7.2 6.6 9.4 4.8 12 4.8z"
                                    fill="#EA4335" />
                            </svg>
                            <span>Google</span>
                        </div>
                    </div>
                </form>
            </div>


            <div id="signupView" class="view" {{ $view === 'signup' ? '' : 'hidden' }}>
                <form action="{{ route('signup') }}" method="POST">
                    @csrf
                    <div class="kaliwa">
                        <div class="top">
                            <div><img src="{{ asset('images/logo.png') }}" alt="Logo" class="logo"></div>
                            <div>
                                <p class="lg1">Sign Up</p>
                            </div>
                        </div>

                        <div>
                            <p class="h31">Create your account to book a room, track payments,<br> and manage your
                                stay.</p>
                        </div>

                        @if (session('error') && $view === 'signup')
                            <p class="message error">{{ session('error') }}</p>
                        @endif
                        @if (session('success') && $view === 'signup')
                            <p class="message success">{{ session('success') }}</p>
                        @endif

                        <div class="inputs1" style="margin-top: 40px;">
                            <svg class="iconsa" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="2" y="4" width="20" height="16" rx="2"></rect>
                                <path d="M2 7l10 6 10-6"></path>
                            </svg>
                            <div class="inputr1">
                                <input type="email" id="signupEmail" name="email" placeholder=" " required>
                                <label for="signupEmail">Email Address</label>
                            </div>
                        </div>

                        <div class="inputs1" style="margin-top: 10px;">
                            <svg class="iconsa" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="4" y="11" width="16" height="10" rx="2"></rect>
                                <path d="M8 11V7a4 4 0 0 1 8 0v4"></path>
                            </svg>
                            <div class="inputr1">
                                <input type="password" id="signupPassword" name="password" placeholder=" " required>
                                <label for="signupPassword">Password</label>
                            </div>

                            <button type="button" class="hides" aria-label="Show password">
                                <svg class="iconsa eye-show" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>

                                <svg class="iconsa eye-hide" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" style="display:none;">
                                    <path
                                        d="M17.94 17.94A10.94 10.94 0 0 1 12 19c-7 0-11-7-11-7a21.4 21.4 0 0 1 5.06-5.94M9.9 4.24A10.94 10.94 0 0 1 12 5c7 0 11 7 11 7a21.4 21.4 0 0 1-2.16 3.19M14.12 14.12a3 3 0 1 1-4.24-4.24">
                                    </path>
                                    <path d="M1 1l22 22"></path>
                                </svg>
                            </button>
                        </div>

                        <div class="inputs1" style="margin-top: 10px;">
                            <svg class="iconsa" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="4" y="11" width="16" height="10" rx="2"></rect>
                                <path d="M8 11V7a4 4 0 0 1 8 0v4"></path>
                            </svg>
                            <div class="inputr1">
                                <input type="password" id="confirmpassword" name="confirm_password" placeholder=" "
                                    required>
                                <label for="confirmpassword">Confirm Password</label>
                            </div>

                            <button type="button" class="hides" aria-label="Show password">
                                <svg class="iconsa eye-show" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>

                                <svg class="iconsa eye-hide" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" style="display:none;">
                                    <path
                                        d="M17.94 17.94A10.94 10.94 0 0 1 12 19c-7 0-11-7-11-7a21.4 21.4 0 0 1 5.06-5.94M9.9 4.24A10.94 10.94 0 0 1 12 5c7 0 11 7 11 7a21.4 21.4 0 0 1-2.16 3.19M14.12 14.12a3 3 0 1 1-4.24-4.24">
                                    </path>
                                    <path d="M1 1l22 22"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="inputs1" style="margin-top: 40px;">
                            <svg class="iconsa" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="8" r="4"></circle>
                                <path d="M4 21a8 8 0 0 1 16 0"></path>
                            </svg>
                            <div class="inputr1">
                                <input type="text" id="signupName" name="full_name" placeholder=" " maxlength="100"
                                    required>
                                <label for="signupName">Full Name</label>
                            </div>
                        </div>
                        <div class="ags">
                            <input type="checkbox" name="agree" id="agree" class="radi" required>
                            <label for="agree">I agree to the Term of Service and Privacy Policy.</label>
                        </div>
                        <div><input type="submit" value="Sign Up" class="loginbo"></div>
                        <p class="have1">Already have an account? &nbsp;<a href="#" class="crea1" id="showLogin">Log
                                in</a></p>
                    </div>
                </form>
            </div>

        </div>
        <nav class="right" aria-label="Main navigation">
            <a href="#home">
                <h3>Home</h3>
            </a>
            <a href="#about">
                <h3>About Us</h3>
            </a>
            <a href="#room">
                <h3>Room</h3>
            </a>
            <a href="#settings">
                <h3>Settings</h3>
            </a>
        </nav>
    </div>

    <section id="home" class="lipat">

        <h2>Home</h2>
        <p class="find">Find your next room, check availability, and manage your stay — <br>all in one place.</p>
        <p class="find2">Featured Rooms</p>
        <hr class="hr1">
        <div class="boxes">
            <div class="box1">
                <h1 class="roomp">Room 101</h1>&nbsp;<h1 class="roomp1">— Good For 6</h1>
                <h1 class="roomp2">Double Bed For 2</h1>
                <h1 class="roomp3">₱2,500 / month</h1>
            </div>
            <div class="box2">
                <h1 class="roomp">Room 111</h1>&nbsp;<h1 class="roomp1">— Good For 6</h1>
                <h1 class="roomp22">Double Bed For 2</h1>
                <h1 class="roomp3">₱2,500 / month</h1>
            </div>
        </div>
        <h1 class="viewsa">View More.</h1>
        <p class="find3">Announcements</p>
        <hr class="hr2">
        <div class="anns">
            <h1 class="wat">Water interruption scheduled on July 28, 8:00 AM – 12:00 PM. Please store water in advance.
            </h1>
        </div>
        <div class="anns1">
            <h1 class="wat1">Friendly reminder that August rent is due on the 5th. Message admin if you need an
                extension.</h1>
        </div>
        <div class="buttonsa"><button type="button" class="book" href="#">Book Now</button> <button type="button"
                class="viewa" href="#">View Rooms</button></div>
    </section>

    <section id="about" class="lipat">
        <h2>About Us</h2>
        <div class="abt">
            <h1 class="wel">Welcome to D'aces Dormitory, Your Comfortable Home Away From Home.</h1>

        </div>
        <p class="find4">Quick Overview</p>
        <hr class="hr3">
        <div class="bomx">
    <div class="bomx1">
        <h1 class="num">{{ $stats['available_rooms'] }}</h1>
        <h1 class="tx">Available Rooms</h1>
    </div>
    <div class="bomx1">
        <h1 class="num">{{ $stats['bed_spaces'] }}</h1>
        <h1 class="tx">Total Bed spaces</h1>
    </div>
    <div class="bomx1">
        <h1 class="num">{{ $stats['vacancies'] }}</h1>
        <h1 class="tx">Current Vacancies</h1>
    </div>
</div>
        <h1 class="viewsa">View More.</h1>
        <p class="find5">Features</p>
        <hr class="hr4">
        <div class="amenities">
            <ul>
                <li>Free Wi-Fi</li>
                <li>Study Area</li>
                <li>24/7 Water &amp; Electricity</li>
            </ul>
            <ul>
                <li>CCTV Security</li>
                <li>Have Gate and fence</li>
            </ul>
        </div>
        <div class="buttonsa"><button type="button" class="book" href="#">Book Now</button> <button type="button"
                class="viewa" href="#">View Rooms</button></div>
    </section>

    <section id="room" class="lipat">
        <h2>Room</h2>
        <div class="glass">
            <div class="circle"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"
                    stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <rect x="4" y="11" width="16" height="10" rx="2"></rect>
                    <path d="M8 11V7a4 4 0 0 1 8 0v4"></path>
                </svg></div>

            <h1 class="lks">Log in to use this feature</h1>
            <h1 class="lks1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Log in to book and manage your reservations,<br> or continue
                as a guest to view the available rooms.</h1>
            <div class="but"><button type="button" class="but1">Log In</button> <button type="button"
                    class="but2">Continue as Guest</button></div>
        </div>
    </section>

    <section id="settings" class="lipat">
        <h2>Settings</h2>
        <div class="glass">
            <div class="circle"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"
                    stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <rect x="4" y="11" width="16" height="10" rx="2"></rect>
                    <path d="M8 11V7a4 4 0 0 1 8 0v4"></path>
                </svg></div>

            <h1 class="lks">Log in to use this feature</h1>
            <h1 class="lks1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Log in to book and manage your reservations,<br> or continue
                as a guest to view the available rooms.</h1>
            <div class="but"><button type="button" class="but1">Log In</button> <button type="button"
                    class="but2">Continue as Guest</button></div>
        </div>
    </section>
</body>
<script>

    document.querySelectorAll('.hides').forEach(btn => {
        btn.addEventListener('click', () => {
            const input = btn.parentElement.querySelector('input');
            const isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';

            btn.querySelector('.eye-show').style.display = isHidden ? 'none' : 'block';
            btn.querySelector('.eye-hide').style.display = isHidden ? 'block' : 'none';
            btn.setAttribute('aria-label', isHidden ? 'Hide password' : 'Show password');
        });
    });


    const loginView = document.getElementById('loginView');
    const signupView = document.getElementById('signupView');

    function hideStatusMessages() {
        document.querySelectorAll('.message').forEach(message => {
            message.hidden = true;
        });
    }

    document.getElementById('showSignup').addEventListener('click', (e) => {
        e.preventDefault();
        hideStatusMessages();
        loginView.hidden = true;
        signupView.hidden = false;
    });

    document.getElementById('showLogin').addEventListener('click', (e) => {
        e.preventDefault();
        hideStatusMessages();
        signupView.hidden = true;
        loginView.hidden = false;
    });


    const navLinks = document.querySelectorAll('.right a');
    const sections = document.querySelectorAll('.lipat');

    function setActiveNav() {
        const hash = window.location.hash || '#home';

        sections.forEach(section => {
            const isVisible = `#${section.id}` === hash;
            section.style.display = isVisible ? 'block' : 'none';
            section.classList.toggle('is-active', isVisible);
        });

        navLinks.forEach(link => {
            const isActive = link.getAttribute('href') === hash;
            link.classList.toggle('active', isActive);
        });
    }

    navLinks.forEach(link => {
        link.addEventListener('click', setActiveNav);
    });

    window.addEventListener('hashchange', setActiveNav);
    setActiveNav();
</script>

</html>