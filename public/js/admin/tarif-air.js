// Global variables
let currentPage = 1;
let currentKategori = 'all';
let currentSearch = '';
let isEditMode = false;
let editingId = null;
let tarifData = [];

document.addEventListener('DOMContentLoaded', function() {
    loadTarifData();
});

async function loadTarifData() {
    try {
        const response = await fetch('/api/tarif-air/public');
        const result = await response.json();

        if (result.success) {
            tarifData = result.data;
        }
    } catch (error) {
        console.error('Error loading tarif:', error);
    }
}

function hitungTarif() {
    const golongan = document.getElementById('golongan').value;
    const pemakaian = parseFloat(document.getElementById('pemakaian').value);

    if (!golongan || !pemakaian || pemakaian <= 0) {
        alert('Mohon pilih golongan dan masukkan pemakaian yang valid');
        return;
    }

    let tarif = 0;
    let tarifInfo = tarifData.find(t => {
        if (golongan.includes(t.kategori)) {
            if (t.max_pemakaian === null) {
                return pemakaian >= t.min_pemakaian;
            }
            return pemakaian >= t.min_pemakaian && pemakaian <= t.max_pemakaian;
        }
        return false;
    });

    if (tarifInfo) {
        tarif = tarifInfo.tarif_per_m3;
    }

    const biayaPemakaian = pemakaian*tarif;
    const biayaAdmin = 2500;
    const biayaPemeliharaan = 1000;
    const total = biayaPemakaian + biayaAdmin + biayaPemeliharaan;

    document.getElementById('result-pemakaian').textContent = pemakaian + 'm³';
    document.getElementById('result-tarif').textContent = 'Rp ' + tarif.toLocaleString('id-ID');
    document.getElementById('result-biaya').textContent = 'Rp ' + biayaPemakaian.toLocaleString('id-ID');
    document.getElementById('result-total').textContent = 'Rp ' + total.toLocaleString('id-ID');
    
    document.getElementById('calculatorResult').style.display = 'block';
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    loadData();
    initEventListeners();
});

// Event Listeners
function initEventListeners() {
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', debounce(function() {
            currentSearch = this.value;
            currentPage = 1;
            loadData();
        }, 500));
    }

    const kategoriFilter = document.getElementById('kategoriFilter');
    if (kategoriFilter) {
        kategoriFilter.addEventListener('change', function() {
            currentKategori = this.value;
            currentPage = 1;
            loadData();
        });
    }

    const form = document.getElementById('tarifForm');
    if (form) {
        form.addEventListener('submit', handleFormSubmit);
    }
}

// Load data
async function loadData() {
    showLoading();

    try {
        const url = `/admin/tarif-air/get-all?page=${currentPage}&search=${currentSearch}&kategori=${currentKategori}`;
        const response = await fetch(url);
        
        if (!response.ok) {
            throw new Error(`Server error: ${response.status}`);
        }
        
        const result = await response.json();

        if (result.success) {
            updateStats(result.stats);
            displayData(result.data);
            updatePagination(result.pagination);
        }
    } catch (error) {
        console.error('Error loading data:', error);
        showNotification('Gagal memuat data: ' + error.message, 'error');
        document.getElementById('loadingState').style.display = 'none';
        document.getElementById('emptyState').style.display = 'block';
    }
}

