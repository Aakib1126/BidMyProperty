async function loadProperties() {
    const listEl = document.getElementById('propertyList');
    const result = await apiGet('userbid.php');
    if (!result.success || result.properties.length === 0) {
        listEl.innerHTML = '<p>No properties available for bidding right now.</p>';
        return;
    }

    listEl.innerHTML = result.properties.map(p => `
        <div class="property">
            <h3>${escapeHtml(p.title)}</h3>
            <p>${escapeHtml(p.description)}</p>
            <p><strong>Current Highest Bid:</strong> $${escapeHtml(p.highest_bid)}</p>
            <form class="bid-form" data-id="${p.id}">
                <input type="number" name="bid_amount" placeholder="Enter bid amount" required>
                <button type="submit">Place Bid</button>
            </form>
        </div>
    `).join('');

    document.querySelectorAll('.bid-form').forEach(form => {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const result = await apiPost('userbid.php', {
                place_bid: '1',
                property_id: form.dataset.id,
                bid_amount: form.bid_amount.value
            });
            alert(result.message);
            if (result.success) loadProperties();
        });
    });
}

loadProperties();
