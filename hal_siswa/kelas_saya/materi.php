<?php
session_start();
if (isset($_SESSION['user_id']) and isset($_GET['id'])) {
    $user_id = $_SESSION['user_id'];
    $email = $_SESSION['email'];
    $materi_id = $_GET['id'];
    require_once '../../config/db.php';

    $sql = "SELECT * FROM materials WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $materi_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data_material = $result->fetch_assoc();
    $stmt->close();
} else {
    echo "Anda belum login atau tidak ada modul yang dipilih!";
    header("Location: ../../index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" type="image/x-icon" href="../../img/logo2.png">
    <title>Materi - <?= $data_material['title'] ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #4e73df;
            --accent: #1cc88a;
            --background: #f8f9fc;
            --text: #343a40;
            --card: #ffffff;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: var(--background);
            color: var(--text);
            line-height: 1.6;
        }

        .navbar {
            background: var(--card);
            padding: 1rem 8%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            flex-wrap: wrap;
        }

        .navbar-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .logo img {
            height: 40px;
        }

        .logo-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--primary);
        }

        .logo-subtitle {
            font-size: 0.8rem;
            color: #888;
        }

        .navbar-menu {
            list-style: none;
            display: flex;
            gap: 1.5rem;
            flex-wrap: wrap;
        }

        .navbar-menu li a {
            text-decoration: none;
            color: var(--text);
            font-weight: 500;
            transition: color 0.2s;
        }

        .navbar-menu li a:hover,
        .navbar-menu li a.active {
            color: var(--primary);
        }

        .navbar-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-avatar {
            position: relative;
            cursor: pointer;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            top: 100%;
            background: var(--card);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        .user-avatar:hover .dropdown-content {
            display: block;
        }

        .dropdown-content a {
            display: block;
            padding: 10px 20px;
            text-decoration: none;
            color: var(--text);
            font-size: 0.9rem;
        }

        .dropdown-content a:hover {
            background: var(--background);
        }

        .container {
            max-width: 900px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .materi-card,
        .konten-card {
            background: var(--card);
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
            padding: 2rem;
        }

        .materi-header h2 {
            font-size: 1.6rem;
            font-weight: 600;
            color: var(--primary);
        }

        .konten-body p {
            margin-top: 1rem;
            color: #444;
        }

        .konten-body a {
            color: var(--accent);
            text-decoration: underline;
        }

        .navbar .logo-title {
            font-weight: 700;
            color: #4e73df;
            font-size: 1.5rem;
            line-height: 1;
            text-decoration: none;
        }

        .navbar .logo-subtitle {
            font-size: .75rem;
            color: rgba(255, 255, 255, .8);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.6rem;
            color: var(--primary);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .navbar-brand:hover {
            color: var(--cc-primary-light);
        }

        .navbar-brand i {
            font-size: 1.3rem;
            color: #FFB74D;
        }

        @media (max-width: 768px) {
            .navbar-menu {
                flex-direction: column;
                gap: 0.5rem;
                margin-top: 1rem;
            }
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-left">
            <div class="logo">
                <a class="navbar-brand logo-title" href="dashboard.php"><i class="fas fa-graduation-cap"></i> ClassConnect</a>
            </div>
        </div>

        <div class="navbar-right">
            <span class="status online"></span>
            <span class="status-text"><?= $email ?></span>
            <div class="user-avatar dropdown">
                <span><i class="fas fa-user-graduate"></i></span>
                <div class="dropdown-content">
                    <a href="#"><i class="fas fa-user"></i> Profil</a>
                    <a href="#"><i class="fas fa-cog"></i> Pengaturan</a>
                    <a href="../../login/logout.php"><i class="fas fa-sign-out-alt"></i> Keluar</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container">
        <div class="materi-card">
            <div class="materi-header">
                <h2><?= $data_material['title'] ?></h2>
            </div>
        </div>

        <div class="konten-card">
            <div class="konten-body">
                <p><strong>Konten Materi:</strong></p>
                <p><?= nl2br($data_material['content']) ?></p>

                <p><strong>File Referensi:</strong></p>
                <p><a href="<?= $data_material['file_link'] ?>" target="_blank"><?= $data_material['file_link'] ?></a></p>
            </div>
        </div>
    </main>

    <script>
        document.getElementById("menuToggle")?.addEventListener("click", function() {
            document.getElementById("navbarMenu").classList.toggle("active");
        });
    </script>
</body>

</html>