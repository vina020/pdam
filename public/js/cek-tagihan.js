// Cek Tagihan Script
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('cekTagihanForm');
    
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            cekTagihan();
        });
    }
});

async function cekTagihan() {
    console.log('CEK TAGIHAN START');
    
    const searchTypeEl = document.getElementById('search_type');
    const searchValueEl = document.getElementById('search_value');
    
    if (!searchTypeEl || !searchValueEl) {
        console.error('Elements not found');
        alert('Error: Form elements tidak ditemukan!');
        return;
    }
    
    const searchType = searchTypeEl.value;
    const searchValue = searchValueEl.value;
    
    console.log('Search params:', { searchType, searchValue });
    
    const loadingSpinner = document.getElementById('loadingSpinner');
    const errorMessage = document.getElementById('errorMessage');
    const errorState = document.getElementById('errorState');
    const resultsSection = document.getElementById('resultsSection');
    const emptyState = document.getElementById('emptyState');
    const submitBtn = document.querySelector('#cekTagihanForm button[type="submit"]');
    const originalBtnHTML = submitBtn ? submitBtn.innerHTML : '';

    // Hide all states
    if (errorMessage) errorMessage.style.display = 'none';
    if (emptyState) emptyState.style.display = 'none';
    if (errorState) errorState.style.display = 'none';
    if (resultsSection) resultsSection.style.display = 'none';
    
    // Show spinner
    if (loadingSpinner) loadingSpinner.style.display = 'block';
    
    // Disable button
    if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mencari...';
    }

    try {
        console.log('Fetching API...');
        
        const response = await fetch('/layanan/cek-tagihan/cek', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || 
                               document.querySelector('input[name="_token"]').value
            },
            body: JSON.stringify({ 
                search_type: searchType,
                search_value: searchValue 
            })
        });

        console.log('Response status:', response.status);
        
        // Baca response sebagai JSON dulu
        const data = await response.json();
        console.log('Response data:', data);

        // Hide spinner
        if (loadingSpinner) loadingSpinner.style.display = 'none';

        // Reset button
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnHTML;
        }

        if (!response.ok) {
            // Tampilkan error detail dari server
            let errorMsg = data.message || `HTTP ${response.status}: ${response.statusText}`;
            if (data.file && data.line) {
                errorMsg += `\n\nFile: ${data.file}\nLine: ${data.line}`;
            }
            throw new Error(errorMsg);
        }

        if (data.success) {
            displayResults(data);
        } else {
            showError(data.message);
        }
        
    } catch (error) {
        console.error('Fetch error:', error);
        
        // Hide spinner
        if (loadingSpinner) loadingSpinner.style.display = 'none';
        
        // Reset button
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnHTML;
        }
        
        showError('Terjadi kesalahan: ' + error.message);
    }
}

