// Sample data
let modules = [
    {
        id: 1,
        name: "Matematika X TKJ 1",
        code: "X TKJ 1",
        subject: "Teknik Jaringan Komputer & Telekomunikasi",
        students: 32,
        materials: 5,
        assignments: 8,
        status: "Aktif",
        description: "Modul ini berisi materi pembelajaran untuk kelas X TKJ semester 1 dengan fokus pada dasar-dasar matematika."
    },
    {
        id: 2,
        name: "Matematika X TITL 2",
        code: "X TITL 2",
        subject: "Teknik Instalasi Tenaga Listrik",
        students: 28,
        materials: 4,
        assignments: 6,
        status: "Aktif",
        description: "Modul pembelajaran dasar matematika untuk teknik listrik."
    },
    {
        id: 3,
        name: "Matematika XI DPIB 1",
        code: "XI DPIB 1",
        subject: "Desain Pemodelan & Informasi Bangunan",
        students: 30,
        materials: 6,
        assignments: 10,
        status: "Aktif",
        description: "Modul matematika terapan untuk desain bangunan."
    }
];

let editingModuleId = null;

// DOM Elements
const modulesGrid = document.getElementById('modulesGrid');
const emptyState = document.getElementById('emptyState');
const viewSection = document.getElementById('viewModuleSection');
const addModuleBtn = document.getElementById('addModuleBtn');
const backToListBtn = document.getElementById('backToListBtn');
const moduleModal = document.getElementById('moduleModal');
const closeModalBtn = document.getElementById('closeModalBtn');
const cancelModalBtn = document.getElementById('cancelModalBtn');
const moduleForm = document.getElementById('moduleForm');
const subjectFilter = document.getElementById('subjectFilter');
const statusFilter = document.getElementById('statusFilter');

// Event Listeners
addModuleBtn.addEventListener('click', openAddModal);
backToListBtn.addEventListener('click', backToList);
closeModalBtn.addEventListener('click', closeModal);
cancelModalBtn.addEventListener('click', closeModal);
subjectFilter.addEventListener('change', filterModules);
statusFilter.addEventListener('change', filterModules);



// Render modules
function renderModules() {
    viewSection.style.display = 'none';
    
    if (modules.length === 0) {
        modulesGrid.style.display = 'none';
        emptyState.style.display = 'block';
        return;
    }

    modulesGrid.style.display = 'grid';
    emptyState.style.display = 'none';

    modulesGrid.innerHTML = modules.map(module => `
        <div class="module-card">
            <div class="module-header">
                <div>
                    <h3 class="module-title">${module.name}</h3>
                    <p class="module-code">Kelas: ${module.code}</p>
                </div>
            </div>
            <div class="module-stats">
                <div class="stat">
                    <div class="stat-number">${module.students}</div>
                    <div class="stat-label">Siswa</div>
                </div>
                <div class="stat">
                    <div class="stat-number">${module.materials}</div>
                    <div class="stat-label">Modul</div>
                </div>
                <div class="stat">
                    <div class="stat-number">${module.assignments}</div>
                    <div class="stat-label">Tugas</div>
                </div>
            </div>
            <div class="module-actions">
                <button class="action-btn btn-view" onclick="viewModule(${module.id})">Lihat</button>
                <button class="action-btn btn-edit" onclick="editModule(${module.id})">Edit</button>
                <button class="action-btn btn-delete" onclick="deleteModule(${module.id})">Hapus</button>
            </div>
        </div>
    `).join('');
}

// Modal functions
function openAddModal() {
    editingModuleId = null;
    document.getElementById('modalTitle').textContent = 'Tambah Modul Baru';
    moduleForm.reset();
    moduleModal.style.display = 'block';
}

function closeModal() {
    moduleModal.style.display = 'none';
    editingModuleId = null;
}

// Module actions
function viewModule(id) {
    const module = modules.find(m => m.id === id);
    
    document.getElementById('viewModuleTitle').textContent = module.name;
    document.getElementById('viewModuleCode').textContent = `Kelas: ${module.code}`;
    document.getElementById('viewModuleSubject').textContent = `Jurusan: ${module.subject}`;
    document.getElementById('viewModuleDescription').textContent = module.description || "Tidak ada deskripsi";
    document.getElementById('viewModuleStudents').textContent = module.students;
    document.getElementById('viewModuleMaterials').textContent = module.materials;
    document.getElementById('viewModuleAssignments').textContent = module.assignments;
    
    modulesGrid.style.display = 'none';
    viewSection.style.display = 'block';
}

function backToList() {
    modulesGrid.style.display = 'grid';
    viewSection.style.display = 'none';
}

function editModule(id) {
    const module = modules.find(m => m.id === id);
    editingModuleId = id;
    
    document.getElementById('modalTitle').textContent = 'Edit Modul';
    document.getElementById('moduleName').value = module.name;
    document.getElementById('moduleCode').value = module.code;
    document.getElementById('moduleSubject').value = module.subject;
    document.getElementById('moduleDescription').value = module.description || '';
    moduleModal.style.display = 'block';
}

function deleteModule(id) {
    const module = modules.find(m => m.id === id);
    if (confirm(`Apakah Anda yakin ingin menghapus modul "${module.name}"?`)) {
        modules = modules.filter(m => m.id !== id);
        renderModules();
    }
}

function filterModules() {
    const subjectFilterValue = subjectFilter.value;
    const statusFilterValue = statusFilter.value;
    
    renderModules();
}

window.onclick = function(event) {
    if (event.target === moduleModal) {
        closeModal();
    }
}

function openModuleModal(moduleId) {
    document.getElementById('moduleId').value = moduleId;

    document.getElementById('materialModal').style.display = 'block';
}

document.getElementById('closeModalBtn1').onclick = function() {
    document.getElementById('materialModal').style.display = 'none';
}

document.getElementById('cancelModalBtn1').onclick = function() {
    document.getElementById('materialModal').style.display = 'none';
}

window.onclick = function(event) {
    if (event.target == document.getElementById('materialModal')) {
        document.getElementById('materialModal').style.display = 'none';
    }
}

function openStudentModal(moduleId) {
    const modal = document.getElementById("viewModal_" + moduleId);
    modal.style.display = "block";
    modal.classList.add("active");
}

// Fungsi untuk menutup modal
function closeStudentModal(moduleId) {
    const modal = document.getElementById("viewModal_" + moduleId);
    modal.style.display = "none";
    modal.classList.remove("active");
}

// Menutup modal saat klik di luar modal
window.addEventListener("click", function(event) {
    const modalElements = document.querySelectorAll(".modal");
    modalElements.forEach(modal => {
        if (event.target === modal) {
            modal.style.display = "none";
            modal.classList.remove("active");
        }
    });
});

function viewMaterial(materialId) {
    window.location.href = `../../hal_siswa/kelas_saya/materi.php?id=${materialId}`;
}

function deleteMaterial(materialId) {
     window.location.href = `deleteMateri.php?id=${materialId}`;
}
