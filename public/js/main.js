$('#availability').on('change', function(){
	var userId = $(this).data('userid');
	var postUrl = $(this).data('url');
	if ($(this).is(':checked') ) {
        $.ajax({
        	url: postUrl,
        	type: "post",
        	data: {userid: userId, is_available: '1', _token: token},
        	success: function(message){
        		var options = {
                    'title': 'Availability',
                    'style': 'success',
                    'message': message,
                    'timeout': 2000,
                    'icon': 'check'
                };

                new notify(options).show();
        	}
        });
    } 
    else {
        $.ajax({
        	url: postUrl,
        	type: "post",
        	data: {userid: userId, is_available: '0', _token: token},
        	success: function(message){
        		var options = {
                    'title': 'Availability',
                    'style': 'error',
                    'message': message,
                    'timeout': 2000,
                    'icon': 'warning'
                };
                new notify(options).show();
        	}
        });
    }
});