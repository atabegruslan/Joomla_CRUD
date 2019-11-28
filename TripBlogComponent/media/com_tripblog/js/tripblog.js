(function($) {

	$(document).ready(function(){

		tripblog = window.tripblog || {};

		tripblog.image = {

			image_fiddle: $('#edit_image').croppie({
				enableExif: true,
				enableOrientation: true,
				viewport: { // Default { width: 100, height: 100, type: 'square' }
					width: 150,
					height: 150,
					type: 'square' // circle
				},
				boundary: {
					width: 200,
					height: 200
				}
			}),

			preUpload: function(input)
			{
				//tripblog.image.simplePreview(input);
				tripblog.image.editPreview(input);
			},

			simplePreview: function(input)
			{
				if (input.files && input.files[0])
				{
					var reader = new FileReader();

					reader.onload = function (e)
					{
						$('img.img').attr('src', e.target.result);
					};

					reader.readAsDataURL(input.files[0]);
				}
			},

			editPreview: function(input)
			{
				if (input.files && input.files[0])
				{
					$("div#crop_preview").css('display', 'block');
					$("img.img").css('display', 'none');

					var reader = new FileReader();

					reader.onload = function (e)
					{
						tripblog.image.image_fiddle.croppie('bind', {
							url: e.target.result
						}).then(function() {
							console.log('Image Uploaded');
						});
					};

					reader.readAsDataURL(input.files[0]);
				}
			}
		};

		$('#preview').on('click', function (e) {

			e.preventDefault();

			tripblog.image.image_fiddle.croppie('result', {
				type: 'canvas',
				size: 'viewport'
			}).then(function (image) {
				var html = '<img src="' + image + '" />';
				$("#preview_image").html(html);

				$('#edited_image').val(image);

				console.log('Image Edited and Previewed');
			});

			return false;
		});

		$("div#crop_preview").css('display', 'none');
		$("img.img").css('display', 'block');

		$('nav-tabs a').on('shown.bs.tab', function() {
			console.log('On tab shown');
		});

		$("form.form-validate button.validate").on('click', function(e) {

			e.preventDefault();

			var form = e.target.closest('form');

			if (document.formvalidator == null)
			{
				document.formvalidator = new JFormValidator;
			}

			document.formvalidator.setHandler('passverify', function(value) {
				return ($(form).find("input.validate-password").val() === value);
			});

			if (document.formvalidator.isValid(form))
			{
				$(form).submit();
			}
		});
	});

	Joomla.submitbutton = function(task)
	{
		switch(task)
		{
			case 'user.save':

				var form = document.adminForm;

				if (document.formvalidator == null)
				{
					document.formvalidator = new JFormValidator;
				}

				document.formvalidator.setHandler('passverify', function(value) {
					return ($('input#jform_password').val() === value);
				});

				if (document.formvalidator.isValid(form))
				{
					Joomla.submitform(task);
				}

				break;
			default:
				Joomla.submitform(task);
		}
	}
})( jQuery );
