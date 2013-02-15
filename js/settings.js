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
	//var a = $('#dbCheckVal').val();
	//$.post("data.php", { action: "dbCheck", value: a},
	$.post("functions/changeSettings.php", { action: "tableNamesLoad"},
		function(data) {
			//alert("Data Loaded: " + data);
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
		alert(data); //return status of update
		loadNames();
	});
}

$(document).ready(function() {
	$('#clickme').click(function() {
	  $('#nodeName').slideToggle('slow', function() {
		// Animation complete.
	  });
	});
});

$(document).ready(function(){
  $('#nodeName').hide();
});