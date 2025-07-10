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

<?php
require_once '../../config/db.php';
$user_id = $_SESSION['user_id'];

$sql = "SELECT id, name FROM classes WHERE teacher_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();

$data_classes = $stmt->get_result();

if (isset($_GET['class_id'])) {
    $class_id = $_GET['class_id'];
    $sql_2 = "SELECT m.*, c.name AS class_name, c.description AS class_description 
            FROM modules m 
            JOIN classes c ON m.class_id = c.id 
            WHERE c.teacher_id = ? AND m.class_id = ?";

    $stmt = $conn->prepare($sql_2);
    $stmt->bind_param("ii", $user_id, $class_id);
} else {
    $sql_2 = "SELECT m.*, c.name AS class_name, c.description AS class_description 
            FROM modules m 
            JOIN classes c ON m.class_id = c.id 
            WHERE c.teacher_id = ?";

    $stmt = $conn->prepare($sql_2);
    $stmt->bind_param("i", $user_id);
}
$stmt->execute();

$data_modules = $stmt->get_result();

$stmt->close();
?>

<?php
// Fetch data materi terkait modul ini
require_once '../../config/db.php';

$sql = "
                    SELECT * 
                    FROM materials
                    WHERE module_id = ?
                ";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $module['id']);
$stmt->execute();

$data_materi = $stmt->get_result();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="../../img/logo2.png">
    <title>Kelas Saya - EduLearn</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet" />
    <link href="../dashboard_guru/Animate.css" rel="stylesheet" />
    <link href="../css/dashboard.css" rel="stylesheet" />
    <link rel="stylesheet" href="Modulguru.css">
    <link rel="stylesheet" href="Kelas.css">
</head>