function displayResults(data) {
    console.log(' DISPLAY RESULTS START ');
    console.log('Data received:', data);
    
    const emptyState = document.getElementById('emptyState');
    const errorState = document.getElementById('errorState');
    const resultsSection = document.getElementById('resultsSection');
    const resultDivider = document.getElementById('resultDivider');
    
    console.log('Elements check:', {
        emptyState: !!emptyState,
        errorState: !!errorState,
        resultsSection: !!resultsSection,
        resultDivider: !!resultDivider
    });
    
    // Hide all states
    if (emptyState) emptyState.style.display = 'none';
    if (errorState) errorState.style.display = 'none';
    
    // Show divider and results
    if (resultDivider) resultDivider.style.display = 'block';
    if (resultsSection) resultsSection.style.display = 'block';

    try {
        const today = new Date();
        const dateStr = today.toLocaleDateString('id-ID', {
            day: 'numeric',
            month: 'long',
            year: 'numeric'
        });
        
        const currentDateEl = document.getElementById('current-date');
        console.log('current-date element:', currentDateEl);
        if (currentDateEl) currentDateEl.textContent = dateStr;
        
        // Set info pelanggan
        const infoPelangganEl = document.getElementById('info-no-pelanggan');
        const infoNamaEl = document.getElementById('info-nama');
        const infoAlamatEl = document.getElementById('info-alamat');
        
        console.log('Info elements:', {
            infoPelanggan: !!infoPelangganEl,
            infoNama: !!infoNamaEl,
            infoAlamat: !!infoAlamatEl
        });
        
        if (infoPelangganEl) infoPelangganEl.textContent = data.pelanggan.no_pelanggan || data.pelanggan.nosambungan || '-';
        if (infoNamaEl) infoNamaEl.textContent = data.pelanggan.nama || '-';
        if (infoAlamatEl) infoAlamatEl.textContent = data.pelanggan.alamat || '-';
        
        // Set total belum bayar
        const totalBelumBayarEl = document.getElementById('total-belum-bayar');
        console.log('total-belum-bayar element:', totalBelumBayarEl);
        if (totalBelumBayarEl) totalBelumBayarEl.textContent = 'Rp ' + data.total_belum_bayar;
        
        // Populate tagihan list
        const tagihanList = document.getElementById('tagihanList');
        console.log('tagihanList element:', tagihanList);
        console.log('Tagihans count:', data.tagihans.length);
        
        if (!tagihanList) {
            console.error('FATAL: tagihanList element not found!');
            alert('Error: Element #tagihanList tidak ditemukan di HTML');
            return;
        }
        
        tagihanList.innerHTML = '';
        
        data.tagihans.forEach((tagihan, index) => {
            console.log(`Adding tagihan ${index}:`, tagihan.periode);
            
            const statusClass = getStatusClass(tagihan.status);
            const item = `
    <div class="tagihan-item">
        <div class="tagihan-item-header">
            <span class="tagihan-periode">${tagihan.periode}</span>
            <span class="status-badge ${statusClass}">${tagihan.status_label}</span>
        </div>
        
        <div class="tagihan-detail-grid">
            <div class="detail-row">
                <span class="label">Meter Awal:</span>
                <span class="value">${tagihan.meter_awal} m³</span>
            </div>
            <div class="detail-row">
                <span class="label">Meter Akhir:</span>
                <span class="value">${tagihan.meter_akhir} m³</span>
            </div>
            <div class="detail-row">
                <span class="label">Pemakaian:</span>
                <span class="value">${tagihan.pemakaian} m³</span>
            </div>
            <div class="detail-row">
                <span class="label">Jatuh Tempo:</span>
                <span class="value">${tagihan.jatuh_tempo}</span>
            </div>
        </div>
        
        <div style="border-top: 1px solid #e5e7eb; margin-top: 15px; padding-top: 15px;">
            <div class="detail-row" style="margin-bottom: 10px;">
                <span class="label">Tagihan Pokok:</span>
                <span class="value">Rp ${tagihan.total_tagihan}</span>
            </div>
            ${tagihan.denda && tagihan.denda > 0 ? `
            <div class="detail-row" style="margin-bottom: 10px;">
                <span class="label">Denda Keterlambatan:</span>
                <span class="value" style="color: #dc3545;">Rp ${tagihan.denda_format}</span>
            </div>
            ` : ''}
            ${tagihan.biaya_pembukaan && tagihan.biaya_pembukaan > 0 ? `
            <div class="detail-row" style="margin-bottom: 10px;">
                <span class="label">Biaya Pembukaan:</span>
                <span class="value" style="color: #dc3545;">Rp ${tagihan.biaya_pembukaan_format}</span>
            </div>
            ` : ''}
            <div class="detail-row" style="border-top: 2px solid #0d6efd; padding-top: 12px; margin-top: 12px;">
                <span class="label" style="font-weight: 600; font-size: 1.05em;">Total yang Harus Dibayar:</span>
                <span class="value" style="color: #0d6efd; font-weight: 600; font-size: 1.15em;">Rp ${tagihan.total_bayar}</span>
            </div>
        </div>
        
        ${tagihan.id ? `
        <div class="tagihan-meta">
            <button class="btn-detail-small" onclick="showDetail(${tagihan.id})">
                <i class="fas fa-eye"></i>
                Lihat Detail
            </button>
        </div>
        ` : ''}
    </div>
`;
            tagihanList.innerHTML += item;
        });
        
        console.log('Tagihan list populated successfully');
        console.log('=== DISPLAY RESULTS END ===');
        
        // Smooth scroll ke hasil
        if (resultsSection) {
            resultsSection.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
        
    } catch (error) {
        console.error('ERROR in displayResults:', error);
        console.error('Stack trace:', error.stack);
        alert('Error saat menampilkan hasil: ' + error.message);
    }
}

function showError(message) {
    const emptyState = document.getElementById('emptyState');
    const errorState = document.getElementById('errorState');
    const resultsSection = document.getElementById('resultsSection');
    const resultDivider = document.getElementById('resultDivider');
    
    // Hide empty state and results
    emptyState.style.display = 'none';
    resultsSection.style.display = 'none';
    resultDivider.style.display = 'none';
    
    // Show error
    document.getElementById('errorMessage').textContent = message;
    errorState.style.display = 'block';
}

function getStatusClass(status) {
    switch(status) {
        case 'sudah_bayar': return 'success';
        case 'belum_bayar': return 'warning';
        case 'terlambat': return 'danger';
        default: return 'secondary';
    }
}

async function showDetail(id) {
    const modal = document.getElementById('detailModal');
    const modalBody = document.getElementById('modalBody');
    
    // Show loading
    modalBody.innerHTML = '<div style="text-align: center; padding: 40px;"><i class="fas fa-spinner fa-spin" style="font-size: 2rem; color: #0d6efd;"></i></div>';
    modal.classList.add('show');
    
    try {
        const response = await fetch(`/layanan/cek-tagihan/detail/${id}`);
        const data = await response.json();
        
        if (data.success) {
            const tagihan = data.tagihan;
            const statusClass = getStatusClass(tagihan.status);
            
            modalBody.innerHTML = `
                <div class="detail-section">
                    <h4><i class="fas fa-info-circle"></i> Informasi Tagihan</h4>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <span class="label">Nomor Pelanggan:</span>
                            <span class="value">${tagihan.no_pelanggan}</span>
                        </div>
                        <div class="detail-item">
                            <span class="label">Nama:</span>
                            <span class="value">${tagihan.nama_pelanggan}</span>
                        </div>
                        <div class="detail-item">
                            <span class="label">Periode:</span>
                            <span class="value">${tagihan.periode}</span>
                        </div>
                        <div class="detail-item">
                            <span class="label">Status:</span>
                            <span class="value"><span class="status-badge ${statusClass}">${tagihan.status_label}</span></span>
                        </div>
                    </div>
                </div>
                
                <div class="detail-section">
                    <h4><i class="fas fa-tachometer-alt"></i> Pemakaian Air</h4>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <span class="label">Meter Awal:</span>
                            <span class="value">${tagihan.meter_awal} m³</span>
                        </div>
                        <div class="detail-item">
                            <span class="label">Meter Akhir:</span>
                            <span class="value">${tagihan.meter_akhir} m³</span>
                        </div>
                        <div class="detail-item">
                            <span class="label">Total Pemakaian:</span>
                            <span class="value">${tagihan.pemakaian} m³</span>
                        </div>
                    </div>
                </div>
                
                <div class="detail-section">
                    <h4><i class="fas fa-calculator"></i> Rincian Biaya</h4>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <span class="label">Tarif per m³:</span>
                            <span class="value">Rp ${tagihan.tarif}</span>
                        </div>
                        <div class="detail-item">
                            <span class="label">Biaya Pemakaian:</span>
                            <span class="value">Rp ${tagihan.biaya_pemakaian}</span>
                        </div>
                        <div class="detail-item">
                            <span class="label">Biaya Admin:</span>
                            <span class="value">Rp ${tagihan.biaya_admin}</span>
                        </div>
                        <div class="detail-item">
                            <span class="label">Biaya Pemeliharaan:</span>
                            <span class="value">Rp ${tagihan.biaya_pemeliharaan}</span>
                        </div>
                        <div class="detail-item">
                            <span class="label">Denda:</span>
                            <span class="value">Rp ${tagihan.denda}</span>
                        </div>
                    </div>
                    
                    <div class="detail-total">
                        <div class="label">Total yang Harus Dibayar</div>
                        <div class="value">Rp ${tagihan.total_bayar}</div>
                    </div>
                </div>
                
                <div class="detail-section">
                    <h4><i class="fas fa-calendar-alt"></i> Tanggal Penting</h4>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <span class="label">Jatuh Tempo:</span>
                            <span class="value">${tagihan.jatuh_tempo}</span>
                        </div>
                    </div>
                </div>
            `;
        }
    } catch (error) {
        modalBody.innerHTML = '<div style="text-align: center; padding: 40px; color: #dc3545;"><i class="fas fa-exclamation-triangle" style="font-size: 2rem; margin-bottom: 15px;"></i><p>Terjadi kesalahan saat memuat detail</p></div>';
        console.error('Error:', error);
    }
}

function closeModal() {
    const modal = document.getElementById('detailModal');
    modal.classList.remove('show');
}

function resetForm() {
    document.getElementById('cekTagihanForm').reset();
    document.getElementById('errorState').style.display = 'none';
    document.getElementById('resultsSection').style.display = 'none';
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('detailModal');
    if (event.target === modal) {
        closeModal();
    }
}

console.log('Cek Tagihan script loaded!');

// ========== ADMIN ONLY SECTION ==========
// Event listener untuk input meteran (hanya ada di halaman admin)
let debounceTimer;

const meterAkhirInput = document.getElementById('meter_akhir');
const meterAwalInput = document.getElementById('meter_awal');

if (meterAkhirInput && meterAwalInput) {
    meterAkhirInput.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(previewTagihan, 500);
    });

    meterAwalInput.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(previewTagihan, 500);
    });
}

