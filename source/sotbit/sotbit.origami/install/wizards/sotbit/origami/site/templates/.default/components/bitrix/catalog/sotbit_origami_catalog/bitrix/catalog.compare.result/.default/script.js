$( document ).ready(function() {

    var sliderElem = document.getElementById('slider_compare');
    var sliderTable = document.getElementById('table_compare');
    var sliderTableInfo = document.getElementById('table_compare_info');
    var thumbElem = sliderElem.children[0];
    thumbElem.onmousedown = function(e) {
        var thumbCoords = getCoords(thumbElem);
        var shiftX = e.pageX - thumbCoords.left;
        var sliderCoords = getCoords(sliderElem);

        document.onmousemove = function(e) {
            var newLeft = e.pageX - shiftX - sliderCoords.left;
            if (newLeft < 0) {
                newLeft = 0;
            }
            var rightEdge = sliderElem.offsetWidth - thumbElem.offsetWidth;
            if (newLeft > rightEdge) {
                newLeft = rightEdge;
            }
            thumbElem.style.left = newLeft + 'px';
            sliderTable.style.transform = 'translate(-'+ 2 * newLeft +'px,0)';
            sliderTableInfo.style.transform = 'translate(-'+ 2 * newLeft +'px,0)';
        }

        document.onmouseup = function() {
            document.onmousemove = document.onmouseup = null;
        };

        return false;
    };
    thumbElem.ondragstart = function() {
        return false;
    };

    function getCoords(elem) {
        var box = elem.getBoundingClientRect();

        return {
            top: box.top + pageYOffset,
            left: box.left + pageXOffset
        };

    }

    function MainCompairWidth() {
        var tableWidth = $('#table_width').width();
        var scrollWidth = $('#axis').width();
        var mainHiddenWidth = $(".table_compare_container_im").width();
        var mainVisibleWidth = $(".table_compare_row_product").width();
        var proportion = (mainVisibleWidth - mainHiddenWidth) / 2;
        var mainWidthConteinerScroll = $(".scrooll_line_container").width();
        var mainWidthScroll = mainWidthConteinerScroll - proportion - 5;
        //var mainWidthScroll = tableWidth * 0.6;
        var maxTransform = mainWidthConteinerScroll - mainWidthScroll;
        if( tableWidth < scrollWidth) {
            $('#axis').css({'display' : 'none'});
        }
        //console.log(mainWidthScroll);
        $('.move-right').css({'width' : (mainWidthScroll) + 'px'});
        $(".leftArrow").click(function () {
            $('.table_compare').css({'transform' : 'translate(0px,0)'});
            $('.move-right').css({'left' : '0'});
        });
        $(".rightArrow").click(function () {
            $('.table_compare').css({'transform' : 'translate(-'+ (maxTransform + 15) +'px,0)'});
            $('.move-right').css({'left' : (maxTransform) + 'px'});
        });
    }

    MainCompairWidth();
});

window.onresize = function(event) {

    var sliderElem = document.getElementById('slider_compare');
    var sliderTable = document.getElementById('table_compare');
    var sliderTableInfo = document.getElementById('table_compare_info');
    var thumbElem = sliderElem.children[0];
    thumbElem.onmousedown = function(e) {
        var thumbCoords = getCoords(thumbElem);
        var shiftX = e.pageX - thumbCoords.left;
        var sliderCoords = getCoords(sliderElem);

        document.onmousemove = function(e) {
            var newLeft = e.pageX - shiftX - sliderCoords.left;
            if (newLeft < 0) {
                newLeft = 0;
            }
            var rightEdge = sliderElem.offsetWidth - thumbElem.offsetWidth;
            if (newLeft > rightEdge) {
                newLeft = rightEdge;
            }
            thumbElem.style.left = newLeft + 'px';
            sliderTable.style.transform = 'translate(-'+ 2 * newLeft +'px,0)';
            sliderTableInfo.style.transform = 'translate(-'+ 2 * newLeft +'px,0)';
        }

        document.onmouseup = function() {
            document.onmousemove = document.onmouseup = null;
        };

        return false;
    };
    thumbElem.ondragstart = function() {
        return false;
    };

    function getCoords(elem) {
        var box = elem.getBoundingClientRect();

        return {
            top: box.top + pageYOffset,
            left: box.left + pageXOffset
        };

    }
    function MainCompairWidth() {
        var tableWidth = $('#table_width').width();
        var scrollWidth = $('#axis').width();
        var mainHiddenWidth = $(".table_compare_container_im").width();
        var mainVisibleWidth = $(".table_compare_row_product").width();
        var proportion = (mainVisibleWidth - mainHiddenWidth) / 2;
        var mainWidthConteinerScroll = $(".scrooll_line_container").width();
        var mainWidthScroll = mainWidthConteinerScroll - proportion - 5;
        var maxTransform = mainWidthConteinerScroll - mainWidthScroll;
        if( tableWidth < scrollWidth) {
            $('#axis').css({'display' : 'none'});
        }
        $('.move-right').css({'width' : (mainWidthScroll) + 'px'});
        $(".leftArrow").click(function () {
            $('.table_compare').css({'transform' : 'translate(0px,0)'});
            $('.move-right').css({'left' : '0'});
        });
        $(".rightArrow").click(function () {
            $('.table_compare').css({'transform' : 'translate(-'+ (maxTransform + 15) +'px,0)'});
            $('.move-right').css({'left' : (maxTransform) + 'px'});
        });
    }
    MainCompairWidth();
};


