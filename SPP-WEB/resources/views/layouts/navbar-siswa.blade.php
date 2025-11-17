<ul class="nav justify-content-center p-4 border-bottom mb-3">
    <li class="nav-item">
        <a class="nav-link active" href="/siswa/beranda">🏠 Beranda</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="/siswa/pembayaran/detail/{{ Auth::guard('siswa')->user()->nisn }}">🗒️ Riwayat Pembayaran</a>
    </li>
    <li class="nav-item">
        <form action="/logout" method="POST">
            @csrf
            <button type="submit" class="nav-link logout" onclick="return confirm('Apakah anda ingin logout?')">⬅️
                Logout</button>
        </form>
    </li>
</ul>
