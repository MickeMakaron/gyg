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
*/
// Size of the blocks. Lower values will decrease performance.
var BLOCK_SIZE = {width: 10, height: 10};

// Size of the players. Higher values may decrease performance.
var PLAYER_SIZE = {height: 15, width: 15};

// A state is a certain context in the game. For example, the main menu is one state, while the options are another. You can, for example, set DEFAULT_STATE as 'singleplayer', and the game will load as singleplayer on page reload. 
// The following are available:
// * menu
// * singleplayer
var DEFAULT_STATE = 'menu';

// The port and host of the server to connect to. Connection to the server is only required for multiplayer.
var PORT = 8014;
var HOST = 'http://mikaelhernvall.asuscomm.com/';




/** 
 * Shim layer, polyfill, for requestAnimationFrame with setTimeout fallback.
 * http://paulirish.com/2011/requestanimationframe-for-smart-animating/
 */ 

 
window.requestAnimFrame = (function()
{
	return  window.requestAnimationFrame       || 
			window.webkitRequestAnimationFrame || 
			window.mozRequestAnimationFrame    || 
			window.oRequestAnimationFrame      || 
			window.msRequestAnimationFrame     || 
			function( callback )
			{
				window.setTimeout(callback, 1000 / 60);
			};
})();



/**
 * Shim layer, polyfill, for cancelAnimationFrame with setTimeout fallback.
 */
window.cancelRequestAnimFrame = (function(){
  return  window.cancelRequestAnimationFrame || 
          window.webkitCancelRequestAnimationFrame || 
          window.mozCancelRequestAnimationFrame    || 
          window.oCancelRequestAnimationFrame      || 
          window.msCancelRequestAnimationFrame     || 
          window.clearTimeout;
})();


/*
* Helper functions
*
*
*/
function getAbsPos(elementId) 
{
	var element = document.getElementById(elementId);

	var left = element.offsetLeft; 
	var top = element.offsetTop;
	
	
	
	while(element = element.offsetParent)
		left += element.offsetLeft;
		
	
		
	element = document.getElementById(elementId);
	while(element = element.offsetParent)
		top += element.offsetTop;
		
	return [left, top];
}

function contains(left, top, width, height, pX, pY)
{
	if(pX < left)
		return false;
	if(pX > left + width)
		return false;
	
	if(pY < top)
		return false;
	if(pY > top + height)
		return false;
		
	return true;
}

/**
 * Trace the keys pressed
 * http://nokarma.org/2011/02/27/javascript-game-development-keyboard-input/index.html
 */
window.Key = {
	pressed: {},

	LEFT:   37,
	UP:     38,
	RIGHT:  39,
	DOWN:   40,
	SPACE:  32,
	A:      65,
	S:      83,
	D:      68,
	W:      87,
	ESC: 	27,

	clicked: {},

	MLEFT: 0,
	MMIDDLE: 1,
	MRIGHT: 2,
	MPOS: new Vector(0, 0),
	
	moved: false,

	isDown: function(keyCode, keyCode1) 
	{
		return this.pressed[keyCode] || this.pressed[keyCode1];
	},

	onKeydown: function(event) 
	{
		this.pressed[event.keyCode] = true;
		
		var key = event.keyCode;
		if(key === this.UP || key === this.DOWN || key === this.LEFT || key === this.RIGHT)
			return false;
		else
			return true;
		//console.log(event.keyCode + " down");
	},

	onKeyup: function(event) 
	{
		delete this.pressed[event.keyCode];
		//console.log(event.keyCode + " up");
	},

	onMouseDown: function(event)
	{
		this.clicked[event.button] = true;
		//console.log(event.button + " down");
	},

	onMouseUp: function(event)
	{
		delete this.clicked[event.button];
		//console.log(event.button + " up");
	},
	
	onMouseMove: function(event)
	{
		this.moved = true;
	},

	isClicked: function(button)
	{
		return this.clicked[button];
	},
	
	mouseMoved: function()
	{
		if(this.moved === true)
		{
			this.moved = false;
			return true;
		}
		else
			return false;
	},
	
	updateMousePos: function(event)
	{
		var absPos = getAbsPos('pix');
		this.MPOS = new Vector(event.pageX - absPos[0], event.pageY - absPos[1]);
	},
};




$(window).keyup(function(event) { return Key.onKeyup(event);});
$(window).keydown(function(event) { return Key.onKeydown(event);});

$(document).on('contextmenu', function(e) 
{
	if($(e.target).is('#pix'))
		return false;
});

$(document)
	.on('mousedown', function(event) { Key.onMouseDown(event); Key.updateMousePos(event);})
	.on('mouseup', function(event) { Key.onMouseUp(event); Key.updateMousePos(event);})
	.on('mousemove', function(event) { Key.onMouseMove(event); Key.updateMousePos(event); });

	
$(document).focus(function()
{
	window.renderAll = true;
});

/**
 * All positions and forces 
 */
function Vector(x, y) {
  this.x = x || 0;
  this.y = y || 0;
}



function OtherPlayer(id, position)
{
	this.id = id;
	this.position = position || new Vector();
	this.velocity = new Vector();
	this.color = 'white';
	this.hasMoved = true;
	this.lastIndex = {};
	
	this.willCrossThreshold = {left: false, right: false, top: false, bot: false};
	this.indexNeedsUpdate = true;
}



/**
 * A Player as an object.
 */
function Player(position, velocity) 
{
	this.position = position  || new Vector();
	this.velocity = velocity  || new Vector(0,0);
	this.color = 'white';
	
	this.hasMoved = true;
	this.lastIndex = {};

	this.willCrossThreshold = {left: false, right: false, top: false, bot: false};
	this.indexNeedsUpdate = true;
}

