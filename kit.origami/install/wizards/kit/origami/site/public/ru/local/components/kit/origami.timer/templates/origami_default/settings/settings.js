function OnMySettingsEdit(arParams)
{
    var arElements = arParams.getElements();
   // var jsOptions = JSON.parse(arParams.data);
    var obLabel = arParams.oCont.appendChild(BX.create('SPAN', {
        html: arParams.oInput.value
    }));
    var obButton = arParams.oCont.appendChild(BX.create('BUTTON', {
        html: arParams.data
    }));
    obButton.onclick = BX.delegate(function(){
        BX.calendar({
            node: obButton,
            //value: arParams.oInput.value,
            field: arParams.oInput,
            callback_after: function(){
                obLabel.innerHTML = arParams.oInput.value;
            }
        });
        return false;
    });
}
