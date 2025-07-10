<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit();
}

/* ---------------- Ambil parameter class_id ---------------- */
$class_id = $_GET['id'] ?? ($_SESSION['class_id'] ?? null);
$_SESSION['class_id'] = $class_id;      // simpan di session utk navigasi selanjutnya

if (!$class_id) {
    header("Location: siswa.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$email   = $_SESSION['email'];

require_once '../../config/db.php';

/* ----- data modul ----- */
$stmt = $conn->prepare("SELECT id,title,description FROM modules WHERE class_id=?");
$stmt->bind_param("i", $class_id);
$stmt->execute();
$data_modules = $stmt->get_result();

/* ----- detail kelas ----- */
$stmt2 = $conn->prepare("SELECT name,description FROM classes WHERE id=?");
$stmt2->bind_param("i", $class_id);
$stmt2->execute();
$data_class = $stmt2->get_result()->fetch_assoc();

$stmt->close();
$stmt2->close();
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0" />
    <title><?= htmlspecialchars($data_class['name']) ?> – Modul Kelas</title>

    <!-- CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="../../img/logo2.png">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <!-- ===== STYLE ===== -->
    <style>
        :root {
            --primary: #5C6BC0;
            --accent: #4DB6AC;
            --secondary: #FFB74D;
            --bg: #F9F9F9;
            --dark: #2E2E2E;
        }

        /* Global */
        * {
            box-sizing: border-box
        }

        body,
        html {
            margin: 0;
            min-height: 100%;
            display: flex;
            flex-direction: column;
            font-family: 'DM Sans', sans-serif;
            background: var(--bg);
        }

        main.container {
            flex: 1;
            padding: 40px 20px;
        }

        /* Navbar */
        .navbar {
            background: linear-gradient(90deg, var(--primary), var(--accent));
            box-shadow: 0 4px 12px rgba(0, 0, 0, .08);
            padding: .75rem 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .navbar-left {
            display: flex;
            align-items: center;
            gap: 1rem
        }

        .logo {
            display: flex;
            align-items: center;
            gap: .5rem
        }

        .logo img {
            height: 40px
        }

        .logo-title {
            color: #fff;
            font-weight: 700;
            font-size: 1.2rem;
            line-height: 1
        }

        .logo-subtitle {
            color: rgba(255, 255, 255, .8);
            font-size: .75rem
        }

        .menu-toggle {
            display: none;
            background: none;
            border: none;
            color: #fff;
            font-size: 1.3rem
        }

        .navbar-menu {
            display: flex;
            gap: 1.5rem;
            list-style: none;
            margin: 0;
            padding: 0;
            align-items: center
        }

        .navbar-menu a {
            color: #fff;
            text-decoration: none;
            font-weight: 500;
            position: relative
        }

        .navbar-menu a::after {
            content: '';
            position: absolute;
            bottom: -3px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--secondary);
            transition: .3s
        }

        .navbar-menu a:hover::after,
        .navbar-menu a.active::after {
            width: 100%
        }

        .navbar-right {
            display: flex;
            align-items: center;
            gap: .75rem;
            color: #fff
        }

        .status {
            width: 9px;
            height: 9px;
            border-radius: 50%;
            background: #66BB6A
        }

        .user-avatar {
            position: relative;
            cursor: pointer
        }

        .user-avatar span {
            font-size: 1.25rem;
            color: #fff
        }

        .dropdown-content {
            position: absolute;
            right: 0;
            top: 100%;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, .1);
            display: none;
            min-width: 170px;
            overflow: hidden
        }

        .dropdown-content a {
            display: block;
            padding: .75rem 1rem;
            color: var(--dark);
            text-decoration: none;
            font-size: .9rem
        }

        .dropdown-content a:hover {
            background: var(--bg)
        }

        .user-avatar:hover .dropdown-content {
            display: block
        }

        @media(max-width:768px) {
            .menu-toggle {
                display: block
            }

            .navbar-menu {
                flex-direction: column;
                position: absolute;
                top: 100%;
                left: 0;
                width: 100%;
                background: linear-gradient(90deg, var(--primary), var(--accent));
                transform: translateY(-15px);
                opacity: 0;
                pointer-events: none;
                transition: .3s
            }

            .navbar-menu.active {
                transform: translateY(0);
                opacity: 1;
                pointer-events: all
            }
        }

        /* Card kelas */
        .kelas-card {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, .06);
            padding: 32px;
            margin-bottom: 40px
        }

        .kelas-title {
            font-weight: 700;
            color: var(--primary)
        }

        .kelas-description {
            color: #666;
            font-size: .95rem;
            margin-bottom: 0
        }

        /* Grid Modul */
        .modul-grid {
            display: grid;
            gap: 1.5rem;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        }


        .modul-card {
            background: #fff;
            border-radius: 15px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            box-shadow: 0 6px 14px rgba(0, 0, 0, .05);
            transition: .3s
        }

        .modul-card:hover {
            transform: translateY(-5px)
        }

        .modul-header {
            background: linear-gradient(120deg, var(--primary), var(--accent));
            color: #fff;
            padding: 20px
        }

        .modul-header h3 {
            font-size: 1.05rem;
            font-weight: 600;
            margin: 0
        }

        .modul-body {
            padding: 20px;
            flex: 1
        }

        .modul-body p {
            margin: 0;
            color: #555;
            font-size: .9rem
        }

        .modul-bubble {
            display: flex;
            gap: .5rem;
            flex-wrap: wrap;
            padding: 0 20px 15px
        }

        .bubble-item {
            background: var(--secondary);
            color: #fff;
            border-radius: 12px;
            font-size: 1rem;
            padding: 10px 15px
        }

        .modul-actions {
            border-top: 1px solid #f1f1f1;
            padding: 15px 20px;
            display: flex;
            gap: .5rem
        }

        .modul-actions button {
            flex: 1;
            border: none;
            border-radius: 25px;
            padding: 9px;
            font-weight: 600;
            font-size: .9rem;
            cursor: pointer;
            transition: .25s
        }

        .btn-view {
            background: var(--primary);
            color: #fff
        }

        .btn-tugas {
            background: var(--accent);
            color: #fff
        }

        .btn-view:hover {
            background: var(--accent)
        }

        .btn-tugas:hover {
            background: var(--primary)
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 100;
            inset: 0;
            background: rgba(0, 0, 0, .6);
            justify-content: center;
            align-items: center;
            padding: 20px
        }

        .modal-content {
            background: #fff;
            border-radius: 15px;
            max-width: 500px;
            width: 100%;
            padding: 30px;
            max-height: 80vh;
            overflow: auto;
            animation: fadeIn .3s
        }

        .materi-list {
            margin-top: 15px
        }

        .materi-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #F5F7FA;
            border-radius: 10px;
            padding: 10px 15px;
            margin-bottom: 10px;
            font-size: .9rem
        }

        .materi-item button {
            background: var(--secondary);
            border: none;
            border-radius: 20px;
            color: #fff;
            font-size: .8rem;
            padding: 6px 14px
        }

        .btn-close {
            background: #ccc;
            color: #333
        }

        .btn-close:hover {
            background: #b3b3b3
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px)
            }

            to {
                opacity: 1;
                transform: none
            }
        }

        /* Footer */
        footer {
            background: linear-gradient(135deg, var(--primary), var(--accent));
            color: #fff;
            padding: 50px 0 35px
        }

        footer h5 {
            font-weight: 700;
            margin-bottom: 1rem
        }

        footer a {
            color: #eee;
            text-decoration: none;
            font-size: .95rem
        }

        footer a:hover {
            color: var(--secondary)
        }

        .footer-icon {
            font-size: 1.1rem;
            margin-right: 8px
        }

        .navbar .logo-title {
            font-weight: 700;
            color: #fff;
            font-size: 1.5rem;
            line-height: 1;
        }

        .navbar .logo-subtitle {
            font-size: .75rem;
            color: rgba(255, 255, 255, .8);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.6rem;
            color: var(--primary);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .navbar-brand:hover {
            color: var(--cc-primary-light);
        }

        .navbar-brand i {
            font-size: 1.3rem;
            color: var(--secondary);
        }
    </style>
</head>

<body>
    <nav class="navbar d-flex align-items-center justify-content-between">
        <div class="container">
            <div class="navbar-left d-flex align-items-center gap-3">
                <div class="logo d-flex align-items-center gap-2">
                    <a class="navbar-brand logo-title" href="dashboard.php"><i class="fas fa-graduation-cap"></i> ClassConnect</a>
                </div>
                <button class="menu-toggle" id="menuToggle"><i class="fas fa-bars"></i></button>
            </div>

            <div class="navbar-right">
                <span class="status"></span>
                <span class="status-text d-none d-md-inline"><?= $email ?></span>
                <div class="user-avatar" onclick="toggleDropdown()">
                    <span><i class="fas fa-user-graduate"></i></span>
                    <div class="dropdown-content">
                        <a href="#"><i class="fas fa-user"></i> Profil</a>
                        <a href="#"><i class="fas fa-cog"></i> Pengaturan</a>
                        <a href="../../login/logout.php"><i class="fas fa-sign-out-alt"></i> Keluar</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- ===== MAIN ===== -->
    <main class="container">
        <div class="kelas-card">
            <div class="kelas-info mb-4">
                <h2 class="kelas-title mb-1"><?= htmlspecialchars($data_class['name']) ?></h2>
                <p class="kelas-description"><?= htmlspecialchars($data_class['description']) ?></p>
                <p class="kelas-description mt-4"><strong>Modul :</strong></p>
            </div>

            <?php if ($data_modules->num_rows === 0): ?>
                <!-- TAMPILAN SAAT TIDAK ADA MODUL -->
                <div class="text-center py-5">
                    <img src="https://cdn-icons-png.flaticon.com/512/4076/4076549.png" alt="no module" width="120" class="mb-4">
                    <h5 style="color:#999">Belum ada modul di kelas ini</h5>
                    <p style="color:#aaa">Tunggu guru menambahkan modul, atau hubungi guru Anda.</p>
                </div>
            <?php else: ?>
                <!-- GRID MODUL -->
                <div class="modul-grid">
                    <?php
                    while ($module = $data_modules->fetch_assoc()):
                        /* hitung materi & tugas */
                        $mCount = $conn->query("SELECT COUNT(*) c FROM materials WHERE module_id=" . $module['id'])->fetch_assoc()['c'];
                        $tCount = $conn->query("SELECT COUNT(*) c FROM assignments WHERE module_id=" . $module['id'])->fetch_assoc()['c']; ?>
                        <div class="modul-card">
                            <div class="modul-header">
                                <h3><?= htmlspecialchars($module['title']) ?></h3>
                            </div>
                            <div class="modul-body">
                                <p><?= htmlspecialchars($module['description']) ?></p>
                            </div>

                            <div class="modul-bubble">
                                <span class="bubble-item"><i class="fas fa-book"></i> Materi: <?= $mCount ?></span>
                                <span class="bubble-item"><i class="fas fa-tasks"></i> Tugas: <?= $tCount ?></span>
                            </div>

                            <div class="modul-actions">
                                <button class="btn-view" onclick="openMateriModal(<?= $module['id'] ?>)">
                                    <i class="fas fa-book-open me-1"></i> Lihat Materi
                                </button>
                                <button class="btn-tugas" onclick="toTask(<?= $module['id'] ?>)">
                                    <i class="fas fa-tasks me-1"></i> Tugas
                                </button>
                            </div>
                        </div>

                        <?php
                        /* -------- list materi untuk modal -------- */
                        $stmtM = $conn->prepare("SELECT id,title FROM materials WHERE module_id=?");
                        $stmtM->bind_param("i", $module['id']);
                        $stmtM->execute();
                        $data_material = $stmtM->get_result(); ?>

                        <!-- Modal Materi -->
                        <div id="materiModal_<?= $module['id'] ?>" class="modal">
                            <div class="modal-content">
                                <h2>List Materi – <?= htmlspecialchars($module['title']) ?></h2>
                                <div class="materi-list">
                                    <?php foreach ($data_material as $material): ?>
                                        <div class="materi-item">
                                            <span><?= htmlspecialchars($material['title']) ?></span>
                                            <button class="btn-pilih" onclick="toMateri(<?= $material['id'] ?>)">Pilih</button>
                                        </div>
                                    <?php endforeach;
                                    $stmtM->close(); ?>
                                </div>
                                <div class="modul-actions mt-3">
                                    <button class="btn-close" onclick="closeMateriModal(<?= $module['id'] ?>)">
                                        <span>&times;</span> Tutup
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <!-- ===== FOOTER ===== -->
    <footer>
        <div class="container">
            <div class="row gy-4">
                <div class="col-lg-6">
                    <h5>🎓 ClassConnect</h5>
                    <p>Platform terintegrasi untuk pengalaman belajar yang sederhana, nyaman, dan menyenangkan.</p>
                </div>
                <div class="col-lg-3">
                    <h5>🔗 Navigasi</h5>
                    <a href="../Dashboard/Dashboard.php">Beranda</a><br>
                    <a href="../kelas_saya/siswa.php">Kelas Saya</a><br>
                    <a href="#">Materi</a>
                </div>
                <div class="col-lg-3">
                    <h5>🌐 Ikuti Kami</h5>
                    <a href="#"><i class="fab fa-facebook footer-icon"></i>Facebook</a><br>
                    <a href="#"><i class="fab fa-instagram footer-icon"></i>Instagram</a><br>
                    <a href="#"><i class="fab fa-twitter footer-icon"></i>Twitter</a>
                </div>
            </div>
            <hr style="border-color:rgba(255,255,255,.25);margin-top:30px">
            <div class="text-center">&copy; 2025 ClassConnect. All rights reserved.</div>
        </div>
    </footer>

    <!-- ===== SCRIPTS ===== -->
    <script>
        /* burger menu */
        document.getElementById('menuToggle').addEventListener('click', () => {
            document.getElementById('navbarMenu').classList.toggle('active');
        });

        /* modal */
        function openMateriModal(id) {
            document.getElementById('materiModal_' + id).style.display = 'flex'
        }

        function closeMateriModal(id) {
            document.getElementById('materiModal_' + id).style.display = 'none'
        }
        window.onclick = e => {
            if (e.target.classList.contains('modal')) e.target.style.display = 'none';
        }

        /* redirect helper */
        function toMateri(id) {
            location.href = 'materi.php?id=' + id
        }

        function toTask(id) {
            location.href = 'Tugas.php?id=' + id
        }
    </script>
</body>

</html>