function renderReviews(reviews) {
    const listEl = document.getElementById('reviewsList');
    if (!reviews || reviews.length === 0) {
        listEl.innerHTML = '<p>No reviews yet. Be the first to leave one!</p>';
        return;
    }
    listEl.innerHTML = reviews.map(r => `
        <div class="review-box">
            <h3>${escapeHtml(r.name)}</h3>
            <p class="rating">Rating: ${'⭐'.repeat(parseInt(r.rating, 10) || 0)}</p>
            <p>${escapeHtml(r.message)}</p>
            <span class="date">${escapeHtml(new Date(r.created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' }))}</span>
        </div>
    `).join('');
}

async function loadReviews() {
    const result = await apiPost('review.php', {}); // GET-only load: POST with no fields just returns the list
    if (result.success) renderReviews(result.reviews);
}

document.getElementById('reviewForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const form = e.target;
    const data = {
        name: form.name.value,
        email: form.email.value,
        rating: form.rating.value,
        message: form.message.value
    };
    const result = await apiPost('review.php', data);
    alert(result.message);
    if (result.success) {
        form.reset();
        renderReviews(result.reviews);
    }
});

loadReviews();