// Update stats
function updateStats(stats) {
    document.getElementById('totalTarif').textContent = stats.total || 0;
    document.getElementById('totalAktif').textContent = stats.aktif || 0;
    document.getElementById('totalRumahTangga').textContent = stats.rumah_tangga || 0;
    document.getElementById('totalNiaga').textContent = stats.niaga || 0;
    document.getElementById('totalIndustri').textContent = stats.industri || 0;
    document.getElementById('totalInstansi').textContent = stats.instansi || 0;
    document.getElementById('totalSosial').textContent = stats.sosial || 0;
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
        const maxText = item.max_pemakaian ? item.max_pemakaian + ' m³' : '<span class="unlimited-badge">Unlimited</span>';
        
        html += `
            <tr>
                <td>${rowNumber}</td>
                <td>
                    <span class="kategori-badge ${item.kategori}">
                        ${formatKategori(item.kategori)}
                    </span>
                </td>
                <td>${item.sub_kategori || '-'}</td>
                <td><strong>${item.blok_pemakaian}</strong></td>
                <td>${item.min_pemakaian} m³</td>
                <td>${maxText}</td>
                <td><span class="tarif-amount">${formatRupiah(item.tarif_per_m3)}</span></td>
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
        'sosial': 'Sosial',
        'rumah_tangga': 'Rumah Tangga',
        'niaga': 'Niaga',
        'industri': 'Industri',
        'instansi': 'Instansi'
    };
    return labels[kategori] || kategori;
}

// Format rupiah
function formatRupiah(angka) {
    return 'Rp ' + new Intl.NumberFormat('id-ID').format(angka);
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

    const from = (pagination.current_page - 1) * pagination.per_page + 1;
    const to = Math.min(pagination.current_page * pagination.per_page, pagination.total);
    
    document.getElementById('showingFrom').textContent = from;
    document.getElementById('showingTo').textContent = to;
    document.getElementById('totalData').textContent = pagination.total;

    let buttonsHtml = '';

    buttonsHtml += `
        <button class="pagination-btn ${pagination.current_page === 1 ? 'disabled' : ''}" 
                onclick="changePage(${pagination.current_page - 1})"
                ${pagination.current_page === 1 ? 'disabled' : ''}>
            <i class="fas fa-chevron-left"></i>
        </button>
    `;

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
    
    document.getElementById('modalTitle').innerHTML = '<i class="fas fa-plus"></i> Tambah Tarif';
    document.getElementById('tarifForm').reset();
    document.getElementById('tarifId').value = '';
    document.getElementById('aktif').checked = true;
    
    const modal = document.getElementById('formModal');
    modal.style.display = 'flex';
    modal.classList.add('show');
}

function closeModal() {
    const modal = document.getElementById('formModal');
    modal.style.display = 'none';
    modal.classList.remove('show');
    document.getElementById('tarifForm').reset();
    isEditMode = false;
    editingId = null;
}

// Edit item
async function editItem(id) {
    try {
        const response = await fetch(`/admin/tarif-air/get-detail/${id}`);
        const result = await response.json();

        if (result.success) {
            const data = result.data;
            
            isEditMode = true;
            editingId = id;
            
            document.getElementById('modalTitle').innerHTML = '<i class="fas fa-edit"></i> Edit Tarif';
            document.getElementById('tarifId').value = data.id;
            document.getElementById('kategori').value = data.kategori;
            document.getElementById('sub_kategori').value = data.sub_kategori || '';
            document.getElementById('blok_pemakaian').value = data.blok_pemakaian;
            document.getElementById('min_pemakaian').value = data.min_pemakaian;
            document.getElementById('max_pemakaian').value = data.max_pemakaian || '';
            document.getElementById('tarif_per_m3').value = data.tarif_per_m3;
            document.getElementById('keterangan').value = data.keterangan || '';
            document.getElementById('urutan').value = data.urutan;
            document.getElementById('aktif').checked = data.aktif;
            
            const modal = document.getElementById('formModal');
            modal.style.display = 'flex';
            modal.classList.add('show');
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
        sub_kategori: document.getElementById('sub_kategori').value || null,
        blok_pemakaian: document.getElementById('blok_pemakaian').value,
        min_pemakaian: parseInt(document.getElementById('min_pemakaian').value) || 0,
        max_pemakaian: document.getElementById('max_pemakaian').value ? parseInt(document.getElementById('max_pemakaian').value) : null,
        tarif_per_m3: parseInt(document.getElementById('tarif_per_m3').value) || 0,
        keterangan: document.getElementById('keterangan').value || null,
        urutan: parseInt(document.getElementById('urutan').value) || 0,
        aktif: document.getElementById('aktif').checked
    };

    try {
        const url = isEditMode 
            ? `/admin/tarif-air/update/${editingId}`
            : '/admin/tarif-air/store';
        
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

        const result = await response.json();

        if (result.success) {
            showNotification(result.message, 'success');
            closeModal();
            loadData();
        } else {
            showNotification(result.message || 'Gagal menyimpan data', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('Terjadi kesalahan: ' + error.message, 'error');
    }
}

// Toggle status
async function toggleStatus(id) {
    try {
        const response = await fetch(`/admin/tarif-air/toggle-aktif/${id}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });

        const result = await response.json();

        if (result.success) {
            showNotification('Status berhasil diubah', 'success');
        } else {
            showNotification('Gagal mengubah status', 'error');
            loadData();
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('Terjadi kesalahan', 'error');
    }
}

// Delete item
async function deleteItem(id) {
    if (!confirm('Apakah Anda yakin ingin menghapus tarif ini?')) {
        return;
    }

    try {
        const response = await fetch(`/admin/tarif-air/delete/${id}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });

        const result = await response.json();

        if (result.success) {
            showNotification('Tarif berhasil dihapus', 'success');
            loadData();
        } else {
            showNotification('Gagal menghapus tarif', 'error');
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

console.log('Tarif Air Admin script loaded!');