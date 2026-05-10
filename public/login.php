<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sidekick - Sign In</title>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/output.css">
</head>
<body class="m-0 p-0 font-sans text-slate-900 dark:text-slate-50 bg-slate-50 dark:bg-slate-950">
    <!-- THEME TOGGLE -->
    <button id="themeToggle" class="fixed top-5 right-5 w-12 h-12 rounded-full bg-white dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 cursor-pointer flex items-center justify-center text-2xl hover:scale-110 transition-all duration-250 z-50 shadow-sm dark:shadow-md">🌙</button>

    <!-- LOGIN CONTAINER -->
    <div class="flex min-h-screen w-full">
        <!-- LEFT PANEL - BRAND (HIDDEN ON MOBILE/TABLET) -->
        <div class="hidden md:flex md:w-1/2 flex-col items-center justify-center p-12 bg-gradient-to-br from-blue-700 via-blue-600 to-blue-500 relative overflow-hidden">
            <!-- Background animated circles -->
            <div class="absolute top-0 right-0 w-80 h-80 -mr-40 -mt-40 rounded-full bg-white bg-opacity-5 blur-3xl animate-pulse"></div>
            <div class="absolute bottom-0 left-0 w-64 h-64 -ml-32 -mb-32 rounded-full bg-white bg-opacity-3 blur-3xl animate-pulse"></div>

            <!-- Content -->
            <div class="relative z-10 text-center max-w-sm">
                <!-- Logo -->
                <div class="flex items-center justify-center w-20 h-20 rounded-full bg-white bg-opacity-20 mx-auto mb-8">
                    <span class="text-4xl">👔</span>
                </div>

                <!-- Heading -->
                <h1 class="text-4xl font-bold text-white mb-6 leading-tight">Get Started</h1>

                <!-- Description -->
                <p class="text-base text-white text-opacity-90 mb-8 leading-relaxed">
                    Join thousands of freelancers managing their projects efficiently
                </p>

                <!-- Features -->
                <div class="space-y-4 mb-8">
                    <div class="flex items-center gap-3 text-white text-sm">
                        <span class="flex items-center justify-center w-5 h-5 rounded-full bg-white bg-opacity-30 font-bold flex-shrink-0">✓</span>
                        <span>Real-time collaboration</span>
                    </div>
                    <div class="flex items-center gap-3 text-white text-sm">
                        <span class="flex items-center justify-center w-5 h-5 rounded-full bg-white bg-opacity-30 font-bold flex-shrink-0">✓</span>
                        <span>Advanced analytics & reporting</span>
                    </div>
                    <div class="flex items-center gap-3 text-white text-sm">
                        <span class="flex items-center justify-center w-5 h-5 rounded-full bg-white bg-opacity-30 font-bold flex-shrink-0">✓</span>
                        <span>Team management tools</span>
                    </div>
                </div>

                <!-- Social Buttons -->
                <div class="flex flex-col gap-3 mb-8 w-full">
                    <button type="button" class="w-full px-4 py-3 rounded-lg border-2 border-white border-opacity-30 bg-transparent text-white text-sm font-medium cursor-pointer transition-all duration-250 hover:bg-white hover:bg-opacity-10 hover:border-opacity-50 flex items-center justify-center gap-2">
                        🔵 Sign in with Google
                    </button>
                    <button type="button" class="w-full px-4 py-3 rounded-lg border-2 border-white border-opacity-30 bg-transparent text-white text-sm font-medium cursor-pointer transition-all duration-250 hover:bg-white hover:bg-opacity-10 hover:border-opacity-50 flex items-center justify-center gap-2">
                        📘 Sign in with Facebook
                    </button>
                </div>

                <!-- Divider -->
                <div class="flex items-center gap-3 mb-8 w-full">
                    <div class="flex-1 h-px bg-white bg-opacity-20"></div>
                    <span class="text-xs text-white text-opacity-60 font-medium uppercase">Or</span>
                    <div class="flex-1 h-px bg-white bg-opacity-20"></div>
                </div>

                <!-- Sign Up Link -->
                <p class="text-center text-white text-sm">
                    Not a member yet? <a href="/SideKick/public/register.php" class="font-semibold no-underline cursor-pointer hover:underline hover:opacity-80 transition-all">Create an account</a>
                </p>
            </div>
        </div>

        <!-- RIGHT PANEL - FORM -->
        <div class="w-full md:w-1/2 flex flex-col items-center justify-center p-6 md:p-12 bg-white dark:bg-slate-800">
            <div class="w-full max-w-md">
                <!-- Form Header -->
                <div class="mb-8 text-center">
                    <h2 class="text-3xl font-bold text-slate-900 dark:text-white mb-2">Log In</h2>
                    <p class="text-sm text-slate-600 dark:text-slate-400">Welcome back! Sign in to your account</p>
                </div>

                <!-- Form -->
                <form id="loginForm" class="space-y-4">
                    <!-- Email Field -->
                    <div class="flex flex-col gap-1">
                        <label class="block text-xs font-medium text-slate-900 dark:text-white">Username or Email</label>
                        <input
                            type="text"
                            id="email"
                            placeholder="Enter your username or email"
                            required
                            class="w-full px-4 py-3 rounded-lg border border-slate-300 dark:border-slate-600 bg-slate-100 dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 dark:placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all"
                        >
                    </div>

                    <!-- Password Field -->
                    <div class="flex flex-col gap-1">
                        <label class="block text-xs font-medium text-slate-900 dark:text-white">Password</label>
                        <div class="relative">
                            <input
                                type="password"
                                id="passwordInput"
                                placeholder="Enter your password"
                                required
                                class="w-full px-4 py-3 pr-10 rounded-lg border border-slate-300 dark:border-slate-600 bg-slate-100 dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 dark:placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all"
                            >
                            <button
                                type="button"
                                id="passwordToggle"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors cursor-pointer text-sm"
                            >👁️</button>
                        </div>
                    </div>

                    <!-- Options -->
                    <div class="flex justify-between items-center mt-4 mb-6 text-sm">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input
                                type="checkbox"
                                id="rememberMe"
                                class="w-4 h-4 rounded border border-slate-300 dark:border-slate-600 cursor-pointer accent-blue-600"
                            >
                            <span class="text-slate-600 dark:text-slate-400">Remember me</span>
                        </label>
                        <a href="/SideKick/public/forgot-password.php" class="font-medium text-blue-600 hover:text-blue-700 dark:hover:text-blue-500 no-underline hover:underline transition-colors">
                            Forgot Password?
                        </a>
                    </div>

                    <!-- Submit Button -->
                    <button
                        type="submit"
                        class="w-full px-4 py-3 rounded-lg bg-blue-600 text-white font-semibold text-sm hover:bg-blue-700 active:bg-blue-800 disabled:opacity-60 disabled:cursor-not-allowed transition-all duration-250 shadow-sm hover:shadow-md mb-5"
                    >
                        Sign In
                    </button>
                </form>

                <!-- Divider -->
                <div class="relative flex items-center gap-3 my-6">
                    <div class="flex-1 h-px bg-slate-300 dark:bg-slate-600"></div>
                    <span class="text-xs text-slate-500 dark:text-slate-400 font-medium uppercase">Or</span>
                    <div class="flex-1 h-px bg-slate-300 dark:bg-slate-600"></div>
                </div>

                <!-- Social Buttons -->
                <div class="flex flex-col gap-3 mb-5">
                    <button type="button" class="w-full px-4 py-2.5 rounded-lg border border-slate-300 dark:border-slate-600 bg-slate-100 dark:bg-slate-700 text-slate-900 dark:text-white text-xs font-medium cursor-pointer transition-all duration-250 hover:bg-slate-200 dark:hover:bg-slate-600 flex items-center justify-center gap-2">
                        🔵 Sign in with Google
                    </button>
                    <button type="button" class="w-full px-4 py-2.5 rounded-lg border border-slate-300 dark:border-slate-600 bg-slate-100 dark:bg-slate-700 text-slate-900 dark:text-white text-xs font-medium cursor-pointer transition-all duration-250 hover:bg-slate-200 dark:hover:bg-slate-600 flex items-center justify-center gap-2">
                        📘 Sign in with Facebook
                    </button>
                </div>

                <!-- Footer -->
                <div class="text-center text-xs text-slate-600 dark:text-slate-400">
                    Not a member yet? 
                    <a href="/SideKick/public/register.php" class="font-semibold text-blue-600 hover:text-blue-700 dark:hover:text-blue-500 no-underline cursor-pointer">
                        Sign up
                    </a>
                </div>
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
        const passwordInput = document.getElementById('passwordInput');
        const passwordToggle = document.getElementById('passwordToggle');

        if (passwordToggle) {
            passwordToggle.addEventListener('click', (e) => {
                e.preventDefault();
                const type = passwordInput.type === 'password' ? 'text' : 'password';
                passwordInput.type = type;
                passwordToggle.textContent = type === 'password' ? '👁️' : '🙈';
            });
        }

        // Form Submit
        const loginForm = document.getElementById('loginForm');
        loginForm.addEventListener('submit', (e) => {
            e.preventDefault();
            alert('Login form submitted! (This is a demo)');
        });
    </script>
</body>
</html>
