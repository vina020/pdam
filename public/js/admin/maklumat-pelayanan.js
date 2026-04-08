// Global variables
let currentPage = 1;
let currentKategori = 'all';
let currentSearch = '';
let isEditMode = false;
let editingId = null;

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    loadData();
    initEventListeners();
});

// Event Listeners
function initEventListeners() {
    // Search
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', debounce(function() {
            currentSearch = this.value;
            currentPage = 1;
            loadData();
        }, 500));
    }

    // Filter kategori
    const kategoriFilter = document.getElementById('kategoriFilter');
    if (kategoriFilter) {
        kategoriFilter.addEventListener('change', function() {
            currentKategori = this.value;
            currentPage = 1;
            loadData();
        });
    }

    // Form submit
    const form = document.getElementById('maklumatForm');
    if (form) {
        form.addEventListener('submit', handleFormSubmit);
    }

    // Close modal saat klik tombol X
    const btnCloseModal = document.querySelector('.btn-close-modal');
    if (btnCloseModal) {
        btnCloseModal.addEventListener('click', closeModal);
    }

    // Close modal saat klik di luar modal box
    const modalOverlay = document.getElementById('formModal');
    if (modalOverlay) {
        modalOverlay.addEventListener('click', function(e) {
            if (e.target === modalOverlay) {
                closeModal();
            }
        });
    }

    // Close modal dengan ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('formModal');
            if (modal.classList.contains('active')) {
                closeModal();
            }
        }
    });
}

// Load data
async function loadData() {
    showLoading();

    try {
        const url = `/admin/maklumat-pelayanan/get-all?page=${currentPage}&search=${currentSearch}&kategori=${currentKategori}`;
        const response = await fetch(url);
        const result = await response.json();

        if (result.success) {
            updateStats(result.stats);
            displayData(result.data);
            updatePagination(result.pagination);
        }
    } catch (error) {
        console.error('Error loading data:', error);
        showError('Gagal memuat data');
    }
}

// Update stats
function updateStats(stats) {
    document.getElementById('totalItems').textContent = stats.total || 0;
    document.getElementById('totalAktif').textContent = stats.aktif || 0;
    document.getElementById('totalStandar').textContent = stats.standar_pelayanan || 0;
    document.getElementById('totalKualitas').textContent = stats.kualitas_air || 0;
    document.getElementById('totalHak').textContent = stats.hak_pelanggan || 0;
    document.getElementById('totalKewajiban').textContent = stats.kewajiban_pelanggan || 0;
    document.getElementById('totalSanksi').textContent = stats.sanksi || 0;
    document.getElementById('totalPengaduan').textContent = stats.pengaduan || 0;
}

