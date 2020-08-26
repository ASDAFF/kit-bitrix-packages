function sendForm(sid, color)
{
	if ($("form[name='" + sid + "'] input[name='personal']").is(':checked'))
	{
		$("form[name='" + sid + "'] input[type='submit']").trigger('click');
	}
	else
	{
		$('.feedback_block__compliance svg path').css({'stroke': color, 'stroke-dashoffset': 0});
	}
}