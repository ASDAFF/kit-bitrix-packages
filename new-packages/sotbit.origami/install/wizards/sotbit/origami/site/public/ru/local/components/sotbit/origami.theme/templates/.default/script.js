(function (window, document, $, undefined)
{
	window.Themes = function (arParams)
	{
		this.Blocks = window.Blocks;
		this.theme = arParams.theme;
		this.close = arParams.close;
		this.option = arParams.option;
		this.btnSwitchOn = arParams.btnSwitchOn;
		this.btnSwitchOff = arParams.btnSwitchOff;
		this.sidebar = arParams.sidebar;
		this.save = arParams.save;
		this.site = arParams.site;
		this.destroy();
		this.init();
	}
	window.Themes.prototype.destroy = function ()
	{

	}
	window.Themes.prototype.init = function ()
	{
		$(document).on("click", this.theme, this, this.clickTheme);
		$(document).on("click", this.close, this, this.clickClose);
		$(document).on("click", this.option, this, this.clickOption);
		$(document).on("click", this.sidebar, this, this.clickSidebar);
        $(document).on("click", this.save, this, this.clickSave);
        // $(document).on("click", this.option, this, this.test);

        //tuvshin events
		//$(document).on("click", this.btnSwitchOn, this, this.switchConstOn);
		//$(document).on("click", this.btnSwitchOff, this, this.switchConstOff);



        if (!$("#theme-panel").hasClass("landing-ui-hide")) {
            $('.overlay').addClass('overlay-show');
        }

		$(document).on("click", ".overlay", this, this.clickClose);
	}

	window.Themes.prototype.switchConstOn = function (e)
	{
		_this = e.data;

		$("#switch-constructor").addClass("hide__constructor-switch");
		$("#switch-constructor__save").removeClass("hide__constructor-switch");
		$("#switch-constructor__off").removeClass("hide__constructor-switch");


		$('#toggle-switches').addClass('switch-on');
		e.data.clickOption(e, $('#toggle-switches'));

		setTimeout(function () {
			const content_action = $('.site-builder__hide div[data-id="content_actions"]');
			const block_action = $('.site-builder__hide div[data-id="block_action"]');
			const create_action = $('.site-builder__hide div[data-id="create_action"]');

			create_action.show();
			block_action.css("display", "inline-flex");
			content_action.css("display", "inline-flex");

			$('#blocks_main_s1').removeClass('site-builder__hide');
			$('#blocks_main_s1').addClass('site-builder__show');

		}, 1000);

	};

	window.Themes.prototype.switchConstOff = function (e)
	{
		_this = e.data;
		$("#switch-constructor").removeClass("hide__constructor-switch");
		$("#switch-constructor__save").addClass("hide__constructor-switch");
		$("#switch-constructor__off").addClass("hide__constructor-switch");

		$('#blocks_main_s1').addClass('site-builder__hide');
		$('#blocks_main_s1').removeClass('site-builder__show');

		const content_action = $('.site-builder__hide div[data-id="content_actions"]');
		const block_action = $('.site-builder__hide div[data-id="block_action"]');
		const create_action = $('.site-builder__hide div[data-id="create_action"]');

		$('#toggle-switches').removeClass('switch-on');
		e.data.clickOption(e, $('#toggle-switches'));
		setTimeout(function () {
			create_action.css("display", "none");
			block_action.css("display", "none");
			content_action.css("display", "none");

		}, 1000);
	};

	window.Themes.prototype.clickTheme = function (e)
	{

		_this = e.data;
		e.preventDefault();
		$('#theme-panel').removeClass('landing-ui-hide').addClass('landing-ui-show');
		$('#theme-panel').removeAttr('hidden');
		$('.overlay').addClass('overlay-show');

		$.ajax({
			type: 'POST',
			url: '/local/components/sotbit/origami.theme/ajax.php',
			data: {
				'action': 'open',
				'open': 'Y',
				'site': _this.site,
			},
			success: function (data)
			{

			},
		});
	};
	window.Themes.prototype.clickClose = function (e)
	{

		_this = e.data;
        let target = $(e.target);
		if ($(this).hasClass('landing-ui-panel-content-close') || (target.attr('id') != 'theme-change' && target.closest('#theme-panel').length == 0))
		{
            $('.landing-ui-panel').addClass('landing-ui-hide').removeClass('landing-ui-show', 'landing-ui-show-just');

			$.ajax({
				type: 'POST',
				url: '/local/components/sotbit/origami.theme/ajax.php',
				data: {
					'action': 'open',
					'open': 'N',
					'site': _this.site,
				},
				success: function (data)
				{
					// location.reload();
				},
				error: function (c,v,b) {

				}
			});
        }

        $('.overlay').removeClass('overlay-show');
	};
	window.Themes.prototype.clickOption = function (e, passingElement=null)
	{

		_this = e.data;
        e.preventDefault();
        var container = $('body');
        createMainLoader(container);

		if(passingElement === null) {
			_this.hasClassNotReload = $(this).hasClass("notreload");
		} else {
			_this.hasClassNotReload = passingElement.hasClass("notreload");
		}
		if(!$(this).hasClass("switch")) {

			if($(this).is('.multiple'))
			{
				if($(this).is(".landing-ui-button-active"))
					$(this).removeClass('landing-ui-button-active');
				else
					$(this).addClass('landing-ui-button-active');

			}else{
				$(this).closest('.options-block').find('.option').removeClass('landing-ui-button-active');
				$(this).addClass('landing-ui-button-active');
			}
		}
		else {
			$(this).toggleClass("switch-on");
		}
		var options = {};
		$('.options-block').each(function (index, element)
		{
			var switchEl = $(element).find(".switch");

			var code = $(this).data('code');
			_this.codeElement = code;

			if(!switchEl.length) {

				if($(this).find('.multiple').length > 0)
				{
					var value = [];
					$(this).find('.landing-ui-button-active').each(function (i, el)
					{
						value[i] = $(this).data('id');

					});

				}else{
					var value = $(this).find('.landing-ui-button-active').data('id');
				}
			}
			else {
				if(switchEl.hasClass("switch-on")) {
					var value = 'Y';
				}
				else {
					var value = 'N';
				}
			}
			options[code] = value;
		});
		var addParams = {};
		$('#theme-panel input').each(function ()
		{
			var name = $(this).attr('name');
			if (name.indexOf('CUSTOM') !== -1)
			{
				var start_pos = name.indexOf('[') + 1;
				var end_pos = name.indexOf(']', start_pos);
				var key = name.substring(start_pos, end_pos);
				addParams[key] = $(this).val();
			}
        });
		let code = $(this).closest('.options-block').data('code');
		if(addParams[code] !== undefined){
			addParams[code] = '';
		}

        var scrollPosition = $(this).closest('.landing-ui-panel-content-body-content').scrollTop();

        $.cookie('scrollPosition', scrollPosition, {path: '/'});

		$.ajax({
			type: 'POST',
			url: '/local/components/sotbit/origami.theme/ajax.php',
			data: {
				'action': 'option',
				'options': JSON.stringify(options),
				'addParams': JSON.stringify(addParams),
				'site': _this.site,
			},
			success: function (data)
			{

				if(_this.hasClassNotReload){
					// to get random number
					let randomId = (Math.random() * (99999 - 10000) + 10000).toString().split('.')[0];
					let randomId1 = (Math.random() * (99999 - 10000) + 10000).toString().split('.')[0];

					// building new link to newly created css script
					let colorStyleLink = data + '/color.css?' + randomId;
					let sizeStyleLink = data + '/size.css?' + randomId;
					let fontStyleLink = data + '/style.css?';

					// $('body').append(`
					// 	<div class="loader">
                    //         <div class="inner">
                    //         <svg width="200px"  height="200px"  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid" class="lds-eclipse" style="background: rgba(0, 0, 0, 0) none repeat scroll 0% 0%;"><path ng-attr-d="{{config.pathCmd}}" ng-attr-fill="{{config.color}}" stroke="none" d="M40 50A10 10 0 0 0 60 50A10 11 0 0 1 40 50"><animateTransform attributeName="transform" type="rotate" calcMode="linear" values="0 50 50.5;360 50 50.5" keyTimes="0;1" dur="0.8s" begin="0s" repeatCount="indefinite"></animateTransform></path></svg>
                    //         </div>
					// 	</div>
					// `);


					// get old links
					let previousColorStyle = $('link[href*="color.css"]');
					let previousSizeStyle = $('link[href*="size.css"]');
					let previousStyle = $(`link[href^="${fontStyleLink}"]`);
					//set newly create scripts
					if(previousColorStyle.length > 0)
						previousColorStyle.after('<link rel="stylesheet" href="' + colorStyleLink + '" type="text/css" />');
					else
                        $('<link rel="stylesheet" href="' + colorStyleLink + '" type="text/css" />').appendTo($('head'));

					if(previousSizeStyle.length > 0)
                        previousSizeStyle.after('<link rel="stylesheet" href="' + sizeStyleLink + '" type="text/css" />');
					else
						$('<link rel="stylesheet" href="' + sizeStyleLink + '" type="text/css" />').appendTo($('head'));

					if(previousStyle.length > 0)
						previousStyle.after('<link rel="stylesheet" href="' + fontStyleLink + randomId1 + '" type="text/css" />');
					else
						$('<link rel="stylesheet" href="' + fontStyleLink + randomId1 + '" type="text/css" />').appendTo('head');

					// giving little time before remove old styles
					let setTime = setTimeout(function () {
						previousColorStyle.remove();
						previousStyle.remove();
						// $(".loader").fadeOut(300, function() { // icon fadeout first
                        //     $("div").remove(".loader");
                        // });
                        removeMainLoader();
                    }, 1000)

				} else {
					// $('body').append(`
					// 	<div class="loader">
					// 		<div class="inner"></div>
					// 	</div>
					// `);
					location.reload();
					// $(".loader").fadeOut(300, function() { // icon fadeout first
                    // //     $("div").remove(".loader");
                    // });
                    removeMainLoader();
				}
			},
		});
		// $("div").remove(".loader");
	};
	window.Themes.prototype.clickSidebar = function (e)
	{
		e.preventDefault();
		_this = e.data;
		if ($(this).hasClass('landing-ui-active'))
		{
			return;
		}
		$(_this.sidebar).removeClass('landing-ui-active');
        $(this).addClass('landing-ui-active');
        $('.options-section-name').hide();
        $('.options-section').hide();
        $('.options-section-name[data-id="' + $(this).data('id') + '-name"]').show();
        $('.options-section[data-id="' + $(this).data('id') + '"]').show();
        $.cookie('activeTabCode', $(this).data('id'), {path: '/'});
	};
	window.Themes.prototype.clickSave = function (e)
	{

		e.preventDefault();
		var container = $('body');
		createMainLoader(container);


		_this = e.data;

		var addParams = {};
		$('#theme-panel input').each(function ()
		{
			var name = $(this).attr('name');
			if (name.indexOf('CUSTOM') !== -1)
			{
				var start_pos = name.indexOf('[') + 1;
				var end_pos = name.indexOf(']', start_pos);
				var key = name.substring(start_pos, end_pos);
				addParams[key] = $(this).val();
			}
		});

		$.ajax({
			type: 'POST',
			url: '/local/components/sotbit/origami.theme/ajax.php',
			data: {
				'action': 'save',
				'site': _this.site,
				'addParams': JSON.stringify(addParams),
			},
			success: function (data)
			{
				removeMainLoader();
				// $('body').append(`
				// 		<div class="loader">
				// 			<div class="inner">
				// 			</div>
				// 		</div>
				// 	`);
				//location.reload();
				// $(".loader .inner").fadeOut(3000, function() { // icon fadeout first
				// });
			},
		});
        // $("div").remove(".loader");
	};

})(window, document, jQuery);

