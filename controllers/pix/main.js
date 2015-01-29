$(document).ready(function(){
	'use strict';

	(function($) 
	{		
		$.fn.enableWYSIWOO = function() 
		{		
			$('body').find('*')
				.on('mousedown.WYSIWOO', function(e)
				{
					e.stopPropagation();
				
					var target = $(this);
					
					$(document).on('mousemove.WYSIWOO', function(e)
					{
						target.css('position', 'absolute');
						
						var width = target.width();
						var height = target.height();
						
						target.css('left', (e.pageX - width/2) + 'px');
						target.css('top', (e.pageY - height/2) + 'px');
					});
				})
				.on('mouseup.WYSIWOO', function()
				{
					$(document).off('mousemove.WYSIWOO');
				})
				.on('mouseenter.WYSIWOO', function(){$(this).css('border', 'solid #000');})
				.on('mouseleave.WYSIWOO', function(){$(this).css('border', '');})
				.attr('unselectable', 'on')
				.css('user-select', 'none')
				.on('selectstart', false);

			$(document).on('keypress.WYSIWOO', function(e) 
			{
				if(e.keyCode === 27)
				{
					$('body').find('*')
						.off('mousedown.WYSIWOO')
						.off('mouseenter.WYSIWOO')
						.off('mouseleave.WYSIWOO')
						.css('border', '');
					$(document).off('mousemove.WYSIWOO');
				}
			});
		};
		

				
		

	}) (jQuery);

	$('#WYSIWOO').click
	(
		function() 
		{
			$(this).enableWYSIWOO();
			console.log('Turned on WYSIWOO editing of page body.');
		}
	);
});