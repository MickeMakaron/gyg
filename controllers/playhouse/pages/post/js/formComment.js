$(document).ready(function()
{
'use strict';


(function($) 
{	
	function success(data)
	{
		if(data.success)
			window.location.reload();
		else
			$('#form-output').html(data.output);
	}
	
	function submit()
	{
		var data = $('#comment-form').serializeArray();
	
		$.ajax(
		{
			type: 'post',
			url: '/ajax/playhouse/post?comment',
			data: data,
			dataType: 'json',
			success: success
		}); 
	}
	
	$('#comment-form-submit').click(submit);
}) (jQuery);
});

