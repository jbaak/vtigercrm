
	$('#contactForm').submit(function(event){

		event.preventDefault();
		$('#alert-success').addClass('d-none');
		$('#alert-error').addClass('d-none');

		$.ajax({
			type: 'POST',
			url: $(this).attr('action'),
			data: $(this).serialize(),
			dataType: 'json',
			success: function(response) {
				if (response.status == 'success') {
					$('#alert-success').removeClass('d-none');
				} else {
					$('#alert-error').removeClass('d-none');
				}

			},
			error: function(response){
				//Cuando la interacción retorne un error, se ejecutará esto.
			}
		});
	});