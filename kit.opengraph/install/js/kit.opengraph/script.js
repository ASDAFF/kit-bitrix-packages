$(document).ready(function () {

    $('.add_exception').click(function(){
        var tr = $(this).closest('tr').prev('tr').clone();
        tr.attr('id', 'tr_EXCEPTION_URL_NEW['+$('[id*="tr_EXCEPTION_URL_NEW"]').size()+']');
        tr.find('input').val('');
        $(this).closest('tr').before(tr);
    });

});