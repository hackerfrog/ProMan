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
	$('#tabs-area #tab-board').on('click', '[id="call-newcard"]', function() {
//	$('[id="call-newcard"]').click(function() {		// Old Call
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
	$('[id="cards"]').on('click', '[id="call-card-box"]', function() {
//	$('[id="call-card-box"]').click(function() {		// Old Call
		$("#model-card-box").toggle();
		cardId = $(this).find("#card-id").val();
		card = $(this).parent().parent();
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
				model.find('#proName').html(obj.project.name);
				model.find('#boardName select option[value=' + obj.board.id + ']').attr("selected", true);
				tick = $("#call-card-done");
				if(tick.hasClass('done') || obj.data.done == 0)
					tick.removeClass('done')
				else
					tick.addClass('done');
			}
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});


		$("#model-card-box select").on('change', function(event) {
			event.preventDefault();
			newBoardId = $("option:selected", this)[0].value;
			$.ajax({
				url: 'ajax/changeboard.ajax.php',
				type: 'POST',
				data: 'cardId=' + cardId + '&newBoardId=' + newBoardId + '&ajax-changeboard=change'
			})
			.done(function(data) {
				obj = $.parseJSON(data);
				console.log(obj);
				if (obj.status == 'error') {
					alert(obj.message);
				} else {
					location.reload();
					//TODO Remove JS reload and make change in live page.
				}
			})
			.fail(function() {
				console.log("error");
			})
			.always(function() {
				console.log("complete");
			});
		});


		$("#call-card-done").on('click', function() {
			tick = $("#call-card-done");
			$.ajax({
				url: 'ajax/carddone.ajax.php',
				type: 'POST',
				data: 'cardId=' + cardId + '&ajax-carddone=done'
			})
			.done(function(data) {
				obj = $.parseJSON(data);
				if (obj.status == 'error') {
					alert(obj.message);
				} else {
					if(tick.hasClass('done')) {
						tick.removeClass('done');
						card.removeClass('done');
					} else {
						tick.addClass('done');
						card.addClass('done');
					}
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
});