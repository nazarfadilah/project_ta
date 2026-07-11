@extends('users.layout.app')

@section('title', 'Daftar Sarana')

@section('css')
<link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="container-fluid" style="padding-left: 20px; padding-right: 20px; margin-top: 20px;">
    
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header d-flex align-items-center justify-content-between" style="background-color: #C9A961; color: #fff; border-radius: 8px 8px 0 0; padding: 14px 20px;">
            <h6 class="mb-0 fw-semibold" style="font-size: 15px;">
                <i class="fas fa-tools me-2"></i>Daftar Semua Sarana
            </h6>
        </div>
        <div class="card-body" style="padding: 20px;">
            @if($saranas->count() > 0)
            <div class="table-responsive">
                <table id="saranaTable" class="table table-hover table-bordered align-middle" style="width: 100%; font-size: 14px;">
                    <thead style="background-color: #f8f9fa;">
                        <tr>
                            <th style="width: 10%; text-align: center;">No</th>
                            <th style="width: 35%;">Nama Sarana</th>
                            <th style="width: 20%;">Kondisi</th>
                            <th style="width: 20%;">Tgl Penerimaan</th>
                            <th style="width: 15%;">Stok</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($saranas as $index => $sarana)
                        <tr>
                            <td style="text-align: center; font-weight: 600; color: #7f8c8d;">{{ $index + 1 }}</td>
                            <td>
                                <strong>{{ $sarana->nama }}</strong>
                            </td>
                            <td>
                                @if($sarana->kondisi === 'Baik Sekali')
                                    <span class="badge bg-success text-white"><i class="fas fa-circle-check me-1"></i> Baik Sekali</span>
                                @elseif($sarana->kondisi === 'Baik')
                                    <span class="badge bg-primary text-white"><i class="fas fa-circle-check me-1"></i> Baik</span>
                                @else
                                    <span class="badge bg-danger text-white"><i class="fas fa-triangle-exclamation me-1"></i> Perlu Perbaikan</span>
                                @endif
                            </td>
                            <td>
                                <span class="text-muted"><i class="fas fa-calendar-day me-1"></i> {{ \Carbon\Carbon::parse($sarana->tgl_penerimaan)->format('d M Y') }}</span>
                            </td>
                            <td>
                                <strong style="color: #B8953F;">{{ $sarana->stok }}</strong> unit
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-5 text-muted">
                <i class="fas fa-inbox fa-3x mb-3" style="opacity: 0.5;"></i>
                <h5>Tidak Ada Sarana</h5>
                <p class="mb-0">Belum ada data sarana yang tersedia.</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('saranaTable')) {
        $('#saranaTable').DataTable({
            responsive: true,
            pageLength: 10,
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
            },
            columnDefs: [
                {
                    targets: 0,
                    orderable: false,
                    searchable: false
                }
            ]
        });
    }
});
</script>
@endsection
