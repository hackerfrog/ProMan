$(function() {
	$("#call-login").click(function(event) {
		event.preventDefault();
		$("#model-login").toggle();
	});
	$("#call-register").click(function(event) {
		event.preventDefault();
		$("#model-register").toggle();
	});
	$("#call-setting").click(function(event) {
		event.preventDefault();
		$("#popup-setting").toggle();
	});
	$("#call-newproject").click(function(event) {
		event.preventDefault();
		$("#model-newproject").toggle();
		$("#model-newproject").find("#ajax-post").submit(function(event) {
			event.preventDefault();
			$.ajax({
				url: 'newproject.php',
				type: 'POST',
				data: $(this).serialize() + '&ajax-create=create'
			})
			.done(function(data) {
				obj = $.parseJSON(data);
				console.log(obj);
				if (obj.status == 'error') {
					alert(obj.message);
				} else {
					$(".message").hide();
					$("#projects").append(obj.result);
					$("#model-newproject").toggle();
				}
			})
			.fail(function() {
				console.log("error");
				//TODO Error;
			})
			.always(function() {
				console.log("complete");
				//TODO Complete;
			});
			
		});
	});
});