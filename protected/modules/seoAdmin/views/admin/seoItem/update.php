<?php
/* @var $this SeoItemController */
/* @var $model SeoItem */

$this->breadcrumbs=array(
	'Seo Items'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List SeoItem', 'url'=>array('index')),
	array('label'=>'Create SeoItem', 'url'=>array('create')),
	array('label'=>'View SeoItem', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage SeoItem', 'url'=>array('admin')),
);
?>

<h1>Update SeoItem <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>