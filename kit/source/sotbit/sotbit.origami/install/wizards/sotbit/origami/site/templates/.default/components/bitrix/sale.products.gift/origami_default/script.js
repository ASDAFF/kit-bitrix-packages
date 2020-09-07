var initGift = false;
(function() {
	'use strict';

	if (!!window.JCSaleProductsGiftComponent)
		return;

	window.JCSaleProductsGiftComponent = function(params) {
		this.formPosting = false;
		this.siteId = params.siteId || '';
		this.template = params.template || '';
		this.componentPath = params.componentPath || '';
		this.parameters = params.parameters || '';

		this.container = document.querySelector('[data-entity="' + params.container + '"]');
		this.currentProductId = params.currentProductId;

		if (params.initiallyShowHeader)
		{
			BX.ready(BX.delegate(this.showHeader, this));
		}

		if (params.deferredLoad)
		{
			BX.ready(BX.delegate(this.deferredLoad, this));
		}

		BX.addCustomEvent(
			'onCatalogStoreProductChange',
			BX.delegate(function(offerId){
				offerId = parseInt(offerId);

				if (this.currentProductId === offerId)
				{
					return;
				}

				this.currentProductId = offerId;
				this.offerChangedEvent();
			}, this)
		);
	};

	window.JCSaleProductsGiftComponent.prototype =
	{
		offerChangedEvent: function()
		{
			this.sendRequest({action: 'deferredLoad', offerId: this.currentProductId});
		},

		deferredLoad: function()
		{
			this.sendRequest({action: 'deferredLoad'});
		},

		sendRequest: function(data)
		{
			var defaultData = {
				siteId: this.siteId,
				template: this.template,
				parameters: this.parameters
			};

			BX.ajax({
				url: this.componentPath + '/ajax.php' + (document.location.href.indexOf('clear_cache=Y') !== -1 ? '?clear_cache=Y' : ''),
				method: 'POST',
				dataType: 'json',
				timeout: 60,
				data: BX.merge(defaultData, data),
				onsuccess: BX.delegate(function(result){
					if (!result || !result.JS)
					{
						this.hideHeader();
						BX.cleanNode(this.container);
						return;
					}




					BX.ajax.processScripts(
						BX.processHTML(result.JS).SCRIPT,
						false,
						BX.delegate(function(){this.showAction(result, data);}, this)
					);
				}, this)
			});
		},

		showAction: function(result, data)
		{
			if (!data)
				return;





			switch (data.action)
			{
				case 'deferredLoad':
					this.processDeferredLoadAction(result);
					break;
			}
		},

		processDeferredLoadAction: function(result)
		{
			if (!result)
				return;

			this.processItems(result.items);
		},

		processItems: function(itemsHtml)
		{
			if (!itemsHtml)
				return;

			var processed = BX.processHTML(itemsHtml, false),
				temporaryNode = BX.create('DIV');

			var items, k, origRows;

			temporaryNode.innerHTML = processed.HTML;

			origRows = this.container.querySelectorAll('[data-entity="items-row"]');
			if (origRows.length)
			{
				BX.cleanNode(this.container);
				this.showHeader(false);
			}
			else
			{
				this.showHeader(true);
			}

			items = temporaryNode.querySelectorAll('[data-entity="items-row"]');
			for (k in items)
			{
				if (items.hasOwnProperty(k))
				{
					// items[k].style.opacity = 0;
					this.container.appendChild(items[k]);
				}
			}

			$('.detail-product-gift').owlCarousel({
				stopOnHover: true,
				loop: true,
				nav: false,
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
					800:{
						items:3
					},
					1000:{
						items:4
					},
					1200:{
						items:5
					}
				},
				onInitialized: onInitialized
			});

			new BX.easing({
				duration: 2000,
				start: {opacity: 0},
				finish: {opacity: 100},
				transition: BX.easing.makeEaseOut(BX.easing.transitions.quad),
				step: function(state){
					for (var k in items)
					{
						if (items.hasOwnProperty(k))
						{
							items[k].style.opacity = state.opacity / 100;
						}
					}
				},
				complete: function(){
					for (var k in items)
					{
						if (items.hasOwnProperty(k))
						{
							items[k].removeAttribute('style');
						}
					}
				}
			}).animate();

			BX.ajax.processScripts(processed.SCRIPT);
		},

		showHeader: function(animate)
		{
			var parentNode = BX.findParent(this.container, {attr: {'data-entity': 'parent-container'}}),
				header;

			if (parentNode && BX.type.isDomNode(parentNode))
			{
				header = parentNode.querySelector('[data-entity="header"');
				if (header && header.getAttribute('data-showed') === 'false')
				{
					header.style.display = '';

					if (animate)
					{
						this.animation = new BX.easing({
							duration: 2000,
							start: {opacity: 0},
							finish: {opacity: 100},
							transition: BX.easing.makeEaseOut(BX.easing.transitions.quad),
							step: function(state){
								header.style.opacity = state.opacity / 100;
							},
							complete: function(){
								header.removeAttribute('style');
								header.setAttribute('data-showed', 'true');
							}
						});
						this.animation.animate()
					}
					else
					{
						header.style.opacity = 100;
					}
				}
			}
		},

		hideHeader: function()
		{
			var parentNode = BX.findParent(this.container, {attr: {'data-entity': 'parent-container'}}),
				header;

			if (parentNode && BX.type.isDomNode(parentNode))
			{
				header = parentNode.querySelector('[data-entity="header"');

				if (header)
				{
					if (this.animation)
					{
						this.animation.stop();
					}

					header.style.display = 'none';
					header.style.opacity = 0;
					header.setAttribute('data-showed', 'false');
				}
			}
		}
	}
})();
function onInitialized(){
	if(initGift === false)
	{
		let ele = [];
		$('.detail-product-gift .owl-item').each(function (i)
		{
			let str = $(this).find('.params-item-gift').html().trim();
			let json = JSON.parse(str);

			if ($(this).hasClass('cloned'))
			{
				for (let k in json.VISUAL)
				{
					if(k == 'SUBSCRIBE_ID'){
						let param = $(this).find('#' + json.VISUAL[k] + '_hidden');
						param.attr('id', json.VISUAL[k] + 1 + '_hidden');
					}
					let param = $(this).find('#' + json.VISUAL[k]);
					let rand = Math.random();
					param.attr('id', param.attr('id') + rand);
					json.VISUAL[k] = json.VISUAL[k] + rand;
				}
			}

			ele[i] = new JCCatalogItemGift(json);

			wishes1 = JSON.parse(wishes);
			for(k in wishes1){
				if(wishes1[k] == $(this).find('.check_basket').data('id')){
					$(this).find('.fa-heart').addClass('active');
				}
			}
			compares1 = JSON.parse(compares);
			for(k in compares1){
				for(j in compares1[k]){
					if(j == $(this).find('.check_basket').data('id')){
						$(this).find('.fa-chart-bar').addClass('active');
					}
				}
			}
		});
		initGift = true;
	}
}
