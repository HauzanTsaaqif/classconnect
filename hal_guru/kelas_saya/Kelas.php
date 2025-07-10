<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="../../img/logo2.png">
    <title>Kelas Saya - EduLearn</title>
    <link rel="stylesheet" href="Kelas.css">

    <!-- Bootstrap 5 + Font -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet" />
    <link href="Animate.css" rel="stylesheet" />
    <link href="../css/dashboard.css" rel="stylesheet" />
</head>

<body>
    <?php
    session_start();

    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $email = $_SESSION['email'];
    } else {
        echo "Anda belum login!";
        header("Location: ../../index.php");
        exit();
    }
    ?>

    <nav class="navbar navbar-expand-lg shadow-sm">
        <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
            <a class="navbar-brand" href="../dashboard_guru/dashboard.php"><i class="fas fa-graduation-cap"></i> ClassConnect</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="ms-auto d-flex align-items-center gap-4">
                <a class="nav-link" href="../dashboard_guru/dashboard.php">Dashboard</a>
                <a class="nav-link active" href="../kelas_saya/Kelas.php">Kelas Saya</a>
                <a class="nav-link" href="../tugas/tugas.php">Tugas</a>
                <a href="../../login/logout.php" class="btn btn-sm btn-outline-light"><i class="fa-solid fa-right-from-bracket me-1"></i>Logout</a>
            </div>
        </div>
    </nav>

    <!-- Main Container -->
    <main class="container">
        <!-- Header -->
        <div class="header my-4">
            <h1 class="h3 fw-bold mb-1">Kelas Saya</h1>
            <p class="text-muted mb-0">Kelola semua kelas yang Anda ampu dengan mudah</p>
        </div>

        <!-- Action Bar -->
        <div class="action-bar">
            <div class="search-bar">
                <input type="text" class="search-input" placeholder="Cari kelas..." oninput="searchClasses(this.value)">
                <span style="color: #667eea; font-size: 1.2rem;">🔍</span>
            </div>
            <div class="d-flex gap-1">
                <button class="btn btn-primary feature-button mt-3 align-self-start" onclick="openAddStudentModal()">
                    <span>Tambah Siswa</span>
                </button>
                <button class="btn btn-outline-primary feature-button mt-3 align-self-start" onclick="openAddClassModal()">
                    <span>Tambah Kelas</span>
                </button>
            </div>
        </div>

        <div class="popup-form" id="addStudentPopup" style="display: none;">
            <div class="popup-content">
                <h2>Tambah Siswa</h2>
                <form id="addStudentForm" method="POST" action="addStudent.php">
                    <label for="studentSelect">Pilih Siswa:</label>
                    <select id="studentSelect" name="student_id" required>
                        <option value="">Pilih Siswa</option>
                        <?php
                        require_once '../../config/db.php';
                        $user_id = $_SESSION['user_id'];
                        $sql = "SELECT id, name FROM users WHERE role = 'siswa'";
                        $stmt = $conn->prepare($sql);
                        $stmt->execute();
                        $data_students = $stmt->get_result();
                        while ($student = $data_students->fetch_assoc()) {
                            echo "<option value='{$student['id']}'>{$student['name']}</option>";
                        }
                        ?>
                    </select>

                    <label for="classSelect">Pilih Kelas:</label>
                    <select id="classSelect" name="class_id" required>
                        <option value="">Pilih Kelas</option>
                        <?php
                        $sql_classes = "SELECT id, name FROM classes WHERE teacher_id = ?";
                        $stmt_classes = $conn->prepare($sql_classes);
                        $stmt_classes->bind_param("i", $user_id);
                        $stmt_classes->execute();
                        $data_classes = $stmt_classes->get_result();
                        while ($class = $data_classes->fetch_assoc()) {
                            echo "<option value='{$class['id']}'>{$class['name']}</option>";
                        }
                        ?>
                    </select>

                    <button class="btn btn-primary feature-button mt-3 align-self-center" type="submit">Simpan</button>
                    <button class="btn btn-outline-primary feature-button mt-3 align-self-center" type="button" onclick="closeAddStudentModal()">Tutup</button>
                </form>
            </div>
        </div>

        <div class="popup-form" id="addClassPopup" style="display: none;">
            <div class="popup-content">
                <h2>Tambah Kelas</h2>
                <form id="addClassForm" method="POST" action="addClass.php">
                    <label for="className">Nama Kelas:</label>
                    <input type="text" id="className" name="class_name" required>

                    <label for="classDescription">Deskripsi Kelas:</label>
                    <textarea id="classDescription" name="class_description" required></textarea>

                    <button class="btn btn-primary feature-button mt-3 align-self-center" type="submit">Simpan</button>
                    <button class="btn btn-outline-primary feature-button mt-3 align-self-center" type="button" onclick="closeAddClassModal()">Tutup</button>
                </form>
            </div>
        </div>

        <!-- Classes Grid -->
        <?php
        require_once '../../config/db.php';
        $user_id = $_SESSION['user_id'];

        $sql = "SELECT * FROM classes WHERE teacher_id = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        $data_classes = $stmt->get_result();
        $stmt->close();
        ?>
        <div class="classes-grid" id="classesGrid">
            <?php
            if ($data_classes->num_rows > 0) {
                foreach ($data_classes as $classes) {
                    $sql_students = "SELECT COUNT(*) AS total_students FROM class_enrollments WHERE class_id = ?";
                    $stmt_students = $conn->prepare($sql_students);
                    $stmt_students->bind_param("i", $classes['id']);
                    $stmt_students->execute();
                    $result_students = $stmt_students->get_result();
                    $total_students = $result_students->fetch_assoc()['total_students'];

                    $sql_modules = "SELECT COUNT(*) AS total_modules FROM modules WHERE class_id = ?";
                    $stmt_modules = $conn->prepare($sql_modules);
                    $stmt_modules->bind_param("i", $classes['id']);
                    $stmt_modules->execute();
                    $result_modules = $stmt_modules->get_result();
                    $total_modules = $result_modules->fetch_assoc()['total_modules'];

                    $sql_assignments = "SELECT COUNT(*) AS total_assignments FROM assignments WHERE module_id IN (SELECT id FROM modules WHERE class_id = ?)";
                    $stmt_assignments = $conn->prepare($sql_assignments);
                    $stmt_assignments->bind_param("i", $classes['id']);
                    $stmt_assignments->execute();
                    $result_assignments = $stmt_assignments->get_result();
                    $total_assignments = $result_assignments->fetch_assoc()['total_assignments'];
            ?>
                    <div class="class-card" data-class="<?= $classes['id'] ?>">
                        <div class="class-header">
                            <div class="class-info">
                                <h3><?= $classes["name"] ?></h3>
                                <span class="class-code"><?= $classes["description"] ?></span>
                            </div>
                            <button class="class-menu" onclick="toggleClassMenu(this)">⋮</button>
                            <div class="dropdown-menu">
                                <button class="dropdown-item" onclick="deleteClass(<?= $classes['id'] ?>)">Hapus Kelas</button>
                            </div>
                        </div>

                        <div class="class-stats">
                            <div class="stat-item">
                                <div class="stat-number"><?= $total_students ?></div>
                                <div class="stat-label">Siswa</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number"><?= $total_modules ?></div>
                                <div class="stat-label">Modul</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number"><?= $total_assignments ?></div>
                                <div class="stat-label">Tugas</div>
                            </div>
                        </div>

                        <div class="class-actions">
                            <a class="action-btn btn-primary" href="Modulguru.php?class_id=<?= $classes['id'] ?>">
                                <span>📖</span>Modul
                            </a>
                            <a class="action-btn btn-secondary" onclick="openStudentModal(<?= $classes['id'] ?>)">
                                <span>📋</span>Siswa
                            </a>
                            <a class="action-btn btn-success" href="../tugas/tugas.php?class_id=<?= $classes['id'] ?>">
                                <span>✅</span>Tugas
                            </a>
                        </div>
                    </div>

                    <?php
                    // Fetch data siswa terkait kelas ini
                    require_once '../../config/db.php';

                    $sql = "
                        SELECT 
                            class_enrollments.*, 
                            users.name AS student_name, 
                            users.email AS student_email
                        FROM class_enrollments
                        INNER JOIN users ON class_enrollments.student_id = users.id
                        WHERE class_enrollments.class_id = ?
                    ";

                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $classes['id']);
                    $stmt->execute();

                    $data_siswa = $stmt->get_result();
                    $stmt->close();
                    ?>

                    <!-- Modal Siswa untuk Kelas Ini -->
                    <div id="studentModal_<?= $classes['id'] ?>" class="modal">
                        <div class="modal-content">
                            <span class="close-btn" onclick="closeStudentModal(<?= $classes['id'] ?>)">&times;</span>

                            <h2>List Siswa</h2>

                            <!-- Table untuk menampilkan list siswa -->
                            <table class="student-table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Email</th>
                                    </tr>
                                </thead>
                                <tbody id="studentListBody_<?= $classes['id'] ?>">
                                    <?php
                                    $i = 1;
                                    if ($data_siswa->num_rows > 0) {
                                        while ($siswa = $data_siswa->fetch_assoc()) { ?>
                                            <tr>
                                                <td><?= $i ?></td>
                                                <td><?= $siswa["student_name"] ?></td>
                                                <td><?= $siswa["student_email"] ?></td>
                                            </tr>
                                    <?php $i++;
                                        }
                                    } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php }
            } else {
                ?>
                <h2>Belum Ada Kelas</h2>
            <?php } ?>
        </div>

        <!-- Quick Access Panel -->
        <div class="quick-access mb-4">
            <h2><span>⚡</span> Akses Cepat</h2>
            <div class="quick-links">
                <a class="quick-link" href="Modulguru.php">
                    <div class="quick-icon">📖</div>
                    <div>Semua Modul</div>
                </a>
                <a class="quick-link" href="../hal_belajar_guru/belajar_guru.php">
                    <div class="quick-icon">📋</div>
                    <div>Webinar</div>
                </a>
                <a class="quick-link" href="../tugas/tugas.php">
                    <div class="quick-icon">✅</div>
                    <div>Daftar Tugas</div>
                </a>
            </div>
        </div>
    </main>

    <footer class="text-center small">
        <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
            <span>&copy; 2025 ClassConnect</span>
            <span>Hubungi Admin: <a href="mailto:hauzantsaaqif28@gmail.com">hauzantsaaqif28@gmail.com</a></span>
        </div>
    </footer>

    <script src="Kelas.js"></script>
</body>

</html>