<body>
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

    <!-- Main Content -->
    <main class="container-fluid pt-4">
        <div class="container-xl">
            <header class="mb-4">
                <h1 class="h3 fw-bold mb-1">Kelola Modul</h1>
                <p class="text-muted mb-0">Buat, kelola, dan bagikan modul pembelajaran untuk siswa</p>
            </header>

            <div class="controls">
                <div class="filters d-flex gap-2">
                    <?php
                    if (isset($class_id)) {
                        require_once '../../config/db.php';

                        $sql = "SELECT * FROM classes WHERE id = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("i", $class_id);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        if ($result->num_rows > 0) {
                            $class_data = $result->fetch_assoc();
                        }
                    ?>
                        <p class="filter-select"><strong>Kelas : </strong><?= $class_data["name"] ?></p>
                        <p class="filter-select"><strong>Deskripsi : </strong><?= $class_data["description"] ?></p>
                    <?php } else { ?>
                        <select class="filter-select" id="subjectFilter" name="moduleSubject" required>
                            <option value="">Semua Kelas</option>
                            <?php foreach ($data_classes as $classes) { ?>
                                <option value="<?= $classes["id"] ?>"
                                    <?php if (isset($_POST['moduleSubject']) && $_POST['moduleSubject'] == $classes['id']) echo 'selected'; ?>>
                                    <?= $classes["name"] ?>
                                </option>
                            <?php } ?>
                        </select>
                    <?php } ?>
                </div>
                <button class="add-module-btn" id="addModuleBtn">
                    + Tambah Modul
                </button>
            </div>

            <div class="classes-grid" id="modulesGrid">
                <?php foreach ($data_modules as $module) { ?>
                    <div class="class-card">
                        <div class="module-header">
                            <div>
                                <h3 class="module-title"><?= $module["title"] ?></h3>
                                <p class="module-code"><?= $module["description"] ?></p>
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="stat col-4 justify-items-center">
                                <div class="stat-label">Kelas</div>
                                <div class="stat-number"><?= $module["class_name"] ?></div>
                            </div>
                            <div class="stat col-4 justify-items-center">
                                <div class="stat-label">Materi</div>
                                <div class="stat-number">2</div>
                            </div>
                            <div class="stat col-4 justify-items-center">
                                <div class="stat-label">Tugas</div>
                                <div class="stat-number">1</div>
                            </div>
                        </div>
                        <div class="module-actions">
                            <button class="action-btn btn-view" value="<?= $module["id"] ?>" onclick="openStudentModal(<?= $module['id'] ?>)">Lihat Materi</button>
                            <button class="action-btn btn-edit" value="<?= $module["id"] ?>" onclick="openModuleModal(<?= $module['id'] ?>)">Buat Materi</button>
                            <button class="action-btn btn-delete" value="<?= $module["id"] ?>" onclick="window.location.href = `deleteModule.php?id=<?= $module['id'] ?>`;">Hapus Module</button>
                        </div>
                    </div>



                    <!-- Modal untuk Materi Modul -->
                    <div id="viewModal_<?= $module['id'] ?>" class="modal">
                        <div class="modal-content">
                            <span class="close" onclick="closeStudentModal(<?= $module['id'] ?>)">&times;</span>
                            <h2>List Materi</h2>

                            <!-- Table untuk menampilkan list materi -->
                            <table class="student-table">
                                <?php if ($data_materi->num_rows > 0) { ?>
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Judul Materi</th>
                                            <th>Konten</th>
                                            <th>Aksi</th> <!-- Kolom Aksi -->
                                        </tr>
                                    </thead>
                                <?php } ?>
                                <tbody id="studentListBody_<?= $module['id'] ?>">
                                    <?php
                                    $i = 1;
                                    if ($data_materi->num_rows > 0) {
                                        while ($materi = $data_materi->fetch_assoc()) {
                                            // Batasi panjang konten (misalnya 100 karakter)
                                            $content = substr($materi["content"], 0, 100);
                                            if (strlen($materi["content"]) > 100) {
                                                $content .= '...'; // Menambahkan elipsis jika konten lebih dari 100 karakter
                                            }
                                    ?>
                                            <tr>
                                                <td><?= $i ?></td>
                                                <td><?= $materi["title"] ?></td>
                                                <td><?= $content ?></td> <!-- Menampilkan konten terbatas -->
                                                <td>
                                                    <!-- Button untuk Lihat dan Hapus -->
                                                    <button class="action-btn btn-view" onclick="viewMaterial(<?= $materi['id'] ?>)">Lihat</button>
                                                    <button class="action-btn btn-delete" onclick="deleteMaterial(<?= $materi['id'] ?>)">Hapus</button>
                                                </td>
                                            </tr>
                                        <?php
                                            $i++;
                                        }
                                    } else { ?>
                                        <tr>
                                            <td colspan="4">Belum ada materi</td> <!-- Menampilkan pesan jika tidak ada materi -->
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                <?php } ?>
            </div>

            <!-- View Module Section -->
            <div class="view-module-section" id="viewModuleSection">
                <div class="view-module-header">
                    <div>
                        <h2 class="view-module-title" id="viewModuleTitle">Matematika X-1</h2>
                        <div class="view-module-meta">
                            <span id="viewModuleCode">Kode: MTK-X1-2024</span> |
                            <span id="viewModuleSubject">Jurusan: Teknik Jaringan Komputer & Telekomunikasi</span>
                        </div>
                    </div>
                </div>
                <div class="view-module-content">
                    <h3>Deskripsi Modul</h3>
                    <p id="viewModuleDescription">Modul ini berisi materi pembelajaran untuk kelas X semester 1 dengan fokus pada dasar-dasar matematika.</p>

                    <h3>Detail Modul</h3>
                    <div class="module-stats">
                        <div class="stat">
                            <div class="stat-number" id="viewModuleStudents">32</div>
                            <div class="stat-label">Siswa</div>
                        </div>
                        <div class="stat">
                            <div class="stat-number" id="viewModuleMaterials">5</div>
                            <div class="stat-label">Modul</div>
                        </div>
                        <div class="stat">
                            <div class="stat-number" id="viewModuleAssignments">8</div>
                            <div class="stat-label">Tugas</div>
                        </div>
                    </div>

                    <button class="back-to-list" id="backToListBtn">Kembali ke Daftar Modul</button>
                </div>
            </div>

            <div class="empty-state" id="emptyState" style="display: none;">
                <h3>Belum Ada Modul</h3>
                <p>Mulai dengan membuat modul pembelajaran pertama Anda</p>
            </div>
        </div>
    </main>

    <!-- Add/Edit Module Modal -->
    <div id="moduleModal" class="modal">
        <div class="modal-content">
            <span class="close" id="closeModalBtn">&times;</span>
            <h2 id="modalTitle">Tambah Materi Baru</h2>
            <form id="moduleForm" action="addModule.php" method="POST">
                <div class="form-group">
                    <label for="moduleName">Nama Modul *</label>
                    <input type="text" id="moduleName" name="moduleName" required>
                </div>
                <div class="form-group">
                    <label for="moduleCode">Deskripsi Modul *</label>
                    <input type="text" id="moduleCode" name="moduleDesc" required>
                </div>
                <div class="form-group">
                    <label for="moduleSubject">Kelas *</label>
                    <select class="filter-select" id="subjectFilter" name="moduleSubject" required>
                        <option value="">Semua Kelas</option>
                        <?php foreach ($data_classes as $classes) { ?>
                            <option value="<?= $classes["id"] ?>"
                                <?php if (isset($_POST['moduleSubject']) && $_POST['moduleSubject'] == $classes['id']) echo 'selected'; ?>>
                                <?= $classes["name"] ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn-secondary" id="cancelModalBtn">Batal</button>
                    <button type="submit" class="btn-primary">Simpan Modul</button>
                </div>
            </form>
        </div>
    </div>



    <div id="materialModal" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close" id="closeModalBtn1">&times;</span>
            <h2 id="modalTitle">Tambah Modul Baru</h2>
            <form id="moduleForm" action="addMaterial.php" method="POST">
                <div class="form-group">
                    <label for="materialName">Judul Materi *</label>
                    <input type="text" id="materialName" name="materialName" required>
                </div>
                <div class="form-group">
                    <label for="materialDesc">Konten Pembelajaran *</label>
                    <textarea id="materialDesc" name="materialDesc" required rows="6"></textarea>
                </div>
                <div class="form-group">
                    <label for="fileLink">File Link (Opsional)</label>
                    <textarea id="fileLink" name="fileLink" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <input type="hidden" id="moduleId" name="moduleId">
                </div>
                <div class="form-actions">
                    <button type="button" class="btn-secondary" id="cancelModalBtn1">Batal</button>
                    <button type="submit" class="btn-primary">Simpan Materi</button>
                </div>
            </form>

        </div>
    </div>

    <footer class="text-center small">
        <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
            <span>&copy; 2025 ClassConnect</span>
            <span>Hubungi Admin: <a href="mailto:hauzantsaaqif28@gmail.com">hauzantsaaqif28@gmail.com</a></span>
        </div>
    </footer>

    <script src="Modulguru.js"></script>
</body>

</html>