(function () {
    $(document).ready(function () {
    	var COLOR_DEFAULT = '#fb0040';
        $(".options-block-custom__btn-reset").data('id', COLOR_DEFAULT);
    	var currentColor = [];
        if(sessionStorage.getItem('currentColor')) {
        	var temp = sessionStorage.getItem('currentColor')[length - 1];
        }

        if ($('.option.option-color.landing-ui-button.landing-ui-button-active').data('id')) {
            currentColor.push($('.option.option-color.landing-ui-button.landing-ui-button-active').data('id'));
            sessionStorage.setItem('currentColor', currentColor);
		}
		if ($(".options-block-custom").val()) {
            currentColor.push($(".options-block-custom").val());
            sessionStorage.setItem('currentColor', currentColor);
		}


		var lastColors = function (colors) {
        	var colorsHistory = colors.split(",");
			if(colorsHistory.length >= 1) {
				return colorsHistory[colorsHistory.length - 1];
			} else {
				return colorsHistory[0];
			}
        }

        function actateCurrentColor () {
			var buttonsColor = $('.option.option-color.landing-ui-button');
            buttonsColor.each(function (i, item) {
                if (item.getAttribute('data-id') === $(".options-block-custom__btn-reset").data('id')) {
                    item.classList.add('landing-ui-button-active');
				};
            })

        }

        $(".option-color").on("click", function (evt) {
            currentColor.push($(this).data('id'));
            sessionStorage.setItem('currentColor', currentColor);
            $(".options-block-custom__btn-reset").data('id', lastColors(sessionStorage.getItem('currentColor')));
        });

        $(".options-block-custom").on("input", function (evt) {
        	var form = $(this).closest(".option-block__form");
            var btnApply = form.find(".option-btn-apply");
                $(btnApply).data('id', $(this).val());
            $(".options-block-custom__btn-reset").data('id', lastColors(sessionStorage.getItem('currentColor')));
            });

        $(".options-block-custom__btn-save").on("click", function (evt) {
            currentColor.push($(".options-block-custom").val());
            sessionStorage.setItem('currentColor', currentColor);
            $(".options-block-custom__btn-reset").data('id', lastColors(sessionStorage.getItem('currentColor')));
        });

        $(".options-block-custom__btn-reset").on("click", function (evt) {
            var colorsHistory = sessionStorage.getItem('currentColor').split(",");
            if (colorsHistory.length >= 2) {
                colorsHistory.pop();
                currentColor.pop();
                sessionStorage.setItem('currentColor', currentColor);
                $(".options-block-custom__btn-reset").data('id', lastColors(sessionStorage.getItem('currentColor')));
			} else {
                $(".options-block-custom__btn-reset").data('id', COLOR_DEFAULT);
			}
             setTimeout(actateCurrentColor, 1000);
		});

    });
})();

