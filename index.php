<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ClassConnect - Integrated Learning Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap">
    <style>
        :root {
            --primary: #5C6BC0;
            --secondary: #FFB74D;
            --accent: #4DB6AC;
            --background: #F9F9F9;
            --dark: #2E2E2E;
            --text-light: #555;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background-color: var(--background);
            color: var(--dark);
            margin: 0;
        }

        .navbar {
            background-color: #ffffffcc;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid #eaeaea;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
            transition: background-color 0.3s ease;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.6rem;
            color: var(--primary);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .navbar-brand i {
            font-size: 1.3rem;
            color: var(--secondary);
        }

        .nav-link {
            color: var(--dark);
            font-weight: 500;
            margin: 0 8px;
            position: relative;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background-color: var(--accent);
            transition: width 0.3s;
        }

        .nav-link:hover::after {
            width: 100%;
        }

        .btn.btn-primary {
            background-color: var(--secondary);
            border: none;
            padding: 8px 20px;
            border-radius: 25px;
            font-weight: 600;
        }

        .btn.btn-primary:hover {
            background-color: var(--accent);
            color: white;
        }

        .hero {
            position: relative;
            overflow: hidden;
        }

        .carousel-item {
            height: 50vh;
            background-size: cover;
            background-position: center;
            color: white;
            text-align: center;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        .carousel-caption h1 {
            font-size: 3rem;
            font-weight: 700;
            animation: fadeInDown 1s;
        }

        .carousel-caption p {
            font-size: 1.2rem;
            animation: fadeInUp 1s;
        }

        .carousel-caption .btn {
            margin-top: 20px;
            background-color: white;
            color: var(--primary);
            font-weight: 600;
            border-radius: 30px;
            padding: 10px 25px;
            transition: 0.3s;
        }

        .carousel-caption .btn:hover {
            background-color: var(--secondary);
            color: var(--dark);
        }

        .features {
            padding: 80px 0;
        }

        .features h2 {
            text-align: center;
            font-weight: 700;
            margin-bottom: 60px;
        }

        .feature-card {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
            text-align: center;
            transition: all 0.3s;
            animation: fadeInUp 0.8s;
        }

        .feature-card:hover {
            transform: translateY(-8px);
        }

        .feature-icon {
            font-size: 2.5rem;
            color: var(--secondary);
            margin-bottom: 20px;
        }

        .feature-card:nth-child(2) .feature-icon {
            color: var(--accent);
        }

        .feature-card:nth-child(3) .feature-icon {
            color: #BA68C8;
        }

        .howto {
            background-color: white;
            padding: 80px 0 40px 0;
            text-align: center;
        }

        .howto p {
            font-size: 1rem;
            font-weight: 500;
            width: 90%;
            margin: 0 auto;
            line-height: 30px;
        }

        .howto h2 {
            font-weight: 700;
            margin-bottom: 20px;
        }

        .howto iframe {
            width: 100%;
            max-width: 720px;
            height: 400px;
            border-radius: 15px;
            border: none;
        }

        footer {
            background-color: var(--primary);
            color: white;
            padding: 50px 0;
        }

        footer a {
            color: #f0f0f0;
            text-decoration: none;
        }

        footer a:hover {
            color: var(--secondary);
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand" href="index.php"><i class="fas fa-graduation-cap"></i> ClassConnect</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="#">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#features">Features</a></li>
                    <li class="nav-item"><a class="nav-link" href="#footer">About</a></li>
                </ul>
                <a href="login.php" class="btn btn-primary ms-3">Login</a>
            </div>
        </div>
    </nav>

    <!-- Hero Carousel -->
    <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="3000">
        <div class="carousel-inner">
            <div class="carousel-item active" style="background-image: url('img/s1.jpg');">
                <div class="carousel-caption">
                    <h1>Simple. Comfortable. Connected.</h1>
                    <p>All-in-one digital learning platform for teachers and students.</p>
                    <a href="login.php" class="btn">Start Now</a>
                </div>
            </div>
            <div class="carousel-item" style="background-image: url('img/s2.jpg');">
                <div class="carousel-caption">
                    <h1>Empowering Modern Classrooms</h1>
                    <p>Integrate modules, materials, and assignments seamlessly.</p>
                    <a href="login.php" class="btn">Start Now</a>
                </div>
            </div>
        </div>
    </div>

    <section class="howto">
        <div class="container">
            <h2>About ClassConnect</h2>
            <p>ClassConnect is a learning platform designed to provide a more organized, convenient, and easily accessible learning experience. Built to facilitate seamless communication and interaction between teachers and students, this website allows teachers to create and manage classes, design teaching modules, and evaluate student assignments, all in one place. Meanwhile, students can join classes, access learning materials, and submit assignments effortlessly.</p>
        </div>
    </section>

    <section id="features" class="features">
        <div class="container">
            <h2>Main Features</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="fas fa-chalkboard"></i></div>
                        <h5>Create & Manage Classes</h5>
                        <p>Teachers can create classes, subjects, and modules effortlessly in one place.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="fas fa-book-open"></i></div>
                        <h5>Integrated Learning Materials</h5>
                        <p>Students get access to curated materials and assignments from their teachers.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="fas fa-check-circle"></i></div>
                        <h5>Submission & Grading</h5>
                        <p>Track, submit, and review assignments—all in a seamless digital flow.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer id="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>About ClassConnect</h5>
                    <p>We make digital learning easier and more human. With ClassConnect, everything you need for modern teaching and learning is integrated in one platform—comfortable, simple, and efficient.</p>
                </div>
                <div class="col-md-3">
                    <h5>Quick Links</h5>
                    <a href="#">Home</a><br>
                    <a href="#features">Features</a><br>
                    <a href="#about">About</a><br>
                    <a href="#contact">Contact</a>
                </div>
                <div class="col-md-3">
                    <h5>Follow Us</h5>
                    <a href="#"><i class="fab fa-facebook me-2"></i>Facebook</a><br>
                    <a href="#"><i class="fab fa-instagram me-2"></i>Instagram</a><br>
                    <a href="#"><i class="fab fa-twitter me-2"></i>Twitter</a>
                </div>
            </div>
            <div class="text-center mt-4">
                <small>&copy; 2025 ClassConnect. All rights reserved.</small>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>