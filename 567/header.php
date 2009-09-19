<?php
$pagetitle='bkm member login';
$cssfile='http://iam.solostyle.net/bkm.css';
$extra_header = '<link rel="alternate" type="application/rss+xml" title="RSS" href="http://iam.solostyle.net/rss.xml" />
<link rel="service.feed" type="application/atom+xml" title="Atom" href="http://iam.solostyle.net/atom.xml" />
<script src="http://iam.solostyle.net/ajax.js"></script>';

print '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<title>' . $pagetitle . '</title>
<style type="text/css">
@import url("' . $cssfile . '");
</style>
	' . $extra_header . '</head>
	
<body>
';


?>
