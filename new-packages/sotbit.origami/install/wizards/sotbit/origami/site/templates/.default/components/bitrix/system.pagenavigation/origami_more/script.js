$(document).ready(function(){
	$(document).on('click', '.show_more_block', function(){

		var targetContainer = $('.block__product_cards'),
			url =  $('.show_more_block').attr('data-url');

		if (url !== undefined) {
			// BX.showWait();
			$.ajax({
				type: 'GET',
				url: url,
				dataType: 'html',
				success: function(data)
				{
					$('.show_more_block').remove();

					var elements = $(data).find('.product_card__block_item');
					var	pagination = $(data).find('.show_more_block');

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