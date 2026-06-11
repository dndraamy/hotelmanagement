@vite(['resources/css/login-custom.css'])

<div class="login-card-container">

    <div class="login-left-side">
        <div class="illustration-wrapper">
            <img src="{{ asset('images/loginIlustrasi.png') }}" alt="Login Ilustrasi">
        </div>

        <div class="curve-divider">
            <svg viewBox="0 0 100 100" preserveAspectRatio="none">
                <path d="M100,0 C70,15 90,40 50,60 C20,75 80,95 100,100 L100,0 Z" fill="#242424" opacity="0.4" />

                <path d="M100,0 C80,18 95,35 60,55 C35,68 85,88 100,100 L100,0 Z" fill="#272727" />

                <path d="M100,0 C85,25 100,45 75,65 C55,80 95,95 100,100 L100,0 Z" fill="#1A1A1A" />
            </svg>
        </div>
    </div>

    <div class="login-right-side">
        <div class="form-wrapper">
            <div class="brand-logo">
                <img src="{{ asset('/logo_hotel.png') }}" alt="Logo Hotel">
            </div>
            <h1 class="login-title">Login</h1>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="input-group">
                    <label for="username">Username</label>
                    <input id="username" type="text" name="username" placeholder="Enter your username" required autofocus>
                </div>

                <div class="input-group">
                    <label for="password">Password</label>
                    <input id="password" type="password" name="password" placeholder="Enter your password" required>
                    <div class="forgot-password-align">
                        <a href="#" class="forgot-link">Forgot Password?</a>
                    </div>
                </div>

                <button type="submit" class="btn-login">Login</button>
            </form>

            <div class="form-footer">
                <p>Don't have an account? <a href="#">Register Now</a></p>
                <div class="terms-link">
                    <a href="#">Terms and Services</a>
                </div>
            </div>
        </div>
    </div>
</div>