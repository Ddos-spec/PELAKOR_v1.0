$(document).ready(function () {
    $('.sidenav').sidenav();
});

document.addEventListener('DOMContentLoaded', function () {
    var elems = document.querySelectorAll('.slider');
    var instances = M.Slider.init(elems, options);
});

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
            // Process data
            console.log(data);
        })
        .finally(() => {
            hideLoading();
        });
}

// Event listener for button clicks
document.querySelector('button').addEventListener('click', function() {
    showLoading();
    // Simulate an action
    setTimeout(() => {
        hideLoading();
        alert('Action completed!');
    }, 2000);
});
