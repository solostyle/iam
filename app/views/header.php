<html>
<head>
<title>Test MVC for iam.solostyle.net</title>
     <!-- Individual YUI JS files --> 
     <?php $html = new HTML();?>
     <?php echo $html->includeJs('yui28yahoo');?>
     <?php echo $html->includeJs('yui28event');?>
     <?php echo $html->includeJs('yui28connection');?>
     <?php echo $html->includeJs('yui28dom');?>
     <?php echo $html->includeJs('iam');?>
     <?php echo $html->includeJs('iam.shell');?>
     <?php echo $html->includeJs('iam.blog');?>
     <?php echo $html->includeJs('iam.archmenu');?>
<?php echo $html->includeCss('layout');?>
<?php echo $html->includeCss('format');?>
</head>
<body>
<div id="page">
  <h1 id="title"><a href="/">meditations</a></h1>
  <div id="login"></div>