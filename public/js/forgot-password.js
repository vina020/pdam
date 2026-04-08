// Forgot Password Page JavaScript

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('forgotPasswordForm');
    const emailInput = document.getElementById('email');
    const submitBtn = document.getElementById('submitBtn');

    // Email validation
    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    // Real-time email validation
    emailInput.addEventListener('input', function() {
        const email = this.value.trim();
        
        // Remove previous error message
        const existingError = this.parentElement.querySelector('.error-message');
        if (existingError && !existingError.classList.contains('server-error')) {
            existingError.remove();
        }

        // Validate if not empty
        if (email && !validateEmail(email)) {
            if (!this.parentElement.querySelector('.error-message')) {
                const errorMsg = document.createElement('span');
                errorMsg.className = 'error-message';
                errorMsg.textContent = 'Format email tidak valid';
                this.parentElement.appendChild(errorMsg);
            }
        }
    });

    // Form submission
    form.addEventListener('submit', function(e) {
        const email = emailInput.value.trim();

        // Clear previous errors
        const errorMessages = form.querySelectorAll('.error-message:not(.server-error)');
        errorMessages.forEach(error => error.remove());

        let isValid = true;

        // Validate email
        if (!email) {
            showError(emailInput, 'Email harus diisi');
            isValid = false;
        } else if (!validateEmail(email)) {
            showError(emailInput, 'Format email tidak valid');
            isValid = false;
        }

        if (!isValid) {
            e.preventDefault();
            return;
        }

        // Show loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengirim...';
    });

    // Show error message
    function showError(input, message) {
        const errorMsg = document.createElement('span');
        errorMsg.className = 'error-message';
        errorMsg.textContent = message;
        input.parentElement.appendChild(errorMsg);
        input.focus();
    }

    // Auto-hide success/error alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';
            setTimeout(() => {
                alert.remove();
            }, 500);
        }, 5000);
    });

    // Prevent multiple form submissions
    let isSubmitting = false;
    form.addEventListener('submit', function(e) {
        if (isSubmitting) {
            e.preventDefault();
            return;
        }
        isSubmitting = true;
    });

    // Handle browser back button
    window.addEventListener('pageshow', function(event) {
        if (event.persisted) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Kirim Link Reset Password';
            isSubmitting = false;
        }
    });
});