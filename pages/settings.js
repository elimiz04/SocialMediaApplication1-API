// After handling the form submission and receiving the updated color scheme
fetch('update_settings.php', {
    method: 'POST',
    body: formData
})
.then(response => response.text()) // Change response type to text
.then(data => {
    // Check if data is valid color scheme ('light' or 'dark')
    if (data === 'light' || data === 'dark') {
        // Apply the color scheme to the entire application
        const body = document.body;
        body.classList.remove('light-mode', 'dark-mode'); // Remove existing classes
        body.classList.add(data + '-mode'); // Add new class based on color scheme
        // Update color scheme in the current page
        updatePageColorScheme(data);
    } else {
        console.error('Invalid color scheme:', data);
    }
})
.catch(error => {
    console.error('Error:', error);
});

// Function to update color scheme in the current page
function updatePageColorScheme(colorScheme) {
    // Add logic here to update specific elements or styles on the page based on the color scheme
}
