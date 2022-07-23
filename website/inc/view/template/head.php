<!DOCTYPE HTML>
<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<meta name="viewport" content="minimal-ui, width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<base href="<?php if(isset($this->baseUrl)) echo $this->baseUrl; else echo "https://$_SERVER[HTTP_HOST]";?>" target="_self">
    <title><?php echo isset($this->title)?$this->title:"Unify";?></title>
	<link href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i" rel="stylesheet">
	<link rel="stylesheet" href="/css/fontawesome.css">

	<style>
		*{
			font-family: 'Roboto', sans-serif;
		}
	</style>

	<?php $this->printJsModule();?>
	<?php $this->printCssModule();?>
</head>

<body>
