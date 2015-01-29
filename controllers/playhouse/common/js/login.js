$(document).ready(function()
{
'use strict';


(function($) 
{	
	function success()
	{
		window.location.reload();
	}
	
	$('#above-logout').removeAttr('onclick')
						.click(function()
	{
		$.ajax(
		{
			url: '/ajax/playhouse/user?logout',
			success: success
		});
		
		return false;
	});	
	
	
	
	function appendForm(data)
	{
		$('body').append(data);
		
		
		var form = $('#user-login-form');
		var top = (window.innerHeight - form.height()) / 2;
		var left = (window.innerWidth - form.width()) / 2;

		form.css({top: top, left: left});
		
		
		function checkLogin(data)
		{
			if(data.success !== true)
				$('#user-output').html(data.output);
			else
				success();
		}
		
		function login(e = null)
		{
			$.ajax(
			{
				type: 'post',
				url: '/ajax/playhouse/user?login',
				data: $('#user-login-form').serialize(),
				dataType: 'json',
				success: checkLogin
			}); 
		}
		
		function onEnterPress(e)
		{
			// If key is pressed, but not enter, return false.
			if(e !== null && e.keyCode == 13)
				login();
		}
		
		
		$('#user-login').click(login);
		$('#user-login-name')
						.on('keypress', function(e){onEnterPress(e)})
						.focus();
		$('#user-login-password').on('keypress', function(e){onEnterPress(e)});
		
		
	}
	
	$('#above-login').removeAttr('onclick')
					.click(function()
	{
		$('<div id="login-overlay"></div>').appendTo('body');
		$('#login-overlay').click(function()
		{
			$('#user-login-form').remove();
			$(this).remove();
		});
		
		$.ajax(
		{
			url: '/ajax/playhouse/user?loginForm',
			dataType: 'html',
			success: appendForm
		});

	
	
		return false;
	});
}) (jQuery);
});



