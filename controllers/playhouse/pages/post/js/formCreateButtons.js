$(document).ready(function()
{
'use strict';


(function($) 
{	
	function success(data)
	{
		if(data.success)
			window.location.replace(data.output);
		else
			$('#form-output').html(data.output);
	}
	
	function save()
	{
		var data = $('#post-form').serializeArray();
	
		$.ajax(
		{
			type: 'post',
			url: '/ajax/playhouse/post?insert',
			data: data,
			dataType: 'json',
			success: success
		}); 
	}
	
	function publish()
	{
		var data = $('#post-form').serializeArray();
		data.push({'name': 'published', 'value': 'now()'});

		$.ajax(
		{
			type: 'post',
			url: '/ajax/playhouse/post?insert',
			data: data,
			dataType: 'json',
			success: success
		}); 
	}
	
	$('#post-form-save').click(save);
	$('#post-form-publish').click(publish);
}) (jQuery);
});

