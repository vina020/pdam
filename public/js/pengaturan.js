// Initialize
document.addEventListener('DOMContentLoaded', function() {
    initTabs();
    initPhotoUpload();
    initPasswordStrength();
    initThemeSelector();
    initColorSelector();
    initForms();
});

// Tab Navigation
function initTabs() {
    const menuItems = document.querySelectorAll('.pengaturan-menu-item');
    const tabContents = document.querySelectorAll('.tab-content');

    menuItems.forEach(item => {
        item.addEventListener('click', function() {
            const tabId = this.dataset.tab;

            // Remove active class
            menuItems.forEach(m => m.classList.remove('active'));
            tabContents.forEach(t => t.classList.remove('active'));

            // Add active class
            this.classList.add('active');
            document.getElementById(tabId).classList.add('active');
        });
    });
}

// Photo Upload
function initPhotoUpload() {
    const photoInput = document.getElementById('photoInput');
    const photoPreview = document.getElementById('photoPreview');

    if (photoInput) {
        photoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            
            if (!file) return;

            // Validate file type
            if (!file.type.startsWith('image/')) {
                showNotification('File harus berupa gambar!', 'error');
                return;
            }

            // Validate file size (max 2MB)
            if (file.size > 2 * 1024 * 1024) {
                showNotification('Ukuran file maksimal 2MB!', 'error');
                return;
            }

            // Preview image
            const reader = new FileReader();
            reader.onload = function(e) {
                photoPreview.src = e.target.result;
            };
            reader.readAsDataURL(file);

            // Upload to server
            uploadPhoto(file);
        });
    }
}

function getApiUrl(endpoint) {
    const currentPath = window.location.pathname;

    if (currentPath.includes('/admin')) {
        const urls = {
            'upload-foto' : '/admin/pengaturan/upload-foto',
            'update-profile': '/admin/pengaturan/update-profil',
            'update-password': '/admin/pengaturan/update-password',
            'update-notifikasi': '/admin/pengaturan/update-notifikasi',
            'update-tampilan': '/admin/pengaturan/update-tampilan',
            'update-preferensi': '/admin/pengaturan/update-preferensi',
            'logout-all': '/admin/pengaturan/logout-all'
        };
        return urls[endpoint];
    } else {
        const urls = {
            'upload-foto' : '/user/pengaturan/upload-foto',
            'update-profile': '/user/pengaturan/update-profil', 
            'update-password': '/user/pengaturan/update-password',
            'update-notifikasi': '/user/pengaturan/update-notifikasi',
            'update-tampilan': '/user/pengaturan/update-tampilan',
            'update-preferensi': '/user/pengaturan/update-preferensi',
            'logout-all': '/user/pengaturan/logout-all'
        };
        return urls[endpoint];
    }
}

