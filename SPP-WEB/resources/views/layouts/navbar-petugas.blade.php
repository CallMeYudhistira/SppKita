<nav class="sidebar" style="overflow-y: auto;">
    <header>
        <div class="image-text">
            <span class="image">
                <img src="{{ asset('image/logo_ti.png') }}" alt="Logo TI">
            </span>

            <div class="text header-text">
                <span class="name">Aplikasi SPP</span>
                <span class="address">Dashboard Petugas</span>
            </div>
        </div>
    </header>

    <div class="menu-bar">

        <hr class="divider" style="margin-bottom: 1.3rem;">

        <div class="profile-bar">
            <div class="profile-info">
                <i class='bx bx-user-circle icon profile-icon'></i>
                <div class="profile-text">
                    <span class="profile-name">{{ Auth::guard('petugas')->user()->nama_petugas }}</span>
                    <span class="profile-level">{{ ucfirst(Auth::guard('petugas')->user()->level) }}</span>
                </div>
            </div>
        </div>

        <div class="menu">
            <ul class="menu-links">

                <hr class="divider">

                <li class="nav-link {{ request()->is('petugas/beranda') ? 'active' : '' }}">
                    <a href="/petugas/beranda">
                        <i class='bx bx-home icon'></i>
                        <span class="text nav-text">Beranda</span>
                    </a>
                </li>

                <hr class="divider">

                @php
                    $isKelolaData =
                        request()->is('petugas') ||
                        request()->is('kelas*') ||
                        request()->is('spp*') ||
                        request()->is('siswa*');
                @endphp

                @if (Auth::guard('petugas')->user()->level == 'admin')
                    <li class="nav-link dropdown-btn {{ $isKelolaData ? 'active' : '' }}">
                        <a href="javascript:void(0);">
                            <i class='bx bx-folder icon'></i>
                            <span class="text nav-text">Kelola Data</span>
                            <i class='bx bx-chevron-down arrow'></i>
                        </a>
                    </li>

                    <ul class="submenu {{ $isKelolaData ? 'show' : '' }}">
                        <li class="nav-link {{ request()->is('petugas') ? 'active' : '' }}">
                            <a href="/petugas">
                                <i class='bx bxs-id-card icon'></i>
                                <span class="text nav-text">Petugas</span>
                            </a>
                        </li>

                        <li class="nav-link {{ request()->is('kelas*') ? 'active' : '' }}">
                            <a href="/kelas">
                                <i class='bx bx-building-house icon'></i>
                                <span class="text nav-text">Kelas</span>
                            </a>
                        </li>

                        <li class="nav-link {{ request()->is('spp*') ? 'active' : '' }}">
                            <a href="/spp">
                                <i class='bx bx-file icon'></i>
                                <span class="text nav-text">SPP</span>
                            </a>
                        </li>

                        <li class="nav-link {{ request()->is('siswa*') ? 'active' : '' }}">
                            <a href="/siswa">
                                <i class='bx bx-user icon'></i>
                                <span class="text nav-text">Siswa</span>
                            </a>
                        </li>
                    </ul>

                    <hr class="divider">
                @endif

                <li class="nav-link {{ request()->is('pembayaran*') ? 'active' : '' }}">
                    <a href="/pembayaran">
                        <i class='bx bx-money icon'></i>
                        <span class="text nav-text">Pembayaran</span>
                    </a>
                </li>

                <hr class="divider">

                @if (Auth::guard('petugas')->user()->level == 'admin')
                    <li class="nav-link {{ request()->is('laporan*') ? 'active' : '' }}">
                        <a href="/laporan">
                            <i class='bx bx-printer icon'></i>
                            <span class="text nav-text">Laporan</span>
                        </a>
                    </li>

                    <hr class="divider">
                @endif

                <li class="nav-link">
                    <a href="javascript:void(0)" id="logoutBtn">
                        <i class='bx bx-log-out icon'></i>
                        <span class="text nav-text">Logout</span>
                    </a>
                </li>

                <hr class="divider">

            </ul>
        </div>
    </div>
</nav>

<div class="modal fade" id="logoutModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Konfirmasi Logout</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <div class="alert alert-danger" role="alert">
                    Apakah anda ingin logout?
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-dark" data-bs-dismiss="modal">Batal</button>
                <form action="/logout" method="POST">
                    @csrf
                    <button class="btn btn-danger">Logout</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    const dropdownBtns = document.querySelectorAll('.dropdown-btn');
    dropdownBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            btn.classList.toggle('active');
            const submenu = btn.nextElementSibling;
            submenu.classList.toggle('show');
        });
    });

    const logoutBtn = document.getElementById('logoutBtn');
    logoutBtn.addEventListener("click", function() {
        var modal = new bootstrap.Modal(document.getElementById('logoutModal'));
        modal.show();
    });
</script>
