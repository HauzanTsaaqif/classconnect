<?php
session_start();

include('../../config/db.php');

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $class_name = $_POST['class_name'];
        $class_description = $_POST['class_description'];

        $sql = "INSERT INTO classes (name, description, teacher_id) 
                VALUES ('$class_name', '$class_description', '$user_id')";

        if ($conn->query($sql) === TRUE) {
            header("Location: kelas.php");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
} else {
    echo "Anda belum login!";
    header("Location: ../../index.php");
    exit();
}
