// Utility functions for form validation

// Email validation function
function validateEmail(email) {
    const re = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    return re.test(String(email).toLowerCase());
}

// Password strength check function
function checkPasswordStrength(password) {
    const strength = {
        strong: /(?=.{8,})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*()_+])/,
        medium: /(?=.{6,})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])|
                 (?=.{6,})(?=.*[a-z])(?=.*[!@#$%^&*()_+])|
                 (?=.{6,})(?=.*[A-Z])(?=.*[!@#$%^&*()_+])/,
        weak: /.{6,}/
    };

    if (strength.strong.test(password)) {
        return 'strong';
    } else if (strength.medium.test(password)) {
        return 'medium';
    } else if (strength.weak.test(password)) {
        return 'weak';
    }
    return 'invalid';
}

// Function to enable interactive features on forms
function addFormInteractivity(formId) {
    const form = document.getElementById(formId);
    const emailInput = form.querySelector('input[type="email"]');
    const passwordInput = form.querySelector('input[type="password"]');

    emailInput.addEventListener('input', () => {
        if (validateEmail(emailInput.value)) {
            emailInput.setCustomValidity(''); // Clear any previous error message
        } else {
            emailInput.setCustomValidity('Please enter a valid email address.');
        }
    });

    passwordInput.addEventListener('input', () => {
        const strength = checkPasswordStrength(passwordInput.value);
        if (strength === 'strong') {
            passwordInput.setCustomValidity('');
        } else if (strength === 'medium') {
            passwordInput.setCustomValidity('Password is medium strength. Consider using a stronger password.');
        } else {
            passwordInput.setCustomValidity('Weak password. Please use a stronger password.');
        }
    });
}