OtherPlayer.prototype = Player.prototype = 
{
	width: PLAYER_SIZE.width,
	height: PLAYER_SIZE.height,
	index: {},
	threshold: {left: 0, right: 0, top: 0, bot: 0},

	draw: function(ct) 
	{
		ct.fillStyle = this.color;
		ct.fillRect(this.position.x, this.position.y, this.width, this.height); 
		ct.fillStyle = 'black';
		ct.fillRect(this.position.x+this.width/4, this.position.y+this.height/4, this.width/2, this.height/2); 
	},	

	moveLeft: function(dt) 
	{
		this.velocity.x -= 1000 * dt;
		//console.log('moveLeft');
	},

	moveRight: function(dt) 
	{
		this.velocity.x += 1000 * dt;
		//console.log('moveRight');
	},

	moveUp: function(dt) 
	{
		this.velocity.y -= 1000 * dt;
		//console.log('moveUp');
	},

	moveDown: function(dt) 
	{
		this.velocity.y += 1000 * dt;
		//console.log('moveDown');
	},
	
	move: function(dt)
	{
		if(this.velocity.x > 100)
			this.velocity.x = 100;
		else if(this.velocity.x < -100)
			this.velocity.x = -100;
		
		if(this.velocity.y > 100)
			this.velocity.y = 100;
		else if(this.velocity.y < -100)
			this.velocity.y = -100;
	
		this.position.x += this.velocity.x * dt;
		this.position.y += this.velocity.y * dt;
	},
	
	snapToGrid: function(chart)
	{
		if(chart.x === 0 && chart.y === 0)
			return;
			
		if(chart.x < 0)
			this.position.x = this.threshold.left;
		else if(chart.x > 0)
			this.position.x = this.threshold.right - this.width;
		
		if(chart.y < 0)
			this.position.y = this.threshold.top;
		else if(chart.y > 0)
			this.position.y = this.threshold.bot - this.height;
		
	},

	handleInput: function(dt) 
	{
		if (Key.isDown(Key.UP, Key.W))     this.moveUp(dt);
		if (Key.isDown(Key.LEFT, Key.A))   this.moveLeft(dt);
		if (Key.isDown(Key.DOWN, Key.S))   this.moveDown(dt);
		if (Key.isDown(Key.RIGHT, Key.D))  this.moveRight(dt);
	},

	update: function(dt)
	{
		this.checkThreshold(dt);
		this.move(dt);
	},
	
	stayInArea: function(width, height) 
	{
		if(this.position.y < 0)
		{
			this.position.y = 0;
			this.velocity.y = 0;
		}
		else if(this.position.y > height - this.height)
		{
			this.position.y = height - this.height;
			this.velocity.y = 0;
		}
			
		if(this.position.x < 0)
		{
			this.position.x = 0;
			this.velocity.x = 0;
		}
		else if(this.position.x > width - this.width)    
		{		
			this.position.x = width - this.width;
			this.velocity.x = 0;
		}
	},
	
	
	checkThreshold: function(dt)
	{
		dt = dt || 0;
	
		var left = this.position.x + this.velocity.x * dt,
			right = this.position.x + this.width + this.velocity.x * dt,
			top = this.position.y + this.velocity.y * dt,
			bot = this.position.y + this.height + this.velocity.y * dt;
	
		
		this.willCrossThreshold.top = false;
		this.willCrossThreshold.left = false;
		this.willCrossThreshold.right = false;
		this.willCrossThreshold.bot = false;
	

	
		if(left < this.threshold.left)
			this.willCrossThreshold.left = true;
		else if(right > this.threshold.right)
			this.willCrossThreshold.right = true;
			
		if(top < this.threshold.top)
			this.willCrossThreshold.top = true;
		else if(bot > this.threshold.bot)
			this.willCrossThreshold.bot = true;
		
		var indexNeedsUpdate = false;

		$.each(this.willCrossThreshold, function(index, value)
		{
			if(value === true)
			{
				indexNeedsUpdate = true;
				return false;
			}
		});
		
		this.indexNeedsUpdate = indexNeedsUpdate;
	},
	
	updateIndex: function(step, maxIndex)
	{

	
		if(this.indexNeedsUpdate !== true)
			return null;
	
		var left = this.position.x,
			right = this.position.x + this.width,
			top = this.position.y,
			bot = this.position.y + this.height;
			
		this.index = 
		{
			left: Math.floor(left/step.x),
			right: Math.floor((right - 1)/step.x),
			top: Math.floor(top/step.y),
			bot: Math.floor((bot - 1)/step.y),
		};
	
		this.index.left = this.index.left < maxIndex.left ? maxIndex.left : this.index.left;
		this.index.right = this.index.right > maxIndex.right ? maxIndex.right : this.index.right;
		this.index.top = this.index.top < maxIndex.top ? maxIndex.top : this.index.top;
		this.index.bot = this.index.bot > maxIndex.bot ? maxIndex.bot : this.index.bot;
		
		
		this.threshold = 
		{
			left: (this.index.left) * step.x,
			right: (this.index.right + 1) * step.x,
			top: (this.index.top) * step.y,
			bot: (this.index.bot + 1) * step.y,
		};
	},	
	
	containsIndex: function(index)
	{
		if(index.x < this.index.left || index.x > this.index.right)
			return false;
			
		if(index.y < this.index.top || index.y > this.index.bot)
			return false;
	
		return true;	
	},
	
	
	getBorderIndices: function(side, modification)
	{
		var i;
		var indices = [];
	
		switch(side)
		{
			case 'left':
				for(i = this.index.top; i <= this.index.bot; i++)
					indices.push(new Vector(this.index.left, i));
				break;
			case 'right':
				for(i = this.index.top; i <= this.index.bot; i++)
					indices.push(new Vector(this.index.right, i));
				break;
			case 'top':
				for(i = this.index.left; i <= this.index.right; i++)
					indices.push(new Vector(i, this.index.top));
				break;
			case 'bot':
				for(i = this.index.left; i <= this.index.right; i++)
					indices.push(new Vector(i, this.index.bot));
				break;
			default:
				break;
		}

		if(modification)
			for(i = 0; i < indices.length; i++)
			{
				indices[i].x += modification.x;
				indices[i].y += modification.y;
			}
		
		return indices;
	},
};










function Block(color, clip)
{
	this.color = color || this.color;
	this.clip = typeof clip !== 'undefined' ? clip : this.clip;
}

Block.prototype =
{
	width: BLOCK_SIZE.width,
	height: BLOCK_SIZE.height,
	position: new Vector(0, 0),
	color: 'black',
	clip: true,

	draw: function(ct)
	{
	
		ct.save();
		ct.fillStyle = this.color;
		ct.fillRect (this.position.x, this.position.y, this.width, this.height);
		ct.restore();
	},
	
	drawOutline: function(ct)
	{
		ct.save();
	
		ct.beginPath();
		ct.rect(this.position.x, this.position.y, this.width, this.height);
		ct.lineWidth = 2;
		ct.strokeStyle = 'white';
		ct.stroke();
		
		ct.restore();
	}
};

function DrawItem(obj, func)
{
	this.obj = obj;
	this.func = func || function(ct, obj)
	{
		obj.draw(ct);
	}
}


function World(width, height, socket)
{
	this.player = new Player(new Vector(width/2, height/2));
	this.width = width;
	this.height = height;
	this.step = {x: Block.prototype.width, y: Block.prototype.height};
	this.numblocks = {x: this.width/this.step.x, y: this.height/this.step.y};
	this.maxIndex = 
	{
			left: 0,
			right: Math.floor(this.width/this.step.x) - 1,
			top: 0,
			bot: Math.floor(this.height/this.step.y) - 1
	};

	this.socket = socket;
	
	this.blockTypes = [new Block(), new Block('aqua', false)];
	this.selectedBlock = {};
	this.blocks = [];
	this.players = [new OtherPlayer(this.socket.id, this.player.position)];
	this.lastMouseIndex = {x: 0, y:0};
	
	// The drawing queue contains three arrays, which in turn contain items to be drawn. The three arrays represent different rendering layers: 0 - background, 3 - foreground.
	this.drawQueue = [[], [], [], []];	
	
	this.buildScene();
	

	


}

