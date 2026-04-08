let currentPage = 1;
let allData = [];

document.addEventListener('DOMContentLoaded', function() {
    loadData();
    loadKecamatanOptions();
    
    // Search functionality
    const searchInput = document.getElementById('searchInput');
    let searchTimeout;
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            currentPage = 1;
            loadData();
        }, 500);
    });

    // Filter functionality
    document.getElementById('filterStatus').addEventListener('change', () => {
        currentPage = 1;
        loadData();
    });

    document.getElementById('filterKecamatan').addEventListener('change', () => {
        currentPage = 1;
        loadData();
    });

    // Status form submit
    document.getElementById('statusForm').addEventListener('submit', handleStatusUpdate);
});

async function loadData(page = 1) {
    const loadingState = document.getElementById('loadingState');
    const tableContainer = document.getElementById('tableContainer');
    const emptyState = document.getElementById('emptyState');
    const paginationContainer = document.getElementById('paginationContainer');
    
    const searchValue = document.getElementById('searchInput').value;
    const statusFilter = document.getElementById('filterStatus').value;
    const kecamatanFilter = document.getElementById('filterKecamatan').value;

    loadingState.style.display = 'block';
    tableContainer.style.display = 'none';
    emptyState.style.display = 'none';
    paginationContainer.style.display = 'none';

    try {
        const params = new URLSearchParams({
            page: page,
            search: searchValue,
            status: statusFilter,
            kecamatan: kecamatanFilter
        });

        const response = await fetch(`/admin/pasang-baru/data?${params}`, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        });

        if (!response.ok) {
            throw new Error('Network response was not ok');
        }

        const result = await response.json();

        loadingState.style.display = 'none';

        if (result.success && result.data.length > 0) {
            allData = result.data;
            renderTable(result.data);
            renderPagination(result.pagination);
            updateStats(result.stats);
            tableContainer.style.display = 'block';
            paginationContainer.style.display = 'flex';
        } else {
            emptyState.style.display = 'block';
        }
    } catch (error) {
        console.error('Error:', error);
        loadingState.style.display = 'none';
        alert('Gagal memuat data pengajuan');
    }
}

