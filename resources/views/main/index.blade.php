@extends('main.layout.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid" style="padding-left: 60px; padding-right: 60px; margin-top: 30px;">
    <!-- <div class="row mb-2 justify-content-center"> -->
    <div class="row mb-2">
        <!-- Card Pengguna -->
        <div class="col-md-3 mb-4">
            <a href="#" class="text-decoration-none">
                <div class="card stat-card border-0 rounded-3" style="background: linear-gradient(135deg, #FFC107 0%, #FFB300 100%); min-height: 150px;">
                    <div class="card-body d-flex flex-column justify-content-between h-100">
                        <div class="d-flex justify-content-between align-items-flex-start">
                            <div>
                                <h3 class="card-title fw-bold display-4 mb-2 text-dark">
                                    {{ $users }}
                                </h3>
                                <p class="card-text text-dark fw-semibold mb-0">Pengguna</p>
                            </div>
                            <i class="fas fa-user fa-3x" style="color: rgba(0,0,0,0.1);"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Card Tamu -->
        <div class="col-md-3 mb-4">
            <a href="#" class="text-decoration-none">
                <div class="card stat-card border-0 rounded-3" style="background: linear-gradient(135deg, #17A2B8 0%, #138496 100%); min-height: 150px;">
                    <div class="card-body d-flex flex-column justify-content-between h-100">
                        <div class="d-flex justify-content-between align-items-flex-start">
                            <div>
                                <h3 class="card-title fw-bold display-4 mb-2 text-white">
                                    {{ $guests }}
                                </h3>
                                <p class="card-text text-white fw-semibold mb-0">Tamu</p>
                            </div>
                            <i class="fas fa-user-tie fa-3x" style="color: rgba(255,255,255,0.2);"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Card Gedung -->
        <div class="col-md-3 mb-4">
            <a href="#" class="text-decoration-none">
                <div class="card stat-card border-0 rounded-3" style="background: linear-gradient(135deg, #6C757D 0%, #5A6268 100%); min-height: 150px;">
                    <div class="card-body d-flex flex-column justify-content-between h-100">
                        <div class="d-flex justify-content-between align-items-flex-start">
                            <div>
                                <h3 class="card-title fw-bold display-4 mb-2 text-white">
                                    {{ $buildings }}
                                </h3>
                                <p class="card-text text-white fw-semibold mb-0">Gedung</p>
                            </div>
                            <i class="fas fa-building fa-3x" style="color: rgba(255,255,255,0.2);"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>

<style>
    .stat-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }

    .display-4 {
        font-size: 3rem;
        line-height: 1;
    }
</style>
@endsection
