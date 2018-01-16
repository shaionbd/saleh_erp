// make preloader
const host = document.getElementsByTagName( 'preloader' )[0];
var preloader = function(){
	var bars = [];

	var i = 0;
	/*host.addEventListener( 'show', host.show, false);
	host.addEventListener( 'hide', host.hide, false);*/

	while( i < 3 ) {
		i++;
		var bar = document.createElement('bar');
		bars.push( bar );
		host.appendChild( bar );
	};

	var barAnimation = function(index) {

		setTimeout(function(){

			setInterval(function(){

				bars[index].setAttribute( 'class', ( bars[index].getAttribute( 'class' ) == 'active' ) ? '' : 'active' );
			}, 700);

		}, ( index == 0 ) ? 50 : index*150 + 50);
	};

	host.setAttribute( 'class', 'animate' );

	setTimeout(function(){

		host.setAttribute( 'class', 'start animate' );
	}, 300);
	setTimeout(function(){

		host.setAttribute( 'class', 'start complete' );
	}, 1100);

	setTimeout(function(){
		for (var b = 0; b < bars.length; b++) {

			barAnimation( b );
		};
	}, 1100);
};



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

host.hide = function() {
	this.style.display = 'none';
};

$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();
});

$('.task').on('click', function(){
		preloader();
    var task_id = $(this).data('taskid');
    var task_type = $(this).data('tasktype');
		var getUrl = $(this).data('url');

    if(task_type == "pending"){
				$('#task-body').html("");
        $('#taskModal').modal('show');
				$.ajax({
						url: getUrl,
						type: "post",
	        	data: {task_id: task_id, _token: token},
	        	success: function(body){
							$('#task-body').html("");
							$('#task-body').html(body);
							setTimeout(function(){host.hide();}, 2000);
						}
				});
    }else if(task_type == "onGoing"){
				$('#task-body').html("");
        $('#taskModal').modal('show');
				$.ajax({
						url: getUrl,
						type: "post",
	        	data: {task_id: task_id, _token: token},
	        	success: function(body){
							$('#task-body').html("");
							$('#task-body').html(body);
							setTimeout(function(){host.hide();}, 2000);
						}
				});
    }else if(task_type == "managerOnGoing"){
				$('#task-body').html("");
        $('#taskModal').modal('show');
				$.ajax({
						url: getUrl,
						type: "post",
	        	data: {task_id: task_id, _token: token},
	        	success: function(body){
							$('#task-body').html("");
							$('#task-body').html(body);
							setTimeout(function(){host.hide();}, 2000);
						}
				});
    }else if(task_type == "submitted"){
				$('#task-body').html("");
        $('#taskModal').modal('show');
				$.ajax({
						url: getUrl,
						type: "post",
	        	data: {task_id: task_id, _token: token},
	        	success: function(body){
							$('#task-body').html("");
							$('#task-body').html(body);
							setTimeout(function(){host.hide();}, 2000);
						}
				});
    }else if(task_type == "manager_submitted"){
				$('#task-body').html("");
        $('#taskModal').modal('show');
				$.ajax({
						url: getUrl,
						type: "post",
	        	data: {task_id: task_id, _token: token},
	        	success: function(body){
							$('#task-body').html("");
							$('#task-body').html(body);
							setTimeout(function(){host.hide();}, 2000);
						}
				});
    }else if(task_type == "review"){
				$('#task-body').html("");
        $('#taskModal').modal('show');
				$.ajax({
						url: getUrl,
						type: "post",
	        	data: {task_id: task_id, _token: token},
	        	success: function(body){
							$('#task-body').html("");
							$('#task-body').html(body);
							setTimeout(function(){host.hide();}, 2000);
						}
				});
    }else if(task_type == "manager_review"){
				$('#task-body').html("");
        $('#taskModal').modal('show');
				$.ajax({
						url: getUrl,
						type: "post",
	        	data: {task_id: task_id, _token: token},
	        	success: function(body){
							$('#task-body').html("");
							$('#task-body').html(body);
							setTimeout(function(){host.hide();}, 2000);
						}
				});
}else if(task_type == "manager_revision"){
				$('#task-body').html("");
        $('#taskModal').modal('show');
				$.ajax({
						url: getUrl,
						type: "post",
	        	data: {task_id: task_id, _token: token},
	        	success: function(body){
							$('#task-body').html("");
							$('#task-body').html(body);
							setTimeout(function(){host.hide();}, 2000);
						}
				});
    }else if(task_type == "assign"){
				$('#task-body').html("");
        $('#taskModal').modal('show');
				$.ajax({
						url: getUrl,
						type: "post",
	        	data: {task_id: task_id, _token: token},
	        	success: function(body){
							$('#task-body').html("");
							$('#task-body').html(body);
							setTimeout(function(){host.hide();}, 2000);
						}
				});
    }else if(task_type == "writter_pending"){
				$('#task-body').html("");
        $('#taskModal').modal('show');
				$.ajax({
						url: getUrl,
						type: "post",
	        	data: {task_id: task_id, _token: token},
	        	success: function(body){
							$('#task-body').html("");
							$('#task-body').html(body);
							setTimeout(function(){host.hide();}, 2000);
						}
				});
    }

});

$('.item').on('click', function(){
		preloader();
		var item_id = $(this).data('itemid');
		var item_type = $(this).data('itemtype');
		var getUrl = $(this).data('url');

		if(item_type == "pending"){
				$('#task-body').html("");
				$('#taskModal').modal('show');
				$.ajax({
						url: getUrl,
						type: "post",
						data: {item_id: item_id, _token: token},
						success: function(body){
							$('#task-body').html("");
							$('#task-body').html(body);
							setTimeout(function(){host.hide();}, 2000);
						}
				});
		}
	});
