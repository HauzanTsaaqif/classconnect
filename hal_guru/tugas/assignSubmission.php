<?php
session_start();
require_once '../../config/db.php';

if (isset($_POST['submission_id'])) {
    $submission_id = $_POST['submission_id'];
    $feedback = isset($_POST['feedback']) ? $_POST['feedback'] : null;
    $status = isset($feedback) ? 'revisi' : 'diterima';

    if ($feedback) {
        $sql = "UPDATE submissions 
                SET status = ?, feedback = ?, reviewed_at = NOW() 
                WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $status, $feedback, $submission_id);
    } else {
        $sql = "UPDATE submissions 
                SET status = ?, reviewed_at = NOW() 
                WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $status, $submission_id);
    }

    if ($stmt->execute()) {
        echo "Status berhasil diperbarui!";
        header("Location: tugas.php");
        exit();
    } else {
        echo "Terjadi kesalahan saat memperbarui status tugas.";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "ID submission tidak ditemukan.";
    header("Location: ../../index.php");
    exit();
}