// Upload Photo
async function uploadPhoto(file) {
    const formData = new FormData();
    formData.append('foto_profil', file);

    try {
        const response = await fetch(getApiUrl('upload-foto'), {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: formData
        });

        const result = await response.json();

        console.log('Upload response:', result);

        if (result.foto_url) {
            const photoPreview = document.getElementById('photoPreview');
            if (photoPreview) {
                photoPreview.src = result.foto_url + '?t=' + new Date().getTime();
            }

            const navbarPhoto = document.querySelector('.navbar .user-avatar img');
            if (navbarPhoto) {
                navbarPhoto.src = result.foto_url + '?t=' + new Date().getTime();
            }
        } else {
            showNotification(result.message || 'Gagal upload foto', 'error');
        }

        if (result.success) {
            showNotification('Profil berhasil diperbarui', 'success');
            if (result.foto_url) {
                const photoPreview = document.getElementById('photoPreview');
                if (photoPreview) {
                    photoPreview.src = result.foto_url;
                }
            }
        } else {
            showNotification(result.message || 'Gagal upload foto', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('Terjadi kesalahan saat upload foto', 'error');
    }
}

// Password Strength Checker
function initPasswordStrength() {
    const newPassword = document.getElementById('newPassword');
    
    if (newPassword) {
        newPassword.addEventListener('input', function() {
            const password = this.value;
            const strengthFill = document.getElementById('strengthFill');
            const strengthText = document.getElementById('strengthText');

            // Calculate strength
            let strength = 0;
            if (password.length >= 8) strength++;
            if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength++;
            if (password.match(/[0-9]/)) strength++;
            if (password.match(/[^a-zA-Z0-9]/)) strength++;

            // Update UI
            strengthFill.className = 'strength-fill';
            strengthText.className = 'strength-text';

            if (strength <= 1) {
                strengthFill.classList.add('weak');
                strengthText.classList.add('weak');
                strengthText.textContent = 'Lemah';
            } else if (strength <= 3) {
                strengthFill.classList.add('medium');
                strengthText.classList.add('medium');
                strengthText.textContent = 'Sedang';
            } else {
                strengthFill.classList.add('strong');
                strengthText.classList.add('strong');
                strengthText.textContent = 'Kuat';
            }
        });
    }
}

// Toggle Password Visibility
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const button = input.parentElement.querySelector('.toggle-password');
    const icon = button.querySelector('i');

    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

// Theme Selector
function initThemeSelector() {
    const themeItems = document.querySelectorAll('.theme-item');

    themeItems.forEach(item => {
        item.addEventListener('click', function() {
            themeItems.forEach(t => t.classList.remove('active'));
            this.classList.add('active');

            const theme = this.dataset.theme;
            applyTheme(theme);
        });
    });
}

// Apply Theme
function applyTheme(theme) {
    // Save to localStorage
    localStorage.setItem('theme', theme);

    // Apply theme
    if (theme === 'dark') {
        document.body.classList.add('dark-mode');
    } else if (theme === 'light') {
        document.body.classList.remove('dark-mode');
    } else {
        // Auto - check system preference
        if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
            document.body.classList.add('dark-mode');
        } else {
            document.body.classList.remove('dark-mode');
        }
    }

    showNotification('Tema berhasil diubah', 'success');
}

// Color Selector
function initColorSelector() {
    const colorItems = document.querySelectorAll('.color-item');

    colorItems.forEach(item => {
        item.addEventListener('click', function() {
            colorItems.forEach(c => c.classList.remove('active'));
            this.classList.add('active');

            const color = this.dataset.color;
            applyAccentColor(color);
        });
    });
}

// Apply Accent Color
function applyAccentColor(color) {
    const colors = {
        blue: '#0d6efd',
        cyan: '#0dcaf0',
        green: '#198754',
        orange: '#fd7e14',
        purple: '#6f42c1',
        red: '#dc3545'
    };

    document.documentElement.style.setProperty('--accent-color', colors[color]);
    localStorage.setItem('accentColor', color);

    showNotification('Warna aksen berhasil diubah', 'success');
}

// Initialize Forms
function initForms() {
    // Profil Form
    const profilForm = document.getElementById('profilForm');
    if (profilForm) {
        profilForm.addEventListener('submit', handleProfilSubmit);
    }

    // Password Form
    const passwordForm = document.getElementById('passwordForm');
    if (passwordForm) {
        passwordForm.addEventListener('submit', handlePasswordSubmit);
    }

    // Notifikasi Form
    const notifikasiForm = document.getElementById('notifikasiForm');
    if (notifikasiForm) {
        notifikasiForm.addEventListener('submit', handleNotifikasiSubmit);
    }

    // Tampilan Form
    const tampilanForm = document.getElementById('tampilanForm');
    if (tampilanForm) {
        tampilanForm.addEventListener('submit', handleTampilanSubmit);
    }

    // Preferensi Form
    const preferensiForm = document.getElementById('preferensiForm');
    if (preferensiForm) {
        preferensiForm.addEventListener('submit', handlePreferensiSubmit);
    }
}

// Handle Profil Submit
async function handleProfilSubmit(e) {
    e.preventDefault();
    
    const namaLengkap = document.getElementById('nama_lengkap');
    const email = document.getElementById('email');
    const noTelepon = document.getElementById('no_telepon');
    const jabatan = document.getElementById('jabatan');
    const alamat = document.getElementById('alamat');

    if (!namaLengkap || !email) {
        console.error('Required form elements not found');
        showNotification('Form tidak lengkap', 'error');
        return;
    }

    const formData = {
        nama_lengkap: namaLengkap.value,
        email: email.value,
        no_telepon: noTelepon ? noTelepon.value : '',
        jabatan: jabatan ? jabatan.value : '',
        alamat: alamat ? alamat.value : ''
    };
    
    console.log('Sending data:', formData);
    
    try {
        const response = await fetch(getApiUrl('update-profile'), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(formData)
        });
        
        const data = await response.json();
        
        console.log('Response status:', response.status);
        console.log('Response body:', data);
        
        if (response.ok && data.success) {
            alert(data.message || 'Profil berhasil diperbarui!', 'success');
        } else {
            alert(data.message || 'Gagal memperbarui profil', 'error');
        }
        
    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat memperbarui profil', 'error');
    }
}