World.prototype = 
{
	update: function(dt)
	{
		this.updateBlocks();

		this.applyFriction(dt);
		this.player.checkThreshold(dt);
		this.checkCollision();
		
		if(this.player.hasMoved === false)
			if(this.player.velocity.x !== 0 || this.player.velocity.y !== 0)
			{
				this.player.hasMoved = true;
				this.player.lastIndex = this.player.index;
			}
		
		this.player.move(dt);
		this.player.stayInArea(this.width, this.height);
		
		
		this.player.updateIndex(this.step, this.maxIndex);
		
		this.serverUpdate();
	},
	
	serverUpdate: function()
	{
		if(!(this.player.velocity.y === 0 && this.player.velocity.x === 0))
		{
			this.socket.send(
			{
				type: 'position', 
				id: this.socket.id, 
				position: this.player.position
			});
		}
		
		while(this.socket.queue.length > 0)
		{
			this.socket.queue[0](this);
			this.socket.queue.shift();
		}
	},
	
	renderQueueUpdated: function()
	{
		var i;
		for(i = 0; i < this.players.length; i++)
		{
			var player = this.players[i];
			if(player.hasMoved === true)
			{
				var index = player.lastIndex;
				this.drawBlockRect(index.left - 1, index.right + 1, index.top - 1, index.bot + 1);
			}
		}
		
		if(this.player.hasMoved === true)
		{
			var index = this.player.lastIndex;
			this.drawBlockRect(index.left - 1, index.right + 1, index.top - 1, index.bot + 1);
		}

		var isQueueEmpty = true;
		for(i = 0; i < this.drawQueue.length; i++)
			if(this.drawQueue[i].length > 0)
			{
				isQueueEmpty = false;
				break;
			}

		if(isQueueEmpty === false)
		{
			for(i = 0; i < this.players.length; i++)
			{
				var player = this.players[i];
				player.checkThreshold();
				player.updateIndex(this.step, this.maxIndex);
				
				if(player.id !== this.socket.id)
					this.drawQueue[2].push(new DrawItem(player));
			}
			this.drawQueue[3].push(new DrawItem(this.player));
		}
		
		this.player.hasMoved = false;
		
		var i;
		for(i = 0; i < this.players.length; i++)
			this.players[i].hasMoved = false;
		
	},
	
	render: function(ct)
	{	
		if(window.renderAll === true)
		{
			this.renderQueueAll();
			window.renderAll = false;
		}
		else
		{
			this.renderQueueUpdated();
		}

		this.draw(ct);
		
		if(this.socket.isWaitingForHost === true)
		{
			ct.fillStyle = 'black';
			ct.fontSize = this.width < this.height ? this.width/25 : this.height/25;
			var text = 'Waiting for host...';
			var center = new Vector(this.width/6, this.height/6);
			ct.fillText(text, center.x, center.y);
		}
	},
	
	draw: function(ct)
	{
		var i, j;
		for(i = 0; i < this.drawQueue.length; i++)
		{
			j = this.drawQueue[i].length - 1;
			while(j >= 0)
			{
				var drawObj = this.drawQueue[i][j];
				drawObj.func(ct, drawObj.obj);
			
				this.drawQueue[i].pop(j);
				j--;
			}
		}
	},
	
	handleInput: function(dt)
	{
		this.player.handleInput(dt);
		
		if(Key.isDown(Key.ESC) === true)
			this.quit = true;
	},
	
	drawPlayers: function(ct)
	{
		var i;
		for(i = 0; i < this.players.length; i++)
		{

			this.players[i].draw(ct);
		}
	},
	
	buildScene: function()
	{
		var i;
		for(i = 0; i < this.numblocks.y; i++)
		{
			this.blocks.push([]);
			
			var j;
			for(j = 0; j < this.numblocks.x; j++)
			{
				this.blocks[i].push(1);
			}
		}
		
		this.renderQueueAll();
		this.player.updateIndex(this.step, this.maxIndex);
	},
	
	// Add all items to the drawing queue.
	renderQueueAll: function()
	{
	
		// Add all blocks
		this.drawBlockRect(this.maxIndex.left, this.maxIndex.right, this.maxIndex.top, this.maxIndex.bot);
		
		// Add player
		this.drawQueue[3].push(new DrawItem(this.player));
		
		
		// Add other players
		for(i = 0; i < this.players.length; i++)
			this.drawQueue[2].push(new DrawItem(this.players[i]));
	},
	
	
	applyGravity: function(dt)
	{
		this.player.velocity.y += 500*dt;
	},
	
	applyFriction: function(dt)
	{
		var damp = 500 * dt;
		
		var v = this.player.velocity;
		
		
		if(v.x > 0)
		{
			if(v.x - damp <= 0)
				this.player.velocity.x = 0;
			else
				this.player.velocity.x -= damp;
		}
		else if(v.x < 0)
		{
			if(v.x + damp >= 0)
				this.player.velocity.x = 0;
			else
				this.player.velocity.x += damp;
		}
		
		
		if(v.y > 0)
		{
			if(v.y - damp <= 0)
				this.player.velocity.y = 0;
			else
				this.player.velocity.y -= damp;
		}
		else if(v.y < 0)
		{
			if(v.y + damp >= 0)
				this.player.velocity.y = 0;
			else
				this.player.velocity.y += damp;
		}
	},
	
	getBlockType: function(index)
	{	
		if(this.indexExists(index) === true)
			return (this.blockTypes[this.blocks[index.y][index.x]]);
			
		return false;
	},
	
	blocksHaveClip: function(indices, breakOnFind)
	{	
		if(!(indices.length > 0))
			return false;
	
		var i, index;
		for(i = 0; i < indices.length; i++)
		{
			index = indices[i];

			if(breakOnFind === true)
			{
				if(this.getBlockType(index).clip === true)
					return index;
			}
			else					
				if(this.getBlockType(index).clip !== true)
					return false;
		}
		
		if(breakOnFind === true)
			return false;
		
		return true;
	},
	
	checkCollision: function()
	{
		var snapChart = new Vector(0, 0);
		var zerofyVelocity = {x:false, y:false};

		var i;
		if(this.player.willCrossThreshold.left || this.player.willCrossThreshold.right)
		{
			var indices = this.player.willCrossThreshold.left ? this.player.getBorderIndices('left', new Vector(-1, 0)) : this.player.getBorderIndices('right', new Vector(1, 0));
			
			var result = this.blocksHaveClip(indices, true);
			
			var self = this;
			function setSnap()
			{
				zerofyVelocity.x = true;
				snapChart.x = self.player.willCrossThreshold.left ? -1 : 1;
			}

			var checkInnerIndex = this.player.willCrossThreshold.left ? (this.blocksHaveClip(this.player.getBorderIndices('left'), true) !== false) : (this.blocksHaveClip(this.player.getBorderIndices('right'), true) !== false);
			
			if(result !== false || checkInnerIndex)
			{
				var legSize = Math.floor(this.player.height/(6 * this.step.y));
				var botIsClipping = (this.blocksHaveClip(this.player.getBorderIndices('bot', new Vector(0, 1)), true) !== false);
				var topIsClipping = (this.blocksHaveClip(this.player.getBorderIndices('top', new Vector(0, -1)), true) !== false);				
				
				function setClimb(direction, stepSize)
				{
					var index = self.player.index;
					self.drawBlockRect(index.left, index.right, index.top, index.bot);
					
					if(direction < 0)
					{
						self.player.threshold.bot -= stepSize * self.step.y;
						snapChart.y = 1;
					}
					else if(direction > 0)
					{
						self.player.threshold.top += stepSize * self.step.y;
						snapChart.y = -1;
					}
				}
				
				var first = indices[0];
				var end = indices[indices.length - 1];
				
				function checkIfClimbClips(stepSize, sideIsClipping, index, direction)
				{
					if(!sideIsClipping && self.blocksHaveClip([index]) === false)
						setClimb(direction, stepSize);
					else
						return false;
				}
				
				function checkResult()
				{
					if(result.y > end.y - legSize && botIsClipping)
					{
						stepSize = end.y - result.y + 1;
						return checkIfClimbClips(stepSize, topIsClipping, new Vector(first.x, first.y - stepSize), -1);
					}
					else
						return false;
				}
				
				function checkReversedResult()
				{
					var reversedResult = self.blocksHaveClip(JSON.parse(JSON.stringify(indices)).reverse(), true);
					if(reversedResult.y < first.y + legSize && topIsClipping)
					{
						stepSize = reversedResult.y - first.y + 1;
						return checkIfClimbClips(stepSize, botIsClipping, new Vector(first.x, end.y + stepSize), 1);
					}
					else
						return false;
				}
				
				if(this.player.velocity.y < 0 && !checkResult())
					setSnap();
				else if(this.player.velocity.y > 0 && !checkReversedResult())
					setSnap();
				else if(!(checkResult() || checkReversedResult()))
					setSnap();
			}
			else 
			{
				
				
				if(this.player.willCrossThreshold.top)
				{			
					var indices = this.player.getBorderIndices('top', new Vector(0, -1));
					var corner = this.player.willCrossThreshold.left ? new Vector(indices[0].x - 1, indices[0].y) : new Vector(indices[indices.length - 1].x + 1, indices[0].y);
					if(this.blocksHaveClip(indices, true) === false && this.blocksHaveClip([corner]) === true)
						setSnap();
				}
				else if(this.player.willCrossThreshold.bot)
				{
					var indices = this.player.getBorderIndices('bot', new Vector(0, 1));
					var corner = this.player.willCrossThreshold.left ? new Vector(indices[0].x - 1, indices[0].y) : new Vector(indices[indices.length - 1].x + 1, indices[0].y);
					if(this.blocksHaveClip(indices, true) === false && this.blocksHaveClip([corner]) === true)
						setSnap()
				}
			}
		}
		if(this.player.willCrossThreshold.top || this.player.willCrossThreshold.bot)
		{
			var indices = this.player.willCrossThreshold.top ? this.player.getBorderIndices('top', new Vector(0, -1)) : this.player.getBorderIndices('bot', new Vector(0, 1));
			
			var result = this.blocksHaveClip(indices, true);
			
			
			var self = this;
			function setSnap()
			{
				zerofyVelocity.y = true;
				snapChart.y = self.player.willCrossThreshold.top ? -1 : 1;
			}
			
			var checkInnerIndex = this.player.willCrossThreshold.top ? (this.blocksHaveClip(this.player.getBorderIndices('top'), true) !== false) : (this.blocksHaveClip(this.player.getBorderIndices('bot'), true) !== false);
			
			if(result !== false || checkInnerIndex)
			{
				var legSize = Math.floor(this.player.width/(6 * this.step.x));
				var rightIsClipping = (this.blocksHaveClip(this.player.getBorderIndices('right', new Vector(1, 0)), true) !== false);
				var leftIsClipping = (this.blocksHaveClip(this.player.getBorderIndices('left', new Vector(-1, 0)), true) !== false);

				var self = this;
				function setClimb(direction, stepSize)
				{
					if(direction < 0)
					{
						self.player.threshold.right -= stepSize * self.step.x;
						snapChart.x = 1;
					}
					else if(direction > 0)
					{
						self.player.threshold.left += stepSize * self.step.x;
						snapChart.x = -1;
					}
				}

				var first = indices[0];
				var end = indices[indices.length - 1];

				function checkIfClimbClips(stepSize, sideIsClipping, index, direction)
				{
					if(!sideIsClipping && self.blocksHaveClip([index]) === false)
					{
						setClimb(direction, stepSize);
						return true;
					}
					else
						return false;
				}

				function checkResult()
				{
					if(result.x > end.x - legSize && rightIsClipping)
					{
						stepSize = end.x - result.x + 1;
						checkIfClimbClips(stepSize, leftIsClipping, new Vector(first.x - stepSize, first.y), -1);
					}
					else
						setSnap();
				}

				function checkReversedResult()
				{
					var reversedResult = self.blocksHaveClip(JSON.parse(JSON.stringify(indices)).reverse(), true);
					if(reversedResult.x < first.x + legSize && leftIsClipping)
					{
						stepSize = reversedResult.x - first.x + 1;
						checkIfClimbClips(stepSize, rightIsClipping, new Vector(first.x + stepSize, end.y), 1);
						return true;
					}
					else
						return false;
				}
				if(this.player.velocity.x < 0 && !checkResult())
					setSnap();
				else if(this.player.velocity.x > 0 && !checkReversedResult())
					setSnap();
				else if(!(checkResult() || checkReversedResult()))
					setSnap();
			}
		}
		
		this.player.velocity.x = zerofyVelocity.x === true ? 0 : this.player.velocity.x;
		this.player.velocity.y = zerofyVelocity.y === true ? 0 : this.player.velocity.y;
		this.player.snapToGrid(snapChart);
	},
	
	drawBlocks: function(indices)
	{
		var index, type, block;
	
		var i;
		for(i = 0; i < indices.length; i++)
		{
			index = indices[i];
			type = this.blockTypes[this.blocks[index.y][index.x]];
			block = new Block(type.color, type.clip);

			block.position = new Vector(index.x * this.step.x, index.y * this.step.y);

			if(this.selectedBlock.x === index.x && this.selectedBlock.y === index.y)
				this.drawQueue[1].push(new DrawItem(block, function(ct, obj)
				{
					obj.drawOutline(ct);
				}));
			
			this.drawQueue[0].push(new DrawItem(block));
		}

	},
	
	drawBlockRect: function(left, right, top, bot)
	{	
		left =	left	< this.maxIndex.left 	? this.maxIndex.left 	: left;
		right = right	> this.maxIndex.right 	? this.maxIndex.right 	: right;
		top =	top		< this.maxIndex.top 	? this.maxIndex.top 	: top;
		bot =	bot 	> this.maxIndex.bot 	? this.maxIndex.bot 	: bot;
		
		
		
		var position = new Vector(0, 0);
		var i;
		var count = 0;
		for(i = top; i <= bot; i++)
		{
			var j;
			for(j = left; j <= right; j++)
			{

			/*
				var blockID = this.blocks[i][j];

				var type = this.blockTypes[blockID];
				
				var block = new Block(type.color, type.clip);
				
				position = new Vector(j * this.step.x, i * this.step.y);
				block.position = JSON.parse(JSON.stringify(position));
				
				if(this.selectedBlock.x === j && this.selectedBlock.y === i)
					this.drawQueue[1].push(new DrawItem(block, function(ct, obj)
					{
						obj.drawOutline(ct);
					}));
			
				this.drawQueue[0].push(new DrawItem(block));
				*/
				
				this.drawBlocks([{x: j, y: i}]);
				
				count++;

				
				position.x += this.step.x;
			}
			
			position.x = 0;
			position.y += this.step.y;
		}
	},
	
	
	createLine: function(p1, p2, blockType)
	{
		var diff = new Vector(p2.x - p1.x, p2.y - p1.y);		
		var diffAbs = new Vector(Math.abs(diff.x), Math.abs(diff.y));

		if(!(diffAbs.x > 1 || diffAbs.y > 1))
			return false;
		
		var fill = [];
		var sign = {};
		sign.x = diff.x > 0 ? 1 : -1;
		sign.y = diff.y > 0 ? 1: -1;
		var i = JSON.parse(JSON.stringify(p1));
	
		while(diffAbs.x > 0 || diffAbs.y > 0)
		{
			if(diffAbs.x > diffAbs.y)
			{
				i.x += sign.x;
				diffAbs.x--;
			}
			else if(diffAbs.x < diffAbs.y)
			{
				i.y += sign.y;
				diffAbs.y--;
			}
			else
			{
				i.x += sign.x;
				diffAbs.x--;
				
				i.y += sign.y;
				diffAbs.y--;
			}
			
			if(this.indexExists(i) === true && this.playerContainsIndex(i) === false)
				fill.push(new Vector(i.x, i.y));
		}

	
	
		if(fill.length > 0)
		{
			var index;
			
			var i;
			for(i = 0; i < fill.length; i++)
			{
				index = fill[i];
				this.blocks[index.y][index.x] = blockType;
				
				this.drawBlocks([index]);
				this.socket.send({type: 'block', block: 0, index: index});
			}

			return true;
		}

		return false;
	},
	
	paint: function(blockType)
	{
		var index = {x: Math.floor(Key.MPOS.x / this.step.x), y: Math.floor(Key.MPOS.y / this.step.y)};
		
		if(this.createLine(this.lastMouseIndex, index, blockType) === false)
		{
			if(this.indexExists(index) === true)
				if(this.blocks[index.y][index.x] !== blockType && (this.playerContainsIndex(index) === false || blockType === 1))
				{
					this.blocks[index.y][index.x] = blockType;
					this.drawBlocks([index]);
					this.socket.send({type: 'block', block: blockType, index: index});
				}
		}
	},
	
	
	updateBlocks: function()
	{	
		if(Key.isClicked(Key.MLEFT))
			this.paint(0);
		else if(Key.isClicked(Key.MRIGHT))
			this.paint(1);
		
		if(Key.mouseMoved())
		{
			var index = {x: Math.floor(Key.MPOS.x / this.step.x), y: Math.floor(Key.MPOS.y / this.step.y)};
			
			if(index.x !== this.selectedBlock.x || index.y !== this.selectedBlock.y)
			{
				var ind = JSON.parse(JSON.stringify(this.selectedBlock));	

				if(this.indexExists(index))
				{
					this.selectedBlock = JSON.parse(JSON.stringify(index));
					this.drawBlocks([index]);
				}
				else
				{
					this.selectedBlock = {x: null, y: null};
				}
				
				
				this.drawBlockRect(ind.x - 2, ind.x + 2, ind.y - 2, ind.y + 2);
				
				this.lastMouseIndex = JSON.parse(JSON.stringify(index));
			}
		}
	},
	
	drawGrid: function(ct)
	{
		var position = new Vector(0, 0);
	
		var i;
		for(i = 0; i < this.blocks.length; i++)
		{
			var j;
			for(j = 0; j < this.blocks[i].length; j++)
			{
				ct.beginPath();
				ct.moveTo(position.x, 0);
				ct.lineTo(position.x, this.height);
				ct.stroke();
				
				position.x += this.step.x;
			}
			
			ct.beginPath();
			ct.moveTo(0, position.y);
			ct.lineTo(this.width, position.y);
			ct.stroke();
			
			position.x = 0;
			position.y += this.step.y;
		}
	},
	
	playerContainsIndex: function(index)
	{
		if(this.player.containsIndex(index) === true)
			return true;
			
		var i;
		for(i = 0; i < this.players.length; i++)
		{
			var player = this.players[i];
			if(player.id !== this.socket.id)
				if(player.containsIndex(index) === true)
					return true;
		}
		
		return false;
	},
	
	indexExists: function(index)
	{
		if(index.x < 0)
			return false;
		if(index.y < 0)
			return false;
		if(index.y >= this.blocks.length)
			return false;
		if(index.x >= this.blocks[index.y].length)
			return false;
			
		return true;
	}

}


