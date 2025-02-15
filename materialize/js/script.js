document.addEventListener('DOMContentLoaded', function() {
    // Initialize sidenav with passive listeners
    var elems = document.querySelectorAll('.sidenav');
    var instances = M.Sidenav.init(elems, {
        draggable: true,
        preventScrolling: true,
        onOpenStart: function() {
            document.body.style.overflow = 'hidden';
        },
        onCloseEnd: function() {
            document.body.style.overflow = '';
        }
    });

    // Initialize slider if used
    var sliders = document.querySelectorAll('.slider');
    var instances = M.Slider.init(sliders, {
        indicators: true,
        height: 400,
        duration: 500,
        interval: 6000
    });
});