jQuery(document).ready(function($) {

	$('#submit').on('click', function(){

		var form = $(this).parents('#form');
		var data = form.serialize();

        $.ajax({
            type        : 'POST',
            url         : form.attr('action'), 
            data        : data,
            dataType    : 'json',
         	success: function(resp){
	        	
	        	console.log(resp);
	        	
	        	if(resp.success == 'true'){
	        		 var content = '<div class="box success">' +
			          '<p>You\'ve sent me a message!</p>' +
			        '</div>'
			        $('#notification').html(content).fadeIn();
      			}else{
      				if(resp.message){
      					var message = resp.message;
      				}else{
      					var message = 'One or more fields need attention';
      				}
	        		var content = '<div class="box error">' +
						          '<p>' + message + '</p>' +
						        '</div>'
					$('#notification').html(content).fadeIn();
	        	}
	        },
	        error: function(e, textStatus){
	        	var content = '<div class="box error">' +
						          '<p>Your message could not be sent, please try again later. - ' + textStatus + '</p>' +
						       '</div>'
				$('#notification').html(content).fadeIn();
	        }
		});

	return false;

	});
});