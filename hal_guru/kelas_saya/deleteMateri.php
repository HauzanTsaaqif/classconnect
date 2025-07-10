<?php
session_start();
include('../../config/db.php'); // Koneksi database

// Pastikan user sudah login
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    if (isset($_GET['id'])) {
        $materi_id = $_GET['id'];

        $sql = "DELETE FROM materials WHERE id = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $materi_id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            header("Location: Modulguru.php");
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