function Socket(host, port, protocol)
{
	this.host = host || this.host;
	this.port = port || this.port;
	this.protocol = protocol || this.protocol;	
}

Socket.prototype =
{
	websocket: null,
	host: HOST,
	port: PORT,
	protocol: 'game-protocol',
	timeout: 5000,
	connected: false,
	//username: null,
	queue: [],
	games: [],
	isHost: false,
	id: null,
	
	connect: function(user, success, fail)
	{
		console.log('Connecting to ' + this.host + ':' + this.port);
		this.init(user, success, fail);
	},
	
	
	disconnect: function()
	{
		if(this.websocket) 
		{
			this.websocket.close();
			this.websocket = null;
		}
	},
	
	init: function(user, success, fail)
	{
		this.disconnect();

		var url = 'ws://' + this.host + ':' + this.port;
		this.websocket = new WebSocket(url, this.protocol);
		
		var self = this;
		this.websocket.onopen = function()
		{
			if(this)
			{
				self.connected = true;
				self.isWaitingForHost = true;
				
				if(success)
					success();

				console.log('Sending authorization check.');
			}
		
			console.log('The websocket is now open.');
			
			//websocket.send('Thanks for letting me connect to you.');
			
		}

		
		this.websocket.onmessage = function(event) 
		{		

/*		
			if(event.data === 'invalid_user')
			{
				console.log('That username is already taken, please try a different username.');
				if(fail)
					fail();
				return;
			}
			else if(event.data === 'valid_user')
			{
				//outputLog('Welcome, ' + user + '!');
				if(success)
					success();
				self.connected = true;
				self.username = user;
				
				self.send({type: 'join', name: null});
				console.log('Connected with user: ' + user);		
				
				return;
			}
			else*/
			{
				var data = JSON.parse(event.data);
				
				if(data.hasOwnProperty('type'))
					switch(data.type)
					{
						case 'id':
							self.id = data.id;
							break;
						case 'player_connect':
							self.queue.push(function(obj)
							{
								
							
							});
							break;
						case 'player_disconnect':
							self.queue.push(function(obj)
							{							
								var i;
								for(i = 0; i < obj.players.length; i++)
								{
									if(obj.players[i].id === data.id)
									{	
										var index = obj.players[i].index;
										obj.drawBlockRect(index.left - 2, index.right + 2, index.top - 2, index.bot + 2);
										obj.players.splice(i, 1);
										
										break;
									}
								}
							});
							break;
						case 'position':
							self.queue.push(function(obj)
							{
								var i;
								for(i = 0; i < obj.players.length; i++)
								{
									if(obj.players[i].id === data.id)
									{
										var player = obj.players[i];
										
										
										if(player.hasMoved !== true)
										{
											player.lastIndex = player.index;
											player.hasMoved = true;
										}
							
										player.hasMoved = true;
										player.position = data.position;									
										break;
									}
								}
							});
							break;
						case 'block':
							self.queue.push(function(obj)
							{
								if(data.index.x >= 0 && data.index.y >= 0)
									if(data.index.y < obj.blocks.length && data.index.x < obj.blocks[data.index.y].length)
									{
										obj.blocks[data.index.y][data.index.x] = data.block;
										obj.drawBlocks([data.index]);
										/*
										
										var type = obj.blockTypes[data.block];
										var block = new Block(type.color, type.clip);
										block.position = new Vector(data.index.x * obj.step.x, data.index.y * obj.step.y);

										obj.drawQueue[1].push(new DrawItem(block));*/
									}
							});
							break;
						case 'join':
							self.queue.push(function(obj)
							{
								if(self.id !== data.id)
								{

									console.log('Received new player.');
									var player = new OtherPlayer(data.id, new Vector(obj.width/2, obj.height/2));
									obj.players.push(player);
									obj.drawQueue[2].push(new DrawItem(player));

									if(self.isHost !== false)
									{
										console.log('Host received join request. Sending scene.');
										self.send({type: 'scene', scene: obj.blocks, players: obj.players, id: data.id});
									}
								}
							});
							break;
						case 'scene':
							self.queue.push(function(obj)
							{
								obj.blocks = data.scene;
								obj.drawQueue[0] = [];
								obj.drawBlockRect(obj.maxIndex.left, obj.maxIndex.right, obj.maxIndex.top, obj.maxIndex.bot);
								
								console.log('Received scene from host.');
								
								var i;
								for(i = 0; i < data.players.length; i++)
								{
									if(data.players[i].id !== self.id)
									{
										var tmp = new OtherPlayer(data.players[i].id, data.players[i].position);
										obj.players.push(tmp);
										obj.drawQueue[2].push(new DrawItem(tmp));
									}
								}
								obj.socket.isHost = false;
								obj.socket.isWaitingForHost = false;
								
							});
							break;
						case 'promote':
						
							self.queue.push(function(obj)
							{
								obj.renderQueueAll();
								obj.socket.isHost = true;
							});
							self.isWaitingForHost = false;
						case 'games':
							self.games = data.games;
							break;
					}
			
			}
			
			
		}

		this.websocket.onclose = function() 
		{
			self.connected = false;
			console.log('The websocket is now closed.');
		}
		
		return true;
	},
	
	send: function(obj)
	{
		if(this.connected === true)
		{
			this.websocket.send(JSON.stringify(obj));
			return true;
		}
		else
		{
			console.log('could not send');
			return false;
		}
	},
};

