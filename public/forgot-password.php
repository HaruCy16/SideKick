<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Sidekick</title>
    <link rel="stylesheet" href="../assets/css/output.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="m-0 p-0 min-h-screen bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-white font-sans">
    <div class="grid grid-cols-1 md:grid-cols-2 min-h-screen gap-0">
        <!-- Left Panel -->
        <div class="hidden md:flex flex-col items-center justify-center p-8 bg-gradient-to-br from-indigo-600 to-purple-600 text-white relative overflow-hidden">
            <div class="absolute inset-0 opacity-10">
                <div class="absolute inset-0" style="background-image: radial-gradient(circle, rgba(255, 255, 255, 0.1) 1px, transparent 1px); background-size: 50px 50px; animation: movePattern 20s linear infinite;"></div>
            </div>
            <div class="relative z-10 text-center max-w-sm">
                <div class="w-16 h-16 bg-white/30 rounded-2xl flex items-center justify-center mx-auto mb-8 text-4xl font-bold">SK</div>
                <h1 class="text-5xl font-bold mb-4 leading-tight">Sidekick</h1>
                <p class="text-lg leading-8 mb-12 opacity-90">Manage projects, collaborate with teams, and track progress seamlessly.</p>
                
                <div class="space-y-4 mt-12">
                    <div class="flex items-center gap-3 text-sm">
                        <div class="w-5 h-5 bg-white/30 rounded-full flex items-center justify-center text-xs">
                            <i class="fas fa-check"></i>
                        </div>
                        <span>Real-time collaboration</span>
                    </div>
                    <div class="flex items-center gap-3 text-sm">
                        <div class="w-5 h-5 bg-white/30 rounded-full flex items-center justify-center text-xs">
                            <i class="fas fa-check"></i>
                        </div>
                        <span>Advanced analytics</span>
                    </div>
                    <div class="flex items-center gap-3 text-sm">
                        <div class="w-5 h-5 bg-white/30 rounded-full flex items-center justify-center text-xs">
                            <i class="fas fa-check"></i>
                        </div>
                        <span>Team management</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Panel -->
        <div class="flex flex-col items-center justify-center p-8 bg-slate-50 dark:bg-slate-950 md:bg-gradient-to-br md:from-indigo-100/30 md:to-purple-100/30 md:dark:from-indigo-950/20 md:dark:to-purple-950/20">
            <div class="w-full max-w-sm">
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-slate-900 dark:text-white mb-2">Forgot Password</h1>
                    <p class="text-sm text-slate-600 dark:text-slate-400">No worries, we'll help you reset it.</p>
                </div>

                <div id="alert-container"></div>

                <div class="flex gap-2 items-start p-3 rounded-lg border border-blue-200 dark:border-blue-800 bg-blue-50 dark:bg-blue-950/30 text-blue-700 dark:text-blue-400 text-sm mb-6">
                    <i class="fas fa-info-circle mt-0.5 flex-shrink-0"></i>
                    <span>We'll send you an email with instructions to reset your password.</span>
                </div>

                <form id="forgot-form" class="space-y-5">
                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-900 dark:text-white mb-2">Email Address</label>
                        <div class="relative">
                            <i class="fas fa-envelope absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-500 dark:text-slate-400 text-base pointer-events-none"></i>
                            <input 
                                type="email" 
                                id="email" 
                                name="email"
                                placeholder="your@email.com"
                                required
                                autocomplete="email"
                                class="w-full pl-10 pr-4 py-3 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-white rounded-lg text-sm placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all duration-250"
                            >
                        </div>
                    </div>

                    <button 
                        type="submit" 
                        class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-base font-semibold cursor-pointer transition-all duration-250 hover:-translate-y-0.5 hover:shadow-lg active:translate-y-0 disabled:opacity-60 disabled:cursor-not-allowed"
                    >
                        Send Reset Link
                    </button>
                </form>

                <div class="text-center text-sm text-slate-600 dark:text-slate-400 mt-6">
                    Back to <a href="/SideKick/public/login.php" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 hover:underline transition-colors duration-250">Sign in</a>
                </div>
            </div>
        </div>
    </div>

    <style>
        @keyframes movePattern {
            0% { transform: translate(0, 0); }
            100% { transform: translate(50px, 50px); }
        }
    </style>

    <script>
        function initTheme() {
            const theme = localStorage.getItem('theme') || 'light';
            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
                document.documentElement.setAttribute('data-theme', 'dark');
            } else {
                document.documentElement.classList.remove('dark');
                document.documentElement.setAttribute('data-theme', 'light');
            }
        }

        const forgotForm = document.getElementById('forgot-form');
        if (forgotForm) {
            forgotForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                const submitBtn = forgotForm.querySelector('button[type="submit"]');
                const email = document.getElementById('email').value.trim();

                if (!email || !isValidEmail(email)) {
                    showAlert('Please enter a valid email address', 'error');
                    return;
                }

                try {
                    submitBtn.disabled = true;
                    submitBtn.textContent = 'Sending...';

                    const response = await fetch('/SideKick/api/auth/forgot-password.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ email })
                    });

                    const data = await response.json();

                    if (data.success) {
                        showAlert('Reset link sent! Check your email for instructions.', 'success');
                        setTimeout(() => { document.getElementById('email').value = ''; }, 2000);
                    } else {
                        showAlert(data.message || 'Failed to send reset link', 'error');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showAlert('An error occurred', 'error');
                } finally {
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Send Reset Link';
                }
            });
        }

        function showAlert(message, type = 'info') {
            const container = document.getElementById('alert-container');
            const alert = document.createElement('div');
            const bgColor = type === 'error' ? 'bg-red-50 dark:bg-red-950/30' : type === 'success' ? 'bg-green-50 dark:bg-green-950/30' : 'bg-blue-50 dark:bg-blue-950/30';
            const textColor = type === 'error' ? 'text-red-700 dark:text-red-400' : type === 'success' ? 'text-green-700 dark:text-green-400' : 'text-blue-700 dark:text-blue-400';
            const borderColor = type === 'error' ? 'border-red-200 dark:border-red-800' : type === 'success' ? 'border-green-200 dark:border-green-800' : 'border-blue-200 dark:border-blue-800';
            const icon = type === 'error' ? 'exclamation-circle' : type === 'success' ? 'check-circle' : 'info-circle';
            
            alert.className = `flex gap-2 items-start p-3 rounded-lg border mb-4 text-sm ${bgColor} ${textColor} ${borderColor}`;
            alert.innerHTML = `<i class="fas fa-${icon} mt-0.5 flex-shrink-0"></i><span>${message}</span>`;
            container.innerHTML = '';
            container.appendChild(alert);

            if (type !== 'error') setTimeout(() => alert.remove(), 4000);
        }

        function isValidEmail(email) {
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
        }

        initTheme();
    </script>
