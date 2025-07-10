<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: ../../index.php");
  exit();
}
$user_id = $_SESSION['user_id'];
$email   = $_SESSION['email'];
$module_id = $_GET['id'] ?? ($_SESSION['task_id'] ?? null);
$_SESSION['task_id'] = $module_id;

require_once '../../config/db.php';

/* --- Ambil data tugas + status submit --- */
$sql = "SELECT a.*, m.title module_name, c.name class_name, u.name teacher_name,
               s.status submission_status, s.submission_link, s.submitted_at, s.note, s.feedback
        FROM assignments a
        JOIN modules m  ON a.module_id = m.id
        JOIN classes c  ON m.class_id  = c.id
        JOIN users   u  ON c.teacher_id = u.id
        LEFT JOIN submissions s ON a.id = s.assignment_id AND s.student_id = ?
        WHERE a.module_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $user_id, $module_id);
$stmt->execute();
$data_task = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="../../img/logo2.png">
  <title>Tugas – ClassConnect</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">

  <!-- ===== STYLE ===== -->
  <style>
    :root {
      --primary: #5C6BC0;
      --accent: #4DB6AC;
      --secondary: #FFB74D;
      --bg: #F9F9F9;
      --card: #fff;
      --text: #333;
      --late: #E57373;
      --pending: #FFA726;
      --ok: #66BB6A;
    }

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
      color: var(--text);
    }

    /* Navbar */
    .navbar {
      background: var(--card);
      padding: 1rem 2rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
      flex-wrap: wrap;
    }

    .navbar-left {
      display: flex;
      align-items: center;
      gap: 1rem;
    }

    .logo {
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .logo img {
      height: 40px;
    }

    .logo-title {
      font-size: 1.2rem;
      font-weight: 600;
      color: var(--primary);
    }

    .logo-subtitle {
      font-size: 0.8rem;
      color: #888;
    }

    .navbar-menu {
      list-style: none;
      display: flex;
      gap: 1.5rem;
      flex-wrap: wrap;
    }

    .navbar-menu li a {
      text-decoration: none;
      color: var(--text);
      font-weight: 500;
      transition: color 0.2s;
    }

    .navbar-menu li a:hover,
    .navbar-menu li a.active {
      color: var(--primary);
    }

    .navbar-right {
      display: flex;
      align-items: center;
      gap: 1rem;
    }

    .user-avatar {
      position: relative;
      cursor: pointer;
    }

    .dropdown-content {
      display: none;
      position: absolute;
      right: 0;
      top: 100%;
      background: var(--card);
      box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
      border-radius: 8px;
      overflow: hidden;
    }

    .user-avatar:hover .dropdown-content {
      display: block;
    }

    .dropdown-content a {
      display: block;
      padding: 10px 20px;
      text-decoration: none;
      color: var(--text);
      font-size: 0.9rem;
      width: max-content;
    }

    .dropdown-content a:hover {
      background: var(--background);
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

    /* Breadcrumb */
    .header-bar {
      background: var(--card);
      box-shadow: 0 3px 10px rgba(0, 0, 0, .04);
      padding: 1rem 1.5rem;
      margin-bottom: 24px
    }

    .breadcrumb {
      display: flex;
      align-items: center;
      gap: .4rem;
      font-size: .9rem;
      color: #666;
      margin-bottom: 0;
    }

    .breadcrumb .active {
      color: var(--primary);
      font-weight: 600
    }

    /* Task grid */
    .content {
      flex: 1;
      margin: 2rem auto;
      padding: 0 1rem
    }

    .task-container {
      display: grid;
      gap: 1.5rem;
      grid-template-columns: repeat(auto-fill, minmax(320px, 1fr))
    }

    .task-card {
      background: var(--card);
      border-radius: 16px;
      box-shadow: 0 6px 14px rgba(0, 0, 0, .06);
      overflow: hidden;
      display: flex;
      flex-direction: column;
      transition: .3s
    }

    .task-card:hover {
      transform: translateY(-6px)
    }

    .task-card-header {
      padding: 20px 24px;
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      background: #eef1ff;
      border-bottom: 1px solid #e0e0e0
    }

    .task-title {
      margin: 0;
      font-size: 1.05rem;
      font-weight: 700;
      color: var(--primary)
    }

    .task-class {
      font-size: .8rem;
      color: #555
    }

    .task-deadline {
      font-size: .8rem;
      padding: 4px 10px;
      border-radius: 12px;
      color: #fff;
      display: flex;
      align-items: center;
      gap: 4px
    }

    .task-deadline i {
      font-size: .8rem
    }

    .task-deadline.active {
      background: var(--accent)
    }

    .task-deadline.late {
      background: var(--late)
    }

    .task-body {
      padding: 20px 24px;
      flex: 1;
      font-size: .9rem;
      color: #444;
      line-height: 1.45
    }

    .task-attachment a {
      color: var(--secondary)
    }

    .task-attachment i {
      margin-right: 4px
    }

    .task-submission {
      margin-top: 12px;
      border-radius: 8px;
      padding: 8px 12px;
      font-size: .85rem;
      display: flex;
      align-items: center;
      gap: 6px
    }

    .task-submission.active {
      background: #ffe6e6;
      color: var(--late)
    }

    .task-submission.pending {
      background: #fff3e0;
      color: var(--pending)
    }

    .task-submission.submitted {
      background: #e8f5e9;
      color: var(--ok)
    }

    .task-submission.late {
      background: #ffe6e6;
      color: var(--late)
    }

    .task-actions {
      padding: 16px 24px;
      border-top: 1px solid #f0f0f0;
      display: flex;
      gap: .6rem
    }

    .task-actions button {
      flex: 1;
      border: none;
      border-radius: 25px;
      padding: 10px 16px;
      font-weight: 600;
      font-size: .85rem;
      cursor: pointer;
      transition: .25s;
      color: #fff
    }

    .btn-primary {
      background: var(--primary)
    }

    .btn-success {
      background: var(--accent)
    }

    .btn-primary:hover {
      background: #3f51b5
    }

    .btn-success:hover {
      background: #009688
    }

    /* Modal (shared) */
    .modal {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, .6);
      z-index: 100;
      justify-content: center;
      align-items: center;
      padding: 20px
    }

    .modal-content-detail,
    .modal-content {
      background: var(--card);
      border-radius: 14px;
      max-width: 550px;
      width: 100%;
      max-height: 80vh;
      overflow: auto;
      padding: 30px;
      animation: fadeIn .3s;
      font-size: .9rem
    }

    .modal-content-detail h2,
    .modal-content h3 {
      font-size: 1.2rem;
      font-weight: 700;
      margin-bottom: 1rem;
      color: var(--primary)
    }

    .close-btn {
      background: none;
      border: none;
      font-size: 1.2rem;
      position: absolute;
      right: 22px;
      top: 18px;
      color: #888;
      cursor: pointer
    }

    .task-detail-row {
      display: flex;
      gap: 1rem;
      margin-bottom: .6rem
    }

    .task-detail-label {
      width: 120px;
      color: #666;
      font-weight: 500
    }

    .task-detail-value {
      flex: 1;
      color: #333
    }

    .task-materials,
    .submission-info {
      margin-top: 1.2rem
    }

    .task-materials h4,
    .submission-info h4 {
      font-size: 1rem;
      font-weight: 600;
      margin-bottom: .4rem;
      color: var(--primary)
    }

    /* Submission modal */
    .modal-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1rem
    }

    .form-group {
      margin-bottom: 1rem
    }

    .form-group label {
      font-weight: 600;
      color: #444;
      font-size: .9rem
    }

    .form-group textarea {
      width: 100%;
      border: 1px solid #ccc;
      border-radius: 8px;
      padding: 10px;
      font-size: .9rem;
      min-height: 90px
    }

    .form-actions {
      display: flex;
      gap: .8rem;
      margin-top: 1rem
    }

    .btn-submit {
      background: var(--primary);
      color: #fff
    }

    .btn-cancel {
      background: #ccc;
      color: #333
    }

    .btn-submit,
    .btn-cancel {
      flex: 1;
      border: none;
      border-radius: 25px;
      padding: 9px 0;
      font-weight: 600;
      font-size: .9rem;
      cursor: pointer
    }

    .btn-submit:hover {
      background: #3f51b5
    }

    .btn-cancel:hover {
      background: #b3b3b3
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(8px)
      }

      to {
        opacity: 1;
        transform: none
      }
    }

    /* Empty state */
    .empty {
      text-align: center;
      padding: 60px 20px;
      color: #777
    }

    .empty img {
      width: 120px;
      opacity: .6;
      margin-bottom: 18px
    }

    /* Footer */
    footer {
      background: linear-gradient(135deg, var(--primary), var(--accent));
      color: #fff;
      padding: 45px 0 30px;
      margin-top: 60px
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

    .navbar .logo-title {
      font-weight: 700;
      color: #4e73df;
      font-size: 1.5rem;
      line-height: 1;
    }

    .navbar .logo-title:hover {
      color: #4e73df;
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

    .menu-toggle {
      display: none;
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

  <div class="header-bar">
    <div class="container">
      <div class="breadcrumb">
        <span>Tugas</span><i class="fas fa-chevron-right"></i>
        <span class="active">
          <?= $data_task->num_rows ? htmlspecialchars($data_task->fetch_assoc()['module_name']) : 'Module' ?>
        </span>
      </div>
    </div>
  </div>

  <main class="container">
    <?php
    if ($data_task->num_rows) $data_task->data_seek(0);
    ?>
    <div class="d-flex task-container">
      <?php if ($data_task->num_rows === 0): ?>
        <div class="empty">
          <img src="https://cdn-icons-png.flaticon.com/512/2748/2748482.png" alt="no-task">
          <h5>Belum ada tugas di modul ini</h5>
          <p>Tunggu guru menambahkan tugas atau tanyakan langsung kepada guru Anda.</p>
        </div>
        <?php else: foreach ($data_task as $task):
          $deadline = strtotime($task['deadline']);
          $now = time(); ?>
          <div class="task-card col-6">
            <div class="task-card-header">
              <div>
                <h3 class="task-title"><?= htmlspecialchars($task['title']) ?></h3>
                <span class="task-class"><?= htmlspecialchars($task['class_name']) ?> – <?= htmlspecialchars($task['module_name']) ?></span>
              </div>
              <?php if ($now > $deadline): ?>
                <div class="task-deadline late"><i class="fas fa-clock"></i><?= $task['deadline'] ?> (Terlambat)</div>
              <?php else: ?>
                <div class="task-deadline active"><i class="fas fa-clock"></i><?= $task['deadline'] ?></div>
              <?php endif; ?>
            </div>

            <div class="task-body">
              <p class="task-description"><?= nl2br($task['description']) ?></p>
              <?php if ($task['file_link']): ?>
                <div class="task-attachment mt-2">
                  <a href="<?= htmlspecialchars($task['file_link']) ?>" target="_blank"><i class="fas fa-paperclip"></i> File Referensi</a>
                </div>
              <?php endif; ?>

              <?php
              $status = $task['submission_status'] ?? 'belum';
              if ($status === 'belum')      echo '<div class="task-submission active"><i class="fas fa-exclamation-circle"></i>Belum dikumpulkan</div>';
              elseif ($status === 'dikirim') echo '<div class="task-submission pending"><i class="fas fa-clock"></i>Sudah terkirim (diperiksa)</div>';
              elseif ($status === 'diterima') echo '<div class="task-submission submitted"><i class="fas fa-check-circle"></i>Tugas diterima</div>';
              elseif ($status === 'revisi') echo '<div class="task-submission late"><i class="fas fa-exclamation-circle"></i>Harus revisi</div>';
              ?>
            </div>

            <div class="task-actions">
              <button class="btn-primary" onclick="openDetail(<?= $task['id'] ?>)"><i class="fas fa-info-circle me-1"></i>Detail</button>
              <?php if ($now < $deadline && !in_array($status, ['dikirim', 'diterima'])): ?>
                <button class="btn-success" onclick="openSubmission(<?= $task['id'] ?>)"><i class="fas fa-file-upload me-1"></i>Kirim</button>
              <?php endif; ?>
            </div>
          </div>

          <div id="detailModal_<?= $task['id'] ?>" class="modal">
            <div class="modal-content-detail position-relative">
              <button class="close-btn" onclick="closeModal(<?= $task['id'] ?>)">&times;</button>
              <h2><i class="fas fa-tasks me-1"></i>Detail Tugas</h2>
              <div class="task-detail-info">
                <div class="task-detail-row">
                  <div class="task-detail-label">Judul</div>
                  <div class="task-detail-value"><?= htmlspecialchars($task['title']) ?></div>
                </div>
                <div class="task-detail-row">
                  <div class="task-detail-label">Modul</div>
                  <div class="task-detail-value"><?= htmlspecialchars($task['module_name']) ?></div>
                </div>
                <div class="task-detail-row">
                  <div class="task-detail-label">Guru</div>
                  <div class="task-detail-value"><?= htmlspecialchars($task['teacher_name']) ?></div>
                </div>
                <div class="task-detail-row">
                  <div class="task-detail-label">Batas Waktu</div>
                  <div class="task-detail-value"><?= $task['deadline'] ?></div>
                </div>
                <div class="task-detail-row">
                  <div class="task-detail-label">Status</div>
                  <div class="task-detail-value"><?= $status ?></div>
                </div>
                <?php if ($status === 'revisi'): ?>
                  <div class="task-detail-row">
                    <div class="task-detail-label">Feedback</div>
                    <div class="task-detail-value"><?= nl2br($task['feedback']) ?></div>
                  </div>
                <?php endif; ?>
                <div class="task-detail-row">
                  <div class="task-detail-label">Deskripsi</div>
                  <div class="task-detail-value"><?= nl2br($task['description']) ?></div>
                </div>
              </div>

              <div class="task-materials">
                <h4><i class="fas fa-book me-1"></i>Materi Pendukung</h4>
                <?php if ($task['file_link']): ?>
                  <a href="<?= htmlspecialchars($task['file_link']) ?>" target="_blank"><?= htmlspecialchars($task['file_link']) ?></a>
                  <?php else: ?>Tidak ada file pendukung<?php endif; ?>
              </div>

              <?php if ($status !== 'belum'): ?>
                <div class="submission-info">
                  <h4><i class="fas fa-file-upload me-1"></i>Pengumpulan</h4>
                  <p>Waktu: <?= $task['submitted_at'] ?></p>
                  <?php if ($task['submission_link']): ?>
                    <div class="submission-file"><a href="<?= htmlspecialchars($task['submission_link']) ?>" target="_blank"><?= htmlspecialchars($task['submission_link']) ?></a></div>
                  <?php endif; ?>
                  <?php if ($task['note']): ?><p><strong>Catatan:</strong> <?= nl2br($task['note']) ?></p><?php endif; ?>
                </div>
              <?php endif; ?>
            </div>
          </div>

          <div id="submissionModal_<?= $task['id'] ?>" class="modal">
            <div class="modal-content position-relative">
              <div class="modal-header">
                <h3><i class="fas fa-file-upload me-1"></i>Kirim Tugas</h3>
                <button class="close-btn" onclick="closeSubmission(<?= $task['id'] ?>)">&times;</button>
              </div>
              <form action="addSubmission.php" method="POST">
                <input type="hidden" name="assignment_id" value="<?= $task['id'] ?>">
                <div class="form-group">
                  <label>Catatan</label>
                  <textarea name="submissionNotes" placeholder="Catatan untuk guru..."></textarea>
                </div>
                <div class="form-group">
                  <label>Link Tugas</label>
                  <textarea name="submissionLink" placeholder="https://contoh.com/tugas-saya"></textarea>
                </div>
                <div class="form-actions">
                  <button type="submit" class="btn-submit">Kirim</button>
                  <button type="button" class="btn-cancel" onclick="closeSubmission(<?= $task['id'] ?>)">Batal</button>
                </div>
              </form>
            </div>
          </div>
      <?php endforeach;
      endif; ?>
    </div>
  </main>

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
      <hr style="border-color:rgba(255,255,255,.25);margin-top:28px">
      <div class="text-center">&copy; 2025 ClassConnect. All rights reserved.</div>
    </div>
  </footer>

  <script>
    document.getElementById('menuToggle').addEventListener('click', () => document.getElementById('navbarMenu').classList.toggle('active'));

    function openDetail(id) {
      document.getElementById('detailModal_' + id).style.display = 'flex'
    }

    function closeModal(id) {
      document.getElementById('detailModal_' + id).style.display = 'none'
    }

    function openSubmission(id) {
      document.getElementById('submissionModal_' + id).style.display = 'flex'
    }

    function closeSubmission(id) {
      document.getElementById('submissionModal_' + id).style.display = 'none'
    }
    window.onclick = e => {
      if (e.target.classList.contains('modal')) e.target.style.display = 'none';
    };
  </script>
</body>

</html>