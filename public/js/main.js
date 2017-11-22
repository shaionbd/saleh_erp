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

$("#image-input").on('change', function(event) {
    var file = event.target.files[0];

    if(!file.type.match('image/jpeg')
        && !file.type.match('image/jpg')
        && !file.type.match('image/png')) {
        $('#image').hide('slow');
        $(this).addClass('error');
        $(this).val('');    //the tricky part is to "empty" the input file here I reset the form.
        $('#image-error').removeClass('hidden');
        
    } else {
        if (event.target.files && event.target.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#image')
                    .attr('src', e.target.result)
                    .show('slow');
                    $('#image').css({'height':'200', 'width':'200'});
            };

            reader.readAsDataURL(event.target.files[0]);
        }
    }
});

$('#image-input').on('focus', function(event) {
    if($(this).hasClass('error')){
        $(this).removeClass('error');
        $('#image-error').addClass('hidden');
    }
});