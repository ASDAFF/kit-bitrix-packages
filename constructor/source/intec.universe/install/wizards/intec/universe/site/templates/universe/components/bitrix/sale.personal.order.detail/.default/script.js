BX.namespace('BX.Sale.PersonalOrderComponent');

(function() {
	BX.Sale.PersonalOrderComponent.PersonalOrderDetail = {
		init : function(params)
		{
			var listShipmentWrapper = document.getElementsByClassName('sale-personal-order-detail-shipment-item');
			var listPaymentWrapper = document.getElementsByClassName('sale-personal-order-detail-payment-item');

			Array.prototype.forEach.call(listShipmentWrapper, function(shipmentWrapper)
			{
				var detailShipmentBlock = shipmentWrapper.getElementsByClassName('sale-personal-order-detail-shipment-item-information')[0];
				var showInformation = shipmentWrapper.getElementsByClassName('sale-personal-order-detail-shipment-item-switch-expand')[0];
				var hideInformation = shipmentWrapper.getElementsByClassName('sale-personal-order-detail-shipment-item-switch-collapse')[0];

				BX.bindDelegate(shipmentWrapper, 'click', { 'class': 'sale-personal-order-detail-shipment-item-switch-expand' }, BX.proxy(function()
				{
					showInformation.style.display = 'none';
					hideInformation.style.display = 'inline-block';
					detailShipmentBlock.style.display = 'block';
				}, this));
				BX.bindDelegate(shipmentWrapper, 'click', { 'class': 'sale-personal-order-detail-shipment-item-switch-collapse' }, BX.proxy(function()
				{
					showInformation.style.display = 'inline-block';
					hideInformation.style.display = 'none';
					detailShipmentBlock.style.display = 'none';
				}, this));
			});

			Array.prototype.forEach.call(listPaymentWrapper, function(paymentWrapper)
			{
				var rowPayment = paymentWrapper.getElementsByClassName('sale-personal-order-detail-payment-item-content-part-common')[0];

				BX.bindDelegate(paymentWrapper, 'click', { 'class': 'sale-personal-order-detail-payment-item-switch' }, BX.proxy(function()
				{
					BX.toggleClass(paymentWrapper, 'sale-personal-order-detail-payment-item-active');
				}, this));

				BX.bindDelegate(rowPayment, 'click', { 'class': 'sale-personal-order-detail-payment-item-change' }, BX.proxy(function(event)
				{
					event.preventDefault();

					var btn = rowPayment.parentNode.getElementsByClassName('sale-personal-order-detail-payment-item-content-part-buttons')[0];
					var linkReturn = rowPayment.parentNode.getElementsByClassName('sale-personal-order-detail-payment-item-cancel')[0];
					BX.ajax(
						{
							method: 'POST',
							dataType: 'html',
							url: params.url,
							data:
							{
								sessid: BX.bitrix_sessid(),
								orderData: params.paymentList[event.target.id]
							},
							onsuccess: BX.proxy(function(result)
							{
								rowPayment.innerHTML = result;
                                BX.removeClass(paymentWrapper, 'sale-personal-order-detail-payment-item-active');
								if (btn)
								{
									btn.parentNode.removeChild(btn);
								}
								linkReturn.style.display = "";
								BX.bind(linkReturn, 'click', function()
								{
									window.location.reload();
								},this);
							},this),
							onfailure: BX.proxy(function()
							{
								return this;
							}, this)
						}, this
					);

				}, this));
			});
		}
	};
})();
