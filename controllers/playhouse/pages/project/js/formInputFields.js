$(document).ready(function()
{
'use strict';


(function($) 
{		

	/*
	 * On focus.
	 */
	function clearPlaceholderText(element)
	{
		if(element.isEmpty === true)
		{
			element.value = '';
		}
		
		console.log('clear');
	}

	/*
	 * On input.
	 */
	function updateInputArea(textarea) 
	{
		$(textarea)	.css({'height':'auto','overflow-y':'hidden'})
					.height(textarea.scrollHeight);
		
		textarea.isEmpty = false;
		console.log('update');
	}

	/*
	 * On blur.
	 */
	function updatePlaceholderText(element)
	{
		if(element.value === '' || element.value === element.defaultValue)
		{	
			element.isEmpty = true;
			element.value = element.defaultValue;
		}
	}

	
	$('textarea').each(function()
	{
		$(this).on('input', function(){updateInputArea(this);});
		updateInputArea(this);
	});
/*
	$('textarea').each(function()
	{
		// Apply functions to all textareas.
		$(this)	.on('input', function(){updateInputArea(this);})
				.focus(function(){clearPlaceholderText(this);})
				.blur(function(){updatePlaceholderText(this);});

				
		
		// Perform update on page load.
		updateInputArea(this);
		updatePlaceholderText(this);
	});
*/
	$('project-form').keypress(function(e)
	{
		// Unbind enter from post submission. 13 is the keycode for enter.
		if(e.keyCode === 13)
			return false;
	});
	


}) (jQuery);
});

