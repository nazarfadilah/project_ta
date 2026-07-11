@extends($layout)

@section('title', 'Notifikasi Saya')

@section('css')
<style>
    .notif-card {
        border-radius: 12px;
        transition: all 0.3s ease;
        border: 1px solid rgba(0, 0, 0, 0.05) !important;
        background-color: #fff;
    }
    .notif-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }
    .notif-card.unread {
        border-left: 4px solid #C9A961 !important;
        background-color: rgba(201, 169, 97, 0.03);
    }
    .notif-icon {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }
    .notif-icon.diajukan {
        background-color: rgba(236, 201, 75, 0.15);
        color: #d69e2e;
    }
    .notif-icon.disetujui {
        background-color: rgba(72, 187, 120, 0.15);
        color: #38a169;
    }
    .notif-icon.dibatalkan {
        background-color: rgba(229, 62, 62, 0.15);
        color: #e53e3e;
    }
    .notif-icon.sistem {
        background-color: rgba(49, 130, 206, 0.15);
        color: #3182ce;
    }
    .dark-theme .notif-card {
        background-color: #1e1e1e !important;
        border-color: #2d2d2d !important;
        color: #e0e0e0;
    }
    .dark-theme .notif-card.unread {
        background-color: rgba(201, 169, 97, 0.05) !important;
    }
</style>
@endsection

@section('content')
<div class="container py-4" style="max-width: 800px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0 text-dark" style="font-family: 'Outfit', sans-serif;">Notifikasi</h4>
            <p class="text-muted mb-0" style="font-size: 13px;">Daftar pesan masuk dan pembaruan sistem Anda.</p>
        </div>
        @if($notifications->whereNull('read_at')->count() > 0)
            <button class="btn btn-outline-secondary btn-sm rounded-3 fw-semibold px-3 py-2" id="btnMarkAllRead" style="font-size: 13px;">
                <i class="fas fa-check-double me-1"></i> Tandai Semua Dibaca
            </button>
        @endif
    </div>

    @if($notifications->isEmpty())
        <div class="card border-0 shadow-sm text-center py-5">
            <div class="card-body">
                <i class="far fa-bell-slash fa-4x mb-3 text-muted" style="opacity: 0.5;"></i>
                <h5 class="fw-bold">Belum Ada Notifikasi</h5>
                <p class="text-muted" style="font-size: 14px;">Semua pemberitahuan aktivitas Anda akan muncul di sini.</p>
            </div>
        </div>
    @else
        <div class="d-flex flex-column gap-3 mb-4">
            @foreach($notifications as $notif)
                @php
                    $isUnread = is_null($notif->read_at);
                    
                    // Determine icon class and background type
                    $iconClass = 'fas fa-info-circle';
                    $iconType = 'sistem';
                    
                    if (stripos($notif->type, 'Diajukan') !== false) {
                        $iconClass = 'fas fa-paper-plane';
                        $iconType = 'diajukan';
                    } elseif (stripos($notif->type, 'Disetujui') !== false) {
                        $iconClass = 'fas fa-check-circle';
                        $iconType = 'disetujui';
                    } elseif (stripos($notif->type, 'Dibatalkan') !== false || stripos($notif->type, 'Ditolak') !== false) {
                        $iconClass = 'fas fa-times-circle';
                        $iconType = 'dibatalkan';
                    } elseif (stripos($notif->type, 'Check-In') !== false || stripos($notif->type, 'Check-Out') !== false) {
                        $iconClass = 'fas fa-door-open';
                        $iconType = 'sistem';
                    }
                @endphp
                <div class="card notif-card {{ $isUnread ? 'unread' : '' }} border-0 shadow-sm p-3" data-id="{{ $notif->id }}">
                    <div class="d-flex align-items-start gap-3">
                        <div class="notif-icon {{ $iconType }}">
                            <i class="{{ $iconClass }}"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <h6 class="fw-bold mb-0 text-dark" style="font-size: 14.5px;">{{ $notif->type }}</h6>
                                <span class="text-muted" style="font-size: 11px;">{{ $notif->created_at ? $notif->created_at->diffForHumans() : '-' }}</span>
                            </div>
                            <p class="mb-0 text-secondary" style="font-size: 13.5px; line-height: 1.5;">
                                {!! $notif->message !!}
                            </p>
                        </div>
                        @if($isUnread)
                            <button class="btn btn-link btn-sm p-0 text-decoration-none text-muted btnReadSingle" title="Tandai telah dibaca" style="font-size: 11px;">
                                <i class="fas fa-circle text-primary" style="font-size: 8px;"></i>
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="d-flex justify-content-center">
            {{ $notifications->links() }}
        </div>
    @endif
</div>
@endsection

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Mark single notification as read
        document.querySelectorAll('.notif-card.unread').forEach(card => {
            const notifId = card.getAttribute('data-id');
            const readBtn = card.querySelector('.btnReadSingle');

            const markReadFn = function() {
                fetch(`/notifications/${notifId}/read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        card.classList.remove('unread');
                        if (readBtn) readBtn.remove();
                        // Update navbar count if global reload is not done
                    }
                });
            };

            // Click on the blue dot to read
            if (readBtn) {
                readBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    markReadFn();
                });
            }
            
            // Hover or click on the card to read
            card.addEventListener('click', markReadFn);
        });

        // Mark all as read
        const btnMarkAllRead = document.getElementById('btnMarkAllRead');
        if (btnMarkAllRead) {
            btnMarkAllRead.addEventListener('click', function() {
                fetch('/notifications/read-all', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    }
                });
            });
        }
    });
</script>
@endsection
