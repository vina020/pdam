// Admin Pengaduan Script
let currentPage = 1;

document.addEventListener('DOMContentLoaded', function() {
    loadPengaduan();
    
    // Search functionality
    const searchInput = document.getElementById('searchInput');
    let searchTimeout;
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            currentPage = 1;
            loadPengaduan();
        }, 500);
    });

    // Form submit
    document.getElementById('tanggapanForm').addEventListener('submit', handleSubmit);
});

async function loadPengaduan(page = 1) {
    const loadingState = document.getElementById('loadingState');
    const tableContainer = document.getElementById('tableContainer');
    const emptyState = document.getElementById('emptyState');
    const paginationContainer = document.getElementById('paginationContainer');
    const searchValue = document.getElementById('searchInput').value;
    const statusFilter = document.getElementById('filterStatus').value;

    loadingState.style.display = 'block';
    tableContainer.style.display = 'none';
    emptyState.style.display = 'none';
    paginationContainer.style.display = 'none';

    try {
        const response = await fetch(`/admin/pengaduan/data?page=${page}&search=${searchValue}&status=${statusFilter}`, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const result = await response.json();

        loadingState.style.display = 'none';

        if (result.success && result.data.length > 0) {
            renderTable(result.data);
            renderPagination(result.pagination);
            updateStats(result.stats);
            tableContainer.style.display = 'block';
            paginationContainer.style.display = 'flex';
        } else {
            emptyState.style.display = 'block';
            updateStats(result.stats || { total: 0, pending: 0, proses: 0, selesai: 0 });
        }
    } catch (error) {
        console.error('Error:', error);
        loadingState.style.display = 'none';
        emptyState.style.display = 'block';
        alert('Gagal memuat data pengaduan');
    }
}

function renderTable(data) {
    const tbody = document.getElementById('tableBody');
    tbody.innerHTML = '';

    data.forEach((item, index) => {
        const row = `
            <tr>
                <td>${(currentPage - 1) * 10 + index + 1}</td>
                <td><strong>${item.nomor_pengaduan}</strong></td>
                <td>${item.no_pelanggan}</td>
                <td>
                    <div><strong>${item.nama_lengkap}</strong></div>
                    <div style="color: #64748b; font-size: 0.85rem; margin-top: 2px;">${item.no_whatsapp}</div>
                </td>
                <td>${item.jenis_pengaduan}</td>
                <td><span class="status-badge ${item.status}">${getStatusLabel(item.status)}</span></td>
                <td style="color: #64748b; font-size: 0.85rem;">${item.tanggal_pengaduan}</td>
                <td>
                    <div class="action-buttons">
                        <button class="btn-action btn-view" onclick="viewDetail(${item.id})" title="Lihat Detail">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn-action btn-delete" onclick="deletePengaduan(${item.id})" title="Hapus">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
        tbody.innerHTML += row;
    });
}

function renderPagination(pagination) {
    const container = document.getElementById('paginationButtons');
    const { current_page, last_page, per_page, total } = pagination;

    document.getElementById('showingFrom').textContent = (current_page - 1) * per_page + 1;
    document.getElementById('showingTo').textContent = Math.min(current_page * per_page, total);
    document.getElementById('totalData').textContent = total;

    let html = '';

    // Previous button
    html += `<button class="page-btn" ${current_page === 1 ? 'disabled' : ''} onclick="loadPengaduan(${current_page - 1})">
        <i class="fas fa-chevron-left"></i>
    </button>`;

    // Page numbers
    for (let i = 1; i <= last_page; i++) {
        if (i === 1 || i === last_page || (i >= current_page - 1 && i <= current_page + 1)) {
            html += `<button class="page-btn ${i === current_page ? 'active' : ''}" onclick="loadPengaduan(${i})">${i}</button>`;
        } else if (i === current_page - 2 || i === current_page + 2) {
            html += `<span style="padding: 8px;">...</span>`;
        }
    }

    // Next button
    html += `<button class="page-btn" ${current_page === last_page ? 'disabled' : ''} onclick="loadPengaduan(${current_page + 1})">
        <i class="fas fa-chevron-right"></i>
    </button>`;

    container.innerHTML = html;
    currentPage = current_page;
}

function updateStats(stats) {
    document.getElementById('totalPengaduan').textContent = stats.total;
    document.getElementById('pendingPengaduan').textContent = stats.pending;
    document.getElementById('prosesPengaduan').textContent = stats.proses;
    document.getElementById('selesaiPengaduan').textContent = stats.selesai;
}

function getStatusLabel(status) {
    const labels = {
        'pending': 'Pending',
        'proses': 'Diproses',
        'selesai': 'Selesai'
    };
    return labels[status] || status;
}

async function viewDetail(id) {
    try {
        const response = await fetch(`/admin/pengaduan/detail/${id}`);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const result = await response.json();
        
        if (result.success) {
            const data = result.data;
            
            document.getElementById('detailContent').innerHTML = `
                <div class="detail-grid">
                    <div class="detail-item">
                        <span class="label">Nomor Pengaduan</span>
                        <span class="value"><strong>${data.nomor_pengaduan}</strong></span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Status</span>
                        <span class="value"><span class="status-badge ${data.status}">${getStatusLabel(data.status)}</span></span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Nomor Pelanggan</span>
                        <span class="value">${data.no_pelanggan}</span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Nama Lengkap</span>
                        <span class="value">${data.nama_lengkap}</span>
                    </div>
                    <div class="detail-item">
                        <span class="label">No. WhatsApp</span>
                        <span class="value">${data.no_whatsapp}</span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Jenis Pengaduan</span>
                        <span class="value">${data.jenis_pengaduan}</span>
                    </div>
                    <div class="detail-item full-width">
                        <span class="label">Alamat</span>
                        <span class="value">${data.alamat}</span>
                    </div>
                    <div class="detail-item full-width">
                        <span class="label">Informasi Pengaduan</span>
                        <span class="value text-large">${data.informasi_pengaduan}</span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Tanggal Pengaduan</span>
                        <span class="value">${data.tanggal_pengaduan}</span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Terakhir Update</span>
                        <span class="value">${data.updated_at}</span>
                    </div>
                </div>
            `;
            
            // Set form values
            document.getElementById('pengaduanId').value = data.id;
            document.getElementById('status').value = data.status;
            document.getElementById('tanggapan').value = data.tanggapan !== '-' ? data.tanggapan : '';
            
            document.getElementById('detailModal').classList.add('show');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Gagal memuat detail pengaduan');
    }
}

async function handleSubmit(e) {
    e.preventDefault();
    
    const pengaduanId = document.getElementById('pengaduanId').value;
    const formData = {
        status: document.getElementById('status').value,
        tanggapan: document.getElementById('tanggapan').value
    };
    
    try {
        const response = await fetch(`/admin/pengaduan/update-status/${pengaduanId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(formData)
        });
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const result = await response.json();
        
        if (result.success) {
            alert(result.message);
            closeModal();
            loadPengaduan(currentPage);
        } else {
            alert('Gagal mengupdate pengaduan: ' + result.message);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat mengupdate pengaduan');
    }
}

async function deletePengaduan(id) {
    if (!confirm('Yakin ingin menghapus pengaduan ini?')) return;
    
    try {
        const response = await fetch(`/admin/pengaduan/delete/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const result = await response.json();
        
        if (result.success) {
            alert(result.message);
            loadPengaduan(currentPage);
        } else {
            alert('Gagal menghapus pengaduan: ' + result.message);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menghapus pengaduan');
    }
}

function closeModal() {
    document.getElementById('detailModal').classList.remove('show');
}

// Close modal on outside click
window.onclick = function(event) {
    const modal = document.getElementById('detailModal');
    if (event.target === modal) {
        closeModal();
    }
}

console.log('Admin Pengaduan script loaded!');