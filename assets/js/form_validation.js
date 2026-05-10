/**
 * Form Validation
 * Client-side validation for registration and login forms
 */

/**
 * Validate email format
 */
function isValidEmail(email) {
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return emailRegex.test(email);
}

/**
 * Validate password strength
 */
function validatePasswordStrength(password) {
  const errors = [];
  
  if (password.length < 8) {
    errors.push('Password must be at least 8 characters long');
  }
  
  if (!/[A-Z]/.test(password)) {
    errors.push('Password must contain at least one uppercase letter');
  }
  
  if (!/[a-z]/.test(password)) {
    errors.push('Password must contain at least one lowercase letter');
  }
  
  if (!/[0-9]/.test(password)) {
    errors.push('Password must contain at least one number');
  }
  
  return {
    valid: errors.length === 0,
    errors: errors
  };
}

/**
 * Show error message
 */
function showError(elementId, message) {
  const element = document.getElementById(elementId);
  if (element) {
    element.textContent = message;
    element.classList.add('show');
  }
}

/**
 * Hide error message
 */
function hideError(elementId) {
  const element = document.getElementById(elementId);
  if (element) {
    element.textContent = '';
    element.classList.remove('show');
  }
}

/**
 * Validate login form
 */
function validateLoginForm(email, password) {
  let isValid = true;
  
  // Validate email
  if (!email.trim()) {
    showError('email-error', 'Email is required');
    isValid = false;
  } else if (!isValidEmail(email)) {
    showError('email-error', 'Please enter a valid email address');
    isValid = false;
  } else {
    hideError('email-error');
  }
  
  // Validate password
  if (!password.trim()) {
    showError('password-error', 'Password is required');
    isValid = false;
  } else if (password.length < 8) {
    showError('password-error', 'Password must be at least 8 characters');
    isValid = false;
  } else {
    hideError('password-error');
  }
  
  return isValid;
}

/**
 * Validate registration form
 */
function validateRegisterForm(formData) {
  let isValid = true;
  
  // Validate first name
  if (!formData.first_name.trim()) {
    showError('first_name-error', 'First name is required');
    isValid = false;
  } else {
    hideError('first_name-error');
  }
  
  // Validate last name
  if (!formData.last_name.trim()) {
    showError('last_name-error', 'Last name is required');
    isValid = false;
  } else {
    hideError('last_name-error');
  }
  
  // Validate email
  if (!formData.email.trim()) {
    showError('email-error', 'Email is required');
    isValid = false;
  } else if (!isValidEmail(formData.email)) {
    showError('email-error', 'Please enter a valid email address');
    isValid = false;
  } else {
    hideError('email-error');
  }
  
  // Validate password strength
  if (!formData.password.trim()) {
    showError('password-error', 'Password is required');
    isValid = false;
  } else {
    const passwordValidation = validatePasswordStrength(formData.password);
    if (!passwordValidation.valid) {
      showError('password-error', passwordValidation.errors[0]);
      isValid = false;
    } else {
      hideError('password-error');
    }
  }
  
  // Validate password confirmation
  if (!formData.password_confirm.trim()) {
    showError('password_confirm-error', 'Please confirm your password');
    isValid = false;
  } else if (formData.password !== formData.password_confirm) {
    showError('password_confirm-error', 'Passwords do not match');
    isValid = false;
  } else {
    hideError('password_confirm-error');
  }
  
  // Validate role
  if (!formData.role) {
    showError('role-error', 'Please select a role');
    isValid = false;
  } else {
    hideError('role-error');
  }
  
  return isValid;
}

/**
 * Clear all error messages
 */
function clearAllErrors() {
  const errorElements = document.querySelectorAll('.form-error');
  errorElements.forEach(element => {
    element.textContent = '';
    element.classList.remove('show');
  });
}

// Attach real-time validation listeners
document.addEventListener('DOMContentLoaded', function() {
  // Email field validation
  const emailInput = document.querySelector('input[type="email"]');
  if (emailInput) {
    emailInput.addEventListener('blur', function() {
      if (this.value.trim()) {
        if (!isValidEmail(this.value)) {
          showError('email-error', 'Please enter a valid email address');
        } else {
          hideError('email-error');
        }
      }
    });
  }
  
  // Password confirmation validation
  const passwordConfirmInput = document.querySelector('input[name="password_confirm"]');
  if (passwordConfirmInput) {
    passwordConfirmInput.addEventListener('blur', function() {
      const passwordInput = document.querySelector('input[name="password"]');
      if (this.value && passwordInput && this.value !== passwordInput.value) {
        showError('password_confirm-error', 'Passwords do not match');
      } else {
        hideError('password_confirm-error');
      }
    });
  }
});
