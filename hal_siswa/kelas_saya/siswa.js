function toModule(moduleId) {
  window.location.href = `module.php?id=${moduleId}`;
}

// Ambil elemen dropdown dan dropdown-content
const dropdown = document.querySelector('.dropdown');
const dropdownContent = document.querySelector('.dropdown-content');

// Toggle dropdown saat diklik
dropdown.addEventListener('click', function(event) {
    event.stopPropagation(); // Mencegah event propagasi ke elemen lain (seperti body)
    
    // Toggle display antara block dan none
    if (dropdownContent.style.display === "none" || dropdownContent.style.display === "") {
        dropdownContent.style.display = "block";
    } else {
        dropdownContent.style.display = "none";
    }
});

// Menutup dropdown jika klik di luar dropdown
window.addEventListener('click', function(event) {
    if (!dropdown.contains(event.target)) {
        dropdownContent.style.display = "none"; // Menyembunyikan dropdown jika klik di luar
    }
});
