(function(window, document, $, undefined)
{
	var id="";
	window.Blocks = function(arParams) {
		this.block = arParams.block;
		this.tab = arParams.tab;
		this.close = arParams.close;
		this.part = arParams.part;
		this.page = arParams.page;
		this.page = arParams.page;
		this.site = arParams.site;
		this.siteTemplate = arParams.siteTemplate;
		this.button = '.blocks-wrapper .landing-ui-button';
		this.btnSwitchSave = arParams.btnSwitchSave;
		this.btnSwitchOn = arParams.btnSwitchOn;
		this.btnSwitchOff = arParams.btnSwitchOff;
		this.destroy();
		this.init();
	}
	window.Blocks.prototype.destroy = function() {

	}
	window.Blocks.prototype.init = function()
	{
		$(document).on("click", this.tab, this, this.clickTab);
		$(document).on("click", this.block, this, this.clickBlock);
		$(document).on("click", this.close, this, this.clickClose);
		$(document).on("click", this.button, this, this.clickButton);
		$(document).on("click", this.btnSwitchSave, this, this.switchSave);

		$(document).on("click", this.btnSwitchOn, this, this.switchConstOn);
		$(document).on("click", this.btnSwitchOff, this, this.switchConstOff);
	}

	window.Blocks.prototype.switchConstOn = function (e)
	{
		var _this = e.data;

		var container = $('body');
		createMainLoader(container);

		$("#switch-constructor").addClass("hide__constructor-switch");
		$("#switch-constructor__save").removeClass("hide__constructor-switch");
		$("#switch-constructor__off").removeClass("hide__constructor-switch");


		$('#toggle-switches').addClass('switch-on');

		setTimeout(function () {
			const content_action = $('.site-builder__hide div[data-id="content_actions"]');
			const block_action = $('.site-builder__hide div[data-id="block_action"]');
			const create_action = $('.site-builder__hide div[data-id="create_action"]');

			create_action.show();
			block_action.css("display", "inline-flex");
			content_action.css("display", "inline-flex");

			$('#blocks_' + _this.part).removeClass('site-builder__hide');
			$('#blocks_' + _this.part).addClass('site-builder__show');
			removeMainLoader();

			BX.setCookie('origamiConstructMode', "Y");


		}, 1000);

	};

	window.Blocks.prototype.switchConstOff = function (e)
	{
		var _this = e.data;

		var container = $('body');
		createMainLoader(container);

		$("#switch-constructor").removeClass("hide__constructor-switch");
		$("#switch-constructor__save").addClass("hide__constructor-switch");
		$("#switch-constructor__off").addClass("hide__constructor-switch");

		$('#blocks_' + _this.part).addClass('site-builder__hide');
		$('#blocks_' + _this.part).removeClass('site-builder__show');

		const content_action = $('.site-builder__hide div[data-id="content_actions"]');
		const block_action = $('.site-builder__hide div[data-id="block_action"]');
		const create_action = $('.site-builder__hide div[data-id="create_action"]');

		setTimeout(function () {
			create_action.css("display", "none");
			block_action.css("display", "none");
			content_action.css("display", "none");
			removeMainLoader();

			BX.setCookie('origamiConstructMode', "N");

		}, 1000);
	};

	window.Blocks.prototype.clickButton = function(e)
	{
		e.preventDefault();
		_this = e.data;

		var action = $(this).data('id');
		_this.id = $(this).closest('.block-wrapper').data('id');

		switch (action)
		{
			case 'insert_after':
				$('.landing-ui-panel-block-list:not("#theme-panel")').removeClass('landing-ui-hide').addClass('landing-ui-show').removeAttr('hidden');
                $('.landing-ui-panel-block-list:not("#theme-panel")').addClass('landing-ui-show');
                $('.overlay').addClass('overlay-show');
				break;
			case 'down':
				if($('.block-wrapper').last().data('id') != _this.id)
				{
					$.ajax({
						type: 'POST',
						url: '/local/components/sotbit/block.include/ajax.php',
						data: {
							'action':'down',
							'part': _this.part,
							'site':_this.site,
							'page':_this.page,
							'id': _this.id
						},
						success: function(data) {
							if(data == '1')
							{
								var k = -1;
								var block;
								$('.block-wrapper').each(function(i,elem)
								{
									if($(elem).data('id') == _this.id)
									{
										block = $(elem);
										k = i+1;
									}

									if(k == i)
									{
										$(elem).after(block);
									}
								});
							}
						},
					});
				}
				break;
			case 'up':
				if($(this).closest('.block-wrapper').index() != 1 )
				{
					$.ajax({
						type: 'POST',
						url: '/local/components/sotbit/block.include/ajax.php',
						data: {
							'action': 'up',
							'part': _this.part,
							'page': _this.page,
							'site': _this.site,
							'id': _this.id
						},
						success: function (data)
						{
							if (data == '1')
							{
								var k = -1;
								var block;
								$('.block-wrapper').each(function (i, elem)
								{
									if ($(elem).data('id') == _this.id)
									{
										block = $(elem);
										k = i - 1;
									}
								});
								$('.block-wrapper').each(function (i, elem)
								{
									if (k == i)
									{
										$(elem).before(block);
									}
								});
							}

						},
					});
				}
				break;
			case 'remove':
				$.ajax({
					type: 'POST',
					url: '/local/components/sotbit/block.include/ajax.php',
					data: {
						'action':'remove',
						'part': _this.part,
						'page':_this.page,
						'site':_this.site,
						'id': _this.id
					},
					success: function(data) {
						if(data == '1')
						{
							$('.block-wrapper[data-id="'+_this.id+'"]').remove();
							$('.landing-ui-panel-style[data-id="'+_this.id+'"]').remove();
						}
						else
						{
							$('.block-wrapper[data-id="'+_this.id+'"]').remove();
							$('.landing-ui-panel-style[data-id="'+_this.id+'"]').remove();
							$('#blocks_' + _this.part).html(data);
						}
					},
				});
				break;
			case 'content':
				$('.block-wrapper[data-id="'+_this.id+'"] .landing-ui-panel-content-edit').removeClass('landing-ui-hide');
				$('.block-wrapper[data-id="'+_this.id+'"] .landing-ui-panel-content-edit').removeClass('landing-ui-hidden');
				$('.block-wrapper[data-id="'+_this.id+'"] .landing-ui-panel-content-edit').addClass('landing-ui-show');
                $('.overlay').addClass('overlay-show');
                break;
			case 'save_block_content':
				var formData = $('#'+_this.id).serialize();
				$.ajax({
					type: 'POST',
					url: '/local/components/sotbit/block.include/ajax.php',
					data: {
						'id': _this.id,
						'action':'save_block_content',
						'part': _this.part,
						'page':_this.page,
						'site':_this.site,
						'data': formData
					},
					success: function(data) {
						$('.block-wrapper[data-id="'+_this.id+'"]').after(data);
						$('.block-wrapper[data-id="'+_this.id+'"]:first').remove();
					},
                });
                $('.overlay').removeClass('overlay-show');
				break;
			case 'cancel_block_content':
				$('.landing-ui-panel').addClass('landing-ui-hide');
                $('.landing-ui-panel').removeClass('landing-ui-show');
                $('.overlay').removeClass('overlay-show');
				break;
			case 'collapse':
				$('.block-wrapper[data-id="'+_this.id+'"]').toggleClass('landing-ui-collapse');
				break;
			case 'actions':
				var menu = [];

				if($('.block-wrapper[data-id="'+_this.id+'"] .block-wrapper-inner').hasClass('landing-block-disabled'))
				{
					menu.push({
						text: BX.message('show'),
						title: BX.message('show'),
						onclick: 'show_block(_this.id);'
					});
				}
				else
				{
					menu.push({
						text: BX.message('hide'),
						title: BX.message('hide'),
						onclick: 'hide_block(_this.id);'
					});
				}
				menu.push({
					text: BX.message('cut'),
					title: BX.message('cut'),
					onclick:'cut_block("'+_this.id+'");'
				});
				menu.push({
					text: BX.message('copy'),
					title: BX.message('copy'),
					onclick:'copy_block("'+_this.id+'");'
				});
				menu.push({
					text: BX.message('paste'),
					title: BX.message('paste'),
					onclick:'paste_block("'+_this.id+'","'+_this.part+'");'
				});

				var params = {
					offsetLeft: 20,
					closeByEsc: true,
					angle: {
						position: 'top'
					},
				}
				var popupMenu = new BX.PopupMenuWindow(
					"actions_"+_this.id,
					BX("actions_"+_this.id),
					menu,
					params
				);

				popupMenu.popupWindow.show();
				break;
			case 'style':
				if($('.landing-ui-panel-style[data-id="'+_this.id+'"]').length)
				{
					$('.landing-ui-panel-style[data-id="'+_this.id+'"]').removeClass('landing-ui-hide');
					$('.landing-ui-panel-style[data-id="'+_this.id+'"]').removeClass('landing-ui-hidden');
					$('.landing-ui-panel-style[data-id="'+_this.id+'"]').addClass('landing-ui-show');
				}
				else
				{
					var copy = $(this).closest('.block-wrapper').data('copyof');
					$('.landing-ui-panel-style[data-id="'+copy+'"]').removeClass('landing-ui-hide');
					$('.landing-ui-panel-style[data-id="'+copy+'"]').removeClass('landing-ui-hidden');
					$('.landing-ui-panel-style[data-id="'+copy+'"]').addClass('landing-ui-show');
				}
                $('.overlay').addClass('overlay-show');
                break;
			case 'l-d-lg-none':
			case 'l-d-md-none':
			case 'l-d-xs-none':
				$(this).toggleClass('landing-ui-active');
				var id = $(this).closest('.landing-ui-panel-style').data('id');
				$.ajax({
					type: 'POST',
					url: '/local/components/sotbit/block.include/ajax.php',
					data: {
						'id': id,
						'action':action,
						'part': _this.part,
						'page':_this.page,
						'site':_this.site,
						'siteTemplate':_this.siteTemplate,
					},
					success: function(data) {
						$('.block-wrapper[data-id="'+id+'"]').after(data);
						$('.block-wrapper[data-id="'+id+'"]:first').remove();
					},
				});
				break;
			case 'save':
				$.ajax({
					type: 'POST',
					url: '/local/components/sotbit/block.include/ajax.php',
					data: {
						'action':action,
						'part': _this.part,
						'page':_this.page,
						'site':_this.site,
						'page':_this.page,
					},
					success: function(data) {
						location.reload();
					},
				});
			case 'settingsTab':
				$('.settings-config-block').removeClass('settings-config-block-show').addClass('settings-config-block-hide');
				$('.settings-config-block[data-code="'+$(this).data('code')+'"]').addClass('settings-config-block-show').removeClass('settings-config-block-hide');
		}
	};
	window.Blocks.prototype.clickBlock = function(e)
	{
		e.preventDefault();
		_this = e.data;
		$.ajax({
			type: 'POST',
			url: '/local/components/sotbit/block.include/ajax.php',
			data: {
				'after': _this.id,
				'code': $(this).data('code'),
				'action':'add',
				'page':_this.page,
				'part': _this.part,
				'site':_this.site,
				'siteTemplate':_this.siteTemplate,
			},
			success: function(data) {
				if(_this.id)
				{
					$('.block-wrapper[data-id="'+_this.id+'"]').after(data);
				}
				else
				{
					$('#blocks_'+_this.part).html(data);
					_this.id = $('.block-wrapper').eq(0).data('id');
				}
			},
		});
	};

	window.Blocks.prototype.switchSave = function(e)
	{
        _this = e.data;
        var container = $('body');
        createMainLoader(container);
		// $('body').append(`
		// 	<div class="loader">
		// 		<div class="inner">
		// 		</div>
		// 	</div>
		// `);

		$.ajax({
			type: 'POST',
			url: '/local/components/sotbit/block.include/ajax.php',
			data: {
				'action':'save',
				'part': _this.part,
				'page':_this.page,
				'site':_this.site,
				'page':_this.page,
			},
			success: function(data) {
                removeMainLoader();
				// $(".loader .inner").fadeOut(1000, function() {
				// 	$('.loader').fadeOut(100);
				// });
			},
		});
	};

	window.Blocks.prototype.clickClose = function(e)
	{
		$('.landing-ui-panel').addClass('landing-ui-hide');
        $('.landing-ui-panel').removeClass('landing-ui-show');
        $('.overlay').removeClass('overlay-show');
	};
	window.Blocks.prototype.clickTab = function(e)
	{
		let section = $(this).data('id');
		$('.landing-ui-card1').each(function()
		{
			if($(this).data('section') == section)
			{
				$(this).removeClass('landing-ui-card-block-noactive');
			}
			else
			{
				$(this).addClass('landing-ui-card-block-noactive');
			}
		})
	};

})(window, document, jQuery);




