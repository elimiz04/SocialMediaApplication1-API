document.addEventListener('DOMContentLoaded', function() {
    const appContainer = document.getElementById('app-container');
    const colorSchemeSelect = document.getElementById('color_scheme');
    const settingsForm = document.querySelector('.settings-form');

    // Function to handle form submission
    function handleFormSubmit(event) {
        event.preventDefault(); // Prevent the default form submission

        const formData = new FormData(settingsForm); // Get form data
        const xhr = new XMLHttpRequest(); // Create new XMLHttpRequest object

        xhr.open('POST', settingsForm.action); // Set up POST request
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest'); // Set X-Requested-With header for AJAX request

        xhr.onload = function() {
            if (xhr.status === 200) {
                // Update UI based on the response
                const response = JSON.parse(xhr.responseText);
                if (response.success) {
                    const colorScheme = formData.get('color_scheme');
                    if (colorScheme === 'dark') {
                        appContainer.classList.add('dark-mode');
                        appContainer.classList.remove('light-mode');
                    } else {
                        appContainer.classList.add('light-mode');
                        appContainer.classList.remove('dark-mode');
                    }
                } else {
                    alert('Failed to update settings.'); 
                }
            } else {
                alert('Failed to update settings. Please try again later.'); 
            }
        };

        xhr.onerror = function() {
            alert('Failed to update settings. Please try again later.'); 
        };

        xhr.send(formData); 
    }

    settingsForm.addEventListener('submit', handleFormSubmit);

    // Initialize color scheme based on the initial value of the select element
    handleColorSchemeChange();

    // Function to handle color scheme change
    function handleColorSchemeChange() {
        const colorScheme = colorSchemeSelect.value;
        if (colorScheme === 'dark') {
            appContainer.classList.add('dark-mode');
            appContainer.classList.remove('light-mode');
        } else {
            appContainer.classList.add('light-mode');
            appContainer.classList.remove('dark-mode');
        }
    }

    // Listen for change event on color scheme select
    colorSchemeSelect.addEventListener('change', handleColorSchemeChange);
});
