<?php
/* @var $this SampleController */
/* @var $model Sample */

$this->breadcrumbs=array(
	'Samples'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Sample', 'url'=>array('index')),
	array('label'=>'Manage Sample', 'url'=>array('admin')),
);
?>

<h1>Create Sample</h1>

<ul id="filelist"></ul>
<br />
 
<div id="container">
    <a id="browse" href="javascript:;">[Browse...]</a>
    <a id="start-upload" href="javascript:;">[Start Upload]</a>
</div>
 
<br />
<pre id="console"></pre>

