/**
 * Authentication - AJAX Form Submission
 * Handles login and registration form submissions via AJAX
 */

/**
 * Handle login form submission
 */
function handleLoginSubmit(event) {
  event.preventDefault();
  
  const form = event.target;
  const submitButton = form.querySelector('button[type="submit"]');
  
  // Collect form data
  const email = form.querySelector('input[name="email"]').value;
  const password = form.querySelector('input[name="password"]').value;
  
  // Validate form
  if (!validateLoginForm(email, password)) {
    return;
  }
  
  // Show loading state
  submitButton.disabled = true;
  submitButton.innerHTML = '<span class="loading"></span> Logging in...';
  
  // Send AJAX request
  fetch('/SideKick/api/auth/login.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    credentials: 'include',
    body: JSON.stringify({
      email: email,
      password: password
    })
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      // Show success message
      showFormAlert('success', 'Login successful! Redirecting...');
      
      // Redirect after brief delay
      setTimeout(() => {
        window.location.href = '/SideKick/public/dashboard.php';
      }, 1000);
    } else {
      // Show error message
      showFormAlert('error', data.message || 'Login failed. Please try again.');
      submitButton.disabled = false;
      submitButton.innerHTML = 'Login';
    }
  })
  .catch(error => {
    console.error('Error:', error);
    showFormAlert('error', 'Network error. Please try again.');
    submitButton.disabled = false;
    submitButton.innerHTML = 'Login';
  });
}

/**
 * Handle registration form submission
 */
function handleRegisterSubmit(event) {
  event.preventDefault();
  
  const form = event.target;
  const submitButton = form.querySelector('button[type="submit"]');
  
  // Collect form data
  const formData = {
    first_name: form.querySelector('input[name="first_name"]').value,
    last_name: form.querySelector('input[name="last_name"]').value,
    email: form.querySelector('input[name="email"]').value,
    password: form.querySelector('input[name="password"]').value,
    password_confirm: form.querySelector('input[name="password_confirm"]').value,
    role: form.querySelector('select[name="role"]').value
  };
  
  // Validate form
  if (!validateRegisterForm(formData)) {
    return;
  }
  
  // Show loading state
  submitButton.disabled = true;
  submitButton.innerHTML = '<span class="loading"></span> Creating account...';
  
  // Send AJAX request
  fetch('/SideKick/api/auth/register.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    credentials: 'include',
    body: JSON.stringify(formData)
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      // Show success message
      showFormAlert('success', data.message + ' Redirecting to login...');
      
      // Redirect to login page after delay
      setTimeout(() => {
        window.location.href = '/SideKick/public/login.php';
      }, 2000);
    } else {
      // Show error message
      showFormAlert('error', data.message || 'Registration failed. Please try again.');
      submitButton.disabled = false;
      submitButton.innerHTML = 'Create Account';
    }
  })
  .catch(error => {
    console.error('Error:', error);
    showFormAlert('error', 'Network error. Please try again.');
    submitButton.disabled = false;
    submitButton.innerHTML = 'Create Account';
  });
}

/**
 * Handle logout
 */
function handleLogout(event) {
  if (event) {
    event.preventDefault();
  }
  
  if (!confirm('Are you sure you want to log out?')) {
    return;
  }
  
  fetch('/SideKick/api/auth/logout.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    credentials: 'include'
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      // Redirect to login page
      window.location.href = '/SideKick/public/login.php';
    }
  })
  .catch(error => {
    console.error('Error:', error);
    // Still redirect on error
    window.location.href = '/SideKick/public/login.php';
  });
}

/**
 * Show form alert message
 */
function showFormAlert(type, message) {
  let alertClass = 'alert-success';
  if (type === 'error') {
    alertClass = 'alert-danger';
  } else if (type === 'warning') {
    alertClass = 'alert-warning';
  }
  
  const alertElement = document.createElement('div');
  alertElement.className = `alert ${alertClass} mt-2 mb-2`;
  alertElement.textContent = message;
  
  const form = document.querySelector('form');
  if (form) {
    form.insertBefore(alertElement, form.firstChild);
    
    // Auto-remove alert after 5 seconds (unless it's success, keep longer)
    if (type !== 'success') {
      setTimeout(() => {
        alertElement.remove();
      }, 5000);
    }
  }
}

/**
 * Check if user is logged in
 */
function checkSession() {
  fetch('/SideKick/api/auth/check_session.php', {
    method: 'GET',
    headers: {
      'Content-Type': 'application/json',
    },
    credentials: 'include'
  })
  .then(response => response.json())
  .then(data => {
    if (!data.success) {
      // User not logged in, redirect to login
      window.location.href = '/SideKick/public/login.php';
    }
  })
  .catch(error => {
    console.error('Session check error:', error);
  });
}

// Attach form submission handlers when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
  // Login form
  const loginForm = document.getElementById('login-form');
  if (loginForm) {
    loginForm.addEventListener('submit', handleLoginSubmit);
  }
  
  // Registration form
  const registerForm = document.getElementById('register-form');
  if (registerForm) {
    registerForm.addEventListener('submit', handleRegisterSubmit);
  }
  
  // Logout button
  const logoutButtons = document.querySelectorAll('[data-action="logout"]');
  logoutButtons.forEach(button => {
    button.addEventListener('click', handleLogout);
  });
});
