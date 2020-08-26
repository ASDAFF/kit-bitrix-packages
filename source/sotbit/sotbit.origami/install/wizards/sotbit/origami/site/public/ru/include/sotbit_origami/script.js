//-------------------------------------

var $topBar;
var $button;
var $visibleLinks;
var $hiddenLinks;
var responsiveBreaks; // Empty List (Array) on initialization

var $topBar2;
var $button2;
var $visibleLinks2;
var $hiddenLinks2;
var responsiveBreaks2; // Empty List (Array) on initialization


window.onresize = function(event) {

	if ( $(window).width() < 576 ) {
		ChangePhoneMobile();
		ChangeCouponMobile();
		titleChangeMobile();
	}
	else {
		titleChangeDevice();
		ChangePhoneDevice();
		ChangeCouponAllDevice();
	}
	if ( $(window).width() < 992 ) {
		ChangePriceMobile();
	}
	else {
		ChangePriceDevice();
	}
	if ($(window).width() < 768) {
		galleryMoreItems(2);
		ChangePhoneMobileDetail();
		ChangeRegions(true);
		//
		previewProductMobileNot();
	}
	else {
		galleryMoreItems(5);
		ChangePhoneDeviceDetail();
		ChangeRegions(false);
	}
	//equivalent();

	if ( $(window).width() > 768 ) {
		updateTopBar($topBar, $button, $visibleLinks, $hiddenLinks, responsiveBreaks);
		updateTopBar($topBar2, $button2, $visibleLinks2, $hiddenLinks2, responsiveBreaks2);
	}
};

$(document).ready(function() {
	$(".hover").mouseleave(
		function () {
			$(this).removeClass("hover");
		}
	);

	$topBar = $('.category-main-menu');
	$button = $('.category-main-menu .responsive-hidden-button');
	$visibleLinks = $('.category-main-menu .visible-links');
	$hiddenLinks = $('.category-main-menu .hidden-links');
	responsiveBreaks = []; // Empty List (Array) on initialization

	$topBar2 = $('.category-menu');
	$button2 = $('.category-menu .responsive-hidden-button');
	$visibleLinks2 = $('.category-menu .visible-links');
	$hiddenLinks2 = $('.category-menu .hidden-links');
	responsiveBreaks2 = []; // Empty List (Array) on initialization

	// Window listeners
	$button.on('click', function() {
		$hiddenLinks.toggleClass('hidden');
	});

	$button2.on('click', function() {
		$hiddenLinks2.toggleClass('hidden');
	});

	if ( $(window).width() > 768 ) {
		updateTopBar($topBar, $button, $visibleLinks, $hiddenLinks, responsiveBreaks);
		updateTopBar($topBar2, $button2, $visibleLinks2, $hiddenLinks2, responsiveBreaks2);
	}


	if ( $(window).width() < 768 ) {
		ChangeBrandMobile();
		galleryMoreItems(2);
		ChangePhoneMobileDetail();
		ChangeRegions(true);
	}
	else {
		ChangeBrandAllDevice();
		galleryMoreItems(5);
		ChangePhoneDeviceDetail();
		ChangeRegions(false);
	}

	if ( $(window).width() < 576 ) {
		ChangeNewsMobile();
		ChangeCouponMobile();
		ChangePhoneMobile();
		titleChangeMobile();
	}
	else {
		titleChangeDevice();
		ChangePhoneDevice();
		ChangeNewsAllDevice();
		ChangeCouponAllDevice();
	}

	if ( $(window).width() < 992 ) {
		ChangePriceMobile();
	}
	else {
		ChangePriceDevice();
	}

	// slider
	CaruselPhone();
	CaruselBrand();
	CaruselProductVariant();
	// CaruselProduct();
	CaruselCanvas();
	CaruselCanvasAllWidth();
	CaruselProductAdvice();
	CaruselProductGift();
	CaruselPhotoBlogDetail();
	CaruselPhotoPromotionDetail();
	// end slider
	MobileBasket();

	TransitionDetail();

	orderOpenProperty();
	// Mobile menu
	changeMobileMenu();
	$("#menu").mmenu({
		"extensions": [
			"pagedim-black"
		]
	});
	// end Mobile menu

	//equivalent();

	//header phone
	allPhone();

	//celect
	MainSelect();





});

// BX.addCustomEvent('onAjaxSuccess', function(){
//     MainSelect();
// });

function previewProductMobileNot() {
	if($("div").is(".main_info_preview_product")) {
		$(".main_info_preview_product").closest(".wrap-popup-window").hide();
	}
}

/// top menu

