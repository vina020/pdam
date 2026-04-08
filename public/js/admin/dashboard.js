// Dashboard Admin Script
let chartPengaduan = null;
let chartStatusPengaduan = null;
let chartPendaftaran = null;

document.addEventListener('DOMContentLoaded', function() {
    console.log('Dashboard Admin loaded!');
    
    // Initialize
    initDashboard();
    updateDateTime();
    setInterval(updateDateTime, 1000);
});

/**
 * Initialize Dashboard
 */
async function initDashboard() {
    try {
        // Load all data
        await Promise.all([
            loadStats(),
            loadChartPengaduan(),
            loadChartPendaftaran(),
            loadRecentActivities()
        ]);
    } catch (error) {
        console.error('Error initializing dashboard:', error);
        showNotification('Gagal memuat data dashboard', 'error');
    }
}

/**
 * Load Stats Cards
 */
async function loadStats() {
    try {
        const response = await fetch('/admin/dashboard/stats');
        const result = await response.json();
        
        if (result.success) {
            const data = result.data;
            
            // Update stat values
            document.getElementById('totalPelanggan').textContent = formatNumber(data.total_pelanggan);
            document.getElementById('pendaftaranBaru').textContent = formatNumber(data.pendaftaran_baru);
            document.getElementById('pengaduanAktif').textContent = formatNumber(data.pengaduan_aktif);
            document.getElementById('totalBerita').textContent = formatNumber(data.total_berita);
            document.getElementById('totalTarif').textContent = formatNumber(data.total_tarif);
            document.getElementById('pengaduanSelesai').textContent = formatNumber(data.pengaduan_selesai);
            document.getElementById('tagihanBelumBayar').textContent = formatNumber(data.tagihan_belum_bayar);
            document.getElementById('maklumatAktif').textContent = formatNumber(data.maklumat_aktif);
            document.getElementById('totalViews').textContent = formatNumber(data.total_views);
            
            // Update growth
            const growthEl = document.getElementById('pendaftaranGrowth');
            const growth = data.pendaftaran_growth;
            
            if (growth > 0) {
                growthEl.innerHTML = `<i class="fas fa-arrow-up"></i> ${growth}%`;
                growthEl.className = 'stat-growth positive';
            } else if (growth < 0) {
                growthEl.innerHTML = `<i class="fas fa-arrow-down"></i> ${Math.abs(growth)}%`;
                growthEl.className = 'stat-growth negative';
            } else {
                growthEl.innerHTML = `<i class="fas fa-minus"></i> 0%`;
                growthEl.className = 'stat-growth';
            }

            const tagihanGrowthEl = document.getElementById('tagihanGrowth');
            const tagihanGrowth = data.tagihan_growth;

            if (tagihanGrowth > 0) {
                tagihanGrowth.innerHTML = `<i class="fas fa-arrow-up"></i> ${tagihanGrowth}%`;
                tagihanGrowthEl.className = 'stat-growth negative';
            } else if (tagihanGrowth < 0) {
                tagihanGrowthEl.innerHTML = `<i class="fas fa-arrow-down"></i> ${Math.abs(tagihanGrowth)}%`;
                tagihanGrowthEl.className = 'stat-growth positive';
            } else {
                tagihanGrowthEl.innerHTML = `<i class="fas fa-minus"></i> 0%`;
                tagihanGrowthEl.className = 'stat-growth';
            }
            
            // Animate stats
            animateStats();
        }
    } catch (error) {
        console.error('Error loading stats:', error);
    }
}

/**
 * Load Chart Pengaduan
 */
async function loadChartPengaduan() {
    try {
        const response = await fetch('/admin/dashboard/chart-pengaduan');
        const result = await response.json();
        
        if (result.success) {
            // Chart harian
            createChartPengaduanDaily(result.daily);
            
            // Chart status
            createChartPengaduanStatus(result.status);
        }
    } catch (error) {
        console.error('Error loading chart pengaduan:', error);
    }
}

/**
 * Create Chart Pengaduan Daily
 */
function createChartPengaduanDaily(data) {
    const ctx = document.getElementById('chartPengaduan');
    
    // Destroy existing chart
    if (chartPengaduan) {
        chartPengaduan.destroy();
    }
    
    // Prepare data
    const labels = data.map(item => formatDateShort(item.date));
    const values = data.map(item => item.total);
    
    chartPengaduan = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Jumlah Pengaduan',
                data: values,
                backgroundColor: 'rgba(253, 126, 20, 0.8)',
                borderColor: 'rgba(253, 126, 20, 1)',
                borderWidth: 2,
                borderRadius: 8,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleFont: {
                        size: 14,
                        weight: 'bold'
                    },
                    bodyFont: {
                        size: 13
                    },
                    cornerRadius: 8
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        font: {
                            size: 12
                        }
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    ticks: {
                        font: {
                            size: 11
                        }
                    },
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
}

/**
 * Create Chart Pengaduan Status
 */
function createChartPengaduanStatus(data) {
    const ctx = document.getElementById('chartStatusPengaduan');
    
    // Destroy existing chart
    if (chartStatusPengaduan) {
        chartStatusPengaduan.destroy();
    }
    
    // Prepare data
    const labels = data.map(item => formatStatus(item.status));
    const values = data.map(item => item.total);
    const colors = data.map(item => getStatusColor(item.status));
    
    chartStatusPengaduan = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: values,
                backgroundColor: colors,
                borderWidth: 3,
                borderColor: '#ffffff',
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        font: {
                            size: 12,
                            weight: '600'
                        },
                        usePointStyle: true,
                        pointStyle: 'circle'
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleFont: {
                        size: 14,
                        weight: 'bold'
                    },
                    bodyFont: {
                        size: 13
                    },
                    cornerRadius: 8,
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((context.parsed / total) * 100).toFixed(1);
                            return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                        }
                    }
                }
            }
        }
    });
}

