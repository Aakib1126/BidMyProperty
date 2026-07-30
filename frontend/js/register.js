document.getElementById('registerForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const form = e.target;
    const data = {
        name: form.name.value,
        email: form.email.value,
        password: form.password.value,
        security_question: form.security_question.value,
        security_answer: form.security_answer.value
    };
    const result = await apiPost('register.php', data);
    alert(result.message);
    if (result.success) window.location.href = 'login.html';
});
