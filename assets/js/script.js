// Toggle sidebar visibility
const sidebarToggle = document.getElementById('sidebar-toggle');
const sidebar = document.getElementById('sidebar');
const sidebarTitle = document.getElementById('sidebar-title');
const sidebarMenu = document.querySelector('.sidebar-menu');
const mainContent = document.querySelector('.main-content');

function showModal(src) {
    var modal = document.getElementById('myModal');
    var imgModal = document.getElementById('imgModal');
    modal.style.display = "block";
    imgModal.src = src;
}

function closeModal() {
    var modal = document.getElementById('myModal');
    modal.style.display = "none";
}


function openEditModal(id, status) {
    document.getElementById('edit_id_feedback').value = id;
    document.getElementById('edit_status_feedback').value = status;
    document.getElementById('editModal').style.display = 'block';
}

function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}



sidebarToggle.addEventListener('click', () => {
    sidebar.classList.toggle('active');

    if (sidebar.classList.contains('active')) {
        mainContent.style.marginLeft = '300px'; // Margin saat sidebar terbuka
        sidebarMenu.style.display = 'flex';
        sidebarMenu.style.flexDirection = 'column';
        sidebarTitle.style.display = 'flex';
        sidebar.style.width = '240px';

    } else {
        mainContent.style.marginLeft = '100px'; // Margin saat sidebar tertutup
        sidebarMenu.style.display = 'none';
        sidebarTitle.style.display = 'none';
        sidebar.style.width = '40px';
    }
});