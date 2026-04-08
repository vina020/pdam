document.addEventListener('DOMContentLoaded', function() {
    
    // Toggle Password Visibility
    const toggleButtons = document.querySelectorAll('.toggle-password');
    
    toggleButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const passwordInput = document.getElementById(targetId);
            
            if (passwordInput) {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                
                const icon = this.querySelector('i');
                if (type === 'password') {
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                    this.classList.remove('active');
                } else {
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                    this.classList.add('active');
                }
            }
        });
    });

    // Password Strength Checker
    const passwordInput = document.getElementById('password');
    const strengthFill = document.getElementById('strengthFill');
    const strengthText = document.getElementById('strengthText');

    if (passwordInput && strengthFill && strengthText) {
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            const strength = checkPasswordStrength(password);
            
            strengthFill.classList.remove('weak', 'medium', 'strong');
            strengthText.classList.remove('weak', 'medium', 'strong');
            
            if (password.length === 0) {
                strengthFill.style.width = '0%';
                strengthText.textContent = 'Kekuatan Password';
            } else if (strength < 3) {
                strengthFill.classList.add('weak');
                strengthText.classList.add('weak');
                strengthText.textContent = 'Password Lemah';
            } else if (strength < 4) {
                strengthFill.classList.add('medium');
                strengthText.classList.add('medium');
                strengthText.textContent = 'Password Sedang';
            } else {
                strengthFill.classList.add('strong');
                strengthText.classList.add('strong');
                strengthText.textContent = 'Password Kuat';
            }
        });
    }

    // Phone number formatting
    const phoneInput = document.getElementById('no_telepon');
    if (phoneInput) {
        phoneInput.addEventListener('input', function() {
            let value = this.value.replace(/\D/g, '');
            
            if (value.length > 13) {
                value = value.slice(0, 13);
            }
            
            this.value = value;
        });
    }

    // Social Register Button
    const googleBtn = document.querySelector('.btn-google');
    if (googleBtn) {
        googleBtn.addEventListener('click', function(e) {
            e.preventDefault();
            showNotification('Fitur registrasi dengan Google akan segera tersedia', 'info');
        });
    }

    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });
});

// Helper Functions
function checkPasswordStrength(password) {
    let strength = 0;
    
    if (password.length >= 8) strength++;
    if (password.length >= 12) strength++;
    if (/[a-z]/.test(password)) strength++;
    if (/[A-Z]/.test(password)) strength++;
    if (/\d/.test(password)) strength++;
    if (/[^a-zA-Z0-9]/.test(password)) strength++;
    
    return strength;
}

function showNotification(message, type = 'info') {
    const existingNotifs = document.querySelectorAll('.notification');
    existingNotifs.forEach(notif => notif.remove());

    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    
    const colors = {
        success: '#28a745',
        error: '#dc3545',
        info: '#17a2b8',
        warning: '#ffc107'
    };
    
    notification.style.cssText = `
        position: fixed;
        top: 100px;
        right: 20px;
        padding: 15px 25px;
        background: ${colors[type] || colors.info};
        color: white;
        border-radius: 8px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        z-index: 9999;
        animation: slideIn 0.3s ease;
        max-width: 300px;
    `;
    
    notification.textContent = message;
    document.body.appendChild(notification);

    setTimeout(() => {
        notification.style.opacity = '0';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(400px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
`;
document.head.appendChild(style);

console.log('Register page loaded successfully');
function closeModal() {
    const modal = document.getElementById('nomorPelangganModal');
    modal.style.animation = 'fadeOut 0.3s ease';
    setTimeout(() => {
        modal.remove();
    }, 300);
}

function copyNomorPelanggan() {
    const nomorText = '{{ session("nomor_pelanggan") }}';
    
    // Copy to clipboard
    navigator.clipboard.writeText(nomorText).then(() => {
        // Show notification
        const btn = event.target.closest('button');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check"></i> Tersalin!';
        btn.style.background = '#28a745';
        
        setTimeout(() => {
            btn.innerHTML = originalText;
            btn.style.background = '#6c757d';
        }, 2000);
    }).catch(err => {
        alert('Gagal menyalin. Silakan catat manual: ' + nomorText);
    });
}

// Prevent closing modal by clicking outside
document.getElementById('nomorPelangganModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        // Optional: bisa dikasi warning
        alert('Mohon simpan nomor pelanggan Anda terlebih dahulu!');
    }
});

//modal
function copyNomor() {
    const nomor = document.getElementById('nomorPelanggan').textContent;
    
    navigator.clipboard.writeText(nomor).then(() => {
        const btn = event.target;
        const originalHTML = btn.innerHTML;
        
        btn.innerHTML = '<i class="fas fa-check"></i> Tersalin!';
        btn.style.background = '#28a745';
        
        setTimeout(() => {
            btn.innerHTML = originalHTML;
            btn.style.background = '#6c757d';
        }, 2000);
    }).catch(err => {
        alert('Gagal menyalin. Nomor Anda: ' + nomor);
    });
}