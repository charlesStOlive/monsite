
$(window).on('ajaxInvalidField', function(event, fieldElement, fieldName, errorMsg, isFirst) {
    $(fieldElement).closest('.form-group').addClass('is-invalid');

});

$(document).on('ajaxPromise', '[data-request]', function() {
    $(this).closest('form').find('.form-group.is-invalid').removeClass('is-invalid');
});