document.getElementById('adminForgotForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const form = e.target;
    const data = {
        email: form.email.value,
        security_question: form.security_question.value,
        security_answer: form.security_answer.value,
        new_password: form.new_password.value
    };
    const result = await apiPost('admin_forgot_password.php', data);
    alert(result.message);
    if (result.success) window.location.href = 'admin_login.html';
});
