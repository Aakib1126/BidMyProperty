renderNav('navContainer');

async function loadProperties() {
    const listEl = document.getElementById('propertyList');
    const result = await apiGet('view_properties.php');

    if (!result.success || result.properties.length === 0) {
        listEl.innerHTML = '<p>No properties available right now.</p>';
        return;
    }

    listEl.innerHTML = result.properties.map(p => `
        <div class="property">
            <h3><a href="property_details.html?id=${encodeURIComponent(p.id)}">${escapeHtml(p.title)}</a></h3>
            <p><strong>Price:</strong> $${escapeHtml(p.current_price)}</p>
            <p><strong>Highest Bid:</strong> $${escapeHtml(p.highest_bid)}</p>
            ${p.images && p.images.length ? `<img src="${UPLOADS_BASE}images/${encodeURIComponent(p.images[0])}" alt="Property Image" width="200">` : ''}
        </div>
    `).join('');
}

loadProperties();
