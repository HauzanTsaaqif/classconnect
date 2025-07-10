<?php
session_start();
include('../../config/db.php');

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Ambil data dari form
        $material_name = $_POST['materialName'];
        $material_desc = $_POST['materialDesc'];
        $file_link = $_POST['fileLink']; // Data untuk file link
        $module_id = $_POST['moduleId'];

        // Validasi input
        if (empty($material_name) || empty($material_desc) || empty($module_id)) {
            echo "Semua field harus diisi!";
            exit();
        }

        // Query untuk memasukkan data materi ke tabel materials
        $sql = "INSERT INTO materials (module_id, title, content, file_link) VALUES (?, ?, ?, ?)";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("isss", $module_id, $material_name, $material_desc, $file_link);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo "Materi berhasil ditambahkan!";
                header("Location: Modulguru.php"); // Redirect ke halaman yang sesuai setelah berhasil
                exit();
            } else {
                echo "Error: Materi gagal ditambahkan.";
            }

            $stmt->close();
        } else {
            echo "Error: Tidak dapat mempersiapkan query.";
        }
    }
} else {
    echo "Anda harus login terlebih dahulu!";
    header("Location: ../../index.php");
    exit();
}

$conn->close();