// Handle Password Submit
async function handlePasswordSubmit(e) {
    e.preventDefault();

    const passwordLama = document.querySelector('[name="password_lama"]').value;
    const passwordBaru = document.querySelector('[name="password_baru"]').value;
    const passwordKonfirmasi = document.querySelector('[name="password_konfirmasi"]').value;

    // Validate
    if (!passwordBaru || !passwordBaru || !passwordKonfirmasi) {
        showNotification('Form tidak lengkap!', 'error');
        return;
    }

    if (passwordBaru.value !== passwordKonfirmasi.value) {
        showNotification('Password baru dan konfirmasi tidak cocok!', 'error');
        return;
    }

    if (passwordBaru.length < 8) {
        showNotification('Password minimal 8 karakter!', 'error');
        return;
    }

    const formData = {
        password_lama: passwordLama,
        password_baru: passwordBaru,
        password_konfirmasi: passwordKonfirmasi
    };

    try {
        const response = await fetch(getApiUrl('update-password'), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(formData)
        });

        const result = await response.json();

        if (result.success) {
            showNotification('Password berhasil diubah!', 'success');
            document.getElementById('passwordForm').reset();
        } else {
            showNotification(result.message || 'Gagal mengubah password', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('Terjadi kesalahan', 'error');
    }
}

// Handle Notifikasi Submit
async function handleNotifikasiSubmit(e) {
    e.preventDefault();

    const formData = {
        email_berita: document.querySelector('[name="email_berita"]').checked,
        email_pengaduan: document.querySelector('[name="email_pengaduan"]').checked,
        push_notif: document.querySelector('[name="push_notif"]').checked,
    };

    try {
        const response = await fetch(getApiUrl('update-notifikasi'), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(formData)
        });

        const result = await response.json();

        if (result.success) {
            showNotification('Preferensi notifikasi berhasil disimpan!', 'success');
        } else {
            showNotification(result.message || 'Gagal menyimpan preferensi', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('Terjadi kesalahan', 'error');
    }
}

// Handle Tampilan Submit
async function handleTampilanSubmit(e) {
    e.preventDefault();

    const fontSize = document.querySelector('[name="font_size"]').value;
    const theme = localStorage.getItem('theme') || 'light';
    const accentColor = localStorage.getItem('accentColor') || 'blue';

    const formData = {
        theme: theme,
        accent_color: accentColor,
        font_size: fontSize,
    };

    try {
        const response = await fetch(getApiUrl('update-tampilan'), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(formData)
        });

        const result = await response.json();

        if (result.success) {
            applyTheme(theme);
            applyAccentColor(accentColor);
            showNotification('Pengaturan tampilan berhasil disimpan!', 'success');
        } else {
            showNotification(result.message || 'Gagal menyimpan pengaturan', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('Terjadi kesalahan', 'error');
    }
}

// Handle Preferensi Submit
async function handlePreferensiSubmit(e) {
    e.preventDefault();

    const formData = {
        bahasa: document.querySelector('[name="bahasa"]').value,
        timezone: document.querySelector('[name="timezone"]').value,
        date_format: document.querySelector('[name="date_format"]').value,
        items_per_page: document.querySelector('[name="items_per_page"]').value,
    };

    try {
        const response = await fetch(getApiUrl('update-preferensi'), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(formData)
        });

        const result = await response.json();

        if (result.success) {
            showNotification('Preferensi berhasil disimpan!', 'success');
        } else {
            showNotification(result.message || 'Gagal menyimpan preferensi', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('Terjadi kesalahan', 'error');
    }
}

// Confirm Logout
function confirmLogout() {
    if (confirm('Apakah Anda yakin ingin logout dari semua perangkat? Anda perlu login ulang di semua perangkat.')) {
        logoutAllDevices();
    }
}

// Logout All Devices
async function logoutAllDevices() {
    try {
        const response = await fetch(getApiUrl('logout-all'), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });

        const result = await response.json();

        if (result.success) {
            showNotification('Berhasil logout dari semua perangkat', 'success');
            setTimeout(() => {
                window.location.href = '/login';
            }, 2000);
        } else {
            showNotification(result.message || 'Gagal logout', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('Terjadi kesalahan', 'error');
    }
}

// Notification
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
        <span>${message}</span>
    `;
    
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${type === 'success' ? 'linear-gradient(135deg, #198754, #20c997)' : 'linear-gradient(135deg, #dc3545, #f85149)'};
        color: white;
        padding: 15px 25px;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 600;
        z-index: 10000;
        animation: slideIn 0.3s ease;
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Add animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from { transform: translateX(400px); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    @keyframes slideOut {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(400px); opacity: 0; }
    }
`;
document.head.appendChild(style);

console.log('Pengaturan script loaded!');