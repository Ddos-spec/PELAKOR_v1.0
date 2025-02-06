// Initialize when document is ready
$(document).ready(function() {
    // Initialize sidenav
    $('.sidenav').sidenav();
    
    // Initialize slider
    $('.slider').slider({
        duration: 500,
        height: 400,
        indicators: true,
        interval: 6000 // Add auto-slide interval
    });

    // Initialize other Materialize components if needed
    $('.dropdown-trigger').dropdown();
    $('.modal').modal();
    $('.tooltipped').tooltip();
});

// Add passive event listeners for better scroll performance
const passiveEvents = ['touchstart', 'touchmove', 'scroll', 'wheel'];
passiveEvents.forEach(eventType => {
    document.addEventListener(eventType, function(event) {
        // Event handling logic here
    }, { passive: true });
});
