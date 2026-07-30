async function loadMyProperties() {
    const listEl = document.getElementById('propertyList');
    const result = await apiGet('my_uploaded_properties.php');

    if (!result.success) {
        listEl.innerHTML = `<p>${escapeHtml(result.message || 'Could not load properties.')}</p>`;
        return;
    }
    if (result.properties.length === 0) {
        listEl.innerHTML = '<p>You have not uploaded any properties yet.</p>';
        return;
    }

    listEl.innerHTML = result.properties.map(p => `
        <div class="property-card">
            <h3>${escapeHtml(p.title)}</h3>
            <p><strong>Price:</strong> $${escapeHtml(p.current_price)}</p>
            <p><strong>Status:</strong> ${escapeHtml((p.status || '').charAt(0).toUpperCase() + (p.status || '').slice(1))}</p>
            ${p.images.length ? `<img src="${UPLOADS_BASE}images/${encodeURIComponent(p.images[0])}" alt="Property Image" width="200">` : ''}
            ${p.status === 'available' ? `
                <form class="mark-sold-form" data-id="${p.id}">
                    <button type="submit">Mark as Sold</button>
                </form>` : ''}
        </div>
    `).join('');

    document.querySelectorAll('.mark-sold-form').forEach(form => {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            await apiPost('my_uploaded_properties.php', { mark_sold: '1', property_id: form.dataset.id });
            loadMyProperties();
        });
    });
}

loadMyProperties();
