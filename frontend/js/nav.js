// Renders the top navigation links based on current login state.
// Call renderNav('navContainerId') after the page loads.
async function renderNav(containerId) {
    const nav = document.getElementById(containerId);
    if (!nav) return;

    let status;
    try {
        status = await apiGet('session_status.php');
    } catch (e) {
        status = { logged_in: false };
    }

    if (status.logged_in && status.role === 'user') {
        const roleLabel = status.user_type
            ? status.user_type.charAt(0).toUpperCase() + status.user_type.slice(1)
            : '';
        nav.innerHTML = `
            <a href="dashboard.html">Manage Profile</a>
            <a href="view_properties.html">View Properties</a>
            <a href="#" id="navRoleLink">Role: ${escapeHtml(roleLabel)}</a>
            <a href="upload_property.html">Sell</a>
            <a href="#" id="navLogout">Logout</a>
        `;
        const roleLink = document.getElementById('navRoleLink');
        if (roleLink) {
            roleLink.addEventListener('click', async (e) => {
                e.preventDefault();
                const result = await apiPost('convertuser.php', {});
                alert(result.message || (result.success ? 'Role changed!' : 'Could not change role.'));
                if (result.success) window.location.reload();
            });
        }
        const logoutLink = document.getElementById('navLogout');
        if (logoutLink) {
            logoutLink.addEventListener('click', async (e) => {
                e.preventDefault();
                await apiPost('logout.php', {});
                window.location.href = 'index.html';
            });
        }
    } else if (status.logged_in && status.role === 'admin') {
        nav.innerHTML = `
            <a href="admin_dashboard.html">Admin Dashboard</a>
            <a href="#" id="navLogout">Logout</a>
        `;
        const logoutLink = document.getElementById('navLogout');
        if (logoutLink) {
            logoutLink.addEventListener('click', async (e) => {
                e.preventDefault();
                await apiPost('admin_logout.php', {});
                window.location.href = 'admin_login.html';
            });
        }
    } else {
        nav.innerHTML = `
            <a href="login.html">Login</a>
            <a href="register.html">Register</a>
        `;
    }
}
