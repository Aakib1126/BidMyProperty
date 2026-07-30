document.getElementById('adminRegisterForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const form = e.target;
    const data = {
        name: form.name.value,
        email: form.email.value,
        password: form.password.value,
        security_question: form.security_question.value,
        security_answer: form.security_answer.value
    };
    const result = await apiPost('admin_register.php', data);
    alert(result.message);
    if (result.success) window.location.href = 'admin_login.html';
});
