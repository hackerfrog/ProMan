$(function() {
	$("#call-setting").click(function(event) {
		event.preventDefault();
		$("#popup-setting").toggle();
	});
	$("#tabs #tab-board, #tabs #tab-convo, #tabs #tab-activ").click(function() {
		$("#tabs").find("div").removeClass('selected');
		$(this).addClass('selected');
		$("#tabs-area").find('#tab-board, #tab-convo, #tab-activ').removeClass('active');
		id = "#" + this.id;
		$("#tabs-area").find(id).addClass('active');
	});
	$("#new-board").click(function() {
		$("#model-newboard").toggle();
		$("#model-newboard").find("#ajax-post").submit(function(event) {
			console.log($(this).serialize());
			event.preventDefault();
			$.ajax({
				url: 'ajax/newboard.ajax.php',
				type: 'POST',
				data: $(this).serialize() + '&ajax-newboard=create'
			})
			.done(function(data) {
				console.log(data);
				obj = $.parseJSON(data);
				$("#new-board").before(obj.result);
				$("#model-newboard").toggle();
			})
			.fail(function() {
				console.log("error");
				//TODO Error
			})
			.always(function() {
				console.log("complete");
				//TODO Complete
			});
			
		});
	});
	$('[id="call-newcard"]').click(function() {
		$("#model-newcard").toggle();
		board = $(this).parent();
		boardId = $(this).find("#boardId").val();
		projectId = $(this).find("#projectId").val();
		boardName = $(this).find("#boardName").val();
		$("#model-newcard").find('#model-boardId').val(boardId);
		$("#model-newcard").find('#model-projectId').val(projectId);
		$("#model-newcard").find('#model-boardName').val(boardName);
		$("#model-newcard").find('#ajax-post').submit(function(event) {
			console.log($(this).serialize());
			event.preventDefault();
			$.ajax({
				url: 'ajax/newcard.ajax.php',
				type: 'POST',
				data: $(this).serialize() + '&ajax-newcard=create'
			})
			.done(function(data) {
				obj = $.parseJSON(data);
				console.log(obj);
				if(obj.status == 'error') {
					alert(obj.message);
				} else {
					$(board).find("#cards").append(obj.result);
					$("#model-newcard").toggle();
				}
			})
			.fail(function() {
				console.log("error");
			})
			.always(function() {
				console.log("complete");
			});
			
		});
	});
	$('[id="call-card-box"]').click(function() {
		$("#model-card-box").toggle();
		cardId = $(this).find("#card-id").val();
		$.ajax({
			url: 'ajax/carddata.ajax.php',
			type: 'POST',
			data: 'cardId=' + cardId + '&ajax-carddata=data'
		})
		.done(function(data) {
			obj = $.parseJSON(data);
			if (obj.status == 'error') {
				alert(obj.message);
			} else {
				console.log(obj);
				model = $("#model-card-box");
				model.find('#card-title .title').html(obj.data.title);
				model.find('#card-body').html(obj.data.body);
			}
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});
		
	});
});