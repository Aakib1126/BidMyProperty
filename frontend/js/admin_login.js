document.getElementById('adminLoginForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const form = e.target;
    const data = { email: form.email.value, password: form.password.value };
    const result = await apiPost('admin_login.php', data);
    if (result.success) {
        alert(result.message || 'Login successful!');
        window.location.href = 'admin_dashboard.html';
    } else {
        alert(result.message || 'Invalid email or password.');
    }
});
