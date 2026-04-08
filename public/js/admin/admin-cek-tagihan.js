// ========== INPUT METERAN FUNCTIONS ==========

// Toggle form visibility
function toggleInputForm() {
    const formBody = document.getElementById('inputFormBody');
    const icon = document.getElementById('toggleIcon');
    
    if (formBody.style.display === 'none') {
        formBody.style.display = 'block';
        icon.classList.remove('fa-chevron-down');
        icon.classList.add('fa-chevron-up');
    } else {
        formBody.style.display = 'none';
        icon.classList.remove('fa-chevron-up');
        icon.classList.add('fa-chevron-down');
    }
}

// Load pelanggan untuk dropdown
async function loadPelanggan() {
    try {
        const response = await fetch('/admin/pelanggan/aktif');
        const result = await response.json();
        
        if (result.success) {
            const select = document.getElementById('no_pelanggan');
            select.innerHTML = '<option value="">-- Pilih Pelanggan --</option>';
            
            result.data.forEach(pelanggan => {
                const option = document.createElement('option');
                option.value = pelanggan.no_pelanggan;
                option.dataset.nama = pelanggan.nama_pelanggan;
                option.dataset.alamat = pelanggan.alamat_lengkap;
                option.dataset.jenis = pelanggan.jenis_pelanggan;
                option.textContent = `${pelanggan.no_pelanggan} - ${pelanggan.nama_pelanggan}`;
                select.appendChild(option);
            });
        }
    } catch (error) {
        console.error('Error loading pelanggan:', error);
    }
}

// Event listener untuk pilih pelanggan
document.getElementById('no_pelanggan')?.addEventListener('change', function() {
    const option = this.options[this.selectedIndex];
    const infoBox = document.getElementById('infoPelanggan');
    
    if (option.value) {
        document.getElementById('display-nama').textContent = option.dataset.nama;
        document.getElementById('display-alamat').textContent = option.dataset.alamat;
        document.getElementById('display-golongan').textContent = option.dataset.jenis;
        infoBox.style.display = 'block';
    } else {
        infoBox.style.display = 'none';
    }
});

// Preview tagihan
let debounceTimer;
document.getElementById('meter_akhir')?.addEventListener('input', function() {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(previewTagihan, 500);
});

document.getElementById('meter_awal')?.addEventListener('input', function() {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(previewTagihan, 500);
});

async function previewTagihan() {
    const noPelanggan = document.getElementById('no_pelanggan').value;
    const meterAwal = document.getElementById('meter_awal').value;
    const meterAkhir = document.getElementById('meter_akhir').value;
    const previewBox = document.getElementById('previewBox');

    if (!noPelanggan || !meterAwal || !meterAkhir) {
        previewBox.style.display = 'none';
        return;
    }

    if (parseInt(meterAkhir) <= parseInt(meterAwal)) {
        previewBox.style.display = 'none';
        return;
    }

    try {
        const response = await fetch('/admin/tagihan/preview', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                no_pelanggan: noPelanggan,
                meter_awal: parseInt(meterAwal),
                meter_akhir: parseInt(meterAkhir)
            })
        });

        const result = await response.json();

        if (result.success) {
            const data = result.data;
            
            document.getElementById('preview-pemakaian').textContent = data.pemakaian.toLocaleString('id-ID');
            document.getElementById('preview-tarif').textContent = data.tarif_rata2.toLocaleString('id-ID');
            document.getElementById('preview-biaya').textContent = data.biaya_pemakaian.toLocaleString('id-ID');
            document.getElementById('preview-admin').textContent = data.biaya_admin.toLocaleString('id-ID');
            document.getElementById('preview-pemeliharaan').textContent = data.biaya_pemeliharaan.toLocaleString('id-ID');
            document.getElementById('preview-total').textContent = data.total_tagihan.toLocaleString('id-ID');
            
            // Detail tarif progresif
            let detailHTML = '<h5>Rincian Tarif Progresif:</h5><ul>';
            data.detail_tarif.forEach(item => {
                detailHTML += `<li>${item.blok}: ${item.pemakaian} m³ × Rp ${item.tarif.toLocaleString('id-ID')} = Rp ${item.biaya.toLocaleString('id-ID')}</li>`;
            });
            detailHTML += '</ul>';
            
            document.getElementById('detail-tarif').innerHTML = detailHTML;
            previewBox.style.display = 'block';
        }
    } catch (error) {
        console.error('Error preview:', error);
    }
}

