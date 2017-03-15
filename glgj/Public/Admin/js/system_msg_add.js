$(function() {
    $('.send_to_type').change(function() {
        if ( $('#preson').prop('checked') ) {
            $('#to_user').show();
        } else {
            $('#to_user').hide().find('input').val('');
        }
    });
});