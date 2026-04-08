// Admin Berita Script
let currentPage = 1;
let allBerita = [];

document.addEventListener('DOMContentLoaded', function() {
    loadBerita();
    
    // Search functionality
    const searchInput = document.getElementById('searchInput');
    let searchTimeout;
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            currentPage = 1;
            loadBerita();
        }, 500);
    });

    // Form submit
    document.getElementById('beritaForm').addEventListener('submit', handleSubmit);
});

async function loadBerita(page = 1) {
    const loadingState = document.getElementById('loadingState');
    const tableContainer = document.getElementById('tableContainer');
    const emptyState = document.getElementById('emptyState');
    const paginationContainer = document.getElementById('paginationContainer');
    const searchValue = document.getElementById('searchInput').value;

    loadingState.style.display = 'block';
    tableContainer.style.display = 'none';
    emptyState.style.display = 'none';
    paginationContainer.style.display = 'none';

    try {
        const response = await fetch(`/admin/berita/data?page=${page}&search=${searchValue}`, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        });

        const contentType = response.headers.get("content-type");
    if (!contentType || !contentType.includes("application/json")) {
        throw new Error("Server tidak mengembalikan JSON. Mungkin ada error di backend.");
    }

        const result = await response.json();

        loadingState.style.display = 'none';

        if (result.success && result.data.length > 0) {
            allBerita = result.data;
            renderTable(result.data);
            renderPagination(result.pagination);
            updateStats(result.data);
            tableContainer.style.display = 'block';
            paginationContainer.style.display = 'flex';
        } else {
            emptyState.style.display = 'block';
        }
    } catch (error) {
        console.error('Error:', error);
        loadingState.style.display = 'none';
        alert('Gagal memuat data berita');
    }
}

