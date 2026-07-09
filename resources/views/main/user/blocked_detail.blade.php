@extends('main.layout.app')

@section('title', 'Detail Permohonan Buka Blokir')

@section('content')
<div class="container-fluid" style="padding-left: 40px; padding-right: 40px; margin-top: 20px;">

    {{-- Back link --}}
    <div class="mb-3">
        <a href="{{ route('main.users.blocked') }}" class="text-decoration-none" style="color: #ab8b46; font-size: 14px; font-weight: 500;">
            <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar Pengguna Terblokir
        </a>
    </div>

    {{-- Detail Card --}}
    <div class="row">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-header" style="background-color: #1a1a1a; color: #fff; border-radius: 8px 8px 0 0; padding: 14px 20px;">
                    <h6 class="mb-0 fw-semibold" style="font-size: 15px;">
                        <i class="fas fa-user me-2"></i>Informasi Akun Pengguna
                    </h6>
                </div>
                <div class="card-body" style="padding: 24px;">
                    <table class="table table-borderless align-middle mb-0" style="font-size: 14px;">
                        <tr>
                            <td style="width: 150px; font-weight: 600; color: #555;">Nama Pengguna:</td>
                            <td style="color: #333;">{{ $user->username }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: 600; color: #555;">Alamat Email:</td>
                            <td style="color: #333;">{{ $user->email }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: 600; color: #555;">No. Telepon:</td>
                            <td style="color: #333;">{{ $user->phone ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: 600; color: #555;">Peran (Role):</td>
                            <td><span class="badge bg-info">{{ $user->role->name ?? 'User' }}</span></td>
                        </tr>
                        <tr>
                            <td style="font-weight: 600; color: #555;">Status Akun:</td>
                            <td>
                                @if($user->status === 'SUSPENDED')
                                    <span class="badge bg-warning text-dark">Di Blokir Sementara</span>
                                @elseif($user->status === 'SUSPENDED_PERMANENT')
                                    <span class="badge bg-danger">Di Blokir Permanen</span>
                                @else
                                    <span class="badge bg-secondary">{{ $user->status }}</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td style="font-weight: 600; color: #555; vertical-align: top;">Alasan Diblokir:</td>
                            <td style="color: #d9534f; font-style: italic;">
                                "{{ $user->blocked_reason ?? 'Tidak ada alasan spesifik.' }}"
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-header" style="background-color: #ab8b46; color: #fff; border-radius: 8px 8px 0 0; padding: 14px 20px;">
                    <h6 class="mb-0 fw-semibold" style="font-size: 15px;">
                        <i class="fas fa-file-alt me-2"></i>Permohonan Pembukaan Blokir
                    </h6>
                </div>
                <div class="card-body" style="padding: 24px;">
                    @if($unblockRequest)
                        <div class="alert alert-info border-0 rounded-3 mb-4" style="background-color: #f7f4eb; color: #7f6424; font-size: 14px;">
                            <i class="fas fa-info-circle me-2"></i>Pengguna telah memverifikasi kode OTP email dan mengirimkan alasan permohonan di bawah ini.
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-secondary" style="font-size: 12px; text-transform: uppercase;">Tanggal Pengajuan</label>
                            <div class="p-2 bg-light rounded" style="font-size: 14px; color: #333;">
                                {{ $unblockRequest->created_at->format('d-m-Y H:i') }} WITA
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold text-secondary" style="font-size: 12px; text-transform: uppercase;">Catatan / Alasan Pengguna</label>
                            <div class="p-3 rounded border" style="background-color: #fafafa; font-size: 14px; line-height: 1.6; color: #333; min-height: 80px; font-style: italic;">
                                "{{ $unblockRequest->reason }}"
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="d-flex gap-2">
                            <form action="{{ route('main.users.unblock.approve', $user->id) }}" method="POST" style="flex: 1;" onsubmit="return confirm('Apakah Anda yakin ingin menyetujui pembukaan blokir akun ini? Password akan di-reset otomatis ke default: password')">
                                @csrf
                                <button type="submit" class="btn btn-success w-100" style="font-size: 13px; padding: 10px;">
                                    <i class="fas fa-check me-1"></i> Setujui Buka Blokir
                                </button>
                            </form>
                            <form action="{{ route('main.users.unblock.reject', $user->id) }}" method="POST" style="flex: 1;" onsubmit="return confirm('Apakah Anda yakin ingin menolak permohonan ini? Akun akan diblokir secara PERMANEN!')">
                                @csrf
                                <button type="submit" class="btn btn-danger w-100" style="font-size: 13px; padding: 10px;">
                                    <i class="fas fa-ban me-1"></i> Tolak (Blokir Permanen)
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-exclamation-triangle text-muted mb-3" style="font-size: 40px;"></i>
                            <p class="text-muted mb-0" style="font-size: 14px;">Belum ada pengajuan pembukaan blokir aktif (PENDING) dari pengguna ini.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