</body>
</html>
        /* Auth Page Specific Styles */
        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }

        .auth-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            min-height: 100vh;
        }

        .auth-left-panel {
            background: linear-gradient(135deg, #6366F1 0%, #8B5CF6 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 32px;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .auth-left-panel::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 1px, transparent 1px);
            background-size: 50px 50px;
            animation: movePattern 20s linear infinite;
        }

        @keyframes movePattern {
            0% { transform: translate(0, 0); }
            100% { transform: translate(50px, 50px); }
        }

        .auth-left-content {
            position: relative;
            z-index: 10;
            text-align: center;
            max-width: 400px;
        }

        .auth-logo {
            width: 64px;
            height: 64px;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 32px;
            font-size: 32px;
            font-weight: 700;
        }

        .auth-left-title {
            font-size: 48px;
            font-weight: 700;
            margin-bottom: 16px;
            line-height: 1.2;
        }

        .auth-left-subtitle {
            font-size: 18px;
            line-height: 28px;
            margin-bottom: 48px;
            opacity: 0.9;
        }

        .auth-features {
            display: flex;
            flex-direction: column;
            gap: 16px;
            margin-top: 48px;
        }

        .auth-feature {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 14px;
        }

        .auth-feature-icon {
            width: 20px;
            height: 20px;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
        }

        .auth-right-panel {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 32px;
            background-color: var(--bg-primary);
        }

        .auth-form-container {
            width: 100%;
            max-width: 420px;
        }

        .auth-form-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .auth-form-header h1 {
            font-size: 28px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 8px;
        }

        .auth-form-header p {
            font-size: 14px;
            color: var(--text-secondary);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 500;
            color: var(--text-primary);
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-wrapper input {
            width: 100%;
            padding: 12px 14px 12px 40px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            background-color: var(--bg-secondary);
            color: var(--text-primary);
            font-size: 14px;
            transition: all 250ms ease-in-out;
        }

        .input-wrapper input::placeholder {
            color: var(--text-tertiary);
        }

        .input-wrapper input:focus {
            outline: none;
            border-color: var(--color-primary);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        .input-icon {
            position: absolute;
            left: 14px;
            color: var(--text-tertiary);
            font-size: 16px;
            pointer-events: none;
        }

        .info-message {
            padding: 12px 14px;
            background-color: rgba(59, 130, 246, 0.1);
            border: 1px solid rgba(59, 130, 246, 0.3);
            border-radius: 8px;
            margin-bottom: 24px;
            font-size: 13px;
            color: var(--color-info);
            display: flex;
            gap: 8px;
            align-items: flex-start;
        }

        .btn-submit {
            width: 100%;
            padding: 12px 16px;
            height: 44px;
            background-color: var(--color-primary);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 250ms ease-in-out;
            margin-bottom: 20px;
        }

        .btn-submit:hover {
            background-color: var(--color-primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 8px 16px rgba(99, 102, 241, 0.4);
        }

        .btn-submit:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .back-to-login {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
        }

        .back-to-login a {
            color: var(--color-primary);
            text-decoration: none;
            transition: all 250ms ease-in-out;
        }

        .back-to-login a:hover {
            color: var(--color-primary-dark);
            text-decoration: underline;
        }

        .alert {
            padding: 12px 14px;
            border-radius: 8px;
            display: flex;
            gap: 8px;
            align-items: flex-start;
            margin-bottom: 20px;
            font-size: 13px;
            animation: slideDown 200ms ease-out;
        }

        .alert-error {
            background-color: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: var(--color-danger);
        }

        .alert-success {
            background-color: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: var(--color-success);
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .auth-container {
                grid-template-columns: 1fr;
            }

            .auth-left-panel {
                display: none;
            }

            .auth-right-panel {
                padding: 16px;
                background: linear-gradient(135deg, rgba(99, 102, 241, 0.1) 0%, rgba(139, 92, 246, 0.1) 100%);
            }

            .auth-form-container {
                max-width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <!-- Left Panel -->
        <div class="auth-left-panel">
            <div class="auth-left-content">
                <div class="auth-logo">SK</div>
                <h1 class="auth-left-title">Sidekick</h1>
                <p class="auth-left-subtitle">Manage projects, collaborate with teams, and track progress seamlessly.</p>
                
                <div class="auth-features">
                    <div class="auth-feature">
                        <div class="auth-feature-icon">
                            <i class="fas fa-check"></i>
                        </div>
                        <span>Real-time collaboration</span>
                    </div>
                    <div class="auth-feature">
                        <div class="auth-feature-icon">
                            <i class="fas fa-check"></i>
                        </div>
                        <span>Advanced analytics</span>
                    </div>
                    <div class="auth-feature">
                        <div class="auth-feature-icon">
                            <i class="fas fa-check"></i>
                        </div>
                        <span>Team management</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Panel -->
        <div class="auth-right-panel">
            <div class="auth-form-container">
                <div class="auth-form-header">
                    <h1>Forgot Password</h1>
                    <p>No worries, we'll help you reset it.</p>
                </div>

                <div id="alert-container"></div>

                <div class="info-message">
                    <i class="fas fa-info-circle"></i>
                    <span>We'll send you an email with instructions to reset your password.</span>
                </div>

                <form id="forgot-form">
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <div class="input-wrapper">
                            <i class="fas fa-envelope input-icon"></i>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                placeholder="your@email.com" 
                                required
                                autocomplete="email"
                            >
                        </div>
                    </div>

                    <button type="submit" class="btn-submit">Send Reset Link</button>
                </form>

                <div class="back-to-login">
                    Back to <a href="/SideKick/public/login.php">Sign in</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Theme Toggle
        function initTheme() {
            const theme = localStorage.getItem('theme') || 'light';
            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
                document.documentElement.setAttribute('data-theme', 'dark');
            } else {
                document.documentElement.classList.remove('dark');
                document.documentElement.setAttribute('data-theme', 'light');
            }
        }

        // Form Submission
        const forgotForm = document.getElementById('forgot-form');
        if (forgotForm) {
            forgotForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                const submitBtn = forgotForm.querySelector('.btn-submit');
                const email = document.getElementById('email').value.trim();

                if (!email || !isValidEmail(email)) {
                    showAlert('Please enter a valid email address', 'error');
                    return;
                }

                try {
                    submitBtn.disabled = true;
                    submitBtn.textContent = 'Sending...';

                    const response = await fetch('/SideKick/api/auth/forgot-password.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({ email })
                    });

                    const data = await response.json();

                    if (data.success) {
                        showAlert('Reset link sent successfully! Check your email.', 'success');
                        setTimeout(() => {
                            window.location.href = '/SideKick/public/login.php';
                        }, 2000);
                    } else {
                        showAlert(data.message || 'Failed to send reset link. Please try again.', 'error');
                        submitBtn.disabled = false;
                        submitBtn.textContent = 'Send Reset Link';
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showAlert('An error occurred. Please try again.', 'error');
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Send Reset Link';
                }
            });
        }

        // Utility Functions
        function showAlert(message, type = 'info') {
            const alertContainer = document.getElementById('alert-container');
            const alert = document.createElement('div');
            alert.className = `alert alert-${type}`;
            alert.innerHTML = `
                <i class="fas fa-${type === 'error' ? 'exclamation-circle' : type === 'success' ? 'check-circle' : 'info-circle'}"></i>
                <span>${message}</span>
            `;
            alertContainer.innerHTML = '';
            alertContainer.appendChild(alert);

            if (type !== 'error') {
                setTimeout(() => alert.remove(), 4000);
            }
        }

        function isValidEmail(email) {
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
        }

        initTheme();
    </script>
</body>
</html>
