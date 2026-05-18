<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sidekick - Create Account</title>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/output.css">
</head>
<body class="m-0 p-0 font-sans text-slate-900 dark:text-slate-50 bg-slate-50 dark:bg-slate-950 min-h-screen">
    <!-- THEME TOGGLE -->
    <button id="themeToggle" class="fixed top-5 right-5 w-12 h-12 rounded-full bg-white dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 cursor-pointer flex items-center justify-center text-2xl hover:scale-110 transition-all duration-250 z-50 shadow-sm dark:shadow-md">🌙</button>

    <!-- REGISTER CONTAINER -->
    <div class="flex items-center justify-center min-h-screen p-6">
        <div class="w-full max-w-md bg-white dark:bg-slate-800 rounded-xl p-12 shadow-md dark:shadow-lg border border-slate-300 dark:border-slate-700">
            <!-- HEADER -->
            <div class="mb-8 text-center">
                <div class="flex justify-center mb-4 text-5xl">👔</div>
                <h1 class="text-3xl font-bold text-slate-900 dark:text-white mb-2">Create Account</h1>
                <p class="text-sm text-slate-600 dark:text-slate-400">Join thousands of freelancers managing their projects</p>
            </div>

            <!-- FORM -->
            <form id="registerForm" class="space-y-4">
                <!-- FIRST NAME -->
                <div class="flex flex-col gap-1">
                    <label class="block text-xs font-medium text-slate-900 dark:text-white">First Name</label>
                    <input
                        type="text"
                        id="firstName"
                        placeholder="Enter your first name"
                        required
                        class="w-full px-4 py-3 rounded-lg border border-slate-300 dark:border-slate-600 bg-slate-100 dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 dark:placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all"
                    >
                </div>

                <!-- LAST NAME -->
                <div class="flex flex-col gap-1">
                    <label class="block text-xs font-medium text-slate-900 dark:text-white">Last Name</label>
                    <input
                        type="text"
                        id="lastName"
                        placeholder="Enter your last name"
                        required
                        class="w-full px-4 py-3 rounded-lg border border-slate-300 dark:border-slate-600 bg-slate-100 dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 dark:placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all"
                    >
                </div>

                <!-- EMAIL -->
                <div class="flex flex-col gap-1">
                    <label class="block text-xs font-medium text-slate-900 dark:text-white">Email Address</label>
                    <input
                        type="email"
                        id="email"
                        placeholder="Enter your email"
                        required
                        class="w-full px-4 py-3 rounded-lg border border-slate-300 dark:border-slate-600 bg-slate-100 dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 dark:placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all"
                    >
                </div>

                <!-- ROLE -->
                <div class="flex flex-col gap-1">
                    <label class="block text-xs font-medium text-slate-900 dark:text-white">I am a</label>
                    <select
                        id="role"
                        required
                        class="w-full px-4 py-3 rounded-lg border border-slate-300 dark:border-slate-600 bg-slate-100 dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all"
                    >
                        <option value="">Select your role...</option>
                        <option value="freelancer">Freelancer</option>
                        <option value="client">Client</option>
                        <option value="manager">Project Manager</option>
                    </select>
                </div>

                <!-- PASSWORD -->
                <div class="flex flex-col gap-1">
                    <label class="block text-xs font-medium text-slate-900 dark:text-white">Password</label>
                    <div class="relative">
                        <input
                            type="password"
                            id="password"
                            placeholder="Create a strong password"
                            required
                            class="w-full px-4 py-3 pr-10 rounded-lg border border-slate-300 dark:border-slate-600 bg-slate-100 dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 dark:placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all"
                        >
                        <button
                            type="button"
                            id="passwordToggle"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors cursor-pointer text-sm"
                        >👁️</button>
                    </div>

                    <!-- STRENGTH METER -->
                    <div id="strengthMeter" class="flex gap-1 mt-2">
                        <div class="flex-1 h-1.5 rounded-full bg-slate-300 dark:bg-slate-600 transition-all duration-250"></div>
                        <div class="flex-1 h-1.5 rounded-full bg-slate-300 dark:bg-slate-600 transition-all duration-250"></div>
                        <div class="flex-1 h-1.5 rounded-full bg-slate-300 dark:bg-slate-600 transition-all duration-250"></div>
                        <div class="flex-1 h-1.5 rounded-full bg-slate-300 dark:bg-slate-600 transition-all duration-250"></div>
                    </div>
                    <span id="strengthText" class="text-xs text-slate-500 dark:text-slate-400 mt-1 block">Enter a password to see strength</span>

                    <!-- REQUIREMENTS -->
                    <div class="mt-3 p-3 bg-indigo-50 dark:bg-indigo-900 dark:bg-opacity-20 rounded-md text-xs text-slate-600 dark:text-slate-300">
                        <span class="font-semibold block mb-2">Password Requirements:</span>
                        <div class="flex items-center gap-1.5 mb-1">
                            <span id="req-length" class="flex-shrink-0 text-slate-400 dark:text-slate-500">✓</span>
                            <span>At least 8 characters</span>
                        </div>
                        <div class="flex items-center gap-1.5 mb-1">
                            <span id="req-upper" class="flex-shrink-0 text-slate-400 dark:text-slate-500">✓</span>
                            <span>One uppercase letter</span>
                        </div>
                        <div class="flex items-center gap-1.5 mb-1">
                            <span id="req-lower" class="flex-shrink-0 text-slate-400 dark:text-slate-500">✓</span>
                            <span>One lowercase letter</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span id="req-number" class="flex-shrink-0 text-slate-400 dark:text-slate-500">✓</span>
                            <span>One number (0-9)</span>
                        </div>
                    </div>
                </div>

                <!-- CONFIRM PASSWORD -->
                <div class="flex flex-col gap-1">
                    <label class="block text-xs font-medium text-slate-900 dark:text-white">Confirm Password</label>
                    <div class="relative">
                        <input
                            type="password"
                            id="confirmPassword"
                            placeholder="Confirm your password"
                            required
                            class="w-full px-4 py-3 pr-10 rounded-lg border border-slate-300 dark:border-slate-600 bg-slate-100 dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 dark:placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all"
                        >
                        <button
                            type="button"
                            id="confirmPasswordToggle"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors cursor-pointer text-sm"
                        >👁️</button>
                    </div>
                </div>

                <!-- TERMS -->
                <div class="flex gap-2 mt-6 mb-6">
                    <input
                        type="checkbox"
                        id="terms"
                        required
                        class="w-4 h-4 rounded border border-slate-300 dark:border-slate-600 mt-0.5 cursor-pointer accent-blue-600 flex-shrink-0"
                    >
                    <div>
                        <label class="text-sm text-slate-600 dark:text-slate-400 cursor-pointer">
                            I agree to the <a href="/terms" class="text-blue-600 hover:text-blue-700 dark:hover:text-blue-500 no-underline hover:underline font-medium">Terms of Service</a> and <a href="/privacy" class="text-blue-600 hover:text-blue-700 dark:hover:text-blue-500 no-underline hover:underline font-medium">Privacy Policy</a>
                        </label>
                        <div id="termsError" class="text-red-500 text-xs mt-1 hidden">You must agree to the terms</div>
                    </div>
                </div>

                <!-- SUBMIT BUTTON -->
                <button
                    type="submit"
                    class="w-full px-4 py-3 rounded-lg bg-blue-600 text-white font-semibold text-sm hover:bg-blue-700 active:bg-blue-800 disabled:opacity-60 disabled:cursor-not-allowed transition-all duration-250 shadow-sm hover:shadow-md mb-4"
                >
                    Create Account
                </button>
            </form>

            <!-- SIGNIN LINK -->
            <div class="text-center text-sm text-slate-600 dark:text-slate-400">
                Already have an account? 
                <a href="/SideKick/public/login.php" class="font-semibold text-blue-600 hover:text-blue-700 dark:hover:text-blue-500 no-underline cursor-pointer">
                    Sign in
                </a>
            </div>
        </div>
    </div>

    <script>
        // Theme Toggle
        const themeToggle = document.getElementById('themeToggle');
        const savedTheme = localStorage.getItem('theme') || 'light';
        
        if (savedTheme === 'dark') {
            document.documentElement.classList.add('dark');
            themeToggle.textContent = '☀️';
        }

        themeToggle.addEventListener('click', () => {
            document.documentElement.classList.toggle('dark');
            const isDark = document.documentElement.classList.contains('dark');
            themeToggle.textContent = isDark ? '☀️' : '🌙';
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
        });

        // Password Toggle
        const passwordInput = document.getElementById('password');
        const confirmPasswordInput = document.getElementById('confirmPassword');
        const passwordToggle = document.getElementById('passwordToggle');
        const confirmPasswordToggle = document.getElementById('confirmPasswordToggle');

        passwordToggle.addEventListener('click', (e) => {
            e.preventDefault();
            const type = passwordInput.type === 'password' ? 'text' : 'password';
            passwordInput.type = type;
            passwordToggle.textContent = type === 'password' ? '👁️' : '🙈';
        });

        confirmPasswordToggle.addEventListener('click', (e) => {
            e.preventDefault();
            const type = confirmPasswordInput.type === 'password' ? 'text' : 'password';
            confirmPasswordInput.type = type;
            confirmPasswordToggle.textContent = type === 'password' ? '👁️' : '🙈';
        });

        // Password Strength & Requirements
        const strengthBars = document.querySelectorAll('#strengthMeter > div');
        const strengthText = document.getElementById('strengthText');

        function updatePasswordStrength() {
            const password = passwordInput.value;
            let strength = 0;
            
            const requirements = {
                length: password.length >= 8,
                upper: /[A-Z]/.test(password),
                lower: /[a-z]/.test(password),
                number: /[0-9]/.test(password)
            };

            // Update requirement indicators
            document.getElementById('req-length').className = requirements.length 
                ? 'flex-shrink-0 text-green-500' 
                : 'flex-shrink-0 text-slate-400 dark:text-slate-500';
            document.getElementById('req-upper').className = requirements.upper 
                ? 'flex-shrink-0 text-green-500' 
                : 'flex-shrink-0 text-slate-400 dark:text-slate-500';
            document.getElementById('req-lower').className = requirements.lower 
                ? 'flex-shrink-0 text-green-500' 
                : 'flex-shrink-0 text-slate-400 dark:text-slate-500';
            document.getElementById('req-number').className = requirements.number 
                ? 'flex-shrink-0 text-green-500' 
                : 'flex-shrink-0 text-slate-400 dark:text-slate-500';

            // Calculate strength
            if (requirements.length) strength++;
            if (requirements.upper) strength++;
            if (requirements.lower) strength++;
            if (requirements.number) strength++;

            // Update strength bars with colors
            const strengthClasses = ['', 'bg-red-500', 'bg-orange-500', 'bg-blue-500', 'bg-green-500'];
            strengthBars.forEach((bar, index) => {
                bar.className = 'flex-1 h-1.5 rounded-full transition-all duration-250';
                if (password.length > 0) {
                    if (index < strength) {
                        bar.classList.add(strengthClasses[strength]);
                    } else {
                        bar.classList.add('bg-slate-300', 'dark:bg-slate-600');
                    }
                } else {
                    bar.classList.add('bg-slate-300', 'dark:bg-slate-600');
                }
            });

            // Update strength text
            const strengthTexts = {
                0: 'Enter a password to see strength',
                1: 'Weak password',
                2: 'Fair password',
                3: 'Good password',
                4: 'Strong password'
            };
            strengthText.textContent = strengthTexts[strength];
        }

        passwordInput.addEventListener('input', updatePasswordStrength);

        // Form Submit
        const registerForm = document.getElementById('registerForm');
        const submitBtn = registerForm.querySelector('button[type="submit"]');
        
        registerForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            // Get form values
            const firstName = document.getElementById('firstName').value.trim();
            const lastName = document.getElementById('lastName').value.trim();
            const email = document.getElementById('email').value.trim();
            const role = document.getElementById('role').value;
            const password = passwordInput.value;
            const confirmPassword = confirmPasswordInput.value;
            
            // Validate required fields
            if (!firstName || !lastName || !email || !role || !password) {
                alert('Please fill in all required fields');
                return;
            }
            
            // Validate passwords match
            if (password !== confirmPassword) {
                alert('Passwords do not match!');
                return;
            }

            // Validate terms
            if (!document.getElementById('terms').checked) {
                document.getElementById('termsError').classList.remove('hidden');
                return;
            }
            
            // Show loading state
            submitBtn.disabled = true;
            submitBtn.textContent = 'Creating Account...';
            
            try {
                // Send registration request to API
                const response = await fetch('/SideKick/api/auth/register.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        first_name: firstName,
                        last_name: lastName,
                        email: email,
                        role: role,
                        password: password,
                        password_confirm: confirmPassword
                    })
                });
                
                const data = await response.json();
                
                if (response.ok && data.success) {
                    // Registration successful - show message and redirect to login
                    alert('Account created successfully! Redirecting to login...');
                    window.location.href = '/SideKick/public/login.php';
                } else {
                    // Registration failed
                    alert(data.message || 'Registration failed. Please try again.');
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Create Account';
                }
            } catch (error) {
                console.error('Registration error:', error);
                alert('An error occurred. Please try again.');
                submitBtn.disabled = false;
                submitBtn.textContent = 'Create Account';
            }
        });

        // Hide terms error when checkbox is checked
        document.getElementById('terms').addEventListener('change', () => {
            document.getElementById('termsError').classList.add('hidden');
        });
    </script>
</body>
</html>
