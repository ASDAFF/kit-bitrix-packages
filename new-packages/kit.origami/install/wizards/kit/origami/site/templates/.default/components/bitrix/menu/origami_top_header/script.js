var $topBar;
var $button;
var $visibleLinks;
var $hiddenLinks;
var responsiveBreaks; // Empty List (Array) on initialization

function updateTopBar($topBar, $button, $visibleLinks, $hiddenLinks, responsiveBreaks) {

    var availableSpace = $button.hasClass('hidden') ? $topBar.width() : $topBar.width() - $button.width() - 25; // Calculation of available space on the logic of whether button has the class `hidden` or not
    //alert($topBar.width());
    if($visibleLinks.width() > availableSpace && window.screen.width>=768) { // Logic when visible list is overflowing the nav

        responsiveBreaks.push($visibleLinks.width()); // Record the width of the list
        $visibleLinks.children().last().prependTo($hiddenLinks); // Move item to the hidden list

        // Show the resonsive hidden button
        if($button.hasClass('hidden')) {
            $button.removeClass('hidden');
        }

    } else { // Logic when visible list is not overflowing the nav
        if(availableSpace > responsiveBreaks[responsiveBreaks.length-1]) { // Logic when there is space for another item in the nav
            $hiddenLinks.children().first().appendTo($visibleLinks);
            responsiveBreaks.pop(); // Move the item to the visible list
            if(responsiveBreaks.length < 1) {
                $button.addClass('hidden');
            }
        }
        //Hide the resonsive hidden button if list is empty
        if(responsiveBreaks.length < 1) {
            $button.addClass('hidden');
        }
    }

    $button.attr("count", responsiveBreaks.length); // Keeping counter updated
    if($visibleLinks.width() > availableSpace && window.screen.width>=768) { // Occur again if the visible list is still overflowing the nav
        updateTopBar($topBar, $button, $visibleLinks, $hiddenLinks, responsiveBreaks);
    }

}

$( window ).resize(function() {
    if ( $(window).width() > 768 ) {
        updateTopBar($topBar, $button, $visibleLinks, $hiddenLinks, responsiveBreaks);
    }
});

