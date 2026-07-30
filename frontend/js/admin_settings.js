async function loadSettings() {
    const result = await apiGet('admin_settings.php');
    if (!result.success) {
        alert(result.message || 'Please log in as admin.');
        window.location.href = 'admin_login.html';
        return;
    }
    const form = document.getElementById('settingsForm');
    form.name.value = result.admin.name;
    form.email.value = result.admin.email;
}

document.getElementById('settingsForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const form = e.target;
    const data = { name: form.name.value, email: form.email.value, password: form.password.value };
    const result = await apiPost('admin_settings.php', data);
    alert(result.message);
});

loadSettings();
