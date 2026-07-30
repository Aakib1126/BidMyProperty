async function loadProperties() {
    const container = document.getElementById('propertyContainer');
    const result = await apiGet('real-timebidding.php');
    if (!result.success) {
        container.innerHTML = `<p>${escapeHtml(result.message || 'Could not load properties.')}</p>`;
        return;
    }

    container.innerHTML = result.properties.map(p => `
        <div class="property">
            <h3>${escapeHtml(p.title)}</h3>
            <p>Current Highest Bid: $<span id="bid-${p.id}">${escapeHtml(p.highest_bid)}</span></p>
            <input type="number" id="input-${p.id}" placeholder="Enter bid amount">
            <button data-id="${p.id}" class="place-bid-btn">Place Bid</button>
        </div>
    `).join('');

    document.querySelectorAll('.place-bid-btn').forEach(btn => {
        btn.addEventListener('click', async () => {
            const propertyId = btn.dataset.id;
            const bidAmount = document.getElementById('input-' + propertyId).value;
            const result = await apiPost('real-timebidding.php', {
                place_bid: '1',
                property_id: propertyId,
                bid_amount: bidAmount
            });
            if (result.success) {
                document.getElementById('bid-' + propertyId).textContent = result.new_bid;
            } else {
                alert(result.message);
            }
        });
    });
}

async function refreshBids() {
    const result = await apiGet('fetch_bids.php');
    if (Array.isArray(result)) {
        result.forEach(property => {
            const el = document.getElementById('bid-' + property.id);
            if (el) el.textContent = property.highest_bid;
        });
    }
}

loadProperties();
setInterval(refreshBids, 3000);
