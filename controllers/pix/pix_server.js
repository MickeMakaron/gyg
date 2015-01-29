/****************************************************************
****************************************************************
*
* PIX - Multiplayer sandbox game in javascript.
* Copyright (C) 2014-2015 Mikael Hernvall (mikael.hernvall@gmail.com)
*
* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with this program. If not, see <http://www.gnu.org/licenses/>.
*
****************************************************************
****************************************************************/

/**
 * CONFIGURATION
 *
 *
*/
// The port with which the server will be listening to.
var port = 8014;
// Only allow connections from origins in this array.
var allowedOrigins = [];





var broadcastTo = [];
var users = [];
var games = [];


// Require the modules we need
var WebSocketServer = require('websocket').server;
var http = require('http');



/**
 * Create a http server with a callback for each request
 *
 */
var httpServer = http.createServer(function(request, response) {
  console.log((new Date()) + ' Received request for ' + request.url);
  response.writeHead(200, {'Content-type': 'text/plain'});
  response.end('Hello world\n');
}).listen(port, function() {
  console.log((new Date()) + ' HTTP server is listening on port ' + port);
});



/**
 * Create an object for the websocket
 * https://github.com/Worlize/WebSocket-Node/wiki/Documentation
 */
wsServer = new WebSocketServer({
  httpServer: httpServer,
  autoAcceptConnections: false
});



/**
 * Always check and explicitly allow the origin
 *
 */
function originIsAllowed(origin) 
{
	if(allowedOrigins.length === 0)
		return true;

	if(allowedOrigins.indexOf(origin) !== -1)
		return true;    
	return false;
}


function broadcast(message, includeSelf, id)
{
	var i;
	var clients = 0;
	for(i = 0; i < broadcastTo.length; i++)
	{
		if(broadcastTo[i] && !(includeSelf === true && i === id))
		{
			clients++;
			broadcastTo[i].sendUTF(message);
		}
	}
	
	//console.log((new Date()) + ' Broadcasted to ' + clients + ' clients: ' + message);
}

/**
 * Accept connection under the game-protocol
 *
 */
