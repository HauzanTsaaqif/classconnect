<?php
session_start();
require_once '../../config/db.php';

if (isset($_SESSION['user_id'])) {
    $student_id = $_SESSION['user_id'];
    $assignment_id = $_POST['assignment_id'];
    $note = $_POST['submissionNotes'];
    $submission_link = $_POST['submissionLink'];
    $submitted_at = date('Y-m-d H:i:s');
    $status = 'dikirim';

    // Cek apakah ada data dengan assignment_id dan student_id yang sama
    $check_sql = "SELECT * FROM submissions WHERE assignment_id = ? AND student_id = ?";

    if ($check_stmt = $conn->prepare($check_sql)) {
        $check_stmt->bind_param("ii", $assignment_id, $student_id);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            // Jika data sudah ada, lakukan UPDATE
            $update_sql = "UPDATE submissions SET note = ?, submission_link = ?, submitted_at = ?, status = ? WHERE assignment_id = ? AND student_id = ?";

            if ($update_stmt = $conn->prepare($update_sql)) {
                $update_stmt->bind_param("ssssii", $note, $submission_link, $submitted_at, $status, $assignment_id, $student_id);

                if ($update_stmt->execute()) {
                    echo "Tugas berhasil diperbarui!";
                    header("Location: module.php");
                    exit();
                } else {
                    echo "Terjadi kesalahan saat memperbarui tugas: " . $update_stmt->error;
                }

                $update_stmt->close();
            } else {
                echo "Query UPDATE gagal: " . $conn->error;
            }
        } else {
            // Jika data belum ada, lakukan INSERT
            $insert_sql = "INSERT INTO submissions (assignment_id, student_id, note, submission_link, submitted_at, status) VALUES (?, ?, ?, ?, ?, ?)";

            if ($insert_stmt = $conn->prepare($insert_sql)) {
                $insert_stmt->bind_param("iissss", $assignment_id, $student_id, $note, $submission_link, $submitted_at, $status);

                if ($insert_stmt->execute()) {
                    echo "Tugas berhasil dikirim!";
                    header("Location: module.php");
                    exit();
                } else {
                    echo "Terjadi kesalahan saat mengirim tugas: " . $insert_stmt->error;
                }

                $insert_stmt->close();
            } else {
                echo "Query INSERT gagal: " . $conn->error;
            }
        }

        $check_stmt->close();
    } else {
        echo "Query cek data gagal: " . $conn->error;
    }

    $conn->close();
} else {
    echo "Anda harus login terlebih dahulu!";
    header("Location: ../../index.php");
    exit();
}
