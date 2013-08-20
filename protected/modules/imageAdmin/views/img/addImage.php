<div class="loader">&nbsp;</div>
<h3>Add image</h3>
<a href="#image-listing" class="image-action">&laquo; Back to listing</a>
<div class="response"></div>
<?php echo CHtml::beginForm('/imageAdmin/img/addImage', 'POST', array(
		 'id' => 'add_image_form',
		 'enctype' => 'multipart/form-data'
	));?>
	<?php echo CHtml::hiddenField('model_name', $model_name)?>
	<?php echo CHtml::hiddenField('parent_id', $parent_id)?>
	<p>
		<?php echo CHtml::label('Image file', 'src')?>
		<?php echo CHtml::fileField('src');?>
	</p>

	<p>
		<?php echo CHtml::label('Image title', 'title')?>
		<?php echo CHtml::textField('title');?>
	</p>
	<p>
		<?php echo CHtml::submitButton('Add image', array(
			 'class' => 'submit-add-image image-form-submit'
		))?>
	</p>
<?php echo CHtml::endForm();?>