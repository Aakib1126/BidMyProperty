document.getElementById('logoutLink').addEventListener('click', async (e) => {
    e.preventDefault();
    await apiPost('logout.php', {});
    window.location.href = 'index.html';
});

async function loadDashboard() {
    const result = await apiGet('dashboard.php');
    if (!result.success) {
        alert(result.message || 'Please log in first.');
        window.location.href = 'login.html';
        return;
    }

    document.getElementById('userName').textContent = result.user.name;
    document.getElementById('userEmail').textContent = result.user.email;

    document.getElementById('bidHistoryBody').innerHTML = result.bids.map(b => `
        <tr>
            <td>${escapeHtml(b.title)}</td>
            <td>$${escapeHtml(b.bid_amount)}</td>
            <td>${escapeHtml(b.bid_time)}</td>
            <td>${escapeHtml(b.status)}</td>
        </tr>
    `).join('');

    document.getElementById('watchlistBody').innerHTML = result.watchlist.map(w => `
        <tr>
            <td>${escapeHtml(w.title)}</td>
            <td>$${escapeHtml(w.current_price)}</td>
        </tr>
    `).join('');
}

loadDashboard();