function Button(position, size, text, fontSize, callback, disabled)
{
	this.x = position.x || 0;
	this.y = position.y || 0;
	this.width = size.x || 0;
	this.height = size.y || 0;
	this.text = text || '';
	this.textPos = new Vector(this.x + this.width/2 - this.text.length * fontSize/4, this.y + this.height/2 - fontSize/2);
	this.callback = callback || function(){return false;};
	this.disabled = disabled === true ? true : false;
	
	if(this.disabled === true)
		this.disable();
	else
		this.enable();
}

Button.prototype = 
{
	draw: function(ct)
	{
		ct.beginPath();
		ct.rect(this.x, this.y, this.width, this.height);
		ct.fillStyle = this.color;
		ct.fill();
		ct.lineWidth = 5;
		ct.strokeStyle = this.borderColor;
		ct.stroke();	
		
		ct.fillStyle = this.textColor;
		ct.textBaseline = "top";
		ct.fillText(this.text, this.textPos.x, this.textPos.y);
	},

	select: function()
	{
		if(this.disabled === false)
		{
			this.color = 'white';
			this.borderColor = 'gray';
			this.textColor = 'gray';
		}
	},
	
	deselect: function()
	{
		if(this.disabled === false)
		{
			this.color = 'gray';
			this.borderColor = 'silver';
			this.textColor = 'white';
		}
	},
	
	enable: function()
	{
		this.disabled = false;
		this.color = 'gray';
		this.borderColor = 'silver';
		this.textColor = 'white';
	},
	
	disable: function()
	{
		this.disabled = true;
		this.color = 'silver';
		this.borderColor = 'gray';
		this.textColor = 'gray';
	}
}



