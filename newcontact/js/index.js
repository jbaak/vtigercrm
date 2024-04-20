
	$('#contactForm').submit(function(event){

		event.preventDefault();
		alert( $(this).attr('action'));
		$.ajax({
			type: 'POST',
			url: $(this).attr('action'),
			data: $(this).serialize(),
			success: function(data){
				alert('ajax', url);
				//Cuando la interacción sea exitosa, se ejecutará esto.
				console.log(data);
				$("#response").html(data)
			},
			error: function(data){
				//Cuando la interacción retorne un error, se ejecutará esto.
			}
		});
	});