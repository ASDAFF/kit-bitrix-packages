$(document).ready(function() {

    $(".inline-search-show, .inline-search-hide").on("click", function () {
        if(window.matchMedia("(min-width: 768px)").matches) {
            $(".inline-search-block").toggleClass("show");

            if ($(".inline-search-block").hasClass("show"))
                $(".overlay-search").addClass("active");
            else {
                $(".overlay-search").removeClass("active");
                $(".title-search-result").hide(); // for quick closing
            }
        }
        else {
            location.href = searchTitleOptions["CATALOG_PAGE_URL"] + "?q";
        }
    });

});
