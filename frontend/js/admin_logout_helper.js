// Shared logout handler used on every admin page.
function wireAdminLogout() {
    const link = document.getElementById('logoutLink');
    if (link) {
        link.addEventListener('click', async (e) => {
            e.preventDefault();
            await apiPost('admin_logout.php', {});
            window.location.href = 'admin_login.html';
        });
    }
}
wireAdminLogout();