function renderTable(data) {
    const tbody = document.getElementById('tableBody');
    tbody.innerHTML = '';

    data.forEach((item, index) => {
        const statusIcons = {
            'pending': 'fa-clock',
            'verifikasi': 'fa-file-check',
            'survei': 'fa-map-marked-alt',
            'approved': 'fa-check',
            'ditolak': 'fa-times-circle',
            'selesai': 'fa-check-double'
        };

        const statusTexts = {
            'pending': 'Menunggu',
            'verifikasi': 'Verifikasi',
            'survei': 'Survei',
            'approved': 'Disetujui',
            'ditolak': 'Ditolak',
            'selesai': 'Selesai'
        };

        const row = `
            <tr>
                <td>${(currentPage - 1) * 10 + index + 1}</td>
                <td>
                    <strong style="color: #667eea;">${item.nomor_registrasi}</strong>
                </td>
                <td>
                    <div class="pemohon-info">
                        <div class="pemohon-name">${item.nama_pemohon}</div>
                        <div class="pemohon-detail">NIK: ${item.nik}</div>
                        <div class="pemohon-detail">
                            <i class="fas fa-phone"></i> ${item.no_telepon}
                        </div>
                    </div>
                </td>
                <td>
                    <div class="alamat-info">
                        <div class="alamat-jalan">${item.jalan} No. ${item.nomor_rumah}</div>
                        <div class="alamat-detail">RT ${item.rt}/RW ${item.rw}</div>
                        <div class="alamat-detail">${item.kelurahan}, ${item.kecamatan}</div>
                    </div>
                </td>
                <td style="color: #64748b; font-size: 0.85rem;">${item.tanggal_pengajuan}</td>
                <td>
                    <span class="status-badge ${item.status}">
                        <i class="fas ${statusIcons[item.status]}"></i>
                        ${statusTexts[item.status]}
                    </span>
                </td>
                <td>
                    <div class="action-buttons">
                        <button class="btn-action btn-view" onclick="viewDetail(${item.id})" title="Lihat Detail">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn-action btn-edit" onclick="openStatusModal(${item.id})" title="Update Status">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn-action btn-delete" onclick="deleteData(${item.id})" title="Hapus">
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

    html += `<button class="page-btn" ${current_page === 1 ? 'disabled' : ''} onclick="loadData(${current_page - 1})">
        <i class="fas fa-chevron-left"></i>
    </button>`;

    for (let i = 1; i <= last_page; i++) {
        if (i === 1 || i === last_page || (i >= current_page - 1 && i <= current_page + 1)) {
            html += `<button class="page-btn ${i === current_page ? 'active' : ''}" onclick="loadData(${i})">${i}</button>`;
        } else if (i === current_page - 2 || i === current_page + 2) {
            html += `<span style="padding: 8px;">...</span>`;
        }
    }

    html += `<button class="page-btn" ${current_page === last_page ? 'disabled' : ''} onclick="loadData(${current_page + 1})">
        <i class="fas fa-chevron-right"></i>
    </button>`;

    container.innerHTML = html;
    currentPage = current_page;
}

function updateStats(stats) {
    console.log('Stats yang diterima:', stats);
    document.getElementById('statPending').textContent = stats.pending || 0;
    document.getElementById('statVerifikasi').textContent = stats.verifikasi || 0;
    document.getElementById('statSurvei').textContent = stats.survei || 0;
    document.getElementById('statApproved').textContent = stats.approved || 0;
    document.getElementById('statSelesai').textContent = stats.selesai || 0;
    document.getElementById('statDitolak').textContent = stats.ditolak || 0;
}

async function loadKecamatanOptions() {
    try {
        const response = await fetch('/admin/pasang-baru/kecamatan', {
            headers: {
                'Accept': 'application/json'
            }
        });
        const result = await response.json();
        
        if (result.success) {
            const select = document.getElementById('filterKecamatan');
            result.data.forEach(kec => {
                const option = document.createElement('option');
                option.value = kec;
                option.textContent = kec;
                select.appendChild(option);
            });
        }
    } catch (error) {
        console.error('Error loading kecamatan:', error);
    }
}

async function viewDetail(id) {
    try {
        const response = await fetch(`/admin/pasang-baru/${id}`, {
            headers: {
                'Accept': 'application/json'
            }
        });
        
        if (!response.ok) {
            throw new Error('Data tidak ditemukan');
        }

        const result = await response.json();
        
        if (result.success) {
            renderDetailModal(result.data);
            document.getElementById('detailModal').classList.add('show');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Gagal memuat detail pengajuan');
    }
}

function renderDetailModal(data) {
    const statusIcons = {
        'pending': 'fa-clock',
        'verifikasi': 'fa-file-check',
        'survei': 'fa-map-marked-alt',
        'approved': 'fa-check-circle',
        'ditolak': 'fa-times-circle',
        'selesai': 'fa-check-double'
    };

    const statusTexts = {
        'pending': 'Menunggu',
        'verifikasi': 'Verifikasi',
        'survei': 'Survei',
        'approved': 'Disetujui',
        'ditolak': 'Ditolak',
        'selesai': 'Selesai'
    };

    const content = `
        <div class="detail-section">
            <div class="section-title">
                <i class="fas fa-user"></i>
                Data Pemohon
            </div>
            <div class="detail-grid">
                <div class="detail-item">
                    <div class="detail-label">Nomor Registrasi</div>
                    <div class="detail-value">${data.nomor_registrasi}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Status</div>
                    <div class="detail-value">
                        <span class="status-badge ${data.status}">
                            <i class="fas ${statusIcons[data.status]}"></i>
                            ${statusTexts[data.status]}
                        </span>
                    </div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Nama Pemohon</div>
                    <div class="detail-value">${data.nama_pemohon}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">NIK</div>
                    <div class="detail-value">${data.nik}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">No. Telepon</div>
                    <div class="detail-value">${data.no_telepon}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Email</div>
                    <div class="detail-value">${data.email || '-'}</div>
                </div>
                <div class="detail-item" style="grid-column: 1/-1;">
                    <div class="detail-label">Alamat Pemohon</div>
                    <div class="detail-value">${data.alamat_pemohon}</div>
                </div>
            </div>
        </div>

        <div class="detail-section">
            <div class="section-title">
                <i class="fas fa-map-marker-alt"></i>
                Alamat Pemasangan
            </div>
            <div class="detail-grid">
                <div class="detail-item">
                    <div class="detail-label">Jalan</div>
                    <div class="detail-value">${data.jalan}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Nomor Rumah</div>
                    <div class="detail-value">${data.nomor_rumah}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">RT / RW</div>
                    <div class="detail-value">RT ${data.rt} / RW ${data.rw}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Kelurahan</div>
                    <div class="detail-value">${data.kelurahan}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Kecamatan</div>
                    <div class="detail-value">${data.kecamatan}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Daya Listrik</div>
                    <div class="detail-value">${data.daya_listrik} VA</div>
                </div>
                ${data.latitude && data.longitude ? `
                <div class="detail-item" style="grid-column: 1/-1;">
                    <div class="detail-label">Koordinat</div>
                    <div class="detail-value">${data.latitude}, ${data.longitude}</div>
                </div>
                ` : ''}
            </div>
        </div>

        <div class="detail-section">
            <div class="section-title">
                <i class="fas fa-file-alt"></i>
                Dokumen Persyaratan
            </div>
            <div class="dokumen-grid">
                ${renderDokumen('KTP', data.dokumen_ktp)}
                ${renderDokumen('Kartu Keluarga', data.dokumen_kk)}
                ${renderDokumen('PBB', data.dokumen_pbb)}
                ${renderDokumen('Rekening Listrik', data.dokumen_listrik)}
                ${renderDokumen('Surat Domisili', data.dokumen_domisili)}
            </div>
        </div>

        ${data.catatan ? `
        <div class="detail-section">
            <div class="section-title">
                <i class="fas fa-comment"></i>
                Catatan
            </div>
            <div class="detail-value">${data.catatan}</div>
        </div>
        ` : ''}

        <div class="detail-section">
            <div class="section-title">
                <i class="fas fa-clock"></i>
                Timeline
            </div>
            <div class="timeline">
                <div class="timeline-item">
                    <div class="timeline-date">${data.tanggal_pengajuan}</div>
                    <div class="timeline-status">Pengajuan Disubmit</div>
                </div>
                ${data.tanggal_verifikasi ? `
                <div class="timeline-item">
                    <div class="timeline-date">${data.tanggal_verifikasi}</div>
                    <div class="timeline-status">Verifikasi Dokumen</div>
                </div>
                ` : ''}
                ${data.tanggal_survei ? `
                <div class="timeline-item">
                    <div class="timeline-date">${data.tanggal_survei}</div>
                    <div class="timeline-status">Survei Lapangan</div>
                </div>
                ` : ''}
                ${data.tanggal_approved ? `
                <div class="timeline-item">
                    <div class="timeline-date">${data.tanggal_approved}</div>
                    <div class="timeline-status">${data.status === 'approved' ? 'Disetujui' : 'Ditolak'}</div>
                </div>
                ` : ''}
            </div>
        </div>
    `;

    document.getElementById('detailContent').innerHTML = content;
}

function renderDokumen(name, url) {
    if (!url) {
        return `
            <div class="dokumen-item">
                <div class="dokumen-preview">
                    <i class="fas fa-file"></i>
                </div>
                <div class="dokumen-name">${name}</div>
                <small style="color: #94a3b8;">Tidak ada</small>
            </div>
        `;
    }

    return `
        <div class="dokumen-item">
            <div class="dokumen-preview">
                <img src="/${url}" alt="${name}" onerror="this.parentElement.innerHTML='<i class=\'fas fa-file\'></i>'">
            </div>
            <div class="dokumen-name">${name}</div>
            <button class="btn-download" onclick="window.open('/${url}', '_blank')">
                <i class="fas fa-download"></i> Download
            </button>
        </div>
    `;
}

function openStatusModal(id) {
    document.getElementById('updateId').value = id;
    document.getElementById('newStatus').value = '';
    document.getElementById('catatan').value = '';
    document.getElementById('statusModal').classList.add('show');
}

async function handleStatusUpdate(e) {
    e.preventDefault();
    
    const id = document.getElementById('updateId').value;
    const status = document.getElementById('newStatus').value;
    const catatan = document.getElementById('catatan').value;

    if (!status) {
        alert('Pilih status terlebih dahulu');
        return;
    }

    try {
        const response = await fetch(`/admin/pasang-baru/update-status/${id}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ status, catatan })
        });

        const result = await response.json();
        console.log('Response dari backend:', result);
        console.log('Status yang dikirim:', status);

        if (result.success) {
            alert(result.message);
            closeStatusModal();
            loadData(currentPage);
        } else {
            alert('Gagal mengupdate status: ' + result.message);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat mengupdate status');
    }
}

async function deleteData(id) {
    if (!confirm('Yakin ingin menghapus pengajuan ini?')) return;

    try {
        const response = await fetch(`/admin/pasang-baru/delete/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        });

        const result = await response.json();

        if (result.success) {
            alert(result.message);
            loadData(currentPage);
        } else {
            alert('Gagal menghapus data: ' + result.message);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menghapus data');
    }
}

function closeDetailModal() {
    document.getElementById('detailModal').classList.remove('show');
}

function closeStatusModal() {
    document.getElementById('statusModal').classList.remove('show');
}

// Close modal on outside click
window.onclick = function(event) {
    const detailModal = document.getElementById('detailModal');
    const statusModal = document.getElementById('statusModal');
    
    if (event.target === detailModal) {
        closeDetailModal();
    }
    if (event.target === statusModal) {
        closeStatusModal();
    }
}

console.log('Admin Pasang Baru script loaded!');