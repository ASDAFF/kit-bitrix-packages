window.SotbitRegions = function(arParams) {
	var list = [];
	//var regions = JSON.parse(arParams.list);
	for(var i = 0; i < arParams.list.length; ++i)
	{
		list[i] = {value:arParams.list[i]['NAME'],text:arParams.list[i]['NAME']};
	}

	var inputText = document.getElementsByClassName("select-city__modal__submit__input");
	var inputWrap = document.getElementsByClassName("select-city__modal__submit__block-wrap__input_wrap");
	var wrap = document.getElementsByClassName("select-city__dropdown-wrap");
	var modal = document.getElementsByClassName("select-city__modal");
	var overlay = document.getElementsByClassName("modal__overlay");
	var yes = document.getElementsByClassName("select-city__dropdown__choose__yes");
	var close_yes = document.getElementsByClassName("select__city_dropdawn_close");
	var no = document.getElementsByClassName("select-city__dropdown__choose__no");
	var textCity = document.getElementsByClassName("select-city__block__text-city");
	var close = document.getElementsByClassName("select-city__close");
	var item = document.getElementsByClassName("select-city__modal__list__item");
	var button = document.getElementsByClassName("select-city__modal__submit__btn");
	var error = document.getElementsByClassName("select-city__modal__submit__block-wrap__input_wrap_error");

	horsey(inputText[0], {
		source: [{ list: list}],
		getText: 'text',
		getValue: 'value',
		limit: 2,
		appendTo:inputWrap[0],
		filter: function(query, suggestion){
			query = query.toLowerCase();
			var sug = suggestion['text'].toLowerCase();
			if(sug.indexOf(query) === 0)
			{
				return true;
			}
			else
			{
				return false;
			}
		}
	});
	
	
	yes[0].addEventListener('click',function ()
	{
		Add();
	});
    close_yes[0].addEventListener('click',function ()
    {
        Add();
    });
	no[0].addEventListener('click',function ()
	{
		Open();
    });

	//textCity[0].addEventListener('click',function ()
	//{
	//	Open();
 //   });
    $(document).on('click', '.select-city__block__text-city', function () {
        Open();
    });

	button[0].addEventListener('click',function ()
	{
		var city = inputText[0].value;
		var isFind = false;
		for(var i = 0; i < arParams.list.length; ++i)
		{
			if(city == arParams.list[i]['NAME'])
			{
				isFind = true;
				SetCookie('sotbit_regions_city_choosed','Y',{'domain':'.'+arParams.rootDomain});
				SetCookie('sotbit_regions_id',arParams.list[i]['ID'],{'domain':arParams.rootDomain});
				if(arParams.singleDomain == 'Y')
				{
					location.reload();
				}
				else
				{
					document.location.href=arParams.list[i]['URL'];
				}
			}
		}
		if(!isFind)
		{
			error[0].style.display = 'block';
		}
	});
	
	for(var i = 0; i < item.length; ++i)
	{
		item[i].addEventListener('click',function ()
		{
			var index = this.getAttribute('data-index');
			SetCookie('sotbit_regions_city_choosed','Y',{'domain':'.'+arParams.rootDomain});
			SetCookie('sotbit_regions_id',arParams.list[index]['ID'],{'domain':arParams.rootDomain});
			if(arParams.singleDomain == 'Y')
			{
				location.reload();
			}
			else
			{
				document.location.href=arParams.list[index]['URL'];
			}
		});
	}
	
	close[0].addEventListener('click',function ()
	{
		Close();
    });

    $(document).on('click', '.select-city__close', function () {
        Close();
    });
	
	function Open()
	{
		modal[0].style.display = 'block';
		overlay[0].style.display = 'block';
		wrap[0].style.display = 'none';
	}
	function Close()
	{
		modal[0].style.display = 'none';
		overlay[0].style.display = 'none';
	}

    function Add() {
        wrap[0].style.display = 'none';
        SetCookie('sotbit_regions_city_choosed', 'Y', {'domain': '.' + arParams.rootDomain});
        SetCookie('sotbit_regions_id', yes[0].dataset.id, {'domain': arParams.rootDomain});
        if (arParams.singleDomain != 'Y') {
            var url = '';
            for (var i = 0; i < arParams.list.length; ++i) {
                if (arParams.list[i]['ID'] == yes[0].dataset.id) {
                    url = arParams.list[i]['URL'];
                }
            }
            if (url.length > 0) {
                document.location.href = url;
            }
        }
        else {
            //location.reload();
        }
    }

	function SetCookie(name, value, options) 
	{
		options = options || {};

		var expires = options.expires;

		if (typeof expires == "number" && expires) 
		{
			var d = new Date();
			d.setTime(d.getTime() + expires * 1000);
			expires = options.expires = d;
		}
		if (expires && expires.toUTCString) 
		{
			options.expires = expires.toUTCString();
		}
		options.path = '/';
		value = encodeURIComponent(value);

		var updatedCookie = name + "=" + value;

		for (var propName in options) 
		{
			updatedCookie += "; " + propName;
			var propValue = options[propName];
			if (propValue !== true) 
			{
				updatedCookie += "=" + propValue;
			}
		}
		document.cookie = updatedCookie;
	}
}
