async function loadReports() {
    const result = await apiGet('admin_reports.php');
    if (!result.success) {
        alert(result.message || 'Please log in as admin.');
        window.location.href = 'admin_login.html';
        return;
    }
    document.getElementById('totalUsers').textContent = result.total_users;
    document.getElementById('totalProperties').textContent = result.total_properties;
    document.getElementById('totalBids').textContent = result.total_bids;
    document.getElementById('totalRevenue').textContent = result.total_revenue;
}
loadReports();
