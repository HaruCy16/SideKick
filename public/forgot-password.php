<?php
// Password reset request handling
// TODO: Implement email verification and reset token functionality
?>

<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - SideKick</title>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/output.css" rel="stylesheet">
</head>
<body class="bg-slate-50 dark:bg-slate-900">
    <nav class="bg-white dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center text-white font-bold text-sm">SK</div>
                <span class="font-bold text-slate-900 dark:text-white">SideKick</span>
            </div>
            <button id="themeToggle" class="p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors text-lg">🌙</button>
        </div>
    </nav>

    <main class="min-h-screen flex items-center justify-center px-4 py-12">
        <div class="w-full max-w-md">
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 p-8">
                <div class="text-center mb-8">
                    <h1 class="text-2xl font-bold text-slate-900 dark:text-white mb-2">Forgot Password?</h1>
                    <p class="text-slate-600 dark:text-slate-400 text-sm">Enter your email and we'"'"'ll send you a link to reset your password</p>
                </div>
                <form id="forgotForm" class="space-y-4">
                    <div class="flex flex-col gap-1">
                        <label class="block text-xs font-medium text-slate-900 dark:text-white">Email Address</label>
                        <input type="email" id="email" placeholder="Enter your email" required class="w-full px-4 py-3 rounded-lg border border-slate-300 dark:border-slate-600 bg-slate-100 dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 dark:placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all">
                    </div>
                    <button type="submit" class="w-full px-4 py-3 rounded-lg bg-blue-600 text-white font-semibold text-sm hover:bg-blue-700 active:bg-blue-800 disabled:opacity-60 transition-all duration-250 shadow-sm hover:shadow-md mt-6">Send Reset Link</button>
                </form>
                <div class="text-center mt-6 text-sm">
                    <span class="text-slate-600 dark:text-slate-400">Remember your password? </span>
                    <a href="/SideKick/public/login.php" class="text-blue-600 hover:text-blue-700 dark:hover:text-blue-500 font-medium">Sign in</a>
                </div>
            </div>
            <div class="mt-6 p-4 rounded-lg bg-blue-50 dark:bg-blue-950/30 border border-blue-200 dark:border-blue-800 text-blue-700 dark:text-blue-400 text-xs">
                <i class="fas fa-info-circle mr-2"></i><span>Password reset functionality is currently in development.</span>
            </div>
        </div>
    </main>

    <script>
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
        document.getElementById('forgotForm').addEventListener('submit', (e) => {
            e.preventDefault();
            alert('Password reset functionality coming soon!');
        });
    </script>
</body>
</html>