function updateTopBar($topBar, $button, $visibleLinks, $hiddenLinks, responsiveBreaks) {

	var availableSpace = $button.hasClass('hidden') ? $topBar.width() : $topBar.width() - $button.width() - 30; // Calculation of available space on the logic of whether button has the class `hidden` or not
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
			updateTopBar($topBar, $button, $visibleLinks, $hiddenLinks, responsiveBreaks);
			//     updateTopBar($topBar2, $button2, $visibleLinks2, $hiddenLinks2, responsiveBreaks2);
		}

		//Hide the resonsive hidden button if list is empty
		if(responsiveBreaks.length < 1) {
			$button.addClass('hidden');
			$hiddenLinks.addClass('hidden');
		}
	}

	$button.attr("count", responsiveBreaks.length); // Keeping counter updated

	if($visibleLinks.width() > availableSpace && window.screen.width>=768) { // Occur again if the visible list is still overflowing the nav
		updateTopBar($topBar, $button, $visibleLinks, $hiddenLinks, responsiveBreaks);

	}
}

/// top menu end

var count_2 = 5;

// ----- catalog galery ----- ///
function galleryMoreItems(count2){


	var size_li_2 = $(".catalog_content__category_block .catalog_content__category_item").length;
	$('.catalog_content__category_block .catalog_content__category_item').css("display","none");
	$('.catalog_content__category_block .catalog_content__category_item:lt('+count_2+')').show();
	$('#loadMore').click(function () {
		count_2 = (count_2+count_2 <= size_li_2) ? count_2+count_2 : size_li_2;
		$('.catalog_content__category_block .catalog_content__category_item:lt('+count_2+')').show();
		if(size_li_2===count_2){
			$("#loadMore").remove();
		};
	});
	if($("div").is('#loadMore2')){
		if(size_li_2<=count_2){
			$("#loadMore").remove();
		};
	};
	return;
};
/// ----- end catalog galery ----- ///


/// menu
// function showMmenu() {
//
// }
/// end menu

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

// order Mobile open property
function orderOpenProperty() {
	$(".main_order_block__item .main_order_block__item_slide_open" ).click(function() {
		$(this).parent().find(".main_order_block__item_slide").toggleClass("active");
		$(this).toggleClass("active");
	});
}
// end order Mobile open property

// ----- owl-carousel phone----- //
function CaruselPhone() {
	$('.carousel-phone').owlCarousel({
		stopOnHover: true,
		loop:true,
		items:1,
		nav:true,
		navText : ["",""],
		autoplay:false,
		smartSpeed:500,
	});
}
// -----end owl-carousel phone----- //

// ----- owl-carousel slider-canvas-all-width ----- //
function CaruselCanvasAllWidth() {
	$('.slider-canvas-all-width').owlCarousel({
		stopOnHover: true,
		loop:true,
		items:1,
		nav:true,
		navText : ["",""],
		autoplay:false,
		smartSpeed:500 
	});
}
// ----- end owl-carousel slider-canvas-all-width ----- //

// ----- variant brand_block ----- //
function CaruselBrand() {
	$('.brand_block_variant').owlCarousel({
		stopOnHover: true,
		loop:true,
		margin: 20,
		nav:true,
		navText : ["",""],
		smartSpeed:500,
		autoplayTimeout:2000,

		responsive:{
			0:{
				items:1
			},
			500:{
				items:3
			},
			1000:{
				items:5
			}
		}
	});
}
// ----- end variant brand_block ----- //

// ----- owl-carousel product ----- //
function CaruselProductVariant() {
	$('.slider-product_variant').owlCarousel({
		stopOnHover: true,
		loop:true,
		nav:true,
		navText : ["",""],
		smartSpeed:500,
		autoplayTimeout:2000,
		responsive:{
			0:{
				items:1
			},
			600:{
				items:2
			},
			1000:{
				items:4
			}
		}
	});
}

function CaruselPhotoBlogDetail()
{
	$('.blog-detail__gallery-items').owlCarousel({
		stopOnHover: true,
		loop:true,
		nav:true,
		navText : ["",""],
		smartSpeed:500,
		autoplayTimeout:2000,
		responsive:{
			0:{
				items:1
			},
			600:{
				items:2
			},
			1000:{
				items:4
			}
		}
	});
}
function CaruselPhotoPromotionDetail()
{/*
	$('.tabs_block__product_card-owl').owlCarousel({
		stopOnHover: true,
		loop:true,
		nav:true,
		navText : ["",""],
		//autoplay:true,
		smartSpeed:500, 
		autoplayTimeout:2000,
		responsive:{
			0:{
				items:1
			},
			600:{
				items:2
			},
			1000:{
				items:5
			}
		}
	});*/
}

