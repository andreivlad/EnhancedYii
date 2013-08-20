<?php
/* @var $this SeoItemController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Seo Items',
);

$this->menu=array(
	array('label'=>'Create SeoItem', 'url'=>array('create')),
	array('label'=>'Manage SeoItem', 'url'=>array('admin')),
);
?>

<h1>Seo Items</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
