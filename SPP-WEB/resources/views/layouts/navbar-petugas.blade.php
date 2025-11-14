<ul class="nav justify-content-center p-4 border-bottom mb-3">
    <li class="nav-item">
        <a class="nav-link active" href="/petugas/beranda">🏠 Beranda</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#">Link</a>
    </li>
    <li class="nav-item">
        <div class="dropdown">
            <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                Kelola Data 🗃️
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="/kelas">Kelas 🏛️</a></li>
                <li><a class="dropdown-item" href="/spp">SPP 📃</a></li>
                <li><a class="dropdown-item" href="/siswa">Siswa 🧑‍🎓</a></li>
            </ul>
        </div>
    </li>
    <li class="nav-item">
        <form action="/logout" method="POST">
            @csrf
            <button type="submit" class="nav-link logout" onclick="return confirm('Apakah anda ingin logout?')">⬅️
                Logout</button>
        </form>
    </li>
</ul>
