// Toggle sidebar visibility
const sidebarToggle = document.getElementById('sidebar-toggle');
const sidebar = document.getElementById('sidebar');
const sidebarTitle = document.getElementById('sidebar-title');
const sidebarMenuTexts = document.querySelectorAll('.menu-text');
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
        mainContent.style.marginLeft = '300px';
        sidebarTitle.style.display = 'flex';
        sidebar.style.width = '240px';
        sidebarToggle.style.marginTop = '0';

        // Tampilkan teks
        sidebarMenuTexts.forEach(text => {
            text.style.display = 'inline';
        });
    } else {
        mainContent.style.marginLeft = '100px';
        sidebarTitle.style.display = 'none';
        sidebar.style.width = '40px';
        sidebarToggle.style.marginTop = '17px';

        // Sembunyikan teks saja
        sidebarMenuTexts.forEach(text => {
            text.style.display = 'none';
        });
    }
});