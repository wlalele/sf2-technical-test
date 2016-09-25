$(document).ready(function() {

    $('form#search-form').on('submit', function( event ) {
        event.preventDefault();

        var username = document.querySelector('input#username');
        if (username.value.length > 0) {
            window.location = username.value + '/comment';
        }
    });

});