async function loadStats() {
    const result = await apiGet('admin_dashboard.php');
    if (!result.success) {
        alert(result.message || 'Please log in as admin.');
        window.location.href = 'admin_login.html';
        return;
    }
    document.getElementById('userCount').textContent = result.user_count;
    document.getElementById('propertyCount').textContent = result.property_count;
    document.getElementById('bidCount').textContent = result.bid_count;
}
loadStats();
