<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReservationStatusMail;

class PeminjamanTransaksi extends Model {
    use HasFactory;
    protected $table = 'peminjaman_transaksi';
    protected $guarded = [];
    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    protected $casts = [
        'tanggal' => 'date',
        'jamMulai' => 'datetime',
        'checkIn' => 'datetime',
        'checkOut' => 'datetime',
        'tanggalApproval' => 'datetime',
    ];

    protected static function booted()
    {
        static::created(function ($peminjaman) {
            $user = $peminjaman->user ?? User::where('guestId', $peminjaman->guestId)->first();
            if ($user && $user->email) {
                if ($peminjaman->statusApproval === 'APPROVED') {
                    try {
                        Mail::to($user->email)->send(new ReservationStatusMail($peminjaman, 'APPROVED'));
                    } catch (\Exception $e) {
                        \Log::error('Gagal kirim email status reservasi (created approved): ' . $e->getMessage());
                    }
                } elseif ($peminjaman->statusApproval === 'PENDING') {
                    try {
                        Mail::to($user->email)->send(new ReservationStatusMail($peminjaman, 'PENDING'));
                    } catch (\Exception $e) {
                        \Log::error('Gagal kirim email status reservasi (created pending): ' . $e->getMessage());
                    }
                }
            }
        });

        static::updated(function ($peminjaman) {
            $user = $peminjaman->user ?? User::where('guestId', $peminjaman->guestId)->first();
            if ($user && $user->email) {
                // Only monitor details changes (email for status change is handled directly in the Controller)
                if (!$peminjaman->wasChanged('statusApproval')) {
                    $monitored = ['tanggal', 'jamMulai', 'durasi', 'facilityId', 'biayaTambahan', 'keterangan'];
                    $changed = false;
                    foreach ($monitored as $field) {
                        if ($peminjaman->wasChanged($field)) {
                            $changed = true;
                            break;
                        }
                    }
                    if ($changed) {
                        try {
                            Mail::to($user->email)->send(new ReservationStatusMail($peminjaman, 'UPDATED'));
                        } catch (\Exception $e) {
                            \Log::error('Gagal kirim email status reservasi (updated details): ' . $e->getMessage());
                        }
                    }
                }
            }
        });
    }

    public function guest() {
        return $this->belongsTo(Guest::class, 'guestId', 'id');
    }

    public function paketRuangan() {
        return $this->belongsTo(PaketRuangan::class, 'facilityId', 'id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'userId', 'id');
    }

    public function invoice() {
        return $this->hasOne(Invoice::class, 'peminjamanId', 'id');
    }

    public function detailSaranas() {
        return $this->hasMany(DetailPeminjamanSarana::class, 'peminjaman_id', 'id');
    }
}