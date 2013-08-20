<div class="loader">&nbsp;</div>
<h3>Images</h3>
<a href="#add-image" class="image-action">Add image</a>
<?php if(!empty($images)):?>
	<ul class="img-admin-list">
		<?php foreach($images as $image):?>
				<li rel="<?php echo $image->id;?>">
					<div class="handle">&nbsp;</div>
					<img src="<?php echo ImgAdmin::getImage($model_name, $image->src, '100_100'); ?>" 
						  alt="<?php echo $image->title;?>" />
					<div class="actions">
						<a class="edit-image" href="#edit">Edit</a>
						<a class="del-image" href="#delete">Delete</a>
					</div>
				</li>
		<?php endforeach;?>
	</ul>
	<?php echo CHtml::beginForm('/imageAdmin/img/reorderImages', 'POST', array(
		 'id' => 'order_image_form'
	));?>
		<?php echo CHtml::hiddenField('image_indexes', '', array(
			 'class' => 'image-indexes'
		))?>
		<?php echo CHtml::hiddenField('order_indexes', '', array(
			 'class' => 'order-indexes'
		))?>
		<?php echo CHtml::hiddenField('model_name', $model_name)?>
		<?php echo CHtml::submitButton('Submit order', array(
			 'class' => 'submit-order image-form-submit'
		))?>
	<?php echo CHtml::endForm();?>
<?php else:?>
	<div class="none">There where no images found</div>
<?php endif; ?>

