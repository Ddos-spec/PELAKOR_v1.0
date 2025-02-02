$(document).ready(function () {
    $('.sidenav').sidenav();
});

document.addEventListener('DOMContentLoaded', function () {
    var elems = document.querySelectorAll('.slider');
    if (typeof M !== 'undefined' && M.Slider) {
        var instances = M.Slider.init(elems);
    } else {
        console.error('M or M.Slider is not defined');
    }

    // Event listener for button clicks
    const button = document.querySelector('button');
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
            console.log(data);
        })
        .finally(() => {
            hideLoading();
        });
}
