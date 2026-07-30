document.getElementById('verifyEmailForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const form = e.target;
    const result = await apiPost('recovery.php', { email: form.email.value, verify_email: '1' });
    if (result.success) {
        document.getElementById('securityQuestionText').textContent = result.security_question;
        document.getElementById('verifyAnswerForm').style.display = 'block';
    } else {
        alert(result.message);
    }
});

document.getElementById('verifyAnswerForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const form = e.target;
    const data = {
        security_answer: form.security_answer.value,
        new_password: form.new_password.value,
        verify_answer: '1'
    };
    const result = await apiPost('recovery.php', data);
    alert(result.message);
    if (result.success) window.location.href = 'login.html';
});
