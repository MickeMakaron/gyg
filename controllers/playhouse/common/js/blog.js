$(document).ready(function()
{
'use strict';


(function($) 
{			
	function refresh()
	{
		$.ajax(
		{
			url: '/ajax/playhouse/post?get',
			dataType: 'html',
			success: function(data){$('#blog').html(data)},
		}); 
	}

	$('#initPost').click(function()
	{
		$.ajax(
		{
			url: '/ajax/playhouse/post?init',
			success: refresh,
		}); 
	});
	
	$('#initTag').click(function()
	{
		$.ajax(
		{
			url: '/ajax/playhouse/tag?init',
			success: refresh,
		}); 
	});
	
	
	$('#clearPostContent').click(function()
	{
		$.ajax(
		{
			url: '/ajax/playhouse/post?clear',
			success: refresh,
		}); 
	});
	
	
}) (jQuery);
});