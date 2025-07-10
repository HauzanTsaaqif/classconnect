<?php
session_start();

include('../../config/db.php');

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $module_name = $_POST['moduleName'];
        $module_desc = $_POST['moduleDesc'];
        $class_id = $_POST['moduleSubject'];

        if (empty($module_name) || empty($module_desc) || empty($class_id)) {
            echo "Semua field harus diisi!";
            exit();
        }

        $sql = "INSERT INTO modules (class_id, title, description) VALUES (?, ?, ?)";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("iss", $class_id, $module_name, $module_desc);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo "Modul berhasil ditambahkan!";
                header("Location: Modulguru.php?class_id=" . $class_id);
                exit();
            } else {
                echo "Error: Modul gagal ditambahkan.";
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
