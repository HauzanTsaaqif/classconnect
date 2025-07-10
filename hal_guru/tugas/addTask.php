<?php
session_start();
include('../../config/db.php');

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Ambil data dari form
        $task_title = $_POST['taskTitle'];
        $task_description = $_POST['taskDescription'];
        $task_module = $_POST['taskModule'];
        $task_deadline = $_POST['taskDeadline'];
        $task_file_link = $_POST['taskFileLink'];
        $created_at = $_POST['createdAt'];

        // Validasi input
        if (empty($task_title) || empty($task_description) || empty($task_module) || empty($task_deadline)) {
            echo "Semua field wajib diisi!";
            exit();
        }

        // Query untuk memasukkan data tugas ke tabel assignments
        $sql = "INSERT INTO assignments (module_id, title, description, deadline, file_link, created_at) 
                VALUES (?, ?, ?, ?, ?, ?)";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("isssss", $task_module, $task_title, $task_description, $task_deadline, $task_file_link, $created_at);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo "Tugas berhasil ditambahkan!";
                header("Location: tugas.php");
                exit();
            } else {
                echo "Error: Tugas gagal ditambahkan.";
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
