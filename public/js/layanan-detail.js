// Smooth scroll untuk anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

document.addEventListener('DOMContentLoaded', function() {
    console.log('Layanan detail page loaded!');
});

// Form Multi-Step Navigation
let currentStep = 1;

function nextStep(step) {
    // Validate current step
    const currentStepElement = document.getElementById(`step-${step}`);
    const inputs = currentStepElement.querySelectorAll('[required]');
    let isValid = true;

    inputs.forEach(input => {
        if (!input.value && input.type !== 'file') {
            isValid = false;
            input.style.borderColor = '#dc3545';
        } else if (input.type === 'file' && input.hasAttribute('required') && input.files.length === 0) {
            isValid = false;
            input.parentElement.style.borderColor = '#dc3545';
        } else {
            input.style.borderColor = '#e0e0e0';
        }
    });

    if (!isValid) {
        alert('Mohon lengkapi semua field yang wajib diisi');
        return;
    }

    // Special handling untuk step 3 -> 4 (populate summary)
    if (step === 3) {
        populateVerificationData();
    }

    // Hide current step
    document.getElementById(`step-${step}`).style.display = 'none';
    
    // Show next step
    const nextStepNum = step + 1;
    document.getElementById(`step-${nextStepNum}`).style.display = 'block';
    
    // Update step indicator
    updateStepIndicator(nextStepNum);
    
    // Scroll to top
    window.scrollTo({ top: 0, behavior: 'smooth' });
    
    currentStep = nextStepNum;
}

function populateVerificationData() {
    // Data Pemohon
    document.getElementById('verify-nama').textContent = document.getElementById('nama_pemohon').value;
    document.getElementById('verify-nik').textContent = document.getElementById('nik').value;
    document.getElementById('verify-alamat').textContent = document.getElementById('alamat_pemohon').value;
    document.getElementById('verify-telp').textContent = document.getElementById('no_telepon').value;
    
    // Lokasi Pemasangan
    const jalan = document.getElementById('jalan').value;
    const nomor = document.getElementById('nomor_rumah').value;
    const rt = document.getElementById('rt').value;
    const rw = document.getElementById('rw').value;
    const kelurahan = document.getElementById('kelurahan').value;
    
    document.getElementById('verify-lokasi').textContent = 
        `${jalan} No. ${nomor}, RT ${rt}/RW ${rw}, ${kelurahan}`;
    document.getElementById('verify-kecamatan').textContent = 
        document.getElementById('kecamatan').value;
    document.getElementById('verify-daya').textContent = 
        document.getElementById('daya_listrik').value;
    
    // Dokumen Terupload
    const dokumenList = document.getElementById('verify-documents');
    const dokumenFields = [
        { id: 'dokumen_ktp', label: 'Fotokopi KTP' },
        { id: 'dokumen_kk', label: 'Fotokopi Kartu Keluarga' },
        { id: 'dokumen_pbb', label: 'Fotokopi PBB' },
        { id: 'dokumen_pbb', label: 'Fotokopi PBB' },
        { id: 'dokumen_listrik', label: 'Fotokopi Rekening Listrik' },
        { id: 'dokumen_domisili', label: 'Surat Keterangan Domisili' }
    ];
    
    dokumenList.innerHTML = '';
    dokumenFields.forEach(dok => {
        const input = document.getElementById(dok.id);
        if (input && input.files.length > 0) {
            const file = input.files[0];
            const fileSize = (file.size / 1024).toFixed(2) + ' KB';
            
            dokumenList.innerHTML += `
                <div class="summary-item-modern">
                    <span class="summary-label-modern">
                        <i class="fas fa-check-circle" style="color: #28a745;"></i> ${dok.label}:
                    </span>
                    <span class="summary-value-modern">${file.name} (${fileSize})</span>
                </div>
            `;
        }
    });
}

function prevStep(step) {
    // Hide current step
    document.getElementById(`step-${step}`).style.display = 'none';
    
    // Show previous step
    const prevStepNum = step - 1;
    document.getElementById(`step-${prevStepNum}`).style.display = 'block';
    
    // Update step indicator
    updateStepIndicator(prevStepNum);
    
    // Scroll to top
    window.scrollTo({ top: 0, behavior: 'smooth' });
    
    currentStep = prevStepNum;
}

