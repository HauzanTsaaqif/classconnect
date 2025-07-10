<?php
require_once '../config/db.php';

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT id, email, password, role FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if ($password == $user['password']) {
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];
            if ($user['role'] == "guru") {
                header("Location: ../hal_guru/dashboard_guru/dashboard.php");
            } else {
                header("Location: ../hal_siswa/kelas_saya/siswa.php");
            }
            exit();
        } else {
            echo "Email atau password salah!";
        }
    } else {
        echo "Pengguna tidak ditemukan!";
    }

    $stmt->close();
    $conn->close();
}
