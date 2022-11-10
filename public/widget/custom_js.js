$(document).ready(function () {
    initPreloader();
});
function initPreloader() {    
    $('#status').fadeOut();
    $('#preloader').delay(350).fadeOut('slow');    
}
function reinitPreloader() {
    $('#status').fadeIn();
    $('#preloader').delay(350).fadeIn('slow');    
}
function showMessage(type = "info", message = "") {
    $.toast({
        heading: message,
        position: {
            right: 15,
            top: 30
        },
        loaderBg: '#ff6849',
        icon: type,
        hideAfter: 3500,
        stack: 6
    })
}
function showMessageBottom(type = "info", message = "") {
    $.toast({
        heading: message,
        position: {
            right: 15,
            bottom: 75
        },
        loaderBg: '#ff6849',
        icon: type,
        hideAfter: 3500,
        stack: 6
    })
}