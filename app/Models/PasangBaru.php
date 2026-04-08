<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class PasangBaru extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Nama tabel
     */
    protected $table = 'pasang_baru';

    /**
     * Field yang bisa diisi mass assignment
     */
    protected $fillable = [
        'nomor_registrasi',
        'nama_pemohon',
        'nik',
        'alamat_pemohon',
        'no_telepon',
        'email',
        'jalan',
        'nomor_rumah',
        'rt',
        'rw',
        'kecamatan',
        'kelurahan',
        'daya_listrik',
        'latitude',
        'longitude',
        'dokumen_ktp',
        'dokumen_kk',
        'dokumen_pbb',
        'dokumen_listrik',
        'dokumen_domisili',
        'status',
        'catatan',
        'tanggal_pengajuan',
        'tanggal_verifikasi',
        'tanggal_survei',
        'tanggal_approved',
        'processed_by',
    ];

    /**
     * Field yang di-cast ke tipe data tertentu
     */
    protected $casts = [
        'tanggal_pengajuan' => 'datetime',
        'tanggal_verifikasi' => 'datetime',
        'tanggal_survei' => 'datetime',
        'tanggal_approved' => 'datetime',
    ];

    /**
     * Accessor: Get full URL untuk dokumen KTP
     */
    public function getDokumenKtpUrlAttribute()
    {
        return $this->dokumen_ktp ? Storage::url($this->dokumen_ktp) : null;
    }

    /**
     * Accessor: Get full URL untuk dokumen KK
     */
    public function getDokumenKkUrlAttribute()
    {
        return $this->dokumen_kk ? Storage::url($this->dokumen_kk) : null;
    }

    /**
     * Accessor: Get full URL untuk dokumen PBB
     */
    public function getDokumenPbbUrlAttribute()
    {
        return $this->dokumen_pbb ? Storage::url($this->dokumen_pbb) : null;
    }

    /**
     * Accessor: Get full URL untuk dokumen Listrik
     */
    public function getDokumenListrikUrlAttribute()
    {
        return $this->dokumen_listrik ? Storage::url($this->dokumen_listrik) : null;
    }

    /**
     * Accessor: Get full URL untuk dokumen Domisili
     */
    public function getDokumenDomisiliUrlAttribute()
    {
        return $this->dokumen_domisili ? Storage::url($this->dokumen_domisili) : null;
    }

    /**
     * Accessor: Get alamat lengkap pemasangan
     */
    public function getAlamatLengkapAttribute()
    {
        return "{$this->jalan} No. {$this->nomor_rumah}, RT {$this->rt}/RW {$this->rw}, {$this->kelurahan}, Kec. {$this->kecamatan}";
    }

    /**
     * Accessor: Get status label dengan warna
     */
    public function getStatusLabelAttribute()
    {
        $labels = [
            'pending' => ['text' => 'Menunggu', 'class' => 'badge-warning'],
            'verifikasi' => ['text' => 'Verifikasi', 'class' => 'badge-info'],
            'survei' => ['text' => 'Survei', 'class' => 'badge-primary'],
            'approved' => ['text' => 'Disetujui', 'class' => 'badge-success'],
            'ditolak' => ['text' => 'Ditolak', 'class' => 'badge-danger'],
            'selesai' =>  ['text' => 'Selesai', 'class' => 'badge-success'],
        ];

        return $labels[$this->status] ?? ['text' => 'Unknown', 'class' => 'badge-secondary'];
    }

    /**
     * Accessor: Get status badge HTML
     */
    public function getStatusBadgeAttribute()
    {
        $label = $this->status_label;
        return "<span class='badge {$label['class']}'>{$label['text']}</span>";
    }

    /**
     * Scope: Filter by status
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope: Filter by kecamatan
     */
    public function scopeKecamatan($query, $kecamatan)
    {
        return $query->where('kecamatan', $kecamatan);
    }

    /**
     * Scope: Filter by tanggal pengajuan
     */
    public function scopeTanggalPengajuan($query, $start, $end = null)
    {
        if ($end) {
            return $query->whereBetween('tanggal_pengajuan', [$start, $end]);
        }
        return $query->whereDate('tanggal_pengajuan', $start);
    }

    /**
     * Scope: Pending only
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope: Approved only
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope: Search by nama atau NIK
     */
    public function scopeSearch($query, $keyword)
    {
        return $query->where(function($q) use ($keyword) {
            $q->where('nama_pemohon', 'like', "%{$keyword}%")
              ->orWhere('nik', 'like', "%{$keyword}%")
              ->orWhere('nomor_registrasi', 'like', "%{$keyword}%");
        });
    }

    /**
     * Relationship: User yang memproses (jika ada)
     */
    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    /**
     * Method: Check if can be edited
     */
    public function canBeEdited()
    {
        return in_array($this->status, ['pending', 'verifikasi']);
    }

    /**
     * Method: Check if can be deleted
     */
    public function canBeDeleted()
    {
        return $this->status === 'pending';
    }

    /**
     * Method: Update status dengan timestamp
     */
    public function updateStatus($status, $catatan = null)
    {
        $this->status = $status;
        $this->catatan = $catatan;

        // Set timestamp sesuai status
        switch ($status) {
            case 'verifikasi':
                $this->tanggal_verifikasi = now();
                break;
            case 'survey':
                $this->tanggal_survei = now();
                break;
            case 'approved':
            case 'ditolak':
                $this->tanggal_approved = now();
                break;
        }

        $this->save();
    }

    /**
     * Boot method untuk event
     */
    protected static function boot()
    {
        parent::boot();

        // Event ketika data dihapus permanent
        static::deleting(function ($pasangBaru) {
            // Hapus semua file dokumen
            $dokumenFields = [
                'dokumen_ktp',
                'dokumen_kk',
                'dokumen_pbb',
                'dokumen_listrik',
                'dokumen_domisili'
            ];

            foreach ($dokumenFields as $field) {
                if ($pasangBaru->$field && Storage::exists('public/' . $pasangBaru->$field)) {
                    Storage::delete('public/' . $pasangBaru->$field);
                }
            }
        });
    }
}