// Toggle sidebar visibility
const sidebarToggle = document.getElementById('sidebar-toggle');
const sidebar = document.getElementById('sidebar');
const sidebarTitle = document.getElementById('sidebar-title');
const sidebarMenu = document.querySelector('.sidebar-menu');
const mainContent = document.querySelector('.main-content');

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