$(document).ready(function()
{
'use strict';


(function($) 
{
	function randomizeSplash()
	{
		var data = "silent=1";
		$.ajax(
		{
			type: 'post',
			url: '/ajax/playhouse/splash',
			dataType: 'html',
			data: data,
			success: function(data){$('#headerSplash').html(data)},
		});
	}

	$('#headerImage').click(randomizeSplash)
					 .hover(function()
					{
						$(this).css("cursor", "pointer")
							.css("cursor", "hand");
					},
					function()
					{
						$(this).css("cursor", "default");
					});
	
}) (jQuery);
});