// Display data
function displayData(data) {
    const tableBody = document.getElementById('tableBody');
    const loadingState = document.getElementById('loadingState');
    const emptyState = document.getElementById('emptyState');
    const tableContainer = document.getElementById('tableContainer');

    loadingState.style.display = 'none';

    if (!data || data.length === 0) {
        emptyState.style.display = 'block';
        tableContainer.style.display = 'none';
        return;
    }

    emptyState.style.display = 'none';
    tableContainer.style.display = 'block';

    let html = '';
    data.forEach((item, index) => {
        const rowNumber = (currentPage - 1) * 10 + index + 1;
        const kategoriClass = item.kategori.replace('_', ' ');
        
        html += `
            <tr>
                <td>${rowNumber}</td>
                <td>
                    <span class="kategori-badge ${item.kategori}">
                        ${formatKategori(item.kategori)}
                    </span>
                </td>
                <td><strong>${item.judul}</strong></td>
                <td>${item.deskripsi ? truncateText(item.deskripsi, 80) : '-'}</td>
                <td>
                    ${item.icon ? `<div class="icon-preview"><i class="${item.icon}"></i></div>` : '-'}
                </td>
                <td>
                    <div class="urutan-badge">${item.urutan}</div>
                </td>
                <td>
                    <label class="status-toggle">
                        <input type="checkbox" ${item.aktif ? 'checked' : ''} 
                               onchange="toggleStatus(${item.id})">
                        <span class="toggle-slider"></span>
                    </label>
                </td>
                <td>
                    <div class="action-buttons">
                        <button class="btn-action btn-edit" onclick="editItem(${item.id})" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn-action btn-delete" onclick="deleteItem(${item.id})" title="Hapus">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    });

    tableBody.innerHTML = html;
}

// Format kategori
function formatKategori(kategori) {
    const labels = {
        'standar_pelayanan': 'Standar Pelayanan',
        'kualitas_air': 'Kualitas Air',
        'hak_pelanggan': 'Hak Pelanggan',
        'kewajiban_pelanggan': 'Kewajiban Pelanggan',
        'sanksi': 'Sanksi',
        'pengaduan': 'Pengaduan'
    };
    return labels[kategori] || kategori;
}

// Update pagination
function updatePagination(pagination) {
    const container = document.getElementById('paginationContainer');
    const buttonsContainer = document.getElementById('paginationButtons');

    if (pagination.total === 0) {
        container.style.display = 'none';
        return;
    }

    container.style.display = 'flex';

    // Update info
    const from = (pagination.current_page - 1) * pagination.per_page + 1;
    const to = Math.min(pagination.current_page * pagination.per_page, pagination.total);
    
    document.getElementById('showingFrom').textContent = from;
    document.getElementById('showingTo').textContent = to;
    document.getElementById('totalData').textContent = pagination.total;

    // Generate buttons
    let buttonsHtml = '';

    // Previous button
    buttonsHtml += `
        <button class="pagination-btn ${pagination.current_page === 1 ? 'disabled' : ''}" 
                onclick="changePage(${pagination.current_page - 1})"
                ${pagination.current_page === 1 ? 'disabled' : ''}>
            <i class="fas fa-chevron-left"></i>
        </button>
    `;

    // Page numbers
    for (let i = 1; i <= pagination.last_page; i++) {
        if (
            i === 1 ||
            i === pagination.last_page ||
            (i >= pagination.current_page - 1 && i <= pagination.current_page + 1)
        ) {
            buttonsHtml += `
                <button class="pagination-btn ${i === pagination.current_page ? 'active' : ''}" 
                        onclick="changePage(${i})">
                    ${i}
                </button>
            `;
        } else if (i === pagination.current_page - 2 || i === pagination.current_page + 2) {
            buttonsHtml += `<span class="pagination-dots">...</span>`;
        }
    }

    // Next button
    buttonsHtml += `
        <button class="pagination-btn ${pagination.current_page === pagination.last_page ? 'disabled' : ''}" 
                onclick="changePage(${pagination.current_page + 1})"
                ${pagination.current_page === pagination.last_page ? 'disabled' : ''}>
            <i class="fas fa-chevron-right"></i>
        </button>
    `;

    buttonsContainer.innerHTML = buttonsHtml;
}

// Change page
function changePage(page) {
    currentPage = page;
    loadData();
}

// Modal functions
function openAddModal() {
    isEditMode = false;
    editingId = null;
    
    document.getElementById('modalTitle').innerHTML = '<i class="fas fa-plus"></i> Tambah Maklumat';
    document.getElementById('maklumatForm').reset();
    document.getElementById('itemId').value = '';
    document.getElementById('aktif').checked = true;
    
    const modal = document.getElementById('formModal');
    modal.style.display = 'flex';
    modal.classList.add('active');
}

function closeModal() {
    const modal = document.getElementById('formModal');
    modal.style.display = 'none';
    modal.classList.remove('active');

    document.getElementById('maklumatForm').reset();
    isEditMode = false;
    editingId = null;

    console.log('Modal closed');
}

// Edit item
async function editItem(id) {
    try {
        const response = await fetch(`/admin/maklumat-pelayanan/get-detail/${id}`);
        const result = await response.json();

        if (result.success) {
            const data = result.data;
            
            isEditMode = true;
            editingId = id;
            
            document.getElementById('modalTitle').innerHTML = '<i class="fas fa-edit"></i> Edit Item';
            document.getElementById('itemId').value = data.id;
            document.getElementById('kategori').value = data.kategori;
            document.getElementById('judul').value = data.judul;
            document.getElementById('deskripsi').value = data.deskripsi || '';
            document.getElementById('icon').value = data.icon || '';
            document.getElementById('color').value = data.color || '';
            document.getElementById('urutan').value = data.urutan;
            document.getElementById('aktif').checked = data.aktif;
            
            const modal = document.getElementById('formModal');
            modal.style.display = 'flex';
            modal.classList.add('active');
        } else {
            showNotification('Gagal memuat data', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('Gagal memuat data', 'error');
    }
}

// Handle form submit
async function handleFormSubmit(e) {
    e.preventDefault();

    const formData = {
        kategori: document.getElementById('kategori').value,
        judul: document.getElementById('judul').value,
        deskripsi: document.getElementById('deskripsi').value || '',
        icon: document.getElementById('icon').value || '',
        color: document.getElementById('color').value || '',
        urutan: parseInt(document.getElementById('urutan').value) || 0,
        aktif: document.getElementById('aktif').checked
    };

    console.log('Sending data:', formData);

    try {
        const url = isEditMode 
            ? `/admin/maklumat-pelayanan/update/${editingId}`
            : '/admin/maklumat-pelayanan/store';
        
        const method = isEditMode ? 'PUT' : 'POST';

        const response = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify(formData)
        });

        const text = await response.text();
        console.log('Raw response:', text);

        let result;
        try {
            result = JSON.parse(text);
        } catch (e) {
            console.error('Not JSON:', text.substring(0, 500));
            showNotification('Server error: ' + text.substring(0, 100), 'error');
            return;
        }

        if (result.success) {
            showNotification(result.message, 'success');
            closeModal();
            loadData();
        } else {
            console.error('Error result:', result);
            showNotification(result.message || 'Gagal menyimpan data', 'error');
        }
    } catch (error) {
        console.error('Fetch error:', error);
        showNotification('Terjadi kesalahan: ' + error.message, 'error');
    }
}

// Toggle status
async function toggleStatus(id) {
    try {
        const response = await fetch(`/admin/maklumat-pelayanan/toggle-aktif/${id}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });

        const result = await response.json();

        if (result.success) {
            showNotification('Status berhasil diubah', 'success');
            loadData();
        } else {
            showNotification('Gagal mengubah status', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('Terjadi kesalahan', 'error');
    }
}

// Delete item
async function deleteItem(id) {
    if (!confirm('Apakah Anda yakin ingin menghapus item ini?')) {
        return;
    }

    try {
        const response = await fetch(`/admin/maklumat-pelayanan/delete/${id}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });

        const result = await response.json();

        if (result.success) {
            showNotification('Data berhasil dihapus', 'success');
            loadData();
        } else {
            showNotification('Gagal menghapus data', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('Terjadi kesalahan', 'error');
    }
}

// Utility functions
function showLoading() {
    document.getElementById('loadingState').style.display = 'block';
    document.getElementById('emptyState').style.display = 'none';
    document.getElementById('tableContainer').style.display = 'none';
}

function showError(message) {
    console.error(message);
}

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

function truncateText(text, length) {
    if (text.length <= length) return text;
    return text.substring(0, length) + '...';
}

function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
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

console.log('Maklumat Pelayanan Admin script loaded!');