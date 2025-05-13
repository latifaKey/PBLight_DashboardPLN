@extends('layouts.app')

@section('title', 'Notifikasi')
@section('page_title', 'NOTIFIKASI')

@section('styles')
<style>
    /* Main Container */
    .dashboard-content {
        max-width: 1800px;
        margin: 0 auto;
        padding: 0 15px;
    }

    /* Page Header - Modern UI */
    .page-header {
        background: linear-gradient(135deg, var(--pln-blue), var(--pln-light-blue));
        color: white;
        border-radius: 12px;
        padding: 20px 25px;
        margin-bottom: 25px;
        box-shadow: 0 5px 15px rgba(0, 123, 255, 0.2);
        display: flex;
        justify-content: space-between;
        align-items: center;
        animation: slideInDown 0.5s ease-out;
    }

    @keyframes slideInDown {
        from {
            transform: translateY(-20px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .page-header h2 {
        font-size: 1.5rem;
        font-weight: 600;
        margin: 0;
    }

    .page-header-subtitle {
        margin-top: 5px;
        font-weight: 400;
        font-size: 0.9rem;
        opacity: 0.9;
    }

    .page-header-actions {
        display: flex;
        gap: 10px;
    }

    .page-header-badge {
        background: rgba(255, 255, 255, 0.2);
        padding: 5px 12px;
        border-radius: 50px;
        font-size: 0.8rem;
        display: flex;
        align-items: center;
    }

    .page-header-badge i {
        margin-right: 5px;
    }

    /* Notification Card */
    .notif-card {
        background: var(--pln-surface);
        border-radius: 16px;
        margin-bottom: 25px;
        position: relative;
        overflow: hidden;
        border: 1px solid var(--pln-border);
        box-shadow: 0 8px 20px var(--pln-shadow);
        animation: fadeIn 0.6s ease-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .notif-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, var(--pln-blue), var(--pln-light-blue));
    }

    .notif-card .card-header {
        background: linear-gradient(135deg, var(--pln-blue), var(--pln-light-blue));
        color: white;
        border: none;
        border-radius: 16px 16px 0 0;
        padding: 15px 20px;
    }

    .notif-card .card-body {
        padding: 0;
    }

    /* Notification Item Styling */
    .notif-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .notif-item {
        padding: 15px 20px;
        border-bottom: 1px solid var(--pln-border);
        transition: all 0.3s ease;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        animation: fadeInRight 0.5s ease-out;
        animation-fill-mode: both;
    }

    @keyframes fadeInRight {
        from {
            opacity: 0;
            transform: translateX(-20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .notif-item:nth-child(odd) {
        animation-delay: 0.1s;
    }

    .notif-item:nth-child(even) {
        animation-delay: 0.2s;
    }

    .notif-item:last-child {
        border-bottom: none;
    }

    .notif-item:hover {
        background-color: var(--pln-accent-bg);
        transform: translateX(5px);
        box-shadow: -5px 0 10px rgba(0, 0, 0, 0.05);
    }

    .notif-item.unread {
        background-color: rgba(0, 123, 255, 0.05);
        border-left: 4px solid var(--pln-blue);
        padding-left: 16px;
    }

    .notif-item.unread::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        background-color: var(--pln-blue);
        animation: pulseHighlight 2s infinite;
    }

    @keyframes pulseHighlight {
        0% { opacity: 0.6; }
        50% { opacity: 1; }
        100% { opacity: 0.6; }
    }

    .notif-content {
        flex: 1;
    }

    .notif-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 5px;
    }

    .notif-title {
        font-weight: 600;
        font-size: 1rem;
        color: var(--pln-text);
        margin: 0;
    }

    .notif-time {
        font-size: 0.8rem;
        color: var(--pln-text-secondary);
    }

    .notif-message {
        color: var(--pln-text);
        margin-bottom: 12px;
        font-size: 0.95rem;
    }

    .notif-actions {
        display: flex;
        gap: 8px;
        margin-top: 10px;
    }

    .notif-btn-group {
        display: flex;
        gap: 8px;
        align-items: center;
    }

    /* Button Styling */
    .btn-action {
        padding: 8px 15px;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        transition: all 0.3s;
        white-space: nowrap;
        position: relative;
        overflow: hidden;
    }

    .btn-action::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 5px;
        height: 5px;
        background: rgba(255, 255, 255, 0.5);
        opacity: 0;
        border-radius: 100%;
        transform: scale(1, 1) translate(-50%);
        transform-origin: 50% 50%;
    }

    .btn-action:hover::after {
        animation: ripple 1s ease-out;
    }

    @keyframes ripple {
        0% {
            transform: scale(0, 0);
            opacity: 0.5;
        }
        100% {
            transform: scale(20, 20);
            opacity: 0;
        }
    }

    .btn-action i {
        margin-right: 6px;
    }

    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px var(--pln-shadow);
    }

    .no-notif {
        padding: 30px 20px;
        text-align: center;
        color: var(--pln-text-secondary);
        animation: fadeInUp 0.8s ease-out;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .no-notif i {
        font-size: 3rem;
        color: var(--pln-light-blue);
        margin-bottom: 15px;
        opacity: 0.6;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.1); }
        100% { transform: scale(1); }
    }

    /* Pagination Styling */
    .pagination-wrapper {
        padding: 15px 20px;
        border-top: 1px solid var(--pln-border);
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }

        .page-header-actions {
            width: 100%;
            justify-content: flex-start;
            margin-top: 10px;
        }

        .notif-item {
            flex-direction: column;
        }

        .notif-btn-group {
            margin-top: 10px;
            justify-content: flex-end;
            width: 100%;
        }
    }
