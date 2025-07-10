<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Daftar - ClassConnect</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <style>
    :root {
      --primary: #5C6BC0;
      --secondary: #FFB74D;
      --accent: #4DB6AC;
      --background: #F9F9F9;
      --dark: #2E2E2E;
    }

    body {
      font-family: 'DM Sans', sans-serif;
      background-color: var(--background);
      margin: 0;
    }

    .signup-container {
      display: flex;
      min-height: 100vh;
    }

    .form-section {
      flex: 1;
      background-color: white;
      padding: 60px 40px;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    .form-section img {
      height: 60px;
      margin-bottom: 20px;
    }

    .form-section h2 {
      font-weight: 700;
      color: var(--primary);
      margin-bottom: 10px;
    }

    .form-section p {
      color: #555;
      margin-bottom: 30px;
    }

    .form-group {
      margin-bottom: 20px;
    }

    .form-group label {
      font-weight: 500;
      margin-bottom: 6px;
      display: block;
    }

    .form-group input,
    .form-group select {
      width: 100%;
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 10px;
      font-size: 1rem;
    }

    button[type="submit"] {
      background-color: var(--primary);
      color: white;
      border: none;
      padding: 12px;
      width: 100%;
      border-radius: 30px;
      font-weight: 600;
      transition: 0.3s;
    }

    button[type="submit"]:hover {
      background-color: var(--accent);
    }

    .login-link {
      margin-top: 20px;
      font-size: 0.95rem;
    }

    .login-link a {
      color: var(--accent);
      font-weight: 500;
      text-decoration: none;
    }

    .info-section {
      flex: 1.2;
      background: linear-gradient(135deg, var(--primary), var(--accent));
      color: white;
      padding: 60px 40px;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    .info-section h3 {
      font-size: 1.8rem;
      font-weight: 700;
      margin-bottom: 20px;
    }

    .info-section ul {
      padding-left: 20px;
    }

    .info-section li {
      margin-bottom: 12px;
      font-size: 1.05rem;
    }

    @media (max-width: 768px) {
      .signup-container {
        flex-direction: column;
      }

      .info-section {
        display: none;
      }
    }
  </style>
</head>

<body>
  <div class="signup-container">
    <div class="form-section">
      <h2>Daftar Akun</h2>
      <p>Buat akun untuk mengakses ClassConnect</p>

      <form action="register.php" method="POST">
        <div class="form-group">
          <label>Nama Lengkap</label>
          <input type="text" name="full_name" placeholder="Masukkan nama lengkap" required />
        </div>
        <div class="form-group">
          <label>Email</label>
          <input type="email" name="email" placeholder="Masukkan email" required />
        </div>
        <div class="form-group">
          <label>Password</label>
          <input type="password" name="password" placeholder="Masukkan password" required />
        </div>
        <div class="form-group">
          <label>Role</label>
          <select name="role" required>
            <option value="">-- Pilih Role --</option>
            <option value="siswa">Siswa</option>
            <option value="guru">Guru</option>
          </select>
        </div>
        <button type="submit" name="register">Daftar</button>
      </form>

      <div class="login-link">
        Sudah punya akun? <a href="../login.php">Masuk</a>
      </div>
    </div>

    <div class="info-section">
      <h3>Gabung dan Mulai Belajar Lebih Mudah!</h3>
      <ul>
        <li><i class="fas fa-book me-2"></i> Akses materi kapan saja</li>
        <li><i class="fas fa-calendar-check me-2"></i> Jadwal terintegrasi</li>
        <li><i class="fas fa-upload me-2"></i> Tugas dan penilaian online</li>
        <li><i class="fas fa-brain me-2"></i> Kembangkan dirimu setiap hari</li>
      </ul>
    </div>
  </div>
</body>

</html>