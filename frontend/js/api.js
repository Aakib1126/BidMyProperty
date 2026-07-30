// Shared helpers for talking to the PHP JSON API.
// All frontend pages live in frontend/pages/*.html, so the API is two
// folders up and then into backend/api/.
const API_BASE = '../../backend/api/';
const UPLOADS_BASE = '../../backend/uploads/';

/**
 * POST form-style data (like a normal HTML form submit) to an API endpoint
 * and get back parsed JSON.
 */
async function apiPost(endpoint, data) {
    const body = new URLSearchParams(data);
    const res = await fetch(API_BASE + endpoint, {
        method: 'POST',
        credentials: 'same-origin',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body
    });
    return res.json();
}

/** POST a FormData object (used for file uploads) to an API endpoint. */
async function apiPostForm(endpoint, formData) {
    const res = await fetch(API_BASE + endpoint, {
        method: 'POST',
        credentials: 'same-origin',
        body: formData
    });
    return res.json();
}

/** GET an API endpoint (optionally with query params) and get back parsed JSON. */
async function apiGet(endpoint, params) {
    let url = API_BASE + endpoint;
    if (params) {
        url += '?' + new URLSearchParams(params).toString();
    }
    const res = await fetch(url, { credentials: 'same-origin' });
    return res.json();
}

/** Escape text before inserting into innerHTML, to avoid breaking layout/XSS. */
function escapeHtml(str) {
    if (str === null || str === undefined) return '';
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
}
