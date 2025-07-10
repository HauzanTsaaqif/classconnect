<?php
session_start();
if (isset($_SESSION['user_id'])) {
  $user_id = $_SESSION['user_id'];
  $email   = $_SESSION['email'];
} else {
  header("Location: ../../index.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" type="image/x-icon" href="../../img/logo2.png">
  <title>Kelas Saya – ClassConnect (Siswa)</title>

  <!-- Bootstrap & Icons (opsional, tapi memudahkan utilitas grid) -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">

  <!-- ===== STYLE KHUSUS HALAMAN SISWA ===== -->
  <style>
    /* ---------- Palet Warna ---------- */
    :root {
      --primary: #5C6BC0;
      /* ungu-­biru tenang            */
      --accent: #4DB6AC;
      /* teal lembut                  */
      --secondary: #FFB74D;
      /* orange hangat                */
      --info: #66BB6A;
      /* hijau segar (badge / ikon)   */
      --bg: #F9F9F9;
      --dark: #2E2E2E;
    }

    body {
      font-family: 'DM Sans', sans-serif;
      background: var(--bg);
      color: var(--dark);
      margin: 0;
    }

    /* ---------- Navbar ---------- */
    .navbar {
      background: linear-gradient(90deg, var(--primary), var(--accent));
      box-shadow: 0 4px 12px rgba(0, 0, 0, .08);
      padding: .75rem 1rem;
    }

    .navbar .logo img {
      height: 40px;
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

    .navbar-menu {
      display: flex;
      gap: 1.5rem;
      list-style: none;
      padding-left: 0;
      margin-bottom: 0;
      align-items: center;
    }

    .navbar-menu li {
      display: inline-block;
    }

    .navbar-menu a {
      color: #fff;
      text-decoration: none;
      font-weight: 500;
      position: relative;
    }

    .navbar-menu a::after {
      content: '';
      position: absolute;
      bottom: -3px;
      left: 0;
      width: 0;
      height: 2px;
      background: var(--secondary);
      transition: .3s;
    }

    .navbar-menu a:hover::after,
    .navbar-menu a.active::after {
      width: 100%;
    }

    .menu-toggle {
      display: none;
      background: none;
      border: none;
      color: #fff;
      font-size: 1.25rem;
    }

    /* ---------- User info kanan ---------- */
    .navbar-right {
      display: flex;
      align-items: center;
      gap: .75rem;
      color: #fff;
    }

    .status {
      width: 9px;
      height: 9px;
      border-radius: 50%;
      background: var(--info);
    }

    .user-avatar {
      position: relative;
      cursor: pointer;
    }

    .user-avatar span {
      font-size: 1.25rem;
      color: #fff;
    }

    .dropdown-content {
      position: absolute;
      right: 0;
      top: 100%;
      background: #fff;
      border-radius: 10px;
      box-shadow: 0 8px 24px rgba(0, 0, 0, .1);
      overflow: hidden;
      display: none;
      min-width: 180px;
    }

    .dropdown-content a {
      display: block;
      padding: .75rem 1rem;
      color: var(--dark);
      text-decoration: none;
      font-size: .95rem;
    }

    .dropdown-content a:hover {
      background: var(--bg);
    }

    .user-avatar:active .dropdown-content {
      display: block;
    }

    .dropdown-content.show {
      display: block;
    }

    /* ---------- Grid Kelas ---------- */
    main.container {
      padding: 40px 20px;
    }

    main h1 {
      font-weight: 700;
      color: var(--primary);
      text-align: center;
      margin-bottom: .25rem;
    }

    .kelas-grid {
      gap: 1.5rem;
      grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
    }

    .kelas-card {
      background: #fff;
      border-radius: 15px;
      overflow: hidden;
      box-shadow: 0 6px 18px rgba(0, 0, 0, .06);
      display: flex;
      flex-direction: column;
      transition: .3s;
    }

    .kelas-card:hover {
      transform: translateY(-6px);
      box-shadow: 0 12px 24px rgba(0, 0, 0, .1);
    }

    .kelas-header {
      padding: 18px 20px;
      background: linear-gradient(120deg, var(--primary), var(--accent));
      color: #fff;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .kelas-header h2 {
      font-size: 1.1rem;
      font-weight: 600;
      margin: 0;
    }

    .badge {
      background: var(--secondary);
      padding: .25rem .6rem;
      border-radius: 12px;
      font-size: .75rem;
    }

    .kelas-body {
      padding: 18px 20px;
      flex: 1;
    }

    .kelas-body p {
      margin-bottom: .5rem;
      font-size: .9rem;
    }

    .kelas-body i {
      color: var(--accent);
      margin-right: 4px;
    }

    .action-buttons {
      padding: 18px 20px;
      border-top: 1px solid #f0f0f0;
      display: flex;
      gap: .5rem;
    }

    .action-buttons button {
      flex: 1;
      border: none;
      border-radius: 25px;
      padding: 10px;
      font-weight: 600;
      font-size: .9rem;
      cursor: pointer;
      transition: .25s;
    }

    .btn-materi {
      background: var(--primary);
      color: #fff;
    }

    .btn-nilai {
      background: var(--accent);
      color: #fff;
    }

    .btn-materi:hover {
      background: var(--accent);
    }

    .btn-nilai:hover {
      background: var(--primary);
    }

    /* ---------- Footer ---------- */
    footer {
      background: linear-gradient(135deg, var(--primary), var(--accent));
      color: #fff;
      padding: 50px 0 35px;
      margin-top: 60px;
    }

    footer h5 {
      font-weight: 700;
      margin-bottom: 1rem;
    }

    footer a {
      color: #eee;
      text-decoration: none;
      font-size: .95rem;
    }

    footer a:hover {
      color: var(--secondary);
    }

    .footer-icon {
      font-size: 1.1rem;
      margin-right: 8px;
    }

    @media(max-width:768px) {
      .navbar-menu {
        flex-direction: column;
        position: absolute;
        top: 100%;
        left: 0;
        background: linear-gradient(90deg, var(--primary), var(--accent));
        width: 100%;
        transform: translateY(-15px);
        opacity: 0;
        pointer-events: none;
        transition: .3s;
      }

      .navbar-menu.active {
        transform: translateY(0);
        opacity: 1;
        pointer-events: all;
      }

      .menu-toggle {
        display: block;
      }
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

  <main class="container">
    <h1>Kelas Saya</h1>
    <p class="text-center" style="color:#666;margin-top:-6px;margin-bottom:32px">
      Kelola semua kelas yang Anda ikuti dengan mudah
    </p>

    <div class="kelas-grid">
      <?php
      require_once '../../config/db.php';
      $sql = "SELECT c.id class_id,c.name class_name,c.description class_description,
                   u.name teacher_name
            FROM classes c
            JOIN class_enrollments ce ON c.id = ce.class_id
            JOIN users u ON c.teacher_id = u.id
            WHERE ce.student_id = ?";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("i", $user_id);
      $stmt->execute();
      $data_classes = $stmt->get_result(); ?>
      <?php if ($data_classes->num_rows === 0): ?>
        <div class="text-center mt-5">
          <img src="https://cdn-icons-png.flaticon.com/512/4076/4076549.png" alt="No class" width="120" class="mb-4" />
          <h5 style="color: #999;">Belum ada kelas yang Anda ikuti</h5>
          <p style="color: #aaa;">Silakan hubungi guru Anda untuk mendapatkan kode kelas atau tunggu undangan dari sistem.</p>
        </div>
      <?php else: ?>
        <div class="d-flex kelas-grid">
          <?php foreach ($data_classes as $class) { ?>
            <div class="d-flex col-4 kelas-card">
              <div class="kelas-header">
                <h2><?= $class['class_name']; ?></h2>
                <span class="badge">Aktif</span>
              </div>
              <div class="kelas-body">
                <p><i class="fas fa-chalkboard-teacher"></i><strong> Guru:</strong> <?= $class['teacher_name']; ?></p>
                <p><?= $class['class_description']; ?></p>
              </div>
              <div class="action-buttons">
                <button class="btn-materi" onclick="toModule(<?= $class['class_id']; ?>)">
                  <i class="fas fa-book-open me-1"></i> Module
                </button>
                <button class="btn-nilai">
                  <i class="fas fa-chart-line me-1"></i> Nilai
                </button>
              </div>
            </div>
          <?php } ?>
        </div>
      <?php endif; ?>
      <?php $stmt->close(); ?>
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
          <a href="#">Materi</a><br>
          <a href="#">Penilaian</a>
        </div>
        <div class="col-lg-3">
          <h5>🌐 Ikuti Kami</h5>
          <a href="#"><i class="fab fa-facebook footer-icon"></i>Facebook</a><br>
          <a href="#"><i class="fab fa-instagram footer-icon"></i>Instagram</a><br>
          <a href="#"><i class="fab fa-twitter footer-icon"></i>Twitter</a>
        </div>
      </div>
      <hr style="border-color:rgba(255,255,255,.25);margin-top:32px;">
      <div class="text-center">&copy; 2025 ClassConnect. All rights reserved.</div>
    </div>
  </footer>

  <!-- ===== SCRIPT ===== -->
  <script>
    /* Toggle menu (mobile) */
    document.getElementById('menuToggle').addEventListener('click', () => {
      document.getElementById('navbarMenu').classList.toggle('active');
    });
    /* Placeholder fungsi pindah modul */
    function toModule(id) {
      window.location.href = 'module.php?id=' + id;
    }

    function toggleDropdown() {
      document.querySelector(".dropdown-content").classList.toggle("show");
    }
  </script>
</body>

</html>