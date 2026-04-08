document.addEventListener('DOMContentLoaded', function() {
    console.log('Pengaduan form loaded!');
});

// Form Submit
document.getElementById('pengaduanForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Validasi checkbox
    const agreeTerms = document.getElementById('agree_terms');
    if (!agreeTerms.checked) {
        alert('Anda harus menyetujui pernyataan!');
        return;
    }

    // Validasi panjang informasi pengaduan
    const infoPengaduan = document.getElementById('informasi_pengaduan').value;
    if (infoPengaduan.length < 20) {
        alert('Informasi pengaduan minimal 20 karakter!');
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
            // Reset form
            this.reset();
            // Reset button
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
            // Scroll to top
            window.scrollTo({ top: 0, behavior: 'smooth' });
        } else {
            alert(data.message || 'Terjadi kesalahan!');
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat mengirim pengaduan!');
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
});

// Validasi nomor telepon (hanya angka)
document.getElementById('no_whatsapp')?.addEventListener('input', function(e) {
    this.value = this.value.replace(/[^0-9]/g, '');
});

// Character counter untuk informasi pengaduan
document.getElementById('informasi_pengaduan')?.addEventListener('input', function(e) {
    const length = this.value.length;
    const minLength = 20;
    
    // Hapus counter lama jika ada
    let counter = this.parentElement.querySelector('.char-counter');
    if (!counter) {
        counter = document.createElement('small');
        counter.className = 'char-counter';
        counter.style.cssText = 'display: block; margin-top: 5px; font-size: 12px;';
        this.parentElement.appendChild(counter);
    }
    
    if (length < minLength) {
        counter.style.color = '#dc3545';
        counter.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${length}/${minLength} karakter (kurang ${minLength - length})`;
    } else {
        counter.style.color = '#28a745';
        counter.innerHTML = `<i class="fas fa-check-circle"></i> ${length} karakter`;
    }
});

console.log('Pengaduan form initialized!');