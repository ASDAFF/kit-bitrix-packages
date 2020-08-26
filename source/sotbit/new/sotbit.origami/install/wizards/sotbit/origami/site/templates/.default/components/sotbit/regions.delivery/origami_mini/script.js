class SotbitRegionsDeliveryMini
{
	constructor(params) {

		if(!params.ajax)
			return false;

		this.contentID = document.getElementById('product_detail_info__delivery');

		this.componentPath = params.componentPath;
		this.parameters = params.parameters;
		this.siteId = params.siteId;
		this.template = params.template;
		this.clickTab();
	}

	clickTab(){
		var defaultData = {
			siteId: this.siteId,
			template: this.template,
			parameters: this.parameters,
		};
        createLoadersMore($(this.contentID));

		BX.ajax({
			url: this.componentPath + '/ajax.php' + (document.location.href.indexOf('clear_cache=Y') !== -1 ? '?clear_cache=Y' : ''),
			method: 'POST',
			dataType: 'html',
			timeout: 60,
			async: true,
			data: defaultData,
			onsuccess: BX.delegate(function(result){
                removeLoadersMore($(this.contentID));
                this.contentID.innerHTML = result;
			}, this)
		});
	}
}