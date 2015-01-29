$(document).ready(function()
{
'use strict';


(function($) 
{		
	/*
	 * On input.
	 */
	function updateInputArea(textarea) 
	{
		$(textarea)	.css({'height':'auto','overflow-y':'hidden'})
					.height(textarea.scrollHeight);
		
		textarea.isEmpty = false;
	}
	
	$('#post-form-data').on('input', function(){updateInputArea(this);});
	$('#post-form-title').on('input', function(){updateInputArea(this);});

	/*
	 * On focus.
	 */
	function clearPlaceholderText(element)
	{
		if(element.isEmpty === true)
		{
			element.value = '';
		}
	}
	
	$('#post-form-title').focus(function()
	{
		clearPlaceholderText(this); 
	});
	$('#post-form-data').focus(function()
	{
		clearPlaceholderText(this);
	});
	
	
	/*
	 * On blur.
	 */
	function updatePlaceholderText(element, text)
	{
		if(element.value === '')
		{	
			element.isEmpty = true;
			element.value = text;
		}
	}
	
	var titleText = 'Type your title here...';
	var dataText = "Whatcha thinkin' about?";
	$('#post-form-title').blur(function()
	{
		updatePlaceholderText(this, titleText); 
	});
	$('#post-form-data').blur(function()
	{
		updatePlaceholderText(this, dataText);
	});
	
	/*
	 * Perform update on page load.
	 */
	updateInputArea($('#post-form-data')[0]);
	updatePlaceholderText($('#post-form-title')[0], titleText);
	updatePlaceholderText($('#post-form-data')[0], dataText);
}) (jQuery);
});

