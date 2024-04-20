
	$('#contactForm').submit(function(event){

		event.preventDefault();

		$.ajax({
			type: 'POST',
			url: $(this).attr('action'),
			data: $(this).serialize(),
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