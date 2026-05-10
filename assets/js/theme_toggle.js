/**
 * Theme Toggle - Light/Dark Mode
 * Handles theme switching and persistence via localStorage
 */

// Initialize theme on page load
document.addEventListener('DOMContentLoaded', function() {
  const savedTheme = localStorage.getItem('theme') || 'light';
  applyTheme(savedTheme);
  updateThemeButton(savedTheme);
});

/**
 * Apply theme to document
 */
function applyTheme(theme) {
  if (theme === 'dark') {
    document.documentElement.setAttribute('data-theme', 'dark');
  } else {
    document.documentElement.removeAttribute('data-theme');
  }
}

/**
 * Update theme toggle button icon
 */
function updateThemeButton(theme) {
  const themeButton = document.getElementById('theme-toggle');
  if (themeButton) {
    if (theme === 'dark') {
      themeButton.textContent = '☀️'; // Show sun icon in dark mode
      themeButton.setAttribute('aria-label', 'Switch to light mode');
    } else {
      themeButton.textContent = '🌙'; // Show moon icon in light mode
      themeButton.setAttribute('aria-label', 'Switch to dark mode');
    }
  }
}

/**
 * Toggle theme
 */
function toggleTheme() {
  const currentTheme = document.documentElement.getAttribute('data-theme');
  const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
  
  applyTheme(newTheme);
  updateThemeButton(newTheme);
  localStorage.setItem('theme', newTheme);
}

// Attach toggle button event listener if it exists
document.addEventListener('DOMContentLoaded', function() {
  const themeButton = document.getElementById('theme-toggle');
  if (themeButton) {
    themeButton.addEventListener('click', toggleTheme);
  }
});
