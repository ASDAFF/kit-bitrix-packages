var $topBarMain;
var $buttonMain;
var $visibleLinksMain;
var $hiddenLinksMain;
var responsiveBreaksMain; // Empty List (Array) on initialization

function updateTopBarMain($topBarMain, $buttonMain, $visibleLinksMain, $hiddenLinksMain, responsiveBreaksMain) {

    var availableSpace = $buttonMain.hasClass('hidden') ? $topBarMain.width() : $topBarMain.width() - $buttonMain.width() - 25; // Calculation of available space on the logic of whether button has the class `hidden` or not
    //alert($topBar.width());
    if($visibleLinksMain.width() > availableSpace && window.screen.width>=768) { // Logic when visible list is overflowing the nav

        responsiveBreaksMain.push($visibleLinksMain.width()); // Record the width of the list
        $visibleLinksMain.children().last().prependTo($hiddenLinksMain); // Move item to the hidden list

        // Show the resonsive hidden button
        if($buttonMain.hasClass('hidden')) {
            $buttonMain.removeClass('hidden');
        }

    } else { // Logic when visible list is not overflowing the nav
        if(availableSpace > responsiveBreaksMain[responsiveBreaksMain.length-1]) { // Logic when there is space for another item in the nav
            $hiddenLinksMain.children().first().appendTo($visibleLinksMain);
            responsiveBreaksMain.pop(); // Move the item to the visible list
            if(responsiveBreaksMain.length < 1) {
                $buttonMain.addClass('hidden');
            }
        }
        //Hide the resonsive hidden button if list is empty
        if(responsiveBreaksMain.length < 1) {
            $buttonMain.addClass('hidden');
        }
    }

    $buttonMain.attr("count", responsiveBreaksMain.length); // Keeping counter updated
    if($visibleLinksMain.width() > availableSpace && window.screen.width>=768) { // Occur again if the visible list is still overflowing the nav
        updateTopBar($topBarMain, $buttonMain, $visibleLinksMain, $hiddenLinksMain, responsiveBreaksMain);
    }

}

$( window ).resize(function() {
    if ( $(window).width() > 768 ) {
        updateTopBarMain($topBarMain, $buttonMain, $visibleLinksMain, $hiddenLinksMain, responsiveBreaksMain);
    }
});


/// change mobile menu
function changeMobileMenu() {
    $( ".category-main-menu .visible-links .visible-links__item" ).clone().appendTo( "#container_menu_mobile" );
    $( ".header_top_block__menu_wrapper .visible-links__item" ).clone().appendTo( ".container_menu_mobile__list_wrapper" );
    $( "#container_menu_mobile .visible-links__item" ).removeClass();
    $( "#container_menu_mobile .category_link__active_content" ).removeClass();
    $( "#container_menu_mobile .category_link__active_content_item" ).removeClass();
    $( "#container_menu_mobile .category_link__active_content_item_link" ).removeClass();
    $( ".container_menu_mobile__list_wrapper .visible-links__item" ).removeClass();
    $( ".container_menu_mobile__list_wrapper .category_link__active_content" ).removeClass();
    $( ".container_menu_mobile__list_wrapper .category_link__active_content_item" ).removeClass();
    $( ".container_menu_mobile__list_wrapper .category_link__active_content_item_link" ).removeClass();
    $( ".container_menu_mobile__list_wrapper li" ).addClass("container_menu_mobile__list_li");
    $( ".container_menu_mobile__list_wrapper li a" ).addClass("container_menu_mobile__list_link");
}
/// end change mobile menu


$(document).ready(function() {
    changeMobileMenu();
});

$( window ).resize(function() {
    $('.mm-page__blocker.mm-slideout').mousedown();
});