BX.addCustomEvent('onAjaxSuccess', function(){

	var sliderElem = document.getElementById('slider_compare');
    var sliderTable = document.getElementById('table_compare');
    var sliderTableInfo = document.getElementById('table_compare_info');
    var thumbElem = sliderElem.children[0];

    thumbElem.onmousedown = function(e) {
        var thumbCoords = getCoords(thumbElem);
        var shiftX = e.pageX - thumbCoords.left;
        var sliderCoords = getCoords(sliderElem);

        document.onmousemove = function(e) {
            var newLeft = e.pageX - shiftX - sliderCoords.left;
            if (newLeft < 0) {
                newLeft = 0;
            }
            var rightEdge = sliderElem.offsetWidth - thumbElem.offsetWidth;
            if (newLeft > rightEdge) {
                newLeft = rightEdge;
            }
            thumbElem.style.left = newLeft + 'px';
            sliderTable.style.transform = 'translate(-'+ 2 * newLeft +'px,0)';
            sliderTableInfo.style.transform = 'translate(-'+ 2 * newLeft +'px,0)';
        }

        document.onmouseup = function() {
            document.onmousemove = document.onmouseup = null;
        };

        return false;
    };

    thumbElem.ondragstart = function() {
        return false;
    };



    function getCoords(elem) { // кроме IE8-
        var box = elem.getBoundingClientRect();

        return {
            top: box.top + pageYOffset,
            left: box.left + pageXOffset
        };

    }
     function MainCompairWidth() {

         mainHiddenWidth = $(".table_compare_container_im").width();
         mainVisibleWidth = $(".table_compare_row_product").width();
         proportion = (mainVisibleWidth - mainHiddenWidth) / 2;
         mainWidthConteinerScroll = $(".scrooll_line_container").width();
         mainWidthScroll = mainWidthConteinerScroll - proportion - 5;
         maxTransform = mainWidthConteinerScroll - mainWidthScroll;

         $('.move-right').css({'width' : (mainWidthScroll) + 'px'});
         $(".leftArrow").click(function () {
             $('.table_compare').css({'transform' : 'translate(0px,0)'});
             $('.move-right').css({'left' : '0'});
         });
         $(".rightArrow").click(function () {
             $('.table_compare').css({'transform' : 'translate(-'+ (maxTransform + 15) +'px,0)'});
             $('.move-right').css({'left' : (maxTransform) + 'px'});
         });
     }
    // widthLine
    MainCompairWidth();

    function MainCompairWidth() {
        var tableWidth = $('#table_width').width();
        var scrollWidth = $('#axis').width();
        var mainHiddenWidth = $(".table_compare_container_im").width();
        var mainVisibleWidth = $(".table_compare_row_product").width();
        var proportion = (mainVisibleWidth - mainHiddenWidth) / 2;
        var mainWidthConteinerScroll = $(".scrooll_line_container").width();
        var mainWidthScroll = mainWidthConteinerScroll - proportion - 5;
        //var mainWidthScroll = tableWidth * 0.6;
        var maxTransform = mainWidthConteinerScroll - mainWidthScroll;
        if( tableWidth < scrollWidth) {
            $('#axis').css({'display' : 'none'});
        }
        //console.log(mainWidthScroll);
        $('.move-right').css({'width' : (mainWidthScroll) + 'px'});
        $(".leftArrow").click(function () {
            $('.table_compare').css({'transform' : 'translate(0px,0)'});
            $('.move-right').css({'left' : '0'});
        });
        $(".rightArrow").click(function () {
            $('.table_compare').css({'transform' : 'translate(-'+ (maxTransform + 15) +'px,0)'});
            $('.move-right').css({'left' : (maxTransform) + 'px'});
        });
    }

    MainCompairWidth();
});


BX.namespace("BX.Iblock.Catalog");

BX.Iblock.Catalog.CompareClass = (function()
{
    var CompareClass = function(wrapObjId, reloadUrl)
    {
        //console.log(reloadUrl);
        this.wrapObjId = wrapObjId;
        this.reloadUrl = reloadUrl;
        BX.addCustomEvent(window, 'onCatalogDeleteCompare', BX.proxy(this.reload, this));
    };

    CompareClass.prototype.MakeAjaxAction = function(url)
    {
        BX.showWait(BX(this.wrapObjId));
        BX.ajax.post(
            url,
            {
                ajax_action: 'Y'
            },
            BX.proxy(this.reloadResult, this)
        );
    };

    CompareClass.prototype.reload = function()
    {
        BX.showWait(BX(this.wrapObjId));
        BX.ajax.post(
            this.reloadUrl,
            {
                compare_result_reload: 'Y'
            },
            BX.proxy(this.reloadResult, this)
        );
    };

    CompareClass.prototype.reloadResult = function(result)
    {
        BX.closeWait();
        BX(this.wrapObjId).innerHTML = result;
    };

    CompareClass.prototype.delete = function(url)
    {
        BX.showWait(BX(this.wrapObjId));
        BX.ajax.post(
            url,
            {
                ajax_action: 'Y'
            },
            BX.proxy(this.deleteResult, this)
        );
    };

    CompareClass.prototype.deleteProperty = function(url)
    {
        BX.showWait(BX(this.wrapObjId));
        BX.ajax.post(
            url,
            {
                ajax_action: 'Y'
            },
            BX.proxy(this.deleteResult, this)
        );
    };

    CompareClass.prototype.deleteResult = function(result)
    {
        BX.closeWait();
        BX.onCustomEvent('OnCompareChange');
        BX(this.wrapObjId).innerHTML = result;
    };

    return CompareClass;
})();