</style>
@endsection

@section('content')
<div class="dashboard-content">
    <!-- Modern Page Header -->
    <div class="page-header">
        <div>
            <h2><i class="fas fa-bell me-2"></i>Notifikasi</h2>
            <div class="page-header-subtitle">
                Kelola pesan dan pemberitahuan sistem
            </div>
        </div>
        <div class="page-header-actions">
            <div class="page-header-badge">
                <i class="fas fa-envelope"></i>
                Total: {{ $notifikasis->total() }}
            </div>
            <div class="page-header-badge">
                <i class="fas fa-envelope-open"></i>
                Belum Dibaca: {{ $notifikasis->where('dibaca', false)->count() }}
            </div>
        </div>
    </div>

    @include('components.alert')

    <div class="notif-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="m-0 font-weight-bold">Daftar Notifikasi</h5>
            <div>
                <button id="markAllRead" class="btn btn-light btn-action">
                    <i class="fas fa-check-double"></i> Tandai Semua Dibaca
                </button>
                <button id="deleteRead" class="btn btn-danger btn-action">
                    <i class="fas fa-trash"></i> Hapus yang Sudah Dibaca
                </button>
            </div>
        </div>
        <div class="card-body">
            @if($notifikasis->count() > 0)
                <ul class="notif-list">
                    @foreach($notifikasis as $notifikasi)
                        <li class="notif-item {{ $notifikasi->dibaca ? '' : 'unread' }}">
                            <div class="notif-content">
                                <div class="notif-header">
                                    <h6 class="notif-title">{{ $notifikasi->judul }}</h6>
                                    <span class="notif-time">{{ $notifikasi->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="notif-message">{{ $notifikasi->pesan }}</p>
                                <div class="notif-actions">
                                    @if($notifikasi->url)
                                        <a href="{{ $notifikasi->url }}" class="btn btn-info btn-action">
                                            <i class="fas fa-external-link-alt"></i> Lihat Detail
                                        </a>
                                    @endif
                                    @if(!$notifikasi->dibaca)
                                        <a href="{{ route('notifikasi.tandaiDibaca', $notifikasi->id) }}" class="btn btn-primary btn-action mark-read" data-id="{{ $notifikasi->id }}">
                                            <i class="fas fa-check"></i> Tandai Dibaca
                                        </a>
                                    @endif
                                </div>
                            </div>
                            <div class="notif-btn-group">
                                <form method="POST" action="{{ route('notifikasi.destroy', $notifikasi->id) }}" class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-action delete-notif" data-id="{{ $notifikasi->id }}">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </li>
                    @endforeach
                </ul>
                <div class="pagination-wrapper">
                    {{ $notifikasis->links() }}
                </div>
            @else
                <div class="no-notif">
                    <i class="fas fa-bell-slash d-block"></i>
                    <h5>Tidak ada notifikasi</h5>
                    <p>Anda belum memiliki notifikasi untuk ditampilkan.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Animasi notifikasi dengan efek expand/collapse
        const notifItems = document.querySelectorAll('.notif-item');
        notifItems.forEach(item => {
            const message = item.querySelector('.notif-message');
            const originalText = message.textContent;

            // Jika pesan lebih dari 100 karakter, tambahkan expand/collapse
            if (originalText.length > 100) {
                const shortText = originalText.substring(0, 100) + '...';
                message.textContent = shortText;

                // Tambahkan tombol expand
                const expandBtn = document.createElement('button');
                expandBtn.className = 'btn btn-sm btn-light expand-btn';
                expandBtn.innerHTML = '<i class="fas fa-angle-down"></i> Selengkapnya';
                expandBtn.style.marginLeft = '5px';
                expandBtn.style.fontSize = '0.75rem';
                expandBtn.style.padding = '2px 8px';
                expandBtn.style.borderRadius = '50px';

                message.parentNode.insertBefore(expandBtn, message.nextSibling);

                // Toggle expand/collapse dengan animasi
                expandBtn.addEventListener('click', function() {
                    if (message.textContent === shortText) {
                        message.style.maxHeight = '0';
                        setTimeout(() => {
                            message.textContent = originalText;
                            message.classList.add('animate__animated', 'animate__fadeIn');
                            message.style.maxHeight = 'none';
                            expandBtn.innerHTML = '<i class="fas fa-angle-up"></i> Sembunyikan';
                        }, 200);
                    } else {
                        message.style.maxHeight = '0';
                        setTimeout(() => {
                            message.textContent = shortText;
                            message.classList.add('animate__animated', 'animate__fadeIn');
                            message.style.maxHeight = 'none';
                            expandBtn.innerHTML = '<i class="fas fa-angle-down"></i> Selengkapnya';
                        }, 200);
                    }
                });
            }
        });

        // Mark all as read dengan animasi
        document.getElementById('markAllRead').addEventListener('click', function(e) {
            e.preventDefault();

            // Ganti konfirmasi standar dengan SweetAlert
            window.confirmAlert(
                'Tandai Semua Dibaca?',
                'Semua notifikasi akan ditandai sebagai telah dibaca.',
                'Ya, Tandai Semua'
            ).then((result) => {
                if (result.isConfirmed) {
                    // Tambahkan animasi ke notifikasi unread sebelum reload
                    document.querySelectorAll('.notif-item.unread').forEach((item, index) => {
                        setTimeout(() => {
                            item.classList.add('animate__animated', 'animate__fadeOutLeft');
                            setTimeout(() => {
                                item.classList.remove('unread');
                                item.classList.remove('animate__fadeOutLeft');
                                item.classList.add('animate__fadeInRight');
                            }, 500);
                        }, index * 100);
                    });

                    // Kirim request ke server
                    setTimeout(() => {
                        fetch('{{ route("notifikasi.tandaiSemuaDibaca") }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            }
                        }).then(response => response.json())
                        .then(data => {
                            if(data.success) {
                                window.successAlert('Berhasil!', 'Semua notifikasi ditandai sebagai dibaca');
                                setTimeout(() => {
                                    window.location.reload();
                                }, 1500);
                            }
                        });
                    }, 800);
                }
            });
        });

        // Delete all read notifications dengan animasi
        document.getElementById('deleteRead').addEventListener('click', function(e) {
            e.preventDefault();

            // Ganti konfirmasi standar dengan SweetAlert
            window.deleteConfirm(
                'Hapus Notifikasi Dibaca?',
                'Semua notifikasi yang sudah dibaca akan dihapus permanen.'
            ).then((result) => {
                if (result.isConfirmed) {
                    // Tambahkan animasi ke notifikasi yang akan dihapus
                    document.querySelectorAll('.notif-item:not(.unread)').forEach((item, index) => {
                        setTimeout(() => {
                            item.classList.add('animate__animated', 'animate__zoomOut');
                        }, index * 100);
                    });

                    // Kirim request ke server
                    setTimeout(() => {
                        fetch('{{ route("notifikasi.hapusSudahDibaca") }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            }
                        }).then(response => response.json())
                        .then(data => {
                            if(data.success) {
                                window.successAlert('Berhasil!', 'Notifikasi yang sudah dibaca telah dihapus');
                                setTimeout(() => {
                                    window.location.reload();
                                }, 1500);
                            }
                        });
                    }, 800);
                }
            });
        });

        // Mark individual notification as read dengan animasi
        document.querySelectorAll('.mark-read').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const id = this.getAttribute('data-id');
                const notifItem = this.closest('.notif-item');

                // Animasi ketika notifikasi ditandai sebagai dibaca
                notifItem.classList.add('animate__animated', 'animate__fadeOutLeft');

                setTimeout(() => {
                    fetch(`/notifikasi/${id}/tandai-dibaca`)
                        .then(response => response.json())
                        .then(data => {
                            if(data.success) {
                                notifItem.classList.remove('unread');
                                notifItem.classList.remove('animate__fadeOutLeft');
                                notifItem.classList.add('animate__fadeInRight');

                                window.showNotification('Berhasil', 'Notifikasi ditandai sebagai dibaca', 'success');
                                this.remove(); // Hapus tombol tandai dibaca
                            }
                        });
                }, 500);
            });
        });

        // Delete individual notification dengan animasi
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const notifItem = this.closest('.notif-item');

                // Ganti konfirmasi standar dengan SweetAlert
                window.deleteConfirm(
                    'Hapus Notifikasi?',
                    'Notifikasi ini akan dihapus permanen.'
                ).then((result) => {
                    if (result.isConfirmed) {
                        // Animasi hapus
                        notifItem.classList.add('animate__animated', 'animate__zoomOut');

                        setTimeout(() => {
                            this.submit();
                        }, 500);
                    }
                });
            });
        });
    });
</script>
@endsection