// Submit form input meteran
document.getElementById('inputMeteranForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = {
        no_pelanggan: document.getElementById('no_pelanggan').value,
        periode: document.getElementById('periode').value,
        meter_awal: parseInt(document.getElementById('meter_awal').value),
        meter_akhir: parseInt(document.getElementById('meter_akhir').value)
    };
    
    try {
        const response = await fetch('/admin/tagihan/store', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(formData)
        });
        
        const result = await response.json();
        
        if (result.success) {
            alert('Tagihan berhasil disimpan!');
            this.reset();
            document.getElementById('previewBox').style.display = 'none';
            document.getElementById('infoPelanggan').style.display = 'none';
        } else {
            alert(result.message || 'Gagal menyimpan tagihan');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menyimpan tagihan');
    }
});

function resetForm() {
    document.getElementById('previewBox').style.display = 'none';
    document.getElementById('infoPelanggan').style.display = 'none';
}

// Load pelanggan saat halaman dimuat
document.addEventListener('DOMContentLoaded', function() {
    loadPelanggan();
});

function renderTagihanList(tagihans) {
    const tagihanList = document.getElementById('tagihanList');
    tagihanList.innerHTML = '';
    
    if (tagihans.length === 0) {
        tagihanList.innerHTML = `
            <div style="grid-column: 1/-1; text-align: center; padding: 60px;">
                <i class="fas fa-inbox" style="font-size: 4rem; color: #cbd5e1; margin-bottom: 15px;"></i>
                <p style="color: #64748b; font-size: 1.1rem;">Tidak ada tagihan yang sesuai filter</p>
            </div>
        `;
        return;
    }
    
    tagihans.forEach(tagihan => {
        const statusClass = getStatusClass(tagihan.status);
        const card = `
            <div class="tagihan-card">
                <div class="tagihan-card-header">
                    <span class="tagihan-periode">${tagihan.periode}</span>
                    <span class="status-badge ${statusClass}">${tagihan.status_label}</span>
                </div>
                
                <div class="tagihan-details">
                    <div class="detail-item">
                        <span class="label">Pemakaian</span>
                        <span class="value">${tagihan.pemakaian} m³</span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Meter</span>
                        <span class="value">${tagihan.meter_awal} → ${tagihan.meter_akhir}</span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Jatuh Tempo</span>
                        <span class="value">${tagihan.jatuh_tempo}</span>
                    </div>
                </div>
                
                <div class="tagihan-total-box">
                    <span class="label">Total Tagihan</span>
                    <span class="value">Rp ${tagihan.total_bayar}</span>
                </div>
                
                <div class="tagihan-actions">
                    <button class="btn-action btn-detail" onclick="showAdminDetail(${tagihan.id})">
                        <i class="fas fa-eye"></i>
                        Detail
                    </button>
                    ${tagihan.status !== 'sudah_bayar' ? `
                        <button class="btn-action btn-pay" onclick="processPayment(${tagihan.id})">
                            <i class="fas fa-money-bill"></i>
                            Bayar
                        </button>
                    ` : ''}
                </div>
            </div>
        `;
        tagihanList.innerHTML += card;
    });
}

function resetSearch() {
    document.getElementById('search_value').value = '';
    document.getElementById('errorState').style.display = 'none';
    document.getElementById('search_value').focus();
}
