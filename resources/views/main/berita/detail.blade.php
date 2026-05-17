@extends('main.layout.app')

@section('title', 'Detail Berita')

@section('content')
<div class="container-fluid" style="padding-left: 40px; padding-right: 40px; margin-top: 20px;">

    {{-- Alert Success --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="font-size: 14px;">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Alert Error --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="font-size: 14px;">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Card Detail --}}
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header" style="background-color: #C9A961; color: #fff; border-radius: 8px 8px 0 0; padding: 14px 20px;">
            <h6 class="mb-0 fw-semibold" style="font-size: 15px;">
                <i class="fas fa-newspaper me-2"></i>Detail Berita
            </h6>
        </div>
        <div class="card-body" style="padding: 24px;">

            {{-- Tombol Approve/Reject (Hanya untuk Draft) --}}
            @if($berita->status === 'draft')
            <div class="mb-4 pb-3 border-bottom">
                <h6 class="mb-3 fw-semibold" style="color: #555; font-size: 14px;">
                    <i class="fas fa-check-double me-2"></i>Publikasi Berita
                </h6>
                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-success" 
                            data-bs-toggle="modal" 
                            data-bs-target="#approveModal"
                            style="font-size: 13px; padding: 8px 16px;">
                        <i class="fas fa-check me-1"></i> Setuju Publikasikan
                    </button>
                    <button class="btn btn-sm btn-danger" 
                            data-bs-toggle="modal" 
                            data-bs-target="#rejectModal"
                            style="font-size: 13px; padding: 8px 16px;">
                        <i class="fas fa-times me-1"></i> Tolak
                    </button>
                </div>
            </div>
            @endif

            {{-- Status Badge --}}
            <div class="mb-4">
                <div style="display: inline-block; padding: 8px 12px; border-radius: 6px; background-color: #f8f9fa; border-left: 4px solid #C9A961;">
                    <small style="color: #666; font-size: 12px;">Status:</small>
                    <br>
                    @if($berita->status === 'approved')
                        <span class="badge bg-success" style="font-size: 12px;">Approved</span>
                        <span style="color: #666; font-size: 12px; margin-left: 8px;">Dipublikasikan</span>
                    @elseif($berita->status === 'rejected')
                        <span class="badge bg-danger" style="font-size: 12px;">Rejected</span>
                        <span style="color: #666; font-size: 12px; margin-left: 8px;">Ditolak</span>
                    @else
                        <span class="badge bg-warning text-dark" style="font-size: 12px;">Draft</span>
                        <span style="color: #666; font-size: 12px; margin-left: 8px;">Belum diperiksa</span>
                    @endif
                </div>
            </div>

            {{-- Judul --}}
            <div class="mb-4">
                <label style="font-weight: 600; font-size: 13px; color: #555; display: block; margin-bottom: 8px;">
                    Judul
                </label>
                <div style="background-color: #f8f9fa; padding: 12px 14px; border-radius: 6px; border-left: 4px solid #C9A961; font-size: 14px; color: #444;">
                    {{ $berita->judul }}
                </div>
            </div>

            {{-- Slug --}}
            <div class="mb-4">
                <label style="font-weight: 600; font-size: 13px; color: #555; display: block; margin-bottom: 8px;">
                    Slug
                </label>
                <div style="background-color: #f8f9fa; padding: 12px 14px; border-radius: 6px; border-left: 4px solid #C9A961; font-size: 14px; color: #666; word-break: break-all;">
                    <code>{{ $berita->slug }}</code>
                </div>
            </div>

            {{-- Gambar --}}
            @if($berita->gambar)
            <div class="mb-4">
                <label style="font-weight: 600; font-size: 13px; color: #555; display: block; margin-bottom: 8px;">
                    Gambar
                </label>
                <div style="max-width: 400px; border-radius: 8px; overflow: hidden; border: 1px solid #ddd; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <img src="{{ asset($berita->gambar) }}" alt="{{ $berita->judul }}" style="width: 100%; height: auto; display: block;">
                </div>
            </div>
            @endif

            {{-- Tanggal Publikasi --}}
            <div class="row mb-4">
                <div class="col-md-6">
                    <label style="font-weight: 600; font-size: 13px; color: #555; display: block; margin-bottom: 8px;">
                        Tanggal Publikasi
                    </label>
                    <div style="background-color: #f8f9fa; padding: 12px 14px; border-radius: 6px; border-left: 4px solid #C9A961; font-size: 14px; color: #444;">
                        {{ $berita->tanggal_publish->format('d M Y') }}
                    </div>
                </div>
                <div class="col-md-6">
                    <label style="font-weight: 600; font-size: 13px; color: #555; display: block; margin-bottom: 8px;">
                        Dibuat Pada
                    </label>
                    <div style="background-color: #f8f9fa; padding: 12px 14px; border-radius: 6px; border-left: 4px solid #C9A961; font-size: 14px; color: #444;">
                        {{ $berita->created_at->format('d M Y H:i') }}
                    </div>
                </div>
            </div>

            {{-- Isi Berita --}}
            <div class="mb-4">
                <label style="font-weight: 600; font-size: 13px; color: #555; display: block; margin-bottom: 8px;">
                    Isi Berita
                </label>
                <div style="background-color: #f8f9fa; padding: 12px 14px; border-radius: 6px; border-left: 4px solid #C9A961; font-size: 14px; color: #444; line-height: 1.6; word-wrap: break-word; white-space: pre-wrap;">
                    {{ $berita->isi }}
                </div>
            </div>

            {{-- Keterangan (jika ada) --}}
            @if($berita->keterangan)
            <div class="mb-4">
                <label style="font-weight: 600; font-size: 13px; color: #555; display: block; margin-bottom: 8px;">
                    Keterangan
                </label>
                <div style="background-color: #f8f9fa; padding: 12px 14px; border-radius: 6px; border-left: 4px solid #C9A961; font-size: 14px; color: #444; word-wrap: break-word; white-space: pre-wrap;">
                    {{ $berita->keterangan }}
                </div>
            </div>
            @endif

            {{-- Tombol Aksi (Bawah) --}}
            <div class="d-flex gap-2 pt-3 border-top">
                <a href="{{ route('main.berita.edit', $berita->id) }}" 
                   class="btn btn-sm btn-warning" 
                   style="font-size: 13px; padding: 8px 20px;">
                    <i class="fas fa-edit me-1"></i> Edit
                </a>
                <button class="btn btn-sm btn-danger" 
                        onclick="hapusData('{{ route('main.berita.destroy', $berita->id) }}')"
                        style="font-size: 13px; padding: 8px 20px;">
                    <i class="fas fa-trash me-1"></i> Hapus
                </button>
                <a href="{{ route('main.berita.index') }}" 
                   class="btn btn-sm btn-secondary" 
                   style="font-size: 13px; padding: 8px 20px;">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Modal Approve --}}