function updateStepIndicator(activeStep) {
    document.querySelectorAll('.step-item').forEach((step, index) => { 
        const stepNum = index + 1;
        step.classList.remove('active', 'completed');
        
        if (stepNum === activeStep) {
            step.classList.add('active');
        } else if (stepNum < activeStep) {
            step.classList.add('completed');
        }
    });
}

//file select//
function handleFileSelect(input, previewId) {
    const file = input.files[0];
    const preview = document.getElementById(previewId);
    
    if (file) {
        // Check file size (2MB max)
        if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran file terlalu besar! Maksimal 2MB');
            input.value = '';
            return;
        }
        
        const fileSize = (file.size / 1024).toFixed(2) + ' KB';
        
        preview.innerHTML = `
            <i class="fas fa-check-circle"></i>
            <div class="file-info">
                <div class="file-name">${file.name}</div>
                <div class="file-size">${fileSize}</div>
            </div>
            <button type="button" class="file-remove" onclick="removeFile('${input.id}', '${previewId}')">
                <i class="fas fa-times" style="color: white;"></i>
            </button>
        `;
        preview.classList.add('has-file');
        preview.style.display = 'flex';
    }
}

// Function removeFile yang BENAR
function removeFile(inputId, previewId) {
    console.log('Removing:', inputId, previewId); // Debug
    
    const input = document.getElementById(inputId);
    const preview = document.getElementById(previewId);
    
    if (input) {
        input.value = '';
    }
    
    if (preview) {
        preview.classList.remove('has-file');
        preview.style.display = 'none';
        preview.innerHTML = '';
    }
}

// Initialize Leaflet Map
let map, marker;

function initMap() {
    if (document.getElementById('map')) {
        // Destroy existing map if any
        if (map) {
            map.remove();
        }
        
        map = L.map('map').setView([-7.6448, 111.3364], 13); // Koordinat Magetan
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(map);
        
        // Refresh map size after a short delay
        setTimeout(() => {
            map.invalidateSize();
        }, 100);
        
        // Click event untuk set marker
        map.on('click', function(e) {
            if (marker) {
                map.removeLayer(marker);
            }
            
            marker = L.marker([e.latlng.lat, e.latlng.lng]).addTo(map);
            
            // Update latitude & longitude input
            document.getElementById('latitude').value = e.latlng.lat.toFixed(6);
            document.getElementById('longitude').value = e.latlng.lng.toFixed(6);
        });
    }
}

// Call initMap when entering step 2
function nextStep(step) {
    // ... kode validasi existing ...

    // Hide current step
    document.getElementById(`step-${step}`).style.display = 'none';
    
    // Show next step
    const nextStepNum = step + 1;
    document.getElementById(`step-${nextStepNum}`).style.display = 'block';
    
    // Initialize map when entering step 2
    if (nextStepNum === 2) {
        setTimeout(() => {
            initMap();
        }, 100);
    }
    
    // Special handling untuk step 3 -> 4 (populate summary)
    if (step === 3) {
        populateVerificationData();
    }
    
    // Update step indicator
    updateStepIndicator(nextStepNum);
    
    // Scroll to top
    window.scrollTo({ top: 0, behavior: 'smooth' });
    
    currentStep = nextStepNum;
}

// Form Submit
document.getElementById('pasangBaruForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Validasi checkbox
    const agreeTerms = document.getElementById('agree_terms');
    if (!agreeTerms.checked) {
        alert('Anda harus menyetujui syarat dan ketentuan!');
        return;
    }
    
    // Show loading
    const submitBtn = this.querySelector('.btn-submit-modern');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengirim...';
    submitBtn.disabled = true;
    
    // Create FormData
    const formData = new FormData(this);
    
    // Submit via AJAX
    fetch(this.action, {
    method: 'POST',
    body: formData,
    headers: {
        'X-Requested-With': 'XMLHttpRequest'
    }
})
    .then(response => response.json())
    .then(data => {
    if (data.success) {
        alert(data.message);
        document.getElementById('pasangBaruForm').reset();
        document.getElementById('step-4').style.display = 'none';
        document.getElementById('step-1').style.display = 'block';
        updateStepIndicator(1);
        currentStep = 1;
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    } else {
        alert(data.message || 'Terjadi kesalahan!');
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    }
})
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat mengirim data!');
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
});

console.log('Pasang Baru form initialized!');