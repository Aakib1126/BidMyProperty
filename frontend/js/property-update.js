document.getElementById('propertyUpdateForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const formData = new FormData(e.target);
    formData.append('add_property', '1');
    const result = await apiPostForm('property-update.php', formData);
    if (result.success) {
        alert(result.message);
        window.location.href = 'view_properties.html';
    } else {
        alert(result.message || 'Error adding property.');
    }
});
