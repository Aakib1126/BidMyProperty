document.getElementById('paymentNote').textContent =
    'Note: payment processing requires Stripe/PayPal SDKs and real API keys to be configured on the backend (see backend/api/payment.php).';

document.getElementById('paymentForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const form = e.target;
    const data = { amount: form.amount.value, payment_method: form.payment_method.value };
    const result = await apiPost('payment.php', data);
    if (result.success) {
        alert(result.message || 'Payment Successful!');
        if (result.redirect_url) {
            window.location.href = result.redirect_url;
        } else {
            window.location.href = 'dashboard.html';
        }
    } else {
        alert(result.message || 'Payment failed.');
    }
});
