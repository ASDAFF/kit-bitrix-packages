BX.namespace("BX.Catalog.SetConstructor");

BX.Catalog.SetConstructor = (function()
{
	var SetConstructor = function(params)
	{
		this.numSetItems = params.numSetItems || 0;
		this.jsId = params.jsId || "";
		this.ajaxPath = params.ajaxPath || "";
		this.currency = params.currency || "";
		this.lid = params.lid || "";
		this.iblockId = params.iblockId || "";
		this.basketUrl = params.basketUrl || "";
		this.setIds = params.setIds || null;
		this.offersCartProps = params.offersCartProps || null;
		this.itemsRatio = params.itemsRatio || null;
		this.messages = params.messages;

		this.canBuy = params.canBuy;
		this.mainElementPrice = params.mainElementPrice || 0;
		this.mainElementOldPrice = params.mainElementOldPrice || 0;
		this.mainElementDiffPrice = params.mainElementDiffPrice || 0;
		this.mainElementBasketQuantity = params.mainElementBasketQuantity || 1;

		this.parentCont = BX(params.parentContId) || null;
		this.setItemsCont = this.parentCont.querySelector("[data-role='set-items']");

		this.setPriceCont = this.parentCont.querySelector("[data-role='set-price']");
		this.setOldPriceCont = this.parentCont.querySelector("[data-role='set-old-price']");
		this.setOldPriceRow = this.setOldPriceCont.parentNode;
		this.setDiffPriceCont = this.parentCont.querySelector("[data-role='set-diff-price']");
		this.setDiffPriceRow = this.setDiffPriceCont.parentNode;

        BX.bindDelegate(this.setItemsCont, 'click', { 'attribute': 'data-role' }, BX.proxy(this.changeSetElement, this));

		var buyButton = this.parentCont.querySelector("[data-role='set-buy-btn']");

		if (this.canBuy)
		{
			BX.show(buyButton);
			BX.bind(buyButton, "click", BX.proxy(this.addToBasket, this));
		}
		else
		{
			BX.hide(buyButton);
		}

	};

    SetConstructor.prototype.changeSetElement = function() {
        var target = BX.proxy_context,
            item,
            itemId,
            i,
            l;

        if (target && target.hasAttribute('data-role') && target.getAttribute('data-role') == 'change-item') {

        	item = target.parentNode.parentNode.parentNode.parentNode;
            itemId = item.getAttribute("data-id");

            if ($(target).prop('checked')){
                this.numSetItems++;
                this.setIds.push(Number(itemId));
                $(item).addClass('append');
                this.recountPrice();
			}else{
                this.numSetItems--;
                for (i = 0, l = this.setIds.length; i < l; i++)
                {
                    if (this.setIds[i] == itemId)
                        this.setIds.splice(i, 1);
                }
                $(item).removeClass('append');
                this.recountPrice();
			}
        }
    }

	SetConstructor.prototype.recountPrice = function()
	{
		var sumPrice = this.mainElementPrice*this.mainElementBasketQuantity,
			sumOldPrice = this.mainElementOldPrice*this.mainElementBasketQuantity,
			sumDiffDiscountPrice = this.mainElementDiffPrice*this.mainElementBasketQuantity,
			setItems = BX.findChildren(this.setItemsCont, {"class": "catalog-set-constructor-main-set-item", "class": "append"}, true),
			i,
			l,
			ratio;
		if (setItems)
		{
			for(i = 0, l = setItems.length; i<l; i++)
			{
				ratio = Number(setItems[i].getAttribute("data-quantity")) || 1;
				sumPrice += Number(setItems[i].getAttribute("data-price"))*ratio;
				sumOldPrice += Number(setItems[i].getAttribute("data-old-price"))*ratio;
				sumDiffDiscountPrice += Number(setItems[i].getAttribute("data-diff-price"))*ratio;
			}
		}

		this.setPriceCont.innerHTML = BX.Currency.currencyFormat(sumPrice, this.currency, true);

		if (Math.floor(sumDiffDiscountPrice*100) > 0)
		{
			this.setOldPriceCont.innerHTML = BX.Currency.currencyFormat(sumOldPrice, this.currency, true);
			this.setDiffPriceCont.innerHTML = BX.Currency.currencyFormat(sumDiffDiscountPrice, this.currency, true);
		}
		else
		{
            this.setOldPriceCont.innerHTML = BX.Currency.currencyFormat(sumPrice, this.currency, true);
			this.setDiffPriceCont.innerHTML = BX.Currency.currencyFormat('0', this.currency, true);
		}
	};


	SetConstructor.prototype.addToBasket = function()
	{
		var target = BX.proxy_context;

		BX.showWait(target.parentNode);

		BX.ajax.post(
			this.ajaxPath,
			{
				sessid: BX.bitrix_sessid(),
				action: 'catalogSetAdd2Basket',
				set_ids: this.setIds,
				lid: this.lid,
				iblockId: this.iblockId,
				setOffersCartProps: this.offersCartProps,
				itemsRatio: this.itemsRatio
			},
			BX.proxy(function(result)
			{
				BX.closeWait();
				document.location.href = this.basketUrl;
			}, this)
		);
	};

	return SetConstructor;
})();