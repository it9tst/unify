<!DOCTYPE HTML>
<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<base href="https://admin.unify-unipa.it/" target="_self">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo isset($this->title)?$this->title:"Unify";?></title>
	<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
	<link rel="stylesheet" href="https://unify-unipa.it/css/fontawesome.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.css">

	<style>
		*{
			font-family: 'Roboto', sans-serif;
		}
	</style>

	<?php $this->printJsModule();?>
	<?php $this->printCssModule();?>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.min.js"></script>
</head>

<body>
