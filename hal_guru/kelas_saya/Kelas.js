function toggleMobileMenu() {
    const menu = document.getElementById('mobileMenu');
    menu.classList.toggle('active');
}

function logout() {
    if (confirm("Apakah Anda yakin ingin keluar?")) {
        window.location.href = "login.html";
    }
}

function navigateToPage(page) {
    // Simulasi navigasi ke halaman tertentu
    window.location.href = page + ".html";
}

function searchClasses(keyword) {
    const cards = document.querySelectorAll('.class-card');
    const searchTerm = keyword.toLowerCase().trim();
    
    if (searchTerm === '') {
        // Jika search kosong, tampilkan semua kartu
        cards.forEach(card => {
            card.style.display = 'block';
        });
        return;
    }
    
    cards.forEach(card => {
        const cardTitle = card.querySelector('h3').textContent.toLowerCase();
        const classCode = card.querySelector('.class-code').textContent.toLowerCase();
        
        // Cari di judul kelas atau kode kelas
        if (cardTitle.includes(searchTerm) || classCode.includes(searchTerm)) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}

// Tambahkan event listener untuk tombol enter di search input
document.querySelector('.search-input').addEventListener('keyup', function(e) {
    if (e.key === 'Enter') {
        searchClasses(this.value);
    }
});

function openAddClassModal() {
    document.getElementById('addClassPopup').style.display = 'flex';
}

function closeAddClassModal() {
    document.getElementById('addClassPopup').style.display = 'none';
}

function toggleClassMenu(button) {
    const allMenus = document.querySelectorAll('.dropdown-menu');
    allMenus.forEach(menu => menu.style.display = 'none'); // Menutup semua menu dropdown lainnya
    
    const menu = button.nextElementSibling; // Mendapatkan menu dropdown setelah tombol yang diklik
    menu.style.display = menu.style.display === 'block' ? 'none' : 'block'; // Menampilkan atau menyembunyikan menu dropdown
}

// Menutup dropdown ketika klik di luar elemen dropdown
window.addEventListener("click", function(event) {
    const allDropdowns = document.querySelectorAll(".dropdown-menu");
    const allButtons = document.querySelectorAll(".dropdown"); // Tombol dropdown untuk mengecek apakah tombol yang diklik

    let isClickInsideDropdown = false;
    allDropdowns.forEach(dropdown => {
        if (dropdown.contains(event.target)) {
            isClickInsideDropdown = true; // Klik berada di dalam dropdown
        }
    });

    let isClickInsideButton = false;
    allButtons.forEach(button => {
        if (button.contains(event.target)) {
            isClickInsideButton = true; // Klik berada di dalam tombol dropdown
        }
    });

    // Jika klik di luar dropdown dan tombol dropdown, tutup menu dropdown
    if (!isClickInsideDropdown && !isClickInsideButton) {
        allMenus.forEach(menu => menu.style.display = 'none');
    }
});

function deleteClass(classId) {
    if (confirm("Apakah Anda yakin ingin menghapus kelas ini?")) {
        window.location.href = `deleteClass.php?id=${classId}`;
    }
}

function openStudentModal(classId) {
    const modal = document.getElementById(`studentModal_${classId}`);
    modal.style.display = "block";
    modal.classList.add("active");
}

function closeStudentModal(classId) {
    const modal = document.getElementById(`studentModal_${classId}`);
    modal.style.display = "none";
    modal.classList.remove("active");
}

window.addEventListener("click", function(event) {
    const modalElements = document.querySelectorAll(".modal");
    modalElements.forEach(modal => {
        if (event.target === modal) {
            modal.style.display = "none";
            modal.classList.remove("active");
        }
    });
});


function openAddStudentModal() {
    document.getElementById('addStudentPopup').style.display = 'flex';
}

function closeAddStudentModal() {
    document.getElementById('addStudentPopup').style.display = 'none';
}