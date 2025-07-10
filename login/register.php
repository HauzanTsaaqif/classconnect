<?php
require_once '../config/db.php';

if (isset($_POST['register'])) {
    // Get the form inputs
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role']; // Added role field

    // Check if email already exists
    $sql_check = "SELECT id FROM users WHERE email = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("s", $email);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        echo "Email sudah terdaftar. Silakan login.";
    } else {
        $sql_insert = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("ssss", $full_name, $email, $password, $role);

        if ($stmt_insert->execute()) {
            echo "Akun berhasil dibuat!";
            header("Location: ../login.php"); // Redirect to login page
            exit();
        } else {
            echo "Terjadi kesalahan saat mendaftar, coba lagi.";
        }

        $stmt_insert->close();
    }

    $stmt_check->close();
    $conn->close();
}
