// make preloader
const host = document.getElementsByTagName( 'preloader' )[0];

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
	
});

$('.project').on('click', function(){
	preloader();
	var project_id = $(this).data('projectid');
	var project_type = $(this).data('projecttype');
	var getUrl = $(this).data('url');
	
	$('#task-body').html("");
	$('#taskModal').modal('show');
	$.ajax({
			url: getUrl,
			type: "post",
			data: {project_id: project_id, _token: token},
			success: function(body){
				$('#task-body').html("");
				$('#task-body').html(body);
				setTimeout(function(){host.hide();}, 2000);
			}
	});
	
});

function createTasksView(){
	var getUrl = $('#item-create-url').val();
	var tasks = $('#total_tasks').val();
	var projectId = $('#project_id').val();
	var form_create = '<form action="'+getUrl+'" method="post">';
	var input = "";
	var name = "";
	var description = "";
	var word_counts="";
	var end_date=""; 
	var end_time=""; 
	var type="";
	var price = "";
	for(var i = 1; i<= tasks; i++){
		if(i > 1){
			name = "name";
			description = "description";
			word_counts = "word_counts";
			end_date = "end_date";
			end_time = "end_time";
			type = "type";
			price = "price";
		}
		input += '<div class="row" id="new-task-'+i+'" style="background: #f1f1f1; margin: 15px 0;padding: 15px">';
		input += 
		'<div class="col-md-4">'
			+'<div class="form-group">'
			+	'<label for="name-'+i+'">Name:</label>'
			+	'<input type="text" id="name-'+i+'" name="name[]" class="form-control '+name+'" required>'
			+'</div>'
		+'</div>';
		input += 
		'<div class="col-md-4">'
			+'<div class="form-group">'
			+	'<label for="word_counts-'+i+'">Word Counts:</label>'
			+	'<input type="number" id="word_counts-'+i+'" name="word_counts[]" class="form-control '+word_counts+' required">'
			+'</div>'
		+'</div>';
		input += 
		'<div class="col-md-4">'
			+'<div class="form-group">'
			+	'<label for="type-'+i+'">Item Type:</label>'
			+	'<input type="text" id="type-'+i+'" name="type[]" class="form-control '+type+'" >'
			+'</div>'
		+'</div>';
		input += 
		'<div class="col-md-4">'
			+'<div class="form-group">'
			+	'<label for="end_date-'+i+'">End Date:</label>'
			+	'<input type="date" id="end_date-'+i+'" name="end_date[]" class="form-control '+end_date+' required">'
			+'</div>'
		+'</div>';
		input += 
		'<div class="col-md-4">'
			+'<div class="form-group">'
			+	'<label for="end_time-'+i+'">End Time:</label>'
			+	'<input type="time" id="end_time-'+i+'" name="end_time[]" class="form-control '+end_time+' required">'
			+'</div>'
		+'</div>'
		input += 
		'<div class="col-md-4">'
			+'<div class="form-group">'
			+	'<label for="price-'+i+'">Price:</label>'
			+	'<input type="text" id="price-'+i+'" name="price[]" class="form-control '+price+'">'
			+'</div>'
		+'</div>';
		input += 
		'<div class="col-md-10">'
			+'<div class="form-group">'
			+	'<label for="description-'+i+'">Description:</label>'
			+	'<textarea id="description-'+i+'" name="description[]" rows="2" class="form-control '+description+'"></textarea>'
			+'</div>'
		+'</div>';
		if(i == 1 && tasks > 1){
			input += '<div style="margin-top: 50px" class="col-md-2"><div class="text-right"><a href="javascript:void()" title="copy to all" class="btn btn-default btn-sm" onclick="copyItem()"><i class="fa fa-copy"></i></a></div></div>';
		}else{
			input += '<div style="margin-top: 50px" class="col-md-2"><div class="text-right"><a href="javascript:void()" title="remove" class="btn btn-danger btn-sm" onclick="removeItem('+i+')"><i class="fa fa-times"></i></a></div></div>';
		}
		input += '</div>';
		
	}		
	input += 
		'<div class="row">'
			+'<div class="col-md-12">'
				+'<div class="form-group">'
					+'<div class="form-group">'
					+	'<input type="submit" class="btn btn-primary btn-sm" value="Create" />'
					+'</div>'
				+'</div>'
			+'</div>'
			+'<input type="hidden" name="_token" value="'+token+'" />'
			+'<input type="hidden" name="project_id" value="'+projectId+'" />'
		+'</div>';

	form_create += input;
	form_create += '</form>';

	$('#task-create').html(form_create);
}

