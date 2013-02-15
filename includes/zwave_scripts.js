
$(document).ready(function() {
$("button").button();
});
//-----------------------------------------------------------------------------


function execPerlON()
{
	$.post("data.php", { action: "execPerlON"},
		function(data) {
			alert("Device is " + data.status + "\n\n=====================================\n" + data.output + "\n=====================================");
		}, "json");
}

function execPerlOFF()
{
	$.post("data.php", { action: "execPerlOFF"},
		function(data) {
			alert("Device is " + data.status + "\n\n=====================================\n" + data.output + "\n=====================================");
		}, "json");
}

function getPortNumber()
{
	$.post("data.php", { action: "getPortNumber"},
		function(data) {
			alert("Data Loaded: " + data);
		});
}
 
function viewPort()
{
	$.post("data.php", { action: "viewPort"},
		function(data) {
			alert("Data Loaded: \n" + data);
		});
}

function getXMLData()
{	
	$.post("data.php", { action: "getXMLData"},
		function(data) {
			//alert("Data Loaded: \n");
			$('#xmlData').html(data);
		});

}

function remove_file()
{
	$.post("data.php", { action: "remove_file"},
		function(data) {
			alert("Data Loaded: \n" + data);
			//$('#remove_file').html(data);
		});
}

function xmlHASH()
{
	$.post("data.php", { action: "xmlHASH"},
		function(data) {
			//alert("Data Loaded: \n" + data);
			$('#hash').html(data);
		});
}

function startServer_2()
{
	var varCounter = 0;
	var done = 0;
	var varName = function(){ 
		 if(done == 0) { 
			  varCounter++; 
			  $('#startServer_2_').html(varCounter);
		 } else { 
			  clearInterval(varName);
		 } 
	};
	

	setInterval(varName, 1000); 
	
		
	$.post("py-openzwave/examples/server.php",
		function(data) {
			done = 1;
			$('#startServer_2_').html(data);
		});

}



//-----------------------------------------------------------------------------
//var statusIntervalId = window.setInterval(dbCheck, 1000); 
function frameLoad()
{	
/*
	//var a = $('#dbCheckVal').val();
	//$.post("data.php", { action: "dbCheck", value: a},
	$.post("data.php", { action: "frameLoad"},
		function(data) {
			//alert("Data Loaded: " + data);
			$('#container').html(data);
		});
		*/
	$("#container").load("data.php", {action: "frameLoad"});
	//$("#container").load("ftest.php");
}

function frameCheck()
{	

	$.post("data.php", { action: "frameCheck"},
		function(data) {
			//$("#container").load("data.php", {action: "frameLoad"});
			if(data.indexOf("false")){
				alert(data + data.indexOf("going"));
				$("#container").load("data.php", {action: "frameLoad"});
				$('#container2').html(data);
			}
		});


	
}

//$(document).ready(function() {
//	setInterval(frameCheck, 3000);//frameLoad
//});
//-----------------------------------------------------------------------------

function frameExecute(id)
{	

	$.post("data.php", { action: "frameExecute", command: id},
		function(data) {
			$('#container2').html(data);
		});

}

//-----------------------------------------------------------------------------

//Reload Frames on Page Refresh
$(document).ready(function() {
	window.onload(frameLoad()); 
});

//var statusIntervalId = window.setInterval(dbCheck, 1000); 
function dbCheck()
{	
	//var a = $('#dbCheckVal').val();
	//$.post("data.php", { action: "dbCheck", value: a},
	$.post("data.php", { action: "dbCheck"},
		function(data) {
			//alert("Data Loaded: " + data);
			//$('#dbCheckVal').html(data.full);
			$('#dbCheckVal_2').html(data.mini);
		}, "json");
	
}

$(document).ready(function() {
	setInterval(dbCheck, 1000);
});
//-----------------------------------------------------------------------------