function MenuState(width, height, fontSize, section)
{
	this.width = width;
	this.height = height;
	this.needsRender = true;
	this.selectedButton = null;
	this.fontSize = fontSize;
	this.connecting = false;
	this.connectionTime = 0;
	this.loadingBar = {};
	this.init(section);
	this.selectedServer = null;
	this.step = new Vector(this.width/24, this.height/24);
	this.title = 
	[
		[1,1,1,0,0,1,0,1,0,0,0,1],
		[1,0,0,1,0,1,0,0,1,0,1,0],
		[1,0,0,1,0,1,0,0,0,1,0,0],
		[1,1,1,0,0,1,0,0,0,1,0,0],
		[1,0,0,0,0,1,0,0,1,0,1,0],
		[1,0,0,0,0,1,0,1,0,0,0,1],
	];
	
	this.titleSize = new Vector(this.title[0].length * this.step.x, this.title.length * this.step.y);
	this.titlePos = new Vector(this.width/2 - this.titleSize.x/2, this.height/6);
	this.gridLineWidth = this.step.x < this.step.y ? this.step.x/20 : this.step.y/20;
}

MenuState.prototype =
{
	update: function(dt)
	{
		if(this.connecting === true)
		{
			if(this.connectionTime < 5)
			{
				this.loadingBar.width += dt * this.loadingBar.step;
				this.connectionTime += dt;
			}
			else
			{
				this.connecting = false;
				this.needsRender = true;
				this.init('connection-failed');
			}
		}
		else
		{
				
			if(Key.mouseMoved())
				this.needsRender = true;
		
			if(this.inGameList === true)
			{
				if(this.connectionTime > 2)
				{
					this.socket.send({type: 'games'});
					this.updateGameList(dt);
					this.connectionTime = 0;
					this.needsRender = true;
				}
				else
					this.connectionTime += dt;
				
				this.updateButtons(this.buttons.concat(this.games));
			}
			else
				this.updateButtons(this.buttons);
		}
	},

	render: function(ct)
	{
		if(this.connecting === true)
		{
			ct.fillStyle = 'black';
			ct.fillRect(0, 0, this.width, this.height);

			ct.fillStyle = 'white';
			ct.textBaseline = "top";
			var text = 'Connecting...';
			ct.fillText(text, this.width/2 - text.length * this.fontSize/4, this.height/2);

			
			ct.fillStyle = 'orange';
			ct.fillRect(this.loadingBar.left, this.loadingBar.top, this.loadingBar.width, this.loadingBar.height);
			
			
			ct.beginPath();
			ct.rect(this.loadingBar.left, this.loadingBar.top, this.loadingBar.maxWidth, this.loadingBar.height);
			ct.lineWidth = 5;
			ct.strokeStyle = 'gray';
			ct.stroke();	
		}
		else
		{
			if(this.needsRender === true)
			{


				this.drawBackground(ct);
				this.drawButtons(ct);
				this.needsRender = false;
			

				
				if(this.inGameList === true)
				{
					this.drawGames(ct);
				}
				else if(this.inAbout === true)
				{
					ct.save();
					var pos = new Vector(this.width/12, this.height/12);
					var i;
					ct.fillStyle = 'black';
					for(i = 0; i < this.text.length; i++)
					{
						ct.fillText(this.text[i], pos.x, pos.y);
						pos.y += this.step.y;
					}
					ct.restore();
				}
			}
		}
	},
	
	handleInput: function(dt)
	{
		if(Key.isClicked(Key.MLEFT))
		{
			if(this.selectedButton !== null && this.selectedButton.disabled === false)
			{			
				this.needsRender = true;
				this.selectedButton.callback();
				delete Key.clicked[Key.MLEFT];
			}
		}
	},
	
	drawBackground: function(ct)
	{
		
		ct.fillStyle = 'lime';
		ct.fillRect(0, 0, this.width, this.height);
		
		ct.lineWidth = this.gridLineWidth;
		ct.strokeStyle = 'silver';
		ct.beginPath();
		
		var x;
		for(x = 0; x < this.width; x += this.step.x)
		{
			ct.moveTo(x, 0);
			ct.lineTo(x, this.height);
		}
		
		var y;
		for(y = 0; y < this.height; y += this.step.y)
		{
			ct.moveTo(0, y);
			ct.lineTo(this.width, y);
		}
		
		ct.stroke();
		
		if(this.inGameList !== true && this.inAbout !== true)
		{
			var pixPos = JSON.parse(JSON.stringify(this.titlePos));
			ct.fillStyle = 'black';
			for(y = 0; y < this.title.length; y++)
			{
				for(x = 0; x < this.title[y].length; x++)
				{
					if(this.title[y][x] === 1)
						ct.fillRect(pixPos.x, pixPos.y, this.step.x, this.step.y);
					pixPos.x += this.step.x;
				}
				pixPos.x = this.titlePos.x;
				pixPos.y += this.step.y;
			}
		}
	},
	
	drawButtons: function(ct)
	{
		var i;
		for(i = 0; i < this.buttons.length; i++)
			this.buttons[i].draw(ct);
	},
	
	drawGames: function(ct)
	{
		var i, game;
		for(i = 0; i < this.games.length; i++)
		{
			game = this.games[i];

			if(this.selectedGame === i)
			{
				ct.beginPath();
				ct.rect(game.x, game.y, game.width, game.height);
				ct.fillStyle = 'orange';
				ct.fill();
				ct.lineWidth = 5;
				ct.strokeStyle = 'yellow';
				ct.stroke();
				
				ct.fillStyle = 'white';
				ct.fillText(game.text, game.textPos.x, game.textPos.y);
			}
			else
				game.draw(ct);
			
		}	
	},
	
	updateButtons: function(buttons)
	{
		var i, button;
		for(i = 0; i < buttons.length; i++)
		{
			button = buttons[i];
			if(button.disabled === false && contains(button.x, button.y, button.width, button.height, Key.MPOS.x, Key.MPOS.y))
			{
				if(this.selectedButton !== null)
					this.selectedButton.deselect();
					
				this.selectedButton = button;
				this.selectedButton.select();
				return;
			}
		}
		
		if(this.selectedButton !== null)
			this.selectedButton.deselect();
		this.selectedButton = null;
	},
	
	updateGameList: function(dt)
	{
		this.games = this.socket.games;
		var top = this.height/6;
		var left = this.width/2;
		var self = this;
		
		var gameHeight = this.height/20;
		var gameWidth = left - this.width/6;
		
		var tmp = [new Button
					(
						new Vector(left, top - this.width/12),
						new Vector(gameWidth + gameWidth/3, gameHeight + gameHeight/3),
						'Game name (players):',
						this.fontSize,
						function(){return false;},
						true
					)];
		var i, game;
		for(i = 0; i < this.games.length; i++)
		{	
			game = this.games[i];
			if(game)
			{
				if(!game.name || game.name.length === 0)
					game.name = tmp.length;
				
				var button = (function(left, top, gameWidth, gameHeight, game, fontSize, self, length)
				{
					var button = new Button
					(
						new Vector(left, top),
						new Vector(gameWidth, gameHeight),
						game.name + ' (' + game.players.length + ')',
						fontSize,
						function()
						{
							if(self.selectedGame === length)
							{
								self.selectedGame = null;
								self.buttons[0].disable();
							}
							else
							{
								self.selectedGame = length;
								self.buttons[0].enable();
							}
						}
					);
					button.hostID = game.hostID;
					return button;
					
				})(left, top, gameWidth, gameHeight, game, this.fontSize, self, tmp.length);

				tmp.push(button);
				
				top += gameHeight*1.5;
			}
		}
		if(tmp.length === 1)
			tmp.push(new Button
					(
						new Vector(left, top),
						new Vector(gameWidth, gameHeight),
						'No results...',
						this.fontSize,
						function(){return false;},
						true
					));
		
		this.games = tmp;
	},	
	
	init: function(section)
	{
		var self = this;
		var buttonSize = new Vector(this.width/3, this.height/10);
		var centerX = this.width/2 - buttonSize.x/2;
		this.needsRender = true;
		switch(section)
		{
			case 'main':
				var singleplayer = new Button
				(
					new Vector(centerX - buttonSize.x/1.5, this.height/2), 
					buttonSize, 
					'Singleplayer', 
					this.fontSize, 
					function(){self.init('singleplayer');}
				);
				var multiplayer = new Button
				(
					new Vector(centerX + buttonSize.x/1.5, this.height/2), 
					buttonSize, 
					'Multiplayer', 
					this.fontSize, 
					function(){self.init('multiplayer');}
				);

				var about = new Button
				(
					new Vector(centerX, this.height*(4/6)),
					buttonSize, 
					'About', 
					this.fontSize, 
					function(){self.init('about');}
				);
				
				this.buttons = [singleplayer, multiplayer, about];
				break;
			case 'multiplayer':
				if(this.connecting !== true)
				{
					this.socket = new Socket();
					
					var success = function()
					{
						self.connecting = false;
						self.init('game-list');
					};
					
					this.socket.connect('', success);
					this.connecting = true;
					
					this.connectionTime = 0;
					var loadingBarMaxWidth = this.width/2;
					
					this.loadingBar = 
					{
						left: this.width/2 - loadingBarMaxWidth/2,
						top: this.height*(4/6),
						height: this.height/20,
						width: 0,
						step: loadingBarMaxWidth/5,
						maxWidth: loadingBarMaxWidth,
					};
				}
				break;
			case 'game-list':
				var left = this.width/12;
			
				var join = new Button
				(
					new Vector(left, this.height*(1/6)),
					buttonSize,
					'Join',
					this.fontSize,
					function()
					{
						setTimeout(function()
						{
							self.socket.send({type: 'join', hostID: self.games[self.selectedGame].hostID}); 

						}, 1000);
						
						self.isPopped = true; 
						self.newState = 'game';
					},
					true
				);
				
				var joinStandard = new Button
				(
					new Vector(left, this.height*(2/6)),
					buttonSize,
					'Join standard',
					this.fontSize,
					function(){self.socket.send({type: 'joinStandard'}); self.isPopped = true; self.newState = 'game';}
				);
				
				var host = new Button
				(
					new Vector(left, this.height*(3/6)),
					buttonSize,
					'Host',
					this.fontSize,
					function(){self.socket.send({type: 'host', name: ''}); self.isPopped = true; self.newState = 'game';}
				);
				
				var back = new Button
				(
					new Vector(left, this.height*(4/6)),
					buttonSize,
					'Back',
					this.fontSize,
					function(){self.init('main'); self.inGameList = false;}
				);
				
				this.inGameList = true;
				this.connectionTime = 5;
				this.games = this.socket.games;
				
				this.buttons = [join, joinStandard, host, back];
				
				this.socket.send({type: 'games'});
				
				break;
			case 'connection-failed':
				this.buttons = [new Button
				(
					new Vector(centerX - buttonSize.x/2, this.height/2),
					new Vector(buttonSize.x * 2, buttonSize.y),
					'Connection timed out',
					this.fontSize,
					function(){self.init('main');}
				)];
				break;
			case 'singleplayer':
				this.isPopped = true; 
				this.newState = 'singleplayer';
				break;
			case 'about':
				this.inAbout = true;
				this.buttons = [new Button
				(
					new Vector(centerX, this.height*(4/6)),
					buttonSize,
					'Back',
					this.fontSize,
					function(){self.init('main'); self.inGameList = false; self.inAbout = false;}
				)];
				this.text = 
				[
					"PIX is a school project by a student",
					"at BTH in Sweden. It's entirely free.",
					"",
					"Source code is available under GNU v3.0.",
					"",
					"PIX is more of a playground than a game.",
					"You navigate a cube in a limited environment.",
					"You can modify the environment by clicking",
					"your mouse.",
					"Black cubes are collideable.",
					"Multiplayer is supported.",
					"",
				];
				
				this.needsRender = true;
				break;
			default:
				this.init('main');
				break;
		}
	},
};

