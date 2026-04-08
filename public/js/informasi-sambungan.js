// Informasi Sambungan Script
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('sambunganForm');
    const searchType = document.getElementById('search_type');
    const searchLabel = document.getElementById('searchLabel');
    const searchInput = document.getElementById('search_value');
    
    // Change label based on search type
    if (searchType) {
        searchType.addEventListener('change', function() {
            if (this.value === 'no_registrasi') {
                searchLabel.textContent = 'Nomor Registrasi';
                searchInput.placeholder = 'Masukkan nomor registrasi...';
            } else {
                searchLabel.textContent = 'NIK';
                searchInput.placeholder = 'Masukkan NIK...';
            }
        });
    }
    
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            cekSambungan();
        });
    }
});

// Store current application data
let currentApplication = null;

async function cekSambungan() {
    const searchType = document.getElementById('search_type').value;
    const searchValue = document.getElementById('search_value').value;
    const loadingState = document.getElementById('loadingState');
    const emptyState = document.getElementById('emptyState');
    const errorState = document.getElementById('errorState');
    const resultsSection = document.getElementById('resultsSection');
    const resultDivider = document.getElementById('resultDivider');

    console.log('Sending data:', { 
        search_type: searchType, 
        search_value: searchValue 
    });

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

        const response = await fetch('/layanan/informasi-sambungan/cek', {
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

        // Cek apakah response OK
        if (!response.ok) {
            const errorText = await response.text();
            console.error('Response not OK:', response.status, errorText);
            throw new Error(`Server error: ${response.status}`);
        }

        const data = await response.json();

        loadingState.style.display = 'none';

        if (data.success) {
            currentApplication = data.application;
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
    
    const app = data.application;
    
    // Set application header
    document.getElementById('app-no-reg').textContent = app.no_registrasi;
    document.getElementById('app-date').textContent = app.tanggal_pengajuan;
    
    // Set applicant info
    document.getElementById('info-nama').textContent = app.nama;
    document.getElementById('info-nik').textContent = app.nik;
    document.getElementById('info-telp').textContent = app.no_telp;
    document.getElementById('info-email').textContent = app.email;
    document.getElementById('info-alamat').textContent = app.alamat_pemasangan;
    document.getElementById('info-jenis').textContent = app.jenis_sambungan;
    document.getElementById('info-golongan').textContent = app.golongan;
    
    // Display progress timeline
    displayTimeline(app.timeline, app.status);
    
    // Display current status
    displayCurrentStatus(app);
    
    // Display documents if any
    if (app.documents && app.documents.length > 0) {
        displayDocuments(app.documents);
    } else {
        document.getElementById('documentsSection').style.display = 'none';
    }
    
    // Smooth scroll to results
    resultsSection.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

function displayTimeline(timeline, currentStatus) {
    const timelineContainer = document.getElementById('progressTimeline');
    timelineContainer.innerHTML = '';
    
    const statusOrder = ['pending', 'verifikasi', 'survei', 'approved', 'ditolak', 'selesai'];
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
        'verifikasi': 'fas fa-file-circle-check',
        'survei': 'fas fa-clipboard-check',
        'approved': 'fas fa-check',
        'ditolak': 'fas fa-xmark',
        'selesai': 'fas fa-check-double'
    };
    
    return icons[status] || 'fas fa-question';
}

function displayCurrentStatus(app) {
    const statusBadge = document.getElementById('current-status');
    const statusDescription = document.getElementById('status-description');
    const statusMeta = document.getElementById('status-meta');
    
    // Set status badge
    statusBadge.textContent = app.status_label;
    statusBadge.className = 'status-badge ' + getStatusClass(app.status);
    
    // Set description
    statusDescription.textContent = app.status_description;
    
    // Set meta info
    let metaHTML = '';
    
    if (app.estimasi_selesai) {
        metaHTML += `
            <div class="meta-item">
                <span class="label">Estimasi Selesai</span>
                <span class="value">${app.estimasi_selesai}</span>
            </div>
        `;
    }
    
    if (app.petugas) {
        metaHTML += `
            <div class="meta-item">
                <span class="label">Petugas</span>
                <span class="value">${app.petugas}</span>
            </div>
        `;
    }
    
    if (app.no_pelanggan) {
        metaHTML += `
            <div class="meta-item">
                <span class="label">No. Pelanggan</span>
                <span class="value">${app.no_pelanggan}</span>
            </div>
        `;
    }
    
    statusMeta.innerHTML = metaHTML;
}

function getStatusClass(status) {
    const classes = {
        'pending': 'warning',
        'verifikasi': 'info',
        'survei': 'primary',
        'approved': 'success',
        'selesai': 'success',
        'ditolak': 'danger'
    };
    
    return classes[status] || 'secondary';
}

function displayDocuments(documents) {
    const documentsSection = document.getElementById('documentsSection');
    const documentList = document.getElementById('documentList');
    
    documentsSection.style.display = 'block';
    documentList.innerHTML = '';
    
    documents.forEach(doc => {
        const docItem = `
            <div class="document-item">
                <div class="document-info">
                    <div class="document-icon">
                        <i class="fas fa-file-pdf"></i>
                    </div>
                    <div class="document-details">
                        <div class="document-name">${doc.name}</div>
                        <div class="document-size">${doc.size}</div>
                    </div>
                </div>
                <button onclick="downloadDocument('${doc.url}')" class="btn-download-doc">
                    <i class="fas fa-download"></i>
                    <span>Download</span>
                </button>
            </div>
        `;
        
        documentList.innerHTML += docItem;
    });
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
    if (currentApplication) {
        const searchType = document.getElementById('search_type').value;
        const searchValue = document.getElementById('search_value').value;
        
        // Re-fetch current application
        cekSambungan();
        
        // Show notification
        showNotification('Status berhasil di-refresh!');
    }
}

function downloadDocument(url) {
    window.open(url, '_blank');
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

console.log('Informasi Sambungan script loaded!');