var block = '';

function hide_block(id)
{
	$.ajax({
		type: 'POST',
		url: '/local/components/sotbit/block.include/ajax.php',
		data: {
			'id': id,
			'action':'hide_block',
			'part': _this.part,
			'site':_this.site,
			'page':_this.page,
			'siteTemplate':_this.siteTemplate,
		},
		success: function(data) {
			$('.popup-window').remove();
			$('.block-wrapper[data-id="'+id+'"]').after(data);
			$('.block-wrapper[data-id="'+id+'"]:first').remove();
		},
	});
}
function show_block(id)
{
	$.ajax({
		type: 'POST',
		url: '/local/components/sotbit/block.include/ajax.php',
		data: {
			'id': id,
			'action':'show_block',
			'part': _this.part,
			'page':_this.page,
			'site':_this.site,
			'siteTemplate':_this.siteTemplate,
		},
		success: function(data) {
			$('.popup-window').remove();
			$('.block-wrapper[data-id="'+id+'"]').after(data);
			$('.block-wrapper[data-id="'+id+'"]:first').remove();
		},
	});
}
function copy_block(id)
{
	block = id;
	$('.popup-window').remove();
}
function paste_block(id,part)
{
	$('.popup-window').remove();
	if(block)
	{
		$.ajax({
			type: 'POST',
			url: '/local/components/sotbit/block.include/ajax.php',
			data: {
				'id': block,
				'after': id,
				'action': 'paste',
				'part': part,
				'page':_this.page,
				'site':_this.site,
				'siteTemplate':_this.siteTemplate,
			},
			success: function (data)
			{
				$('.block-wrapper[data-id="'+id+'"]').after(data);
			},
		});
	}
}
function cut_block(id)
{
	$('.popup-window').remove();
	block = id+'_cut';
	$('.block-wrapper[data-id="'+id+'"]').remove();
}


