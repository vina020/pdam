// Tarif Air Script
let tarifDataFromDB = [];

document.addEventListener('DOMContentLoaded', function() {
    console.log('Tarif Air page loaded!');
    
    // Load tarif dari API
    loadTarifData();
    
    // Initialize smooth scroll
    initSmoothScroll();
    
    // Initialize animations
    initScrollAnimation();
});

/**
 * Load tarif data dari database
 */
async function loadTarifData() {
    try {
        const response = await fetch('/api/tarif-air/public');
        const result = await response.json();
        
        if (result.success) {
            tarifDataFromDB = result.data;
            console.log('Tarif data loaded:', tarifDataFromDB);
        }
    } catch (error) {
        console.error('Error loading tarif data:', error);
        alert('Gagal memuat data tarif');
    }
}

/**
 * Hitung tarif berdasarkan golongan dan pemakaian
 */
function hitungTarif() {
    const golongan = document.getElementById('golongan').value;
    const pemakaian = parseFloat(document.getElementById('pemakaian').value);
    
    // Validasi input
    if (!golongan) {
        alert('Pilih golongan pelanggan terlebih dahulu');
        return;
    }
    
    if (!pemakaian || pemakaian <= 0) {
        alert('Masukkan jumlah pemakaian air (m³)');
        return;
    }
    
    // Cari tarif yang sesuai dari database
    let tarifTerpilih = null;
    
    // Extract kategori dari golongan (misal: "rumah_tangga_1" -> "rumah_tangga")
    let kategori = golongan;
    if (golongan.includes('_')) {
        const parts = golongan.split('_');
        // Untuk rumah_tangga_1, rumah_tangga_2, dll
        if (parts.length === 3 && (parts[0] + '_' + parts[1]) === 'rumah_tangga') {
            kategori = 'rumah_tangga';
        } else if (parts.length === 2) {
            // niaga_kecil, niaga_besar
            kategori = parts[0];
        }
    }
    
    // Cari tarif yang cocok berdasarkan kategori dan pemakaian
    const tarifList = tarifDataFromDB.filter(t => t.kategori === kategori);
    
    if (tarifList.length === 0) {
        alert('Data tarif tidak ditemukan untuk golongan ini');
        return;
    }
    
    // Pilih tarif berdasarkan range pemakaian
    for (let tarif of tarifList) {
        const min = tarif.min_pemakaian || 0;
        const max = tarif.max_pemakaian;
        
        // Jika max_pemakaian null (unlimited)
        if (max === null) {
            if (pemakaian >= min) {
                tarifTerpilih = tarif;
                break;
            }
        } else {
            // Range normal
            if (pemakaian >= min && pemakaian <= max) {
                tarifTerpilih = tarif;
                break;
            }
        }
    }
    
    // Fallback: ambil tarif terakhir jika tidak ada yang cocok (untuk unlimited)
    if (!tarifTerpilih && tarifList.length > 0) {
        tarifTerpilih = tarifList[tarifList.length - 1];
    }
    
    if (!tarifTerpilih) {
        alert('Tidak dapat menentukan tarif untuk pemakaian ini');
        return;
    }
    
    // Hitung biaya
    const tarif = tarifTerpilih.tarif_per_m3;
    const biayaPemakaian = pemakaian * tarif;
    
    // Biaya tambahan
    const biayaAdmin = 2500;
    const biayaPemeliharaan = 1000;
    const totalTagihan = biayaPemakaian + biayaAdmin + biayaPemeliharaan;
    
    // Tampilkan hasil
    displayResult(pemakaian, tarif, biayaPemakaian, totalTagihan);
}

/**
 * Display calculation result
 */
function displayResult(pemakaian, tarif, biayaPemakaian, total) {
    const resultDiv = document.getElementById('calculatorResult');
    
    // Update values
    document.getElementById('result-pemakaian').textContent = pemakaian + ' m³';
    document.getElementById('result-tarif').textContent = formatRupiah(tarif);
    document.getElementById('result-biaya').textContent = formatRupiah(biayaPemakaian);
    document.getElementById('result-total').textContent = formatRupiah(total);
    
    // Show result
    resultDiv.style.display = 'block';
    
    // Scroll to result
    resultDiv.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

/**
 * Format number to Rupiah
 */
function formatRupiah(angka) {
    return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

/**
 * Switch tarif tabs
 */
function switchTab(tab) {
    // Remove active from all tabs
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    
    // Add active to clicked tab
    event.target.closest('.tab-btn').classList.add('active');
    
    // Get all categories
    const allCategories = document.querySelectorAll('.tarif-category');
    
    if (tab === 'all') {
        // Show all categories
        allCategories.forEach(category => {
            category.style.display = 'block';
        });
    } else {
        // Hide all first
        allCategories.forEach(category => {
            category.style.display = 'none';
        });
        
        // Show only selected category
        allCategories.forEach(category => {
            const categoryIcon = category.querySelector('.category-icon');
            
            if (tab === 'sosial' && categoryIcon.classList.contains('sosial')) {
                category.style.display = 'block';
            } else if (tab === 'rumah_tangga' && categoryIcon.classList.contains('rumah-tangga')) {
                category.style.display = 'block';
            } else if (tab === 'niaga' && categoryIcon.classList.contains('niaga')) {
                category.style.display = 'block';
            } else if (tab === 'lainnya') {
                if (categoryIcon.classList.contains('industri') || categoryIcon.classList.contains('instansi')) {
                    category.style.display = 'block';
                }
            }
        });
    }
}

/**
 * Initialize smooth scroll
 */
function initSmoothScroll() {
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
}

/**
 * Initialize scroll animations
 */
function initScrollAnimation() {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    // Observe elements
    document.querySelectorAll('.tarif-category, .biaya-card').forEach(element => {
        element.style.opacity = '0';
        element.style.transform = 'translateY(20px)';
        element.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(element);
    });
}

/**
 * Reset calculator
 */
function resetCalculator() {
    document.getElementById('golongan').value = '';
    document.getElementById('pemakaian').value = '';
    document.getElementById('calculatorResult').style.display = 'none';
}

// Add keyboard shortcut for calculator (Enter to calculate)
document.getElementById('pemakaian')?.addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        hitungTarif();
    }
});

// Add animation for calculator result
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    #calculatorResult {
        animation: slideIn 0.4s ease;
    }
`;
document.head.appendChild(style);

console.log('Tarif Air functionality initialized!');