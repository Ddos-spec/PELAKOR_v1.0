console.log('Script loaded'); // Debugging log

const button = document.querySelector('button[name="gantiPassword"]'); // Use a more specific selector
console.log('Button found:', button); // Debugging log

if (button) {
    button.addEventListener('click', function() {
        showLoading();
        setTimeout(() => {
            hideLoading();
            alert('Action completed!');
        }, 2000);
    });
} else {
    console.error('Button element not found');
}

// Function to show loading feedback
function showLoading() {
    const loadingIndicator = document.createElement('div');
    loadingIndicator.innerText = 'Loading...';
    loadingIndicator.className = 'loading-indicator';
    document.body.appendChild(loadingIndicator);
}

// Function to hide loading feedback
function hideLoading() {
    const loadingIndicator = document.querySelector('.loading-indicator');
    if (loadingIndicator) {
        loadingIndicator.remove();
    }
}

// Example of using the loading feedback in an AJAX request
function fetchData() {
    showLoading();
    fetch('/api/data')
        .then(response => response.json())
        .then(data => {
            console.log(data);
        })
        .catch(error => {
            console.error('Error fetching data:', error);
        })
        .finally(() => {
            hideLoading();
        });
}