// ----------------lazyLoad------------------------
/**
    @attr class="lazy"                                      ==> tag img for lazyLoad
    @attr src="/upload/sotbit.origami/no_photo_medium.svg"  ==> img default(const)
    @attr data-src="image-to-lazy-load-1x.jpg"              ==> img load
    @attr data-srcset="image-to-lazy-load-2x.jpg 2x         ==> alternative image

    example ==>
    <img class="lazy" src="placeholder-image.jpg" data-src="image-to-lazy-load-1x.jpg" data-srcset="image-to-lazy-load-2x.jpg 2x, image-to-lazy-load-1x.jpg 1x" alt="I'm an image!"></img>
*/
window.lazyLoadUiOn = function () {

    let uiPanel = document.querySelector('.blocks_panel');

    let observer = new MutationObserver (function () {
        let lazyImages = [].slice.call(document.querySelectorAll('.landing-ui-panel-content-body-content > .landing-ui-card-block-preview'));
        let lazyBlockImagesVisible = lazyImages.filter(function (item) {
            let styles = getComputedStyle(item);
            if(styles.display !== 'none') {
                return true;
            };
        });
        let lazyImagesVisible = lazyBlockImagesVisible.map(function (item) {
            return item.querySelector('.lazy-ui');
        });

        const lazyLoad = function() {
            setTimeout(function() {
                lazyImagesVisible.forEach(function(lazyImage) {
                if ((lazyImage && lazyImage.getBoundingClientRect().top <= window.innerHeight && lazyImage.getBoundingClientRect().bottom >= 0) && getComputedStyle(lazyImage).display !== "none") {
                    lazyImage.src = lazyImage.dataset.src;
                    if (lazyImage.dataset.srcset) {
                        lazyImage.srcset = lazyImage.dataset.srcset;
                    }
                    lazyImage.classList.remove("lazy-ui");
                    if(lazyImage.nextElementSibling && lazyImage.nextElementSibling.classList.contains('loader-lazy')) {
                        lazyImage.nextElementSibling.parentElement.removeChild(lazyImage.nextElementSibling);
                        }
                    lazyImages = lazyImages.filter(function(image) {
                    return image !== lazyImage;
                    });

                    if (lazyImages.length === 0) {
                    document.removeEventListener("scroll", lazyLoad);
                    window.removeEventListener("resize", lazyLoad);
                    window.removeEventListener("orientationchange", lazyLoad);
                    }
                }
                });

                active = false;
            }, 200);
        };
        lazyLoad();

        document.addEventListener("scroll", lazyLoad);
        window.addEventListener("resize", lazyLoad);
        window.addEventListener("orientationchange", lazyLoad);
    });

    observer.observe(uiPanel, {
        childList: true,
        subtree: true,
        attributes: true
    });

};

document.addEventListener("DOMContentLoaded", function() {
    let panelUI = document.querySelector('.blocks_panel');
    if(!!panelUI){
        window.lazyLoadUiOn();
    };
});
// ----------------end lazyLoad---------------------=---
