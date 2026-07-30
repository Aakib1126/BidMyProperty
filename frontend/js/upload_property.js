document.getElementById('uploadForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const formData = new FormData(e.target);
    const result = await apiPostForm('upload_property.php', formData);
    alert(result.message);
    if (result.success) window.location.href = 'dashboard.html';
});
