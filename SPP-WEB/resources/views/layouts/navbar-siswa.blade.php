<nav class="sidebar" style="overflow-y: auto;">
    <header>
        <div class="image-text">
            <span class="image">
                <img src="{{ asset('image/logo_ti.png') }}" alt="Logo TI">
            </span>

            <div class="text header-text">
                <span class="name">Aplikasi SPP</span>
                <span class="address">Dashboard Siswa</span>
            </div>
        </div>
    </header>

    <div class="menu-bar">
        <div class="menu">
            <ul class="menu-links">

                <hr class="divider">

                <li class="nav-link {{ request()->is('siswa/beranda') ? 'active' : '' }}">
                    <a href="/siswa/beranda">
                        <i class='bx bx-home icon'></i>
                        <span class="text nav-text">Beranda</span>
                    </a>
                </li>

                <hr class="divider">

                <li class="nav-link {{ request()->is('siswa/pembayaran*') ? 'active' : '' }}">
                    <a href="/siswa/pembayaran/detail/{{ Auth::guard('siswa')->user()->nisn }}">
                        <i class='bx bx-money icon'></i>
                        <span class="text nav-text">Riwayat Pembayaran</span>
                    </a>
                </li>

                <hr class="divider">

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
