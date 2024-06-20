document.getElementById('projectForm').addEventListener('submit', async function (event) {
    event.preventDefault();
    const formData = new FormData(event.target);
    const response = await fetch('AddProject.php', {
        method: 'POST',
        body: formData
    });
    const result = await response.json();
    if (result.success) {
        alert('Project added successfully');
        // Optionally reset the form
        event.target.reset();
    } else {
        alert('Error: ' + result.error);
    }
});