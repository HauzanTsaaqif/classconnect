<?php
session_start();
require_once '../../config/db.php';

if (isset($_POST['student_id']) && isset($_POST['class_id'])) {
    $student_id = $_POST['student_id'];
    $class_id = $_POST['class_id'];

    // Check if the student is already enrolled in the class
    $sql_check = "SELECT * FROM class_enrollments WHERE student_id = ? AND class_id = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("ii", $student_id, $class_id);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        echo "Siswa sudah terdaftar di kelas ini!";
    } else {
        // Insert student into the class
        $sql_insert = "INSERT INTO class_enrollments (class_id, student_id) VALUES (?, ?)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("ii", $class_id, $student_id);
        if ($stmt_insert->execute()) {
            echo "Siswa berhasil ditambahkan ke kelas!";
            header("Location: kelas.php");
            exit();
        } else {
            echo "Terjadi kesalahan saat menambahkan siswa!";
        }
        $stmt_insert->close();
    }

    $stmt_check->close();
    $conn->close();
} else {
    echo "Data tidak lengkap!";
    header("Location: ../../index.php");
    exit();
}