function CaruselProductAdvice() {
	$('.detail-product-advice').owlCarousel({
		stopOnHover: true,
		loop:true,
		nav:true,
		navText : ["",""],
		smartSpeed:500,
		autoplayTimeout:2000,
		responsive:{
			0:{
				items:1
			},
			600:{
				items:3
			},
			1000:{
				items:5
			}
		}
	});
}

function CaruselProductGift() {
	$('.detail-product-gift').owlCarousel({
		stopOnHover: true,
		loop:true,
		nav:true,
		navText : ["",""],
		smartSpeed:500,
		autoplayTimeout:2000,
		responsive:{
			0:{
				items:1
			},
			600:{
				items:3
			},
			1000:{
				items:5
			}
		}
	});
}

function CaruselProduct() {
	$('.slider-product').owlCarousel({
		stopOnHover: true,
		loop:true,
		nav:true,
		navText : ["",""],
		smartSpeed:500,
		autoplayTimeout:2000,

		responsive:{
			0:{
				items:1
			},
			600:{
				items:2
			},
			1000:{
				items:3
			}
		}
	});
}
// ----- end owl-carousel product ----- //

// ----- owl-carousel main canvas ----- //
function CaruselCanvas() {
	$('.slider-canvas').owlCarousel({
		stopOnHover: true,
		loop:true,
		items:1,
		nav:true,
		navText : ["",""],
		autoplay:false,
		smartSpeed:500,
	});
}
// ----- owl-carousel end main canvas ----- //

function ChangeBrandMobile() {
	$(".brand_block").addClass("brand_block_variant owl-carousel");
	$(".brand_block_variant_two").addClass("brand_block_variant owl-carousel");
	$(".brand_block_variant_two").removeClass("row");
}
function ChangeBrandAllDevice() {
	$(".brand_block").removeClass("brand_block_variant owl-carousel");
	$(".brand_block_variant_two").removeClass("brand_block_variant owl-carousel");
	$(".brand_block_variant_two").addClass("row");
}

function ChangeNewsMobile() {
	$(".news_block").addClass("carousel-phone owl-carousel");
	$(".news_block_three").addClass("carousel-phone owl-carousel");

}
function ChangeNewsAllDevice() {
	$(".news_block").removeClass("carousel-phone owl-carousel");
	$(".news_block_three").removeClass("carousel-phone owl-carousel");

}

// ChangeCoupon
function ChangeCouponMobile() {
	$( ".main_order_coupon" ).prependTo( ".main_order" );
}
function ChangeCouponAllDevice() {
	$( ".main_order_coupon" ).appendTo( ".main_order" );
}
// end ChangeCoupon

// ChangePrice
function ChangePriceMobile() {
	$( ".main_order_all_price" ).prependTo( ".main_order" );
}
function ChangePriceDevice() {
	$( ".main_order_coupon" ).appendTo( ".main_order" );
}
// end ChangePrice


// ChangePhoneMobile
function ChangePhoneMobile() {
	$( ".container_menu_mobile__phone_block" ).appendTo( ".header_mmenu__phone_block_hidden" );
}

function ChangePhoneDevice() {
	$( ".container_menu_mobile__phone_block" ).prependTo( ".header_mmenu__content_phone_and_basket" );
}
// end ChangePrice


// ChangePhoneMobile
function ChangePhoneMobileDetail() {
	$( ".product-detail-info-block-comment" ).prependTo( ".product-detail-photo-block" );
	$( ".product-detail-info-block-title" ).prependTo( ".product-detail-photo-block" );
	$(".product-detail-info-block-brand").prependTo( ".article-mobile-block" );
	$(".product-detail-share-block").appendTo(".product-detail-info-block-one-click-basket");
	//$(".slider-ditail-card").addClass("slider-product_variant owl-carousel");
}

function ChangePhoneDeviceDetail() {
	$( ".product-detail-info-block-comment" ).prependTo( ".detail-title-block" );
	$( ".product-detail-info-block-title" ).prependTo( ".detail-title-block" );
	$(".product-detail-info-block-brand").prependTo( "#right_detail_card" );
	$(".product-detail-share-block").prependTo( ".product-detail-share" );
	//$(".slider-ditail-card").removeClass("slider-product_variant owl-carousel");
}



function TransitionDetail() {
	$("#all_property").on("click",".block-basic-property-link", function (event) {
		event.preventDefault();
		var id  = $(this).attr('href'),
			top = $(id).offset().top;
		$('body,html').animate({scrollTop: top}, 1500);
	});
}


