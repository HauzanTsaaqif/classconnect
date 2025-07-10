<?php
session_start();
include('../../config/db.php'); // Koneksi database

// Pastikan user sudah login
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    if (isset($_GET['id'])) {
        $class_id = $_GET['id'];

        $sql = "DELETE FROM classes WHERE id = ? AND teacher_id = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $class_id, $user_id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            header("Location: Kelas.php");
            exit();
        } else {
            echo "Error: Gagal menghapus kelas.";
        }

        $stmt->close();
    } else {
        echo "ID kelas tidak ditemukan.";
    }
} else {
    echo "Anda belum login!";
    header("Location: ../../index.php");
    exit();
}