<div class="modal fade" id="approveModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content border-0 rounded-3">
            <div class="modal-header border-0" style="background-color: #C9A961; color: #fff;">
                <h6 class="modal-title fw-semibold" style="font-size: 15px;">
                    <i class="fas fa-check me-2"></i>Setujui Publikasi
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('main.berita.approve', $berita->id) }}" method="POST">
                @csrf
                <div class="modal-body" style="padding: 20px;">
                    <p style="font-size: 14px; color: #555; margin-bottom: 15px;">
                        Apakah Anda yakin ingin mempublikasikan berita ini?
                    </p>
                    <div class="form-group">
                        <label for="keterangan_approve" style="font-size: 13px; color: #555; margin-bottom: 8px; display: block;">
                            Catatan (Opsional)
                        </label>
                        <textarea class="form-control" 
                                  id="keterangan_approve" 
                                  name="keterangan" 
                                  rows="3" 
                                  placeholder="Tambahkan catatan atau alasan persetujuan"
                                  style="font-size: 13px; padding: 10px;"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0" style="padding: 15px 20px;">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-sm btn-success">
                        <i class="fas fa-check me-1"></i> Ya, Publikasikan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Reject --}}
<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content border-0 rounded-3">
            <div class="modal-header border-0" style="background-color: #C9A961; color: #fff;">
                <h6 class="modal-title fw-semibold" style="font-size: 15px;">
                    <i class="fas fa-times me-2"></i>Tolak Publikasi
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('main.berita.reject', $berita->id) }}" method="POST">
                @csrf
                <div class="modal-body" style="padding: 20px;">
                    <p style="font-size: 14px; color: #555; margin-bottom: 15px;">
                        Apakah Anda yakin ingin menolak publikasi berita ini?
                    </p>
                    <div class="form-group">
                        <label for="keterangan_reject" style="font-size: 13px; color: #555; margin-bottom: 8px; display: block;">
                            Alasan Penolakan <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control" 
                                  id="keterangan_reject" 
                                  name="keterangan" 
                                  rows="3" 
                                  placeholder="Jelaskan alasan penolakan publikasi berita"
                                  style="font-size: 13px; padding: 10px;" required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0" style="padding: 15px 20px;">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-sm btn-danger">
                        <i class="fas fa-times me-1"></i> Ya, Tolak
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function hapusData(url) {
        if (confirm('Apakah Anda yakin ingin menghapus berita ini?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = url;
            form.innerHTML = '@csrf @method("DELETE")';
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>
@endpush
@endsection
