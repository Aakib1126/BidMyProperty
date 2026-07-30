renderNav('navContainer');

const params = new URLSearchParams(window.location.search);
const propertyId = params.get('id');

let sessionInfo = null;

async function loadProperty() {
    const contentEl = document.getElementById('propertyContent');

    if (!propertyId) {
        alert('Invalid property!');
        window.location.href = 'view_properties.html';
        return;
    }

    sessionInfo = await apiGet('session_status.php');
    const result = await apiGet('property_details.php', { id: propertyId });

    if (!result.success) {
        alert(result.message || 'Property not found!');
        window.location.href = 'view_properties.html';
        return;
    }

    const p = result.property;

    const imagesHtml = p.images.length ? `
        <div class="property-images">
            ${p.images.map(img => `<img src="${UPLOADS_BASE}images/${encodeURIComponent(img)}" alt="Property Image" width="300">`).join('')}
        </div>` : '';

    const videosHtml = p.videos.length ? `
        <div class="property-videos">
            ${p.videos.map(v => `
                <video width="400" controls>
                    <source src="${UPLOADS_BASE}videos/${encodeURIComponent(v)}" type="video/mp4">
                    Your browser does not support the video tag.
                </video>`).join('')}
        </div>` : '';

    const bidFormHtml = (sessionInfo.logged_in && sessionInfo.role === 'user') ? `
        <h3>Place a Bid</h3>
        <form id="bidForm">
            <input type="number" name="bid_amount" placeholder="Enter bid amount" required>
            <button type="submit">Place Bid</button>
        </form>` : '';

    contentEl.innerHTML = `
        ${imagesHtml}
        ${videosHtml}
        <div class="property-details">
            <h2>${escapeHtml(p.title)}</h2>
            <p><strong>Description:</strong> ${escapeHtml(p.description)}</p>
            <p><strong>Price:</strong> $${escapeHtml(p.current_price)}</p>
            <p><strong>Highest Bid:</strong> $<span id="highest_bid">${escapeHtml(p.highest_bid)}</span></p>
            <p><strong>Category:</strong> ${escapeHtml((p.category || '').charAt(0).toUpperCase() + (p.category || '').slice(1))}</p>
            ${bidFormHtml}
        </div>
    `;

    const bidForm = document.getElementById('bidForm');
    if (bidForm) {
        bidForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const bidAmount = bidForm.bid_amount.value;
            const result = await apiPost('property_details.php', {
                place_bid: '1',
                property_id: propertyId,
                bid_amount: bidAmount
            });
            alert(result.message);
            if (result.success) {
                document.getElementById('highest_bid').textContent = result.new_bid;
                bidForm.reset();
            }
        });
    }
}

// Poll for the latest highest bid every 5 seconds (replaces the original jQuery polling)
async function refreshHighestBid() {
    const el = document.getElementById('highest_bid');
    if (!el || !propertyId) return;
    try {
        const result = await apiGet('property_details.php', { id: propertyId });
        if (result.success) el.textContent = result.property.highest_bid;
    } catch (e) { /* ignore transient errors */ }
}

loadProperty();
setInterval(refreshHighestBid, 5000);
