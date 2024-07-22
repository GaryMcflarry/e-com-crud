document.addEventListener('DOMContentLoaded', function() {
    // Check if we're on the specific page
    if (window.location.pathname === '/shopping-cart.php') {
        // Get all forms with the class 'add-form'
        const forms = document.querySelectorAll('form[id^="update-form-"]');
        // Loop through each form and attach event listener
        forms.forEach(function(form) {
            form.addEventListener('submit', function(event) {
                event.preventDefault(); // Prevent the default form submission

                // Capture form data
                const formData = new FormData(form);

                // Loop through form data entries
                for (const entry of formData.entries()) {
                    const fieldName = entry[0]; // Get the name of the form field
                    const fieldValue = entry[1]; // Get the value of the form field
                    
                    if (fieldName == 'counter' && fieldValue != null) {
                        fetch('server/update_cart_items.php', {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.text())
                        .then(data => {
                            console.log(data); // You can process the response here
                            // Optionally, you can refresh part of the page or update the UI dynamically
                            alert('Item updated successfully');
                        })
                        .catch(error => console.error('Error:', error));
                    }
                }
            });
        });
    }
});