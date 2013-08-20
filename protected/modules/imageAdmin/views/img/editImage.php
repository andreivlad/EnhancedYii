<div class="loader">&nbsp;</div>
<h3>Edit image</h3>
<a href="#image-listing" class="image-action">&laquo; Back to listing</a>
<div class="response"></div>
<?php echo CHtml::beginForm('/imageAdmin/img/editImage', 'POST', array(
		 'id' => 'edit_image_form',
		 'enctype' => 'multipart/form-data'
	));?>
	<img class="image-to-edit" src="<?php echo ImgAdmin::getImage($model_name, $img->src, '100_100'); ?>" 
						  alt="<?php echo $img->title;?>" />
	<?php echo CHtml::hiddenField('model_name', $model_name)?>
	<?php echo CHtml::hiddenField('image_id', $img->id)?>
	<p>
		<?php echo CHtml::label('Image file', 'src')?>
		<?php echo CHtml::fileField('src');?>
	</p>

	<p>
		<?php echo CHtml::label('Image title', 'title')?>
		<?php echo CHtml::textField('title', $img->title);?>
	</p>
	<p>
		<?php echo CHtml::submitButton('Edit image', array(
			 'class' => 'submit-add-image image-form-submit'
		))?>
	</p>
<?php echo CHtml::endForm();?>