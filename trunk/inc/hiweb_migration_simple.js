jQuery(document).ready(function($){

    $.ajax({
        url: wp_ajax_url,
        success: function(response){
            if(response.hasOwnProperty('success')) {
                if(response.success) {
                    console.info('reload...');
                    $('[data-button-reload]').show('slow');
                    location.reload();
                } else {
                    alert(response.data);
                }
            } else {
                alert('Unknown error...');
            }
            console.info(response);
        },
        error: function(response){
            $('body').append(response.response);
        }
    });

});