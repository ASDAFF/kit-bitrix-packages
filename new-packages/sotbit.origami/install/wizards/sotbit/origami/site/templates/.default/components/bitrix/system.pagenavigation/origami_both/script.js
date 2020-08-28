$(document).ready(function(){
	$(document).on('click', '.show_more_block', function(){

		var targetContainer = $('.block__product_cards'),
            url =  $('.show_more_block').attr('data-url');
            $(this).addClass('loaderBtns');
            var adr = this;
		if (url !== undefined) {
            // BX.showWait();
            createLoadersMore ($(this));
			$.ajax({
				type: 'GET',
				url: url,
				dataType: 'html',
				success: function(data)
				{
                    $('.show_more_block').parent().remove();
                    $(this).removeClass('loaderBtns');
                    removeLoadersMore ($(this));

					var elements = $(data).find('.product_card__block_item');
					var pagination = $(data).find('.show_more_block').parent();
						if(pagination.length == 0)
							pagination = $(data).find('.block_page_navigation').parent();

					targetContainer.append(elements);
					pagination.insertAfter(targetContainer);
					// BX.closeWait();
					url = url.replace(/[&]?\s*AJAX\s*=\s*Y\s*/g, '');
					url = url.replace(/\?&/g, '?');
					history.pushState({}, false, url);
				}
			})
		}

	});
});