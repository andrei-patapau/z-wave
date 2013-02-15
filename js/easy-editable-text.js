$(document).ready(function(){
	
	$('.edit').click(function(){
		$(this).hide();
		$(this).prev().hide();
		$(this).next().show();
		$(this).next().select();
	});
	
	
	
	
	$('input.diff').blur(function() {
         if ($.trim(this.value) == ''){
			 this.value = (this.defaultValue ? this.defaultValue : '');  
			 alert("Node name hasn't been updated\nCan't be empty string");
		 }
		 else{
			 $(this).prev().prev().html(this.value);
				var node_id = this.id;
				var node_name = this.value;
				updateNodeName_(node_id, node_name);
		 }
		 
		 $(this).hide();
		 $(this).prev().show();
		 $(this).prev().prev().show();
     });
	  
	  $('input.diff').keypress(function(event) {
		  if (event.keyCode == '13') {
			 if ($.trim(this.value) == ''){
				 this.value = (this.defaultValue ? this.defaultValue : '');
				 //reload table
				 alert("Node name hasn't been updated\nCan't be empty string");
			 }
			 else
			 {
				$(this).prev().prev().html(this.value);
				var node_id = this.id;
				var node_name = this.value;
				updateNodeName_(node_id, node_name);
			 }
			 
			 $(this).hide();
			 $(this).prev().show();
			 $(this).prev().prev().show();
		  }
	  });
		  
});