async function previewTagihan() {
    const noPelanggan = document.getElementById('no_pelanggan')?.value;
    const meterAwal = document.getElementById('meter_awal')?.value;
    const meterAkhir = document.getElementById('meter_akhir')?.value;
    const previewBox = document.getElementById('previewBox');

    if (!noPelanggan || !meterAwal || !meterAkhir || !previewBox) {
        if (previewBox) previewBox.style.display = 'none';
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
            
            document.getElementById('preview-pemakaian').textContent = data.pemakaian.toLocaleString();
            document.getElementById('preview-tarif').textContent = data.tarif_rata2.toLocaleString();
            document.getElementById('preview-biaya').textContent = data.biaya_pemakaian.toLocaleString();
            document.getElementById('preview-admin').textContent = data.biaya_admin.toLocaleString();
            document.getElementById('preview-pemeliharaan').textContent = data.biaya_pemeliharaan.toLocaleString();
            document.getElementById('preview-total').textContent = data.total_tagihan.toLocaleString();
            
            let detailHTML = '<div class="tarif-detail"><h5>Detail Tarif Progresif:</h5><ul>';
            data.detail_tarif.forEach(item => {
                detailHTML += `<li>${item.blok}: ${item.pemakaian} m³ × Rp ${item.tarif.toLocaleString()} = Rp ${item.biaya.toLocaleString()}</li>`;
            });
            detailHTML += '</ul></div>';
            
            const detailTarif = document.getElementById('detail-tarif');
            if (detailTarif) {
                detailTarif.innerHTML = detailHTML;
            }
            
            previewBox.style.display = 'block';
        }
    } catch (error) {
        console.error('Error:', error);
    }
}