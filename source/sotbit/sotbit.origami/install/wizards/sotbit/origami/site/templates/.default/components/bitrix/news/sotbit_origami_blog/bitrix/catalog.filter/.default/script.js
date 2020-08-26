function clickTag(value, filterName)
{
	var input = $('#tagsForm input[name="'+filterName+'_ff[TAGS]"]');
	if(input.val() == value)
	{
		$('#tagsForm input[name="del_filter"]').trigger('click');
	}
	else
	{
		if(input.length > 0)
		{
			input.val(value);
			$('#tagsForm input[name="set_filter"]').trigger('click');
		}
	}
}