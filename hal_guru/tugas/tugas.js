function logout() {
    // Logout functionality
    console.log("Logout clicked");
    // Add your logout logic here
}

function toggleMobileMenu() {
    const mobileMenu = document.getElementById('mobileMenu');
    mobileMenu.classList.toggle('active');
}

function filterTasks(status) {
    // Filter tasks by status
    console.log("Filtering tasks by status:", status);
    // Add your filtering logic here
}

function filterByClass(className) {
    // Filter tasks by class
    console.log("Filtering tasks by class:", className);
    // Add your filtering logic here
}

function openAddTaskModal() {
    // Open add task modal
    console.log("Opening add task modal");
    // Add your modal opening logic here
}

function viewSubmissions(taskName, className) {
    console.log("Viewing submissions for task:", taskName, "in class:", className);
}

function editTask(taskId) {
    console.log("Editing task with ID:", taskId);
}

function extendDeadline(taskId) {
    console.log("Extending deadline for task with ID:", taskId);
}

function openTaskModal() {
    document.getElementById('taskModal').style.display = 'block';
}

document.getElementById('closeModalBtn').onclick = function() {
    document.getElementById('taskModal').style.display = 'none';
}

window.onclick = function(event) {
    if (event.target == document.getElementById('taskModal')) {
        document.getElementById('taskModal').style.display = 'none';
    }
}

document.getElementById('cancelModalBtn').onclick = function() {
    document.getElementById('taskModal').style.display = 'none';
}

function openSubmissionModal(taskId) {
    const modal = document.getElementById('submissionModal_' + taskId);
    modal.style.display = 'block';
    modal.classList.add('active');
}

function closeModal(taskId) {
    const modal = document.getElementById('submissionModal_' + taskId);
    modal.style.display = 'none';
    modal.classList.remove('active');
}

function assignSubmission(submissionId, action) {
    const form = document.createElement('form');
    form.action = 'assignSubmission.php';
    form.method = 'POST';

    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'submission_id';
    input.value = submissionId;

    form.appendChild(input);

    // Append the form to the body (required for form submission)
    document.body.appendChild(form);

    if (action === 'terima') {
        form.submit(); // Submit the form
    } else {
        alert('Periksa tugas untuk memberikan feedback');
    }

    // Optionally remove the form after submission to clean up
    document.body.removeChild(form);
}

function openRevisionModal(submissionId) {
    const modal = document.getElementById('revisionModal_' + submissionId);
    modal.style.display = 'block';
}

function closeRevisionModal(submissionId) {
    const modal = document.getElementById('revisionModal_' + submissionId);
    modal.style.display = 'none';
}
