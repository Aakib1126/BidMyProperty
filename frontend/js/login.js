document.getElementById('loginForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const form = e.target;
    const data = {
        email: form.email.value,
        password: form.password.value
    };
    const result = await apiPost('login.php', data);
    if (result.success) {
        alert(result.message || 'Login successful!');
        window.location.href = 'index.html';
    } else {
        alert(result.message || 'Invalid email or password.');
    }
});
