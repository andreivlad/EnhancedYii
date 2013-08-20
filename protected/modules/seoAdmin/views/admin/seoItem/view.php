<?php
/* @var $this SeoItemController */
/* @var $model SeoItem */

$this->breadcrumbs=array(
	'Seo Items'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List SeoItem', 'url'=>array('index')),
	array('label'=>'Create SeoItem', 'url'=>array('create')),
	array('label'=>'Update SeoItem', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete SeoItem', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage SeoItem', 'url'=>array('admin')),
);
?>

<h1>View SeoItem #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'meta_keywords',
		'meta_description',
		'identifier',
		'created',
	),
)); ?>