function renderTable(data) {
    const tbody = document.getElementById('tableBody');
    tbody.innerHTML = '';

    data.forEach((item, index) => {
        const row = `
            <tr>
                <td>${(currentPage - 1) * 10 + index + 1}</td>
                <td>
                    ${item.foto ? `<img src="/${item.foto}" alt="${item.judul}" class="berita-img">` : '<div style="width: 80px; height: 60px; background: #f1f5f9; border-radius: 8px; display: flex; align-items: center; justify-content: center;"><i class="fas fa-image" style="color: #cbd5e1;"></i></div>'}
                </td>
                <td>
                    <div class="berita-title">${item.judul}</div>
                    <div style="color: #64748b; font-size: 0.85rem; margin-top: 4px;">${truncate(item.deskripsi, 80)}</div>
                </td>
                <td><span class="category-badge ${item.kategori}">${item.kategori}</span></td>
                <td style="color: #64748b;">${item.penulis}</td>
                <td>
                    <div class="views-count">
                        <i class="fas fa-eye"></i>
                        <span>${item.views}</span>
                    </div>
                </td>
                <td style="color: #64748b; font-size: 0.85rem;">${item.created_at}</td>
                <td>
                    <div class="action-buttons">
                        <button class="btn-action btn-edit" onclick="editBerita(${item.id})" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn-action btn-delete" onclick="deleteBerita(${item.id})" title="Hapus">
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
    html += `<button class="page-btn" ${current_page === 1 ? 'disabled' : ''} onclick="loadBerita(${current_page - 1})">
        <i class="fas fa-chevron-left"></i>
    </button>`;

    // Page numbers
    for (let i = 1; i <= last_page; i++) {
        if (i === 1 || i === last_page || (i >= current_page - 1 && i <= current_page + 1)) {
            html += `<button class="page-btn ${i === current_page ? 'active' : ''}" onclick="loadBerita(${i})">${i}</button>`;
        } else if (i === current_page - 2 || i === current_page + 2) {
            html += `<span style="padding: 8px;">...</span>`;
        }
    }

    // Next button
    html += `<button class="page-btn" ${current_page === last_page ? 'disabled' : ''} onclick="loadBerita(${current_page + 1})">
        <i class="fas fa-chevron-right"></i>
    </button>`;

    container.innerHTML = html;
    currentPage = current_page;
}

function updateStats(data) {
    document.getElementById('totalBerita').textContent = data.length;
    const totalViews = data.reduce((sum, item) => sum + item.views, 0);
    document.getElementById('totalViews').textContent = totalViews.toLocaleString('id-ID');
    
    const today = new Date().toISOString().split('T')[0];
    const todayCount = data.filter(item => item.created_at.includes(today)).length;
    document.getElementById('todayBerita').textContent = todayCount;
}

function truncate(str, length) {
    return str.length > length ? str.substring(0, length) + '...' : str;
}

function openAddModal() {
    document.getElementById('modalTitle').innerHTML = '<i class="fas fa-plus"></i> Tambah Berita';
    document.getElementById('beritaForm').reset();
    document.getElementById('beritaId').value = '';
    document.getElementById('imagePreview').style.display = 'none';
    document.getElementById('formModal').classList.add('show');
}

async function editBerita(id) {
    try {
        const response = await fetch(`/api/berita/${id}`);

        if (!response.ok) {
            throw new Error('Http error! status: ${response.statu}');
        }
        const result = await response.json();
        
        if (result.data) {
            const berita = result.data;
            document.getElementById('modalTitle').innerHTML = '<i class="fas fa-edit"></i> Edit Berita';
            document.getElementById('beritaId').value = berita.id;
            document.getElementById('judul').value = berita.title;
            document.getElementById('deskripsi').value = berita.excerpt;
            document.getElementById('konten').value = berita.content;
            document.getElementById('kategori').value = berita.category;
            document.getElementById('penulis').value = berita.author;
            
            if (berita.image) {
                document.getElementById('previewImg').src = '/' + berita.image;
                document.getElementById('imagePreview').style.display = 'block';
            }
            
            document.getElementById('formModal').classList.add('show');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Gagal memuat data berita');
    }
}

async function handleSubmit(e) {
    e.preventDefault();
    
    const beritaId = document.getElementById('beritaId').value;
    const formData = new FormData();
    
    formData.append('judul', document.getElementById('judul').value);
    formData.append('deskripsi', document.getElementById('deskripsi').value);
    formData.append('konten', document.getElementById('konten').value);
    formData.append('kategori', document.getElementById('kategori').value);
    formData.append('penulis', document.getElementById('penulis').value);
    
    const fotoInput = document.getElementById('foto');
    if (fotoInput.files.length > 0) {
        formData.append('foto', fotoInput.files[0]);
    }
    
    const url = beritaId ? `/admin/berita/update/${beritaId}` : '/admin/berita/store';
    
    try {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: formData
        });

    const responseText = await response.text();
    console.log('Response Status:', response.status);
    console.log('Response Text:', responseText);
        
         let result;
         try {
        result = JSON.parse(responseText);
    } catch (e) {
        console.error('Bukan JSON! Response:', responseText.substring(0, 500));
        throw new Error("Server tidak mengembalikan JSON. Cek console untuk detail.");
    }
        
        if (result.success) {
            alert(result.message);
            closeModal();
            loadBerita(currentPage);
        } else {
            alert('Gagal menyimpan berita: ' + result.message);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menyimpan berita');
    }
}

async function deleteBerita(id) {
    if (!confirm('Yakin ingin menghapus berita ini?')) return;
    
    try {
        const response = await fetch(`/admin/berita/delete/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        });
        
        const result = await response.json();
        
        if (result.success) {
            alert(result.message);
            loadBerita(currentPage);
        } else {
            alert('Gagal menghapus berita: ' + result.message);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menghapus berita');
    }
}

function closeModal() {
    document.getElementById('formModal').classList.remove('show');
}

function previewImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('imagePreview').style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
}

function removeImage() {
    document.getElementById('foto').value = '';
    document.getElementById('imagePreview').style.display = 'none';
}

// Close modal on outside click
window.onclick = function(event) {
    const modal = document.getElementById('formModal');
    if (event.target === modal) {
        closeModal();
    }
}

console.log('Admin Berita script loaded!');