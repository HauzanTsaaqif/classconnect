<?php
require_once '../../config/db.php';
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'guru') {
    header("Location: ../../index.php");
    exit();
}
$user_id = $_SESSION['user_id'];
$email   = $_SESSION['email'];

/* ambil nama guru */
$stmt = $conn->prepare("SELECT name FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

/* statistik ringkas */
// total kelas
$stmt = $conn->prepare("SELECT COUNT(*) AS c FROM classes WHERE teacher_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$total_classes = $stmt->get_result()->fetch_assoc()['c'];
$stmt->close();

// total siswa unik di semua kelas milik guru
$stmt = $conn->prepare("SELECT COUNT(DISTINCT student_id) AS c FROM class_enrollments WHERE class_id IN (SELECT id FROM classes WHERE teacher_id = ?)");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$total_students = $stmt->get_result()->fetch_assoc()['c'];
$stmt->close();

?>
<!DOCTYPE html>
<html lang="id" data-bs-theme="light">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" type="image/x-icon" href="../../img/logo2.png">
    <title>Dashboard Guru – ClassConnect</title>

    <!-- Bootstrap 5 + Font -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet" />
    <link href="Animate.css" rel="stylesheet" />
    <link href="../css/dashboard.css" rel="stylesheet" />
</head>


<body class="animate__animated animate__fadeIn">
    <nav class="navbar navbar-expand-lg shadow-sm">
        <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
            <a class="navbar-brand" href="dashboard.php"><i class="fas fa-graduation-cap"></i> ClassConnect</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="ms-auto d-flex align-items-center gap-4">
                <a class="nav-link active" href="../dashboard_guru/dashboard.php">Dashboard</a>
                <a class="nav-link" href="../kelas_saya/Kelas.php">Kelas Saya</a>
                <a class="nav-link" href="../tugas/tugas.php">Tugas</a>
                <a href="../../login/logout.php" class="btn btn-sm btn-outline-light"><i class="fa-solid fa-right-from-bracket me-1"></i>Logout</a>
            </div>
        </div>
    </nav>

    <main class="container-fluid pt-4">
        <div class="container-xl">
            <header class="mb-4">
                <h1 class="h3 fw-bold mb-1">Selamat datang, <?= htmlspecialchars($user['name']) ?> 👋</h1>
                <p class="text-muted mb-0">Berikut ringkasan aktivitas mengajar Anda hari ini.</p>
            </header>

            <section class="row g-3 mb-4" aria-label="Statistik mengajar">
                <div class="col-6 col-lg-3">
                    <div class="stat-card" tabindex="0" role="button" aria-label="Total kelas">
                        <i class="fa-solid fa-layer-group"></i>
                        <div>
                            <div class="stat-number" aria-live="polite"><?= $total_classes ?></div>
                            <div class="small text-muted">Total Kelas</div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="stat-card" tabindex="0" role="button" aria-label="Total siswa">
                        <i class="fa-solid fa-users"></i>
                        <div>
                            <div class="stat-number" aria-live="polite"><?= $total_students ?></div>
                            <div class="small text-muted">Total Siswa</div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Fitur utama -->
            <section class="row g-4" aria-label="Fitur penting">
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card h-100">
                        <div>
                            <i class="fa-solid fa-book-open mb-2"></i>
                            <h2 class="h5 mb-2">Kelola Modul & Materi</h2>
                            <p class="small text-muted">Atur modul, unggah materi, dan susun konten kurikulum dengan mudah melalui antarmuka drag-and-drop.</p>
                        </div>
                        <button class="btn btn-primary feature-button mt-3 align-self-start" onclick="location.href='../kelas_saya/Kelas.php'">Kelola Modul</button>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card h-100">
                        <div>
                            <i class="fa-solid fa-stopwatch mb-2"></i>
                            <h2 class="h5 mb-2">Buat Tugas & Deadline</h2>
                            <p class="small text-muted">Rancang tugas, tetapkan tenggat, dan kirim notifikasi otomatis ke siswa.</p>
                        </div>
                        <button class="btn btn-primary feature-button mt-3 align-self-start" onclick="location.href='../tugas/tugas.php'">Atur Tugas</button>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card h-100">
                        <div>
                            <i class="fa-solid fa-chart-line mb-2"></i>
                            <h2 class="h5 mb-2">Statistik Kinerja</h2>
                            <p class="small text-muted">Pantau partisipasi siswa dan analisis performa kelas secara visual.</p>
                        </div>
                        <button class="btn btn-outline-primary feature-button mt-3 align-self-start" data-bs-toggle="modal" data-bs-target="#statistikModal">Lihat Statistik</button>
                    </div>
                </div>
            </section>
        </div>
    </main>

    <!-- MODAL Statistik -->
    <div class="modal fade" tabindex="-1" id="statistikModal" aria-labelledby="statistikModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title h5" id="statistikModalLabel">📊 Statistik Kelas</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul class="list-unstyled mb-0">
                        <li>• Grafik kehadiran dan partisipasi (coming soon)</li>
                        <li>• Distribusi nilai siswa</li>
                        <li>• Laporan perkembangan per modul</li>
                        <li>• Ekspor laporan ke PDF</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <footer class="text-center small">
        <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
            <span>&copy; 2025 ClassConnect</span>
            <span>Hubungi Admin: <a href="mailto:hauzantsaaqif28@gmail.com">hauzantsaaqif28@gmail.com</a></span>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const themeToggler = document.getElementById('themeToggler');
        const htmlTag = document.documentElement;
        const storedTheme = localStorage.getItem('cc-theme');
        if (storedTheme) {
            htmlTag.setAttribute('data-bs-theme', storedTheme);
        }
        themeToggler.addEventListener('click', () => {
            const currentTheme = htmlTag.getAttribute('data-bs-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            htmlTag.setAttribute('data-bs-theme', newTheme);
            localStorage.setItem('cc-theme', newTheme);
            themeToggler.innerHTML = newTheme === 'dark' ? '<i class="fa-solid fa-sun"></i>' : '<i class="fa-solid fa-moon"></i>';
        });
    </script>
</body>

</html>