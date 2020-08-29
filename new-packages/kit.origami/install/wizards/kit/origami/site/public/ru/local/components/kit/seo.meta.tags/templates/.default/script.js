BX.ready(function () {
    $('document').ready(function(){
        $('.kit-seometa-tags__hide').click(function(){
            $('.kit-seometa-tags-container').toggleClass("tags-container-hide");
            $('.seometa-tags__hide').toggle();
            $('.seometa-tags__show').toggle();

        });
    });
});