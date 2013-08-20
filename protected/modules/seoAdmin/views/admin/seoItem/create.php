<?php
/* @var $this SeoItemController */
/* @var $model SeoItem */

$this->breadcrumbs=array(
	'Seo Items'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List SeoItem', 'url'=>array('index')),
	array('label'=>'Manage SeoItem', 'url'=>array('admin')),
);
?>

<h1>Create SeoItem</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>