function GameState(width, height, socket)
{
	this.socket = socket || new Socket();
	this.world = new World(width, height, this.socket);
}

GameState.prototype =
{
	isPopped: false,
	newState: '',

	update: function(dt)
	{
		this.world.update(dt);
		
		if(this.world.quit === true)
		{
			this.isPopped = true;
			this.newState = 'main';
			this.socket.disconnect();
		}
	},

	render: function(ct)
	{
		this.world.render(ct);
	},
	
	handleInput: function(dt)
	{
		this.world.handleInput(dt);
	},
};


window.Game = (function()
{
	var canvas, ct, world, TIME_PER_FRAME, timeSinceLastUpdate, timeOfLastUpdate, socket, mState, states, fontSize;

	
	var init = function(canvas) 
	{
		canvas = document.getElementById(canvas);
		ct = canvas.getContext('2d');

		width = canvas.getAttribute('width').replace('px',''),
		height = canvas.getAttribute('height').replace('px','');
		fontSize = width < height ? width/25 : height/25;
		ct.font = 'bold ' + fontSize + 'px sans-serif';
		TIME_PER_FRAME = 1/60,
		timeSinceLastUpdate = 0,
		timeOfLastUpdate = 0;

		changeState(DEFAULT_STATE);
		
		
		//world = new World(width, height, socket);
		

		console.log('Init the game');
	};
	

	var update = function(dt) 
	{
		if(mState.isPopped == true)
			changeState(mState.newState);
	
		mState.handleInput(dt);
		mState.update(dt);
	};

	var render = function(ct)
	{
		mState.render(ct);
	};

	var gameLoop = function()
	{
		var now = Date.now();
		dt = (now - (timeOfLastUpdate || now)) / 1000;
		timeOfLastUpdate = now;

		timeSinceLastUpdate += dt;
		
		while(timeSinceLastUpdate > TIME_PER_FRAME)
		{
			timeSinceLastUpdate -= TIME_PER_FRAME;
			update(TIME_PER_FRAME);
		}
		
		requestAnimFrame(gameLoop);
		render(ct);

	};
	
	var changeState = function(state)
	{
		switch(state)
		{
			case 'menu':
				mState = new MenuState(width, height, fontSize);
				break;
			case 'game':
				mState = new GameState(width, height, mState.socket);
				break;
			case 'singleplayer':
				mState = new GameState(width, height);
				break;
			default:
				changeState('menu');
				break;
		}
	};
	

  return {

    'init': init,
    'gameLoop': gameLoop,
	'changeState': changeState,
	

  };
})();



// On ready
$(function(){
	'use strict';
	//Game.initSocket();	
	
	
	// Give the websocket a chance to initialize and connect.
	Game.init('pix');
	Game.gameLoop();

	
	//setTimeout(run, 3000);
});