/**
 * A websocket client.
 */
$(document).ready(function(){
  'use strict';

  var url = null,
    websocket = null,
    form = $('#form1'),
    output = $('#output'),
	user = null;

  // Display the url in form field for the user to change
  $('#connect_url').val(url);

	if(websocket === null)
		$('#send').attr('disabled', 'disabled');
  
  
	$('#connect-chat').click(function(event)
	{
		user = $('#chat-user').val();
	
		if(user && user.length !== 0)
		{
			initSocket('sweet.student.bth.se', 8014, 'chat-protocol');
			console.log('Connecting to "sweet.student.bth.se:8014"');
		}
		else
			outputLog('Please enter a valid username.');
	});
	
	$('#chat-user').on('keypress', function(e)
	{
		if(e.keyCode === 13)
			$('#connect-chat').trigger('click');
	});
	
	// Send a message to the server
	$('#send').click(function(event) 
	{
		var msg = $('#message').val();
		
		$('#message').val("");
		
		if(!websocket || websocket.readyState === 3) 
			console.log('The websocket is not connected to a server.');
		else 
			websocket.send(JSON.stringify({user: user, message: msg}));
	});
	
	$('#message').on('keypress', function(e)
	{
		if(e.keyCode === 13)
			$('#send').trigger('click');
	});
  
	function initSocket(host, port, protocol)
	{
		if(websocket) 
		{
			websocket.close();
			websocket = null;
		}
		
		
		
		url = 'ws://' + host + ':' + port;
		websocket = new WebSocket('ws://' + host + ':' + port, protocol);
		
		
		
		

		websocket.onopen = function()
		{
			if(websocket)
				websocket.send(JSON.stringify({validate_user: user}));
		
			console.log('The websocket is now open.');
			console.log(websocket);
			
			//websocket.send('Thanks for letting me connect to you.');
			
		}

		websocket.onmessage = function(event) 
		{			
			if(event.data === 'invalid_user')
			{
				outputLog('That username is already taken, please try a different username.');
				return;
			}
			else if(event.data === 'valid_user')
			{
				$('#send').removeAttr('disabled');
				outputLog('Welcome, ' + user + '!');
				return;
			}
			
			var data = JSON.parse(event.data);
			
		
			if(data.hasOwnProperty('user') && data.hasOwnProperty('message'))
			{
				
			
				console.log('Receiving message: ' + event.data);
				
				var data = JSON.parse(event.data);
				
				
				outputLog(data.user + ': ' + data.message);
			}
		}

		websocket.onclose = function() 
		{
			console.log('The websocket is now closed.');
			console.log(websocket);
			$('#send').attr('disabled', 'disabled');
			//websocket = null;
		}
	}


  // Add the message to the log
  function outputLog(message) {
    var now = new Date();
    $(output).append(now.toLocaleTimeString() + ' ' + message + '<br/>').scrollTop(output[0].scrollHeight);
  }



  console.log('Everything is ready.');   
});
