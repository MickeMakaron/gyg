$(document).ready(function()
{
'use strict';


(function($) 
{	
	function success(data)
	{
		$("#" + data.table + "-output").html(data.output);
	}
	
	function dropTable()
	{
		var data = "table=" + this.value;
		console.log(data);
		$.ajax(
		{
			type: 'post',
			url: '/ajax/playhouse/user?drop',
			data: data,
			dataType: 'json',
			success: success
		}); 
	}
	
	$('button').click(dropTable);
}) (jQuery);
});