function copyUrl()
{
	var dummy = document.createElement('input'),
		text = window.location.href;

	document.body.appendChild(dummy);
	dummy.value = text;
	dummy.select();
	document.execCommand('copy');
	document.body.removeChild(dummy);
}

function showShares()
{
	$('#sharing-buttons').toggleClass('active');
}

function showModal(html)
{
	var block = '<div class="wrap-popup-window">' +
		'<div class="modal-popup-bg" onclick="closeModal();">&nbsp;</div>' +
		'<div class="popup-window">' +
		'<div class="popup-close" onclick="closeModal();"></div>'+
		'<div class="popup-content">';
	block = block + html;
	block = block +'</div>'
	'</div>' +
	'</div>';
	$("body").append(block);
	fixBody();
}
function closeModal() {
	BX.onCustomEvent('OnBasketChange');
	$('.wrap-popup-window').last().remove();
	unfixBody();
}

function fixBody() {
	// document.body.setAttribute("class", "body-fixed")
}

function unfixBody() {
	// if (document.body.hasAttribute("class")) {
	// 	document.body.setAttribute("class", "");
	// }
}


function allPhone() {
	if($(".header_top_block__phone__title span").is(".many_tels_wrapper")) {
		$(".header_top_block__phone__number").addClass("icons");
	}
}


function titleChangeMobile() {
	$( "div.personal_title_block" ).appendTo( ".sidebar" );
}

function titleChangeDevice() {
	$( "div.personal_title_block" ).appendTo( ".personal_block_component" );
}

//personal_title_block

function callbackPhone(siteDir, lid)
{
	$.ajax({
		url: siteDir+'include/ajax/callbackphone.php',
		type: 'POST',
		data:{'lid':lid},
		success: function(html)
		{
			showModal(html);
		}
	});
}

function foundCheaper(siteDir, lid, name)
{
    $.ajax({
        url: siteDir + 'include/ajax/foundcheaper.php',
        type: 'POST',
        data: {
            'lid': lid,
            'name': name
		},
        success: function(html)
        {
            showModal(html);
        }
    });
}

function wantGift(siteDir, lid, name, url, img, price, oldPrice)
{
    $.ajax({
        url: siteDir + 'include/ajax/wantgift.php',
        type: 'POST',
        data: {
            'lid': lid,
            'name': name,
            'url': url,
            'img': img,
            'price': price,
            'oldPrice': oldPrice
		},
        success: function(html)
        {
            showModal(html);
        }
    });
}

function checkStock(siteDir, lid, name)
{
    $.ajax({
        url: siteDir + 'include/ajax/checkstock.php',
        type: 'POST',
        data: {
            'lid': lid,
            'name': name
		},
        success: function(html)
        {
            showModal(html);
        }
    });
}

function MobileBasket()
{
	let cntBasket = $('.basket-block.header_info_block__item .basket-block__link_main_basket .basket-block__link_basket_cal').html();
	let cntCompare = $('.basket-block.header_info_block__item .basket-block__link:eq(1) .basket-block__link_basket_cal').html();
	let cntFavorite = $('.basket-block.header_info_block__item .basket-block__link:eq(2) .basket-block__link_basket_cal').html();

	$('.container_menu_mobile__item_link:eq(1) .container_menu_mobile__item_link_col').html(cntCompare);
	$('.container_menu_mobile__item_link:eq(2) .container_menu_mobile__item_link_col').html(cntFavorite);
	$('.container_menu_mobile__item_link:eq(3) .container_menu_mobile__item_link_col').html(cntBasket);
	$('.header_mmenu__content_phone_and_basket .basket-block__link_basket_cal').html(cntBasket);
}


function quickView(url)
{
	BX.showWait();
	let add = '?preview=Y';
	let location = window.location.href;
	if(location.indexOf('clear_cache=Y') !== false)
	{
		add+='&clear_cache=Y';
	}
	url += add;

	$.ajax({
		url: url,
		type: 'POST',
		data:{'sessid': BX.bitrix_sessid()},
		success: function(html)
		{   
			showModal(html);
		}
	});
	BX.closeWait();
}

