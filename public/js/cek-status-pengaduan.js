// Cek Status Pengaduan Script
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('pengaduanForm');
    const searchType = document.getElementById('search_type');
    const searchLabel = document.getElementById('searchLabel');
    const searchInput = document.getElementById('search_value');
    
    // Change label based on search type
    if (searchType) {
        searchType.addEventListener('change', function() {
            if (this.value === 'nomor_pengaduan') {
                searchLabel.innerHTML = '<i class="fas fa-hashtag"></i> Nomor Pengaduan';
                searchInput.placeholder = 'Masukkan nomor pengaduan...';
            } else {
                searchLabel.innerHTML = '<i class="fas fa-id-badge"></i> Nomor Pelanggan';
                searchInput.placeholder = 'Masukkan nomor pelanggan...';
            }
        });
    }
    
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            cekStatusPengaduan();
        });
    }
});

// Store current pengaduan data
let currentPengaduan = null;

async function cekStatusPengaduan() {
    const searchType = document.getElementById('search_type').value;
    const searchValue = document.getElementById('search_value').value;
    const loadingState = document.getElementById('loadingState');
    const emptyState = document.getElementById('emptyState');
    const errorState = document.getElementById('errorState');
    const resultsSection = document.getElementById('resultsSection');
    const resultDivider = document.getElementById('resultDivider');

    // Reset states
    loadingState.style.display = 'block';
    emptyState.style.display = 'none';
    errorState.style.display = 'none';
    resultsSection.style.display = 'none';
    resultDivider.style.display = 'none';

    try {
        // Get CSRF token
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        
        if (!token) {
            throw new Error('CSRF token not found');
        }

        const response = await fetch('/layanan/cek-status-pengaduan/cek', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ 
                search_type: searchType,
                search_value: searchValue 
            })
        });

        if (!response.ok) {
            throw new Error(`Server error: ${response.status}`);
        }

        const data = await response.json();

        loadingState.style.display = 'none';

        if (data.success) {
            currentPengaduan = data.pengaduan;
            displayResults(data);
        } else {
            showError(data.message);
        }
    } catch (error) {
        loadingState.style.display = 'none';
        showError('Terjadi kesalahan saat mengambil data. Silakan coba lagi.');
        console.error('Error:', error);
    }
}

function displayResults(data) {
    const emptyState = document.getElementById('emptyState');
    const errorState = document.getElementById('errorState');
    const resultsSection = document.getElementById('resultsSection');
    const resultDivider = document.getElementById('resultDivider');
    
    // Hide all states
    emptyState.style.display = 'none';
    errorState.style.display = 'none';
    
    // Show divider and results
    resultDivider.style.display = 'block';
    resultsSection.style.display = 'block';
    
    const pengaduan = data.pengaduan;
    
    // Set header
    document.getElementById('pengaduan-no-reg').innerHTML = `<i class="fas fa-hashtag"></i> ${pengaduan.nomor_pengaduan}`;
    document.getElementById('pengaduan-date').innerHTML = `<i class="fas fa-calendar"></i> ${pengaduan.tanggal_pengaduan}`;
    
    // Set info pelapor
    document.getElementById('info-nama').textContent = pengaduan.nama;
    document.getElementById('info-pelanggan').textContent = pengaduan.no_pelanggan;
    document.getElementById('info-whatsapp').textContent = pengaduan.no_whatsapp;
    document.getElementById('info-alamat').textContent = pengaduan.alamat;
    
    // Set detail pengaduan
    document.getElementById('info-jenis').textContent = pengaduan.jenis_pengaduan;
    document.getElementById('info-detail').textContent = pengaduan.informasi_pengaduan;
    
    // Display progress timeline
    displayTimeline(pengaduan.timeline, pengaduan.status);
    
    // Display current status
    displayCurrentStatus(pengaduan);
    
    // Smooth scroll to results
    resultsSection.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

function displayTimeline(timeline, currentStatus) {
    const timelineContainer = document.getElementById('progressTimeline');
    timelineContainer.innerHTML = '';
    
    const statusOrder = ['pending', 'proses', 'selesai'];
    const currentIndex = statusOrder.indexOf(currentStatus);
    
    timeline.forEach((item, index) => {
        const status = item.status;
        const statusIndex = statusOrder.indexOf(status);
        
        let itemClass = 'inactive';
        if (statusIndex < currentIndex) {
            itemClass = 'completed';
        } else if (statusIndex === currentIndex) {
            itemClass = 'active';
        }
        
        const iconClass = getStatusIconClass(status);
        
        const timelineItem = `
            <div class="timeline-item ${itemClass}">
                <div class="timeline-icon ${status}">
                    <i class="${iconClass}"></i>
                </div>
                <div class="timeline-content">
                    <div class="timeline-title">${item.title}</div>
                    ${item.date ? `
                        <div class="timeline-date">
                            <i class="fas fa-calendar"></i>
                            ${item.date}
                        </div>
                    ` : ''}
                    ${item.description ? `
                        <div class="timeline-desc">${item.description}</div>
                    ` : ''}
                </div>
            </div>
        `;
        
        timelineContainer.innerHTML += timelineItem;
    });
}

function getStatusIconClass(status) {
    const icons = {
        'pending': 'fas fa-clock',
        'proses': 'fas fa-cog',
        'selesai': 'fas fa-check-double'
    };
    
    return icons[status] || 'fas fa-question';
}

function displayCurrentStatus(pengaduan) {
    const statusBadge = document.getElementById('current-status');
    const statusDescription = document.getElementById('status-description');
    const tanggapanText = document.getElementById('tanggapan-text');
    
    // Set status badge
    statusBadge.textContent = pengaduan.status_label;
    statusBadge.className = 'status-badge ' + getStatusClass(pengaduan.status);
    
    // Set description
    statusDescription.textContent = pengaduan.status_description;
    
    // Set tanggapan
    tanggapanText.textContent = pengaduan.tanggapan;
}

function getStatusClass(status) {
    const classes = {
        'pending': 'warning',
        'proses': 'primary',
        'selesai': 'success'
    };
    
    return classes[status] || 'secondary';
}

function showError(message) {
    const emptyState = document.getElementById('emptyState');
    const errorState = document.getElementById('errorState');
    const resultsSection = document.getElementById('resultsSection');
    const resultDivider = document.getElementById('resultDivider');
    
    // Hide other states
    emptyState.style.display = 'none';
    resultsSection.style.display = 'none';
    resultDivider.style.display = 'none';
    
    // Show error
    document.getElementById('errorMessage').textContent = message;
    errorState.style.display = 'block';
}

function printInfo() {
    window.print();
}

function refreshStatus() {
    if (currentPengaduan) {
        // Re-fetch current pengaduan
        cekStatusPengaduan();
        
        // Show notification
        showNotification('Status berhasil di-refresh!');
    }
}

function showNotification(message) {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = 'notification';
    notification.innerHTML = `
        <i class="fas fa-check-circle"></i>
        <span>${message}</span>
    `;
    
    // Add styles
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: linear-gradient(135deg, #198754, #20c997);
        color: white;
        padding: 15px 25px;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(25, 135, 84, 0.3);
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 600;
        z-index: 10000;
        animation: slideInRight 0.3s ease;
    `;
    
    document.body.appendChild(notification);
    
    // Remove after 3 seconds
    setTimeout(() => {
        notification.style.animation = 'slideOutRight 0.3s ease';
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
}

// Add animation styles
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInRight {
        from {
            transform: translateX(400px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(400px);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);

console.log('Cek Status Pengaduan script loaded!');