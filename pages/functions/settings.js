
function reloadUsers(){

	$.post("functions/changeSettings.php", { action: "reloadUsers"},
		function(data) {
			$('#users_menu').html(data);
		});

}

function removeUser(user){
	
	$.post("functions/changeSettings.php", { action: "removeUser", user: user},
		function(data) {
			alert(data);
			//$('#schedules_menu').html(data);
			reloadUsers();
		});
		
}

function administrator(idBox){
	
	var checkedStatus = $('#' + idBox).attr('checked');
	//alert(idBox + checkedStatus);
	
	var administratorActive = new Array(idBox, checkedStatus);
	
	$.post("functions/changeSettings.php", { action: "administratorOrNot", administratorActive: administratorActive},
		function(data) {
			//alert(data);
			//$('#schedules_menu').html(data);
			reloadUsers();
		});
	
}


function addNewUser()
{	
	var uname = $('#newUserUsername').val();
	var pass = $('#newUserPassword').val();
	var pass_confirm = $('#newUserConfirmPassword').val();
	var admin = $('#newUserAdminPrivileges').is(':checked');
	
	//alert(uname + "\n" + pass + "\n" + pass_confirm + "\n" + admin);
	
	$.post("functions/changeSettings.php", { action: "addNewUser", uname: uname, pass: pass, pass_confirm: pass_confirm, admin: admin},
		function(data) {

			$('#statusNewUser').html(data);
			$(':input').clearForm();
			reloadUsers();
		});
		

}

function changePassword()
{	
	var ouname = $('#oldusername').val();
	var opass = $('#oldpassword').val();
	var npass = $('#newpassword').val();
	var cpass = $('#confirmpassword').val();
	
	$.post("functions/changeSettings.php", { action: "changePassword", oldusername: ouname, oldpassword: opass, newpassword: npass, confirmpassword: cpass},
		function(data) {

			$('#statusPassword').html(data);
			$(':input').clearForm();
		});
	
}

function changeUsername()
{	
	var uname = $('#username_u').val();
	var pass = $('#password_u').val();
	var nuname = $('#newusername').val();
	var cuname = $('#confirmusername').val();
	
	$.post("functions/changeSettings.php", { action: "changeUsername", username_u: uname, password_u: pass, newusername: nuname, confirmusername: cuname},
		function(data) {

			$('#statusUsername').html(data);
			$(':input').clearForm();
		});
	
}


$.fn.clearForm = function() {
  return this.each(function() {
	var type = this.type, tag = this.tagName.toLowerCase();
	if (tag == 'form')
	  return $(':input',this).clearForm();
	if (type == 'text' || type == 'password' || tag == 'textarea')
	  this.value = '';
	else if (type == 'checkbox' || type == 'radio')
	  this.checked = false;
	else if (tag == 'select')
	  this.selectedIndex = -1;
  });
};

function loadNames()
{	
	$.post("functions/changeSettings.php", { action: "tableNamesLoad"},
		function(data) {
			$('#nodeName').html(data);
		});
}

function checkLoadNames()
{	
	$.post("functions/changeSettings.php", { action: "checkLoadNames"},
		function(data) {
			if(data.indexOf("reload") != -1){
				loadNames();
			}
		});
}

$(document).ready(function() {
	setInterval(checkLoadNames, 1000);//frameLoad
});

function updateNodeName_(node_id, node_name)
{
	$.post("functions/changeSettings.php", { action: "updateNodeName_", node_id: node_id, node_name: node_name},
	function(data) {
		//alert(data); //return status of update
		loadNames();
	});
}

$(document).ready(function() {
	$('#clickme').click(function() {
		loadNames();
		slideThisThing();
	});
});

function slideThisThing()
{	
	$('#nodeName').slideToggle('slow', function() {
	// Animation complete.
	});
}

$(document).ready(function(){
  $('#nodeName').hide();
});

//===============================================================================================
//var statusIntervalId = window.setInterval(dbCheck, 1000); 
function read_log()
{	
	//var a = $('#dbCheckVal').val();
	//$.post("data.php", { action: "dbCheck", value: a},
	$.post("functions/changeSettings.php", { action: "read_log"},
		function(data) {
			$('#server_1_div').html(data);
		});
	
}

$(document).ready(function() {
	setInterval(read_log, 1000);
});
//-----------------------------------------------------------------------------

function server_1()
{
	/*
	var varCounter = 0;
	var done = 0;
	var varName = function(){ 
		 if(done == 0) { 
			  varCounter++; 
			  $('#server_1_div').html(varCounter);
		 } else { 
			  clearInterval(varName);
		 } 
	};
	setInterval(varName, 1000); 
	*/
	
	$.post("functions/changeSettings.php", { action: "server_1"},
		function(data) {
			//done = 1;
			
			if(data.indexOf("running") != -1){
				alert(data);
			}
		});

}


function server_1_stop()
{

	$.post("functions/changeSettings.php", { action: "server_1_stop"},
		function(data) {
			//done = 1;
			$('#server_1_stop_div').html(data);
			
		});

}
//===============================================================================================

//var statusIntervalId = window.setInterval(dbCheck, 1000); 
function dbCheck()
{	
	//var a = $('#dbCheckVal').val();
	//$.post("data.php", { action: "dbCheck", value: a},
	$.post("functions/changeSettings.php", { action: "dbCheck"},
		function(data) {
			//alert("Data Loaded: " + data);
			$('#dbCheckVal').html(data.full);
			//$('#dbCheckVal_2').html(data.mini);
		}, "json");
	
}

$(document).ready(function() {
	setInterval(dbCheck, 1000);
});
//-----------------------------------------------------------------------------

function getXMLData()
{	
	$.post("functions/changeSettings.php", { action: "getXMLData"},
		function(data) {
			//alert("Data Loaded: \n");
			$('#xmlData').html(data);
		});

}

function remove_file()
{
	$.post("functions/changeSettings.php", { action: "remove_file"},
		function(data) {
			alert("Data Loaded: \n" + data);
			//$('#remove_file').html(data);
		});
}

function xmlHASH()
{
	$.post("functions/changeSettings.php", { action: "xmlHASH"},
		function(data) {
			//alert("Data Loaded: \n" + data);
			$('#hash').html(data);
		});
}

function viewPort()
{
	$.post("functions/changeSettings.php", { action: "viewPort"},
		function(data) {
			alert(data);
		});
}