function createTasksViewForAdmin(){
	var getUrl = $('#item-create-url').val();
	var tasks = $('#total_tasks').val();
	var projectId = $('#project_id').val();
	var manager_id = $('#pmanager_id').val().trim().split(',');
	var manager_name = $('#pmanager_name').val().trim().split(',');
	
	var form_create = '<form action="'+getUrl+'" method="post" enctype="multipart/form-data">';
	var input = "";
	var name = "";
	var description = "";
	var word_counts="";
	var end_date=""; 
	var end_time=""; 
	var type="";
	var price = "";

	var make_options = "";
	for(var j = 0; j < manager_id.length-1; j++ ){
		make_options += '<option value="'+manager_id[j]+'">'+manager_name[j]+'</option>';
	}
	// alert(make_options);
	for(var i = 1; i<= tasks; i++){
		if(i > 1){
			name = "name";
			description = "description";
			word_counts = "word_counts";
			end_date = "end_date";
			end_time = "end_time";
			type = "type";
			price = "price";
		}
		input += '<div class="row" id="new-task-'+i+'" style="background: #f1f1f1; margin: 15px 0;padding: 15px">';
		input += 
		'<div class="col-md-4">'
			+'<div class="form-group">'
			+	'<label for="name-'+i+'">Name:</label>'
			+	'<input type="text" id="name-'+i+'" name="name[]" class="form-control '+name+'" required>'
			+'</div>'
		+'</div>';
		input += 
		'<div class="col-md-4">'
			+'<div class="form-group">'
			+	'<label for="word_counts-'+i+'">Word Counts:</label>'
			+	'<input type="number" id="word_counts-'+i+'" name="word_counts[]" class="form-control '+word_counts+' required">'
			+'</div>'
		+'</div>';
		input += 
		'<div class="col-md-4">'
			+'<div class="form-group">'
			+	'<label for="type-'+i+'">Item Type:</label>'
			+	'<input type="text" id="type-'+i+'" name="type[]" class="form-control '+type+'" >'
			+'</div>'
		+'</div>';
		input += 
		'<div class="col-md-4">'
			+'<div class="form-group">'
			+	'<label for="end_date-'+i+'">End Date:</label>'
			+	'<input type="date" id="end_date-'+i+'" name="end_date[]" class="form-control '+end_date+' required">'
			+'</div>'
		+'</div>';
		input += 
		'<div class="col-md-4">'
			+'<div class="form-group">'
			+	'<label for="end_time-'+i+'">End Time:</label>'
			+	'<input type="time" id="end_time-'+i+'" name="end_time[]" class="form-control '+end_time+' required">'
			+'</div>'
		+'</div>'
		input += 
		'<div class="col-md-4">'
			+'<div class="form-group">'
			+	'<label for="price-'+i+'">Price:</label>'
			+	'<input type="text" id="price-'+i+'" name="price[]" class="form-control '+price+'">'
			+'</div>'
		+'</div>';
		input += 
		'<div class="col-md-12">'
			+'<div class="form-group">'
			+	'<label for="manager-'+i+'">Assign To:</label>'
			+	'<select name="manager_id[]" class="form-control">'+make_options+'</select>'
			+'</div>'
		+'</div>';
		input += 
		'<div class="col-md-10">'
			+'<div class="form-group">'
			+	'<label for="description-'+i+'">Description: <input type="file" name="description_file[]"></label>'
			+	'<textarea id="description-'+i+'" name="description[]" rows="2" class="form-control '+description+'"></textarea>'
			+'</div>'
		+'</div>';
		if(i == 1 && tasks > 1){
			input += '<div style="margin-top: 50px" class="col-md-2"><div class="text-right"><a href="javascript:void()" title="copy to all" class="btn btn-default btn-sm" onclick="copyItem()"><i class="fa fa-copy"></i></a></div></div>';
		}else{
			input += '<div style="margin-top: 50px" class="col-md-2"><div class="text-right"><a href="javascript:void()" title="remove" class="btn btn-danger btn-sm" onclick="removeItem('+i+')"><i class="fa fa-times"></i></a></div></div>';
		}
		input += '</div>';
		
	}		
	input += 
		'<div class="row">'
			+'<div class="col-md-12">'
				+'<div class="form-group">'
					+'<div class="form-group">'
					+	'<input type="submit" class="btn btn-primary btn-sm" value="Create" />'
					+'</div>'
				+'</div>'
			+'</div>'
			+'<input type="hidden" name="_token" value="'+token+'" />'
			+'<input type="hidden" name="project_id" value="'+projectId+'" />'
		+'</div>';

	form_create += input;
	form_create += '</form>';

	$('#task-create').html(form_create);
}

function removeItem(id){
	$('#new-task-'+id).remove();
}
function copyItem(){
	$('.name').val($('#name-1').val());
	$('.description').val($('#description-1').val());
	$('.word_counts').val($('#word_counts-1').val());
	$('.end_date').val($('#end_date-1').val());
	$('.end_time').val($('#end_time-1').val());
	$('.type').val($('#type-1').val());
	$('.price').val($('#price-1').val());
}
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
