<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="../../img/logo2.png">
    <title>Tugas - EduLearn</title>
    <link rel="stylesheet" href="tugas.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet" />
    <link href="../dashboard_guru/Animate.css" rel="stylesheet" />
    <link href="../css/dashboard.css" rel="stylesheet" />
    <link rel="stylesheet" href="Kelas.css">
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
                <a class="nav-link" href="../kelas_saya/Kelas.php">Kelas Saya</a>
                <a class="nav-link active" href="../tugas/tugas.php">Tugas</a>
                <a href="../../login/logout.php" class="btn btn-sm btn-outline-light"><i class="fa-solid fa-right-from-bracket me-1"></i>Logout</a>
            </div>
        </div>
    </nav>

    <main class="container-fluid pt-4">
        <div class="container-xl">
            <div class="header">
                <h1 class="h3 fw-bold mb-1">Kelola Tugas</h1>
                <p class="text-muted mb-0">Atur tugas, tenggat waktu, dan pantau pengumpulan siswa</p>
            </div>

            <?php
            require_once '../../config/db.php';
            $user_id = $_SESSION['user_id'];

            if (isset($_GET['class_id'])) {
                $class_id = $_GET['class_id'];
                $sql_2 = "SELECT * 
            FROM classes c 
            WHERE c.teacher_id = ? AND c.id = ?";

                $stmt = $conn->prepare($sql_2);
                $stmt->bind_param("ii", $user_id, $class_id);

                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
                    $classes = $result->fetch_assoc();
                }
                $stmt->close();
            }
            ?>
            <div class="action-bar">
                <div class="filter-options">
                    <?php if (isset($classes)) { ?>
                        <p class="filter-select"><strong>Kelas: </strong><?= $classes["name"] ?></p>
                        <p class="filter-select"><strong>Deskripsi: </strong><?= $classes["description"] ?></p>
                    <?php } else { ?>
                        <select class="filter-select" onchange="filterTasks(this.value)">
                            <option value="all">Semua Tugas</option>
                            <option value="active">Aktif</option>
                            <option value="due-soon">Akan Berakhir</option>
                            <option value="overdue">Terlambat</option>
                            <option value="completed">Selesai</option>
                        </select>
                        <select class="filter-select" onchange="filterByClass(this.value)">
                            <option value="all">Semua Kelas</option>
                            <option value="matematika-x-1">Matematika X-1</option>
                            <option value="matematika-x-2">Matematika X-2</option>
                            <option value="matematika-xi-1">Matematika XI-1</option>
                            <option value="matematika-xi-2">Matematika XI-2</option>
                            <option value="matematika-xii-1">Matematika XII-1</option>
                        </select>
                    <?php } ?>
                </div>
                <button class="add-class-btn" onclick="openTaskModal()">
                    <span>Buat Tugas</span>
                </button>
            </div>

            <?php
            require_once '../../config/db.php';
            $user_id = $_SESSION['user_id'];

            if (isset($_GET['class_id'])) {
                $class_id = $_GET['class_id'];
                $sql = "SELECT a.*, m.title AS module_title, c.name AS class_name
                FROM assignments a
                JOIN modules m ON a.module_id = m.id
                JOIN classes c ON m.class_id = c.id
                WHERE c.teacher_id = ? AND c.id = ?";

                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ii", $user_id, $class_id);
            } else {
                $sql = "SELECT a.*, m.title AS module_title, c.name AS class_name
                FROM assignments a
                JOIN modules m ON a.module_id = m.id
                JOIN classes c ON m.class_id = c.id
                WHERE c.teacher_id = ?";

                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $user_id);
            }

            $stmt->execute();
            $data_task = $stmt->get_result();

            $sql_2 = "SELECT m.*, c.name AS class_name, c.description AS class_description 
          FROM modules m 
          JOIN classes c ON m.class_id = c.id 
          WHERE c.teacher_id = ?";

            $stmt = $conn->prepare($sql_2);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();

            $data_modules = $stmt->get_result();

            $stmt->close();
            ?>

            <div id="taskModal" class="modal" style="display: none;">
                <div class="modal-content">
                    <span class="close" id="closeModalBtn">&times;</span>
                    <h2>Tambah Tugas Baru</h2>
                    <form id="taskForm" action="addTask.php" method="POST">
                        <div class="form-group">
                            <label for="taskTitle">Judul Tugas *</label>
                            <input type="text" id="taskTitle" name="taskTitle" required>
                        </div>
                        <div class="form-group">
                            <label for="taskDescription">Keterangan Tugas *</label>
                            <textarea id="taskDescription" name="taskDescription" required rows="4"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="taskModule">Modul *</label>
                            <select id="taskModule" name="taskModule" required>
                                <option value="">Pilih Modul</option>
                                <?php foreach ($data_modules as $module) { ?>
                                    <option value="<?= $module['id'] ?>"><?= $module['title'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="taskDeadline">Deadline *</label>
                            <input type="datetime-local" id="taskDeadline" name="taskDeadline" required>
                        </div>
                        <div class="form-group">
                            <label for="taskFileLink">File Referensi (Opsional)</label>
                            <textarea id="taskFileLink" name="taskFileLink" rows="3"></textarea>
                        </div>
                        <input type="hidden" name="createdAt" value="<?= date('Y-m-d H:i:s') ?>">

                        <div class="form-actions">
                            <button type="button" class="action-btn btn-secondary" id="cancelModalBtn">Batal</button>
                            <button type="submit" class="action-btn btn-primary">Simpan Tugas</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tasks Grid -->
            <div class="tasks-grid" id="tasksGrid">
                <?php
                if (isset($data_task)) {
                    foreach ($data_task as $task) { ?>
                        <div class="task-card" data-status="active" data-class="matematika-x-1">
                            <div class="task-header">
                                <div class="task-info">
                                    <h3><?= $task["title"] ?></h3>
                                    <span class="task-class">Kelas : <?= $task["class_name"] ?></span>
                                    <span class="task-class">Module : <?= $task["module_title"] ?></span>
                                </div>
                                <div class="task-status status-active">Aktif</div>
                            </div>
                            <div class="task-deadline">
                                <span class="deadline-icon">⏰</span>
                                <div>
                                    <div class="deadline-text">Tenggat Waktu:</div>
                                    <div class="deadline-date"><?= $task["deadline"] ?></div>
                                </div>
                            </div>
                            <div class="task-description">
                                <?= $task["description"] ?>
                                <?php if ($task["file_link"]) { ?>
                                    <div class="deadline-text">File Referensi:</div>
                                <?php echo $task["file_link"];
                                } ?>
                            </div>

                            <div class="task-actions">
                                <a href="javascript:void(0)" class="action-btn btn-primary" onclick="openSubmissionModal(<?= $task['id'] ?>)">
                                    <span>👥</span>Lihat Pengumpulan
                                </a>
                                <button class="action-btn btn-secondary" onclick="window.location.href = `deleteTask.php?id=<?= $task['id'] ?>`;">
                                    <span><i class="fas fa-trash"></i></span> Hapus
                                </button>
                            </div>
                        </div>

                        <?php
                        require_once '../../config/db.php';
                        $user_id = $_SESSION['user_id'];

                        $sql_2 = "SELECT s.id AS submission_id,
                                u.name AS student_name,
                                u.email AS student_email,
                                s.note AS submission_note,
                                s.submission_link,
                                s.submitted_at,
                                s.status AS submission_status,
                                s.*
                            FROM submissions s
                            JOIN users u ON s.student_id = u.id
                            WHERE s.assignment_id = ?
                        ";

                        $stmt = $conn->prepare($sql_2);
                        $stmt->bind_param("i", $task["id"]);
                        $stmt->execute();

                        $data_submissions  = $stmt->get_result();

                        $stmt->close();
                        ?>

                        <div id="submissionModal_<?= $task['id'] ?>" class="modal">
                            <div class="modal-submission">
                                <h2>List Pengumpulan Tugas - <?= $task["title"] ?></h2>

                                <table class="submission-table">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Siswa</th>
                                            <th>Catatan</th>
                                            <th>Link Pengumpulan</th>
                                            <th>Waktu Pengumpulan</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 1; ?>
                                        <?php while ($submission = $data_submissions->fetch_assoc()) { ?>
                                            <tr>
                                                <td><?= $i ?></td>
                                                <td><?= $submission["student_name"] ?></td>
                                                <td><?= $submission["submission_note"] ?></td>
                                                <td><?= nl2br($submission["submission_link"]) ?></td>
                                                <td><?= $submission["submitted_at"] ?></td>
                                                <td><?= $submission["submission_status"] ?></td>
                                                <td>
                                                    <?php if ($submission["submission_status"] == "diterima") { ?>
                                                        Selesai pada <?= $submission["reviewed_at"] ?>
                                                    <?php } elseif ($submission["submission_status"] == "revisi") { ?>
                                                        Revisi : <?= $submission["feedback"] ?>
                                                    <?php } else { ?>
                                                        <button class="action-btn btn-warning" onclick="assignSubmission(<?= $submission['submission_id'] ?>, 'terima')">
                                                            Terima
                                                        </button>
                                                        <button class="action-btn btn-secondary" onclick="openRevisionModal(<?= $submission['submission_id'] ?>)">
                                                            Revisi
                                                        </button>
                                                    <?php } ?>
                                                </td>
                                            </tr>

                                            <!-- Modal Revisi -->
                                            <div id="revisionModal_<?= $submission['submission_id'] ?>" class="modal">
                                                <div class="modal-content">
                                                    <h2>Feedback - <?= $submission["student_name"] ?></h2>

                                                    <form id="revisionForm_<?= $submission['submission_id'] ?>" method="POST" action="assignSubmission.php">
                                                        <input type="hidden" name="submission_id" value="<?= $submission['submission_id'] ?>">
                                                        <div class="form-group">
                                                            <label for="feedback">Feedback</label>
                                                            <textarea name="feedback" id="feedback" placeholder="Masukkan feedback untuk siswa..." required></textarea>
                                                        </div>
                                                        <div class="form-actions">
                                                            <button type="submit" class="action-btn btn-warning">Kirim Revisi</button>
                                                            <button type="button" class="action-btn btn-secondary" onclick="closeRevisionModal(<?= $submission['submission_id'] ?>)">Batal</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>

                                            <?php $i++; ?>
                                        <?php } ?>
                                    </tbody>
                                </table>
                                <button class="close-btn" onclick="closeModal(<?= $task['id'] ?>)">
                                    <p>&times; Tutup</p>
                                </button>
                            </div>
                        </div>
                    <?php }
                } else { ?>
                    <strong>Belum Ada Tugas</strong>
                <?php } ?>
                <?php
                if ($data_task->num_rows <= 0) { ?>
                    <strong class="no-task">Belum Ada Tugas</strong>
                <?php } ?>
            </div>
        </div>
    </main>

    <footer class="text-center small">
        <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
            <span>&copy; 2025 ClassConnect</span>
            <span>Hubungi Admin: <a href="mailto:hauzantsaaqif28@gmail.com">hauzantsaaqif28@gmail.com</a></span>
        </div>
    </footer>

    <script src="tugas.js"></script>
</body>

</html>