/**
* Common javascript functions
*/

/* 	Generate a pseudo-random number between min and max. Both inclusive.
*  	@param min, mininum value
*	@param max, maximum value
*	@returns integer between min and max (inclusive)
*/
function random(min, max)
{
	return Math.floor(Math.random() * (max - min + 1)) + min;
}

/* 	Get the absolute position of a HTML element.
*
*	@param elementId, ID of the HTML element
*	@returns array of [left, top]. 
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