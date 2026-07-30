async function loadUsers() {
    const body = document.getElementById('usersBody');
    const result = await apiGet('admin_users.php');
    if (!result.success) {
        alert(result.message || 'Please log in as admin.');
        window.location.href = 'admin_login.html';
        return;
    }
    body.innerHTML = result.users.map(u => `
        <tr>
            <td>${escapeHtml(u.id)}</td>
            <td>${escapeHtml(u.name)}</td>
            <td>${escapeHtml(u.email)}</td>
            <td>${escapeHtml(u.created_at)}</td>
            <td><button class="delete-btn" data-id="${u.id}">Delete</button></td>
        </tr>
    `).join('');

    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', async () => {
            if (!confirm('Delete this user?')) return;
            await apiPost('admin_users.php', { delete: btn.dataset.id });
            loadUsers();
        });
    });
}
loadUsers();
