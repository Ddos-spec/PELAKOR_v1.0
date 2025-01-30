$(document).ready(function () {
    $('.sidenav').sidenav();
});

document.addEventListener('DOMContentLoaded', function () {
    var elems = document.querySelectorAll('.slider');
    var options = {
        // Define your options here
        duration: 500,
        height: 400,
        indicators: true,
        interval: 6000
    };
    var instances = M.Slider.init(elems, options);
});