function ChangeRegions(isMobile)
{
	if(isMobile)
	{
		var Regions = $('.header_info_block .header_info_block__block_region').html();
		if(Regions.length > 0)
		{
			$('#mobileRegion').html(Regions);
		}
	}
	else
	{
		var Regions = $('#mobileRegion').html();
		if(Regions.length > 0)
		{
			$('.header_info_block .header_info_block__block_region').html(Regions);
		}
	}
}


 function MainSelect() {
// 	$(".custom-select-block").each(function() {
// 		if(!$(this).parent('.custom-select-wrapper').find('div.custom-select-block').length)
// 		{
// 			var classes = $(this).attr("class"),
// 				id = $(this).attr("id"),
// 				name = $(this).attr("name");
//
// 			var name = $(this).find('option:selected').html();
//
// 			$(this).attr('placeholder', name);
// 			let placeholder = $(this).data('placeholder');
// 			if(placeholder === undefined){
// 				placeholder = $(this).attr("placeholder");
// 			}
// 			let option = $(this).find("option:selected");
// 			if(option !== undefined){
// 				placeholder = option.html();
// 			}
// 			var template = '<div class="' + classes + '">';
// 			template += '<span class="custom-select-trigger">' + placeholder + '</span>';
// 			template += '<div class="custom-options">';
// 			$(this).find("option").each(function ()
// 			{
// 				template += '<span class="custom-option ' + $(this).attr("class") + '" data-value="' + $(this).attr("value") + '">' + $(this).html() + '</span>';
// 			});
// 			template += '</div></div>';
//
// 			$(this).wrap('<div class="custom-select-wrapper"></div>');
// 			$(this).hide();
// 			$(this).after(template);
// 		}
// 	});
// 	$(".custom-option:first-of-type").hover(function() {
// 		$(this).parents(".custom-options").addClass("option-hover");
// 	}, function() {
// 		$(this).parents(".custom-options").removeClass("option-hover");
// 	});
// 	$(".custom-select-trigger").on("click", function() {
// 		$('html').one('click',function() {
// 			$(".custom-select-block").removeClass("opened");
// 		});
// 		$(this).parents(".custom-select-block").toggleClass("opened");
// 		event.stopPropagation();
// 	});
// 	$(".custom-option").on("click", function() {
// 		$(this).parents(".custom-options").find(".custom-option").removeClass("selection");
// 		$(this).addClass("selection");
// 		$(this).parents(".custom-select-block").removeClass("opened");
// 		$(this).parents(".custom-select-block").find(".custom-select-trigger").text($(this).text());
// 		$(this).parents(".custom-select-wrapper").find("select").val($(this).data("value"));
// 		$(this).parents(".custom-select-wrapper").find("select").trigger("change");
// 	});
//

	 $(".custom-select-block").each(function() {
		 if(!$(this).parent('.custom-select-wrapper').find('div.custom-select-block').length)
		 {
			 var classes = $(this).attr("class"),
				 id = $(this).attr("id"),
				 name = $(this).attr("name");

			 var name = $(this).find('option:selected').html();

			 $(this).attr('placeholder', name);
			 let placeholder = $(this).data('placeholder');
			 if(placeholder === undefined){
				 placeholder = $(this).attr("placeholder");
			 }
			 let option = $(this).find("option:selected");
			 if(option !== undefined){
				 placeholder = option.html();
			 }
			 var template = '<div class="' + classes + '">';
			 template += '<span class="custom-select-trigger">' + placeholder + '</span>';
			 template += '<div class="custom-options">';
			 $(this).find("option").each(function ()
			 {
				 template += '<span class="custom-option ' + $(this).attr("class") + '" data-value="' + $(this).attr("value") + '">' + $(this).html() + '</span>';
			 });
			 template += '</div></div>';

			 $(this).wrap('<div class="custom-select-wrapper"></div>');
			 $(this).hide();
			 $(this).after(template);
		 }
	 });
	 $(".custom-option:first-of-type").hover(function() {
		 $(this).parents(".custom-options").addClass("option-hover");
	 }, function() {
		 $(this).parents(".custom-options").removeClass("option-hover");
	 });
	 $(".custom-select-trigger").on("click", function(event) {
		 $('html').one('click',function() {
			 $(".custom-select-block").removeClass("opened");
		 });
		 $(this).parents(".custom-select-block").toggleClass("opened");
		 event.stopPropagation();
	 });
	 $(".custom-option").on("click", function() {
		 $(this).parents(".custom-options").find(".custom-option").removeClass("selection");
		 $(this).addClass("selection");
		 $(this).parents(".custom-select-block").removeClass("opened");
		 $(this).parents(".custom-select-block").find(".custom-select-trigger").text($(this).text());
		 $(this).parents(".custom-select-wrapper").find("select").val($(this).data("value"));
		 $(this).parents(".custom-select-wrapper").find("select").trigger("change");
	 });


 }
