BX.ready(function () {
    $('document').ready(function(){
        $('.sotbit-seometa-tags__hide').click(function(){
            $('.sotbit-seometa-tags-container').toggleClass("tags-container-hide");
            $('.seometa-tags__hide').toggle();
            $('.seometa-tags__show').toggle();

        });
    });
});