// Modal functionality
function openModal(type) {
    const modalId = type + 'Modal';
    document.getElementById(modalId).style.display = 'block';
}

function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

// Navigation functionality
function setActiveNav(element) {
    // Remove active class from all nav links
    const navLinks = document.querySelectorAll('.nav-link');
    navLinks.forEach(link => link.classList.remove('active'));
    
    // Add active class to clicked element
    element.classList.add('active');
    
    // Update page content based on navigation
    const page = element.getAttribute('href').substring(1);
    updatePageContent(page);
}

function updatePageContent(page) {
    const header = document.querySelector('.header h1');
    const description = document.querySelector('.header p');
    
    switch(page) {
        case 'dashboard':
            header.textContent = 'Dashboard Guru';
            description.textContent = 'Kelola kelas dan monitoring pembelajaran dengan mudah';
            break;
        case 'kelas':
            header.textContent = 'Kelas Saya';
            description.textContent = 'Kelola semua kelas yang Anda ampu';
            break;
        case 'modul':
            header.textContent = 'Modul Pembelajaran';
            description.textContent = 'Atur dan kelola modul pembelajaran sesuai kurikulum';
            break;
        case 'materi':
            header.textContent = 'Materi & Tugas';
            description.textContent = 'Kelola materi pembelajaran dan tugas dengan tenggat waktu';
            break;
        case 'tugas':
            header.textContent = 'Manajemen Tugas';
            description.textContent = 'Monitor dan kelola semua tugas beserta tenggat waktunya';
            break;
    }
}

function toggleMobileMenu() {
    const mobileMenu = document.getElementById('mobileMenu');
    mobileMenu.classList.toggle('active');
}

function closeMobileMenu() {
    const mobileMenu = document.getElementById('mobileMenu');
    mobileMenu.classList.remove('active');
}

function logout() {
    if (confirm('Apakah Anda yakin ingin logout?')) {
        // Add logout animation
        document.body.style.opacity = '0';
        document.body.style.transition = 'opacity 0.5s ease';
       
        setTimeout(() => {
            alert('Anda telah berhasil logout!');
            // In real application, redirect to login page
            // window.location.href = '/login';
            document.body.style.opacity = '1';
        }, 500);
    }
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });
    
    // Close mobile menu when clicking outside
    const mobileMenu = document.getElementById('mobileMenu');
    if (!event.target.closest('.nav-right') && !event.target.closest('.mobile-menu')) {
        mobileMenu.classList.remove('active');
    }
}

// Add some interactive effects
document.addEventListener('DOMContentLoaded', function() {
    // Animate stats numbers
    const statNumbers = document.querySelectorAll('.stat-number');
    statNumbers.forEach(stat => {
        const finalNumber = parseInt(stat.textContent);
        let currentNumber = 0;
        const increment = finalNumber / 20;
       
        const timer = setInterval(() => {
            currentNumber += increment;
            if (currentNumber >= finalNumber) {
                stat.textContent = finalNumber;
                clearInterval(timer);
            } else {
                stat.textContent = Math.floor(currentNumber);
            }
        }, 50);
    });
    
    // Add hover effects to cards
    const cards = document.querySelectorAll('.feature-card, .stat-card');
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-10px) scale(1.02)';
        });
       
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
});

// Add floating animation to random elements
setInterval(() => {
    const icons = document.querySelectorAll('.feature-icon');
    const randomIcon = icons[Math.floor(Math.random() * icons.length)];
    randomIcon.style.animation = 'bounce 1s ease-in-out';
    setTimeout(() => {
         randomIcon.style.animation = 'rotate 3s linear infinite';
            }, 1000);
        }, 5000);