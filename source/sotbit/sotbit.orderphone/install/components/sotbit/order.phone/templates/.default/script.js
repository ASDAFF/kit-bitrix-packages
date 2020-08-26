$(function() {

    version = parseFloat(jQuery.fn.jquery);
    if(version<1.9)
    {
        $(".sotbit_order_phone form").live("submit", submitOrderPhone);
    }else{
        $(".sotbit_order_phone").on("submit", "form", submitOrderPhone);
    }

    maska = $(".sotbit_order_phone form input[name='TEL_MASK']").eq(0).val();
    maska = $.trim(maska);
    if(maska!="")$(".sotbit_order_phone form input[name='order_phone']").mask(maska, {placeholder:"_"});

    function submitOrderPhone(e)
    {
        e.preventDefault();
        v = $(this).find("input[name='TEL_MASK']").val();
        v = $.trim(v);
        req = strReplace(v);
        var _this = $(this);
        v = $(this).find("input[type='text']").val();
        if(v.search(req)!=-1 || v=="")
        {
            $(this).find("input[type='text']").removeClass("red");
            ser = $(this).serialize();
            $.post("/bitrix/components/sotbit/order.phone/ajax.php", ser, function(data){
                data = $.trim(data);
                //if(data=="SUCCESS")
                if(data.indexOf("SUCCESS")>=0)
                {
                    _this.find(".sotbit_order_success").show();
                    id = data.replace("SUCCESS", "");
                    localHref = $('input[name="LOCAL_REDIRECT"]').val();
                    orderID = $('input[name="ORDER_ID"]').val();
                    if(typeof(localHref) != "undefined" && localHref!="")
                    {
                        location.href = localHref+"?"+orderID+"="+id;    
                    }
                }
            })

        }else{
            $(this).find("input[type='text']").addClass("red");
        }
    }

    function strReplace(str)
    {
        str = str.replace("+", "\\+");
        str = str.replace("(", "\\(");
        str = str.replace(")", "\\)");
        str = str.replace(/[0-9]/g, "[0-9]{1}");
        return new RegExp(str, 'g');;

    }
});