/**
 * Load Chart Pendaftaran
 */
async function loadChartPendaftaran() {
    try {
        const response = await fetch('/admin/dashboard/chart-pendaftaran');
        const result = await response.json();
        
        if (result.success) {
            createChartPendaftaran(result.data);
        }
    } catch (error) {
        console.error('Error loading chart pendaftaran:', error);
    }
}

/**
 * Create Chart Pendaftaran
 */
function createChartPendaftaran(data) {
    const ctx = document.getElementById('chartPendaftaran');
    
    // Destroy existing chart
    if (chartPendaftaran) {
        chartPendaftaran.destroy();
    }
    
    // Prepare data
    const labels = data.map(item => formatMonth(item.month, item.year));
    const values = data.map(item => item.total);
    
    chartPendaftaran = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Jumlah Pendaftaran',
                data: values,
                backgroundColor: 'rgba(25, 135, 84, 0.1)',
                borderColor: 'rgba(25, 135, 84, 1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointRadius: 5,
                pointHoverRadius: 8,
                pointBackgroundColor: '#ffffff',
                pointBorderColor: 'rgba(25, 135, 84, 1)',
                pointBorderWidth: 3,
                pointHoverBackgroundColor: 'rgba(25, 135, 84, 1)',
                pointHoverBorderColor: '#ffffff',
                pointHoverBorderWidth: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleFont: {
                        size: 14,
                        weight: 'bold'
                    },
                    bodyFont: {
                        size: 13
                    },
                    cornerRadius: 8
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 5,
                        font: {
                            size: 12
                        }
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    ticks: {
                        font: {
                            size: 12
                        }
                    },
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
}

/**
 * Load Recent Activities
 */
async function loadRecentActivities() {
    try {
        const response = await fetch('/admin/dashboard/recent-activities');
        const result = await response.json();
        
        if (result.success) {
            displayActivities(result.data);
        }
    } catch (error) {
        console.error('Error loading activities:', error);
    }
}

/**
 * Display Activities
 */
function displayActivities(data) {
    const container = document.getElementById('activityList');
    
    if (data.length === 0) {
        container.innerHTML = `
            <div class="loading-activities">
                <i class="fas fa-inbox"></i>
                <p>Belum ada aktivitas</p>
            </div>
        `;
        return;
    }
    
    let html = '';
    
    data.forEach(activity => {
        const iconClass = getActivityIcon(activity.type);
        const timeAgo = formatTimeAgo(activity.created_at);
        
        html += `
            <div class="activity-item">
                <div class="activity-icon ${activity.type}">
                    <i class="${iconClass}"></i>
                </div>
                <div class="activity-content">
                    <span class="activity-title">${activity.judul}</span>
                    <span class="activity-time">${timeAgo}</span>
                </div>
            </div>
        `;
    });
    
    container.innerHTML = html;
}

/**
 * Helper Functions
 */
function formatNumber(num) {
    return new Intl.NumberFormat('id-ID').format(num);
}

function formatDateShort(dateStr) {
    const date = new Date(dateStr);
    const options = { day: 'numeric', month: 'short' };
    return date.toLocaleDateString('id-ID', options);
}

function formatMonth(month, year) {
    const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
    return monthNames[month - 1] + ' ' + year;
}

function formatStatus(status) {
    const statusMap = {
        'pending': 'Pending',
        'proses': 'Proses',
        'selesai': 'Selesai',
        'ditolak': 'Ditolak'
    };
    return statusMap[status] || status;
}

function getStatusColor(status) {
    const colors = {
        'pending': '#ffc107',
        'proses': '#0dcaf0',
        'selesai': '#198754',
        'ditolak': '#dc3545'
    };
    return colors[status] || '#6c757d';
}

function getActivityIcon(type) {
    const icons = {
        'pengaduan': 'fas fa-headset',
        'pendaftaran': 'fas fa-user-plus',
        'berita': 'fas fa-newspaper'
    };
    return icons[type] || 'fas fa-circle';
}

function formatTimeAgo(dateStr) {
    const date = new Date(dateStr);
    const now = new Date();
    const diffMs = now - date;
    const diffMins = Math.floor(diffMs / 60000);
    const diffHours = Math.floor(diffMs / 3600000);
    const diffDays = Math.floor(diffMs / 86400000);
    
    if (diffMins < 1) {
        return 'Baru saja';
    } else if (diffMins < 60) {
        return diffMins + ' menit yang lalu';
    } else if (diffHours < 24) {
        return diffHours + ' jam yang lalu';
    } else if (diffDays < 7) {
        return diffDays + ' hari yang lalu';
    } else {
        return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
    }
}

function updateDateTime() {
    const now = new Date();
    const options = { 
        weekday: 'long', 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    };
    document.getElementById('currentDate').textContent = now.toLocaleDateString('id-ID', options);
}

function animateStats() {
    const statValues = document.querySelectorAll('.stat-value');
    
    statValues.forEach(stat => {
        const finalValue = parseInt(stat.textContent.replace(/\./g, ''));
        let currentValue = 0;
        const increment = finalValue / 50;
        const duration = 1000;
        const stepTime = duration / 50;
        
        const counter = setInterval(() => {
            currentValue += increment;
            if (currentValue >= finalValue) {
                stat.textContent = formatNumber(finalValue);
                clearInterval(counter);
            } else {
                stat.textContent = formatNumber(Math.floor(currentValue));
            }
        }, stepTime);
    });
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

// Auto refresh every 5 minutes
setInterval(() => {
    loadStats();
    loadChartPengaduan();
    loadRecentActivities();
}, 300000);

console.log('Dashboard Admin script loaded!');