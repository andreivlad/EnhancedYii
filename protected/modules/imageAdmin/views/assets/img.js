img = {
	imageModelName:'',
	parentId:'',
	initAdmin: function(){
		attr = $('#img-admin').attr('rel').split('.');
		img.imageModelName = attr[0];
		img.parentId = attr[1];
		img.actions.getImages();
	},
	actions:{
		getImages:function(){
			$('#img-admin .loader').show();
			$.post('/imageAdmin/img/getImages', 
				{imageModelName:img.imageModelName, parentId:img.parentId}, 
				function(response){
					$('#img-admin').html(response);
					$('#img-admin .loader').hide();
					img.actions.init.initReorder();
					img.actions.init.initAddImage();
					img.actions.init.initEditImage();
					img.actions.init.initDeleteImage();
				});
		},
		init:{
			initReorder:function(){
				$('#img-admin ul.img-admin-list').sortable({
					placeholder: 'ui-sortable-placeholder',
					stop: function( event, ui ) {
						var image_indexes = new Array(), order_indexes = new Array();
						$('#img-admin ul.img-admin-list li').each(function( index ) {
							image_indexes.push($(this).attr('rel'));
							order_indexes.push(index);
						});
						$('#img-admin .submit-order').show();
						$('#order_image_form .image-indexes').val(image_indexes.join(','));
						$('#order_image_form .order-indexes').val(order_indexes.join(','));
					}
				});
				$('#img-admin ul.img-admin-list').disableSelection();
				
				//Init reorder form
				$('#order_image_form').submit(function(e){
					e.preventDefault();
					$('#img-admin .loader').show();
					$.post($('#order_image_form').attr('action'), $('#order_image_form').serialize(),function(){
						$('#img-admin .loader').hide();
					});
				});
			},
			initAddImage:function(){
				$('#img-admin a.image-action').click(function(e){
					e.preventDefault();
					$('#img-admin .loader').show();
					$.get('/imageAdmin/img/addImage', {model_name:img.imageModelName, parent_id:img.parentId}, function(response){
						$('#img-admin').html(response);
						$('#img-admin .loader').hide();
						
						$('#add_image_form').ajaxForm({
							 beforeSubmit: function(){
								 $('#img-admin .loader').show();
							 },
							 success: function(responseText, statusText, xhr, $form){
								 $('#img-admin .loader').hide();
								 if(responseText.trim() != ''){
									 responseText = JSON.parse(responseText);
									 
									 if(responseText.success){
										$('#img-admin .response').html('<div class="success">Successfully added image</div>');
										$('#add_image_form').clearForm();
									 }else{
										 errors = '';
										 $.each( responseText.errors, function( key, value ){
											 errors += '<li>' + value + '</li>';
										 });
										 $('#img-admin .response').html('<ul class="errors">' + errors + '</ul>');
									 }
								 }

								 
							 }
						});
						
						$('#img-admin a.image-action').click(function(e){
							e.preventDefault();
							img.actions.getImages();
						});
					});
				});
			},
			initEditImage:function(){
				$('#img-admin .actions a.edit-image').click(function(e){
					e.preventDefault();
					$('#img-admin .loader').show();
					$.get('/imageAdmin/img/editImage', {model_name:img.imageModelName, image_id:$(this).closest('li').attr('rel')}, function(response){
						$('#img-admin').html(response);
						$('#img-admin .loader').hide();
						
						$('#edit_image_form').ajaxForm({
							 beforeSubmit: function(){
								 $('#img-admin .loader').show();
							 },
							 success: function(responseText, statusText, xhr, $form){
								 $('#img-admin .loader').hide();
								 if(responseText.trim() != ''){
									 responseText = JSON.parse(responseText);
									 
									 if(responseText.success){
										$('#img-admin .response').html('<div class="success">Successfully edited image</div>');
										$('#img-admin img.image-to-edit').attr('src', responseText.src);
									 }else{
										 errors = '';
										 $.each( responseText.errors, function( key, value ){
											 errors += '<li>' + value + '</li>';
										 });
										 $('#img-admin .response').html('<ul class="errors">' + errors + '</ul>');
									 }
								 }
							 }
						});
						
						$('#img-admin a.image-action').click(function(e){
							e.preventDefault();
							img.actions.getImages();
						});
					});
				});
			},
			initDeleteImage:function(){
				$('#img-admin .actions a.del-image').click(function(e){
					var image_li = $(this).closest('li');
					e.preventDefault();
					if(confirm("Are you sure you want to delete this image?")){
						$('#img-admin .loader').show();
						$.post('/imageAdmin/img/deleteImage', {image_id:image_li.attr('rel'), model_name: img.imageModelName}, function(){
							$('#img-admin .loader').hide();
							image_li.remove();
						});
					}
				});
			}
		}
	}
}
$(document).ready(img.initAdmin);