function Game (name, hostID, players)
{
	this.name = name;
	this.hostID = hostID;
	this.players = players;
}		
 
 
function acceptConnectionAsGame(request) 
{
	var connection = request.accept('game-protocol', request.origin);
	
	var i;
	var id = null;
	for(i = 0; i < broadcastTo.length; i++)
	{
		if(broadcastTo[i] === null)
		{
			id = i;
			break;
		}
	}
	
	if(id !== null)
	{
		broadcastTo[id] = connection;
		connection.broadcastId = id;
	}
	else
		connection.broadcastId = broadcastTo.push(connection) - 1;
		
	connection.sendUTF(JSON.stringify({type: 'id', id: connection.broadcastId}));

	function disconnectFromGame()
	{
		if(typeof connection.gameID !== 'undefined')
		{
			console.log('Disconnecting player from previous game.');
			var i, player;
			for(i = 0; i < games[connection.gameID].players.length; i++)
			{
				player = games[connection.gameID].players[i];
				broadcastTo[player].sendUTF(JSON.stringify({type: 'player_disconnect', id: connection.broadcastId}));
			}
		}
		
		var gameID = connection.gameID;

		if(gameID !== null && games[gameID])
		{
			var playerIndex = games[gameID].players.indexOf(connection.broadcastId);
			if(playerIndex !== -1)
				games[gameID].players.splice(playerIndex, 1);
		
			if(games[gameID].hostID === connection.broadcastId)
			{
				
			
				if(games[gameID].players.length > 0)
				{
					games[gameID].hostID = games[gameID].players[0];
					broadcastTo[games[gameID].hostID].sendUTF(JSON.stringify({type:'promote'}));
					
					
					console.log((new Date()) + ' Host dropped from game "' + games[gameID].name + '", gameID = ' + gameID + '. New host: "' + broadcastTo[games[gameID].hostID].user + '", userID = ' + games[gameID].hostID + '.');
				}
				else
				{
					console.log((new Date()) + ' Host dropped from game "' + games[gameID].name + '", gameID = + ' + gameID + '. There are no other players. Game dropped.');
					games[gameID] = null;
				}
			}
			

		}
	
	
	}
	// Callback to handle each message from the client
	connection.on('message', function(message)
	{
		var data = JSON.parse(message.utf8Data);
		if(data.hasOwnProperty('type'))
		{
			switch(data.type)
			{
				case 'block':
					broadcast(message.utf8Data, true, connection.broadcastId);
					break;
				case 'position':
					broadcast(message.utf8Data, true, connection.broadcastId);
					break;
				case 'host':
					disconnectFromGame();
				
					var i;
					for(i = 0; i < games.length; i++)
						if(games[i] !== null && connection.broadcastId === games[i].hostID)
							return;
					
					var id = null;
					for(i = 0; i < games.length; i++)
						if(games[i] === null)
						{
							id = i;
							break;
						}
					
					var game = new Game(data.name, connection.broadcastId, [connection.broadcastId]);
					if(id !== null)
					{
						games[id] = game;
						connection.gameID = id;
					}
					else
						connection.gameID = games.push(game) - 1;
							
					connection.sendUTF(JSON.stringify({type: 'promote'}));
					console.log((new Date()) + ' Games: ' + JSON.stringify(games));
					break;
				case 'join':				
					disconnectFromGame();
				
					if(broadcastTo[data.hostID] && games[broadcastTo[data.hostID].gameID])
					{
						var game = games[broadcastTo[data.hostID].gameID];
					
						var i;
						for(i = 0; i < game.players.length; i++)
							broadcastTo[game.players[i]].sendUTF(JSON.stringify({type:'join', id: connection.broadcastId}));

						game.players.push(connection.broadcastId);
						connection.gameID = broadcastTo[data.hostID].gameID;
					}
					else
						connection.sendUTF(JSON.stringify({type:'error', message: (new Date()) + ' Could not find game.'}));
					break;
				case 'joinStandard':
					disconnectFromGame();
					
					var i;
					for(i = 0; i < games.length; i++)
						if(games[i] && games[i].name === 'Standard')
						{
							var game = games[i];
							if(broadcastTo[game.hostID])
							{
								var i;
								for(i = 0; i < game.players.length; i++)
									broadcastTo[game.players[i]].sendUTF(JSON.stringify({type:'join', id: connection.broadcastId}));

								game.players.push(connection.broadcastId);
								connection.gameID = broadcastTo[game.hostID].gameID;
							}
							
							console.log((new Date()) + 'Joined standard game.');
							return;
						}	
					
					var id = null;
					for(i = 0; i < games.length; i++)
						if(games[i] === null)
						{
							id = i;
							break;
						}
					
					var game = new Game('Standard', connection.broadcastId, [connection.broadcastId]);
					if(id !== null)
					{
						games[id] = game;
						connection.gameID = id;
					}
					else
						connection.gameID = games.push(game) - 1;
					console.log((new Date()) + ' Games: ' + JSON.stringify(games));
					connection.sendUTF(JSON.stringify({type: 'promote'}));
					break;
				case 'scene':
					if(broadcastTo[data.id])
						broadcastTo[data.id].sendUTF(JSON.stringify({type: 'scene', scene: data.scene, players: data.players}));
					break;
				case 'games':
					connection.sendUTF(JSON.stringify({type: 'games', games: games}));
					break;
			}
		}

		
	});
	

	// Callback when client closes the connection
	connection.on('close', function(reasonCode, description) 
	{
		console.log((new Date()) + ' Peer ' + connection.remoteAddress + ' disconnected broadcastid = ' + connection.broadcastId + '.');
		
		disconnectFromGame();
		
		if(broadcastTo[connection.broadcastId])	
			broadcastTo[connection.broadcastId] = null;
		
		var userIndex = users.indexOf(connection.user);
		if(userIndex !== -1)
			users.splice(userIndex, 1);
			
		broadcast(JSON.stringify({type: 'player_disconnect', id: connection.broadcastId}));
		
	});

	return true;
}



/**
 * Create a callback to handle each connection request
 *
 */
wsServer.on('request', function(request) 
{
	var status = null;
	
	if (!originIsAllowed(request.origin))
	{
		// Make sure we only accept requests from an allowed origin
		request.reject();
		console.log((new Date()) + ' Connection from origin ' + request.origin + ' rejected.');
		return;
	}

	// Loop through protocols. Accept by highest order first.
	for (var i=0; i < request.requestedProtocols.length; i++) 
	{
	
		switch(request.requestedProtocols[i])
		{
			case 'broadcast-protocol':
				status = acceptConnectionAsBroadcast(request);
				break;
			case 'echo-protocol':
				status = acceptConnectionAsEcho(request);
				break;
			case 'game-protocol':
				status = acceptConnectionAsGame(request);
				break;
		}
	};

	// Unsupported protocol.
	if(!status) 
	{
		acceptConnectionAsEcho(request, null);
		//console.log('Subprotocol not supported');
		//request.reject(404, 'Subprotocol not supported');
	}

}); 

