
document.getElementById('loginForm').addEventListener('submit', async function (event) {
    event.preventDefault();
    const formData = new FormData(event.target);
    const response = await fetch('login.php', {
        method: 'POST',
        body: formData
    });
    const result = await response.json();
    if (result.success) {
        window.location.href = result.redirect;
    } else {
        alert('Error: ' + result.error);
    }
});