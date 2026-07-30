async function loadPending() {
    const body = document.getElementById('pendingBody');
    const result = await apiGet('admin_properties.php');
    if (!result.success) {
        alert(result.message || 'Please log in as admin.');
        window.location.href = 'admin_login.html';
        return;
    }
    if (result.properties.length === 0) {
        body.innerHTML = '<tr><td colspan="4">No pending properties.</td></tr>';
        return;
    }
    body.innerHTML = result.properties.map(p => `
        <tr>
            <td>${escapeHtml(p.title)}</td>
            <td>${escapeHtml(p.user_id)}</td>
            <td>$${escapeHtml(p.current_price)}</td>
            <td>
                <button class="button approve-btn" data-id="${p.id}">Approve</button>
                <button class="button delete" data-id="${p.id}">Reject</button>
            </td>
        </tr>
    `).join('');

    document.querySelectorAll('.approve-btn').forEach(btn => {
        btn.addEventListener('click', async () => {
            await apiPost('admin_properties.php', { approve: btn.dataset.id });
            loadPending();
        });
    });
    document.querySelectorAll('.delete').forEach(btn => {
        btn.addEventListener('click', async () => {
            if (!confirm('Reject and delete this property?')) return;
            await apiPost('admin_properties.php', { delete: btn.dataset.id });
            loadPending();
        });
    });
}
loadPending();
