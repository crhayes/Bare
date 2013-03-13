<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title><?= $title ?></title>
    <meta name="description" content="<?= $description ?>">
    <meta name="keywords" content="<?= $keywords ?>">
    <meta name="viewport" content="width=device-width">
    <link rel="icon" type="image/png" href="<?php asset('images/favicon.png') ?>">

    <!-- Stylesheets -->
    <? section('styles') ?>
        <link rel="stylesheet" type="text/css" href="<?php asset('css/normalize.css') ?>">
        <link rel="stylesheet" type="text/css" href="<?php asset('css/font-awesome.min.css') ?>">
        <link rel="stylesheet" type="text/css" href="<?php asset('css/style.css') ?>">
        <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Abril+Fatface|Gentium+Book+Basic|Gravitas+One|Lato|Merriweather|Old+Standard+TT|Open+Sans|Playfair+Display|PT+Sans|PT+Sans+Narrow|PT+Serif|Vollkorn" type="text/css">
        <!--
        <link rel="stylesheet" type="text/css" href="<?php asset('css/colorbox/style-1.css') ?>">
        -->
        <!--[if IE 7]><link rel="stylesheet" href="<?php asset('css/font-awesome-ie7.min.css') ?>"><![endif]-->
    <? close() ?>
  </head>
  <body>

    <? show('content') ?>

    <? section('scripts') ?>
        <!-- Javascripts -->
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
        <script type="text/javascript" src="<?php asset('js/app.js') ?>"></script>
        <!--
        <script type="text/javascript" src="<?php asset('js/jquery.colorbox-min.js') ?>"></script>
        <script type="text/javascript" src="<?php asset('js/less-1.3.0.min.js') ?>"></script>
        <script type="text/javascript" src="<?php asset('js/form-default-toggle.js') ?>"></script>
        <script type="text/javascript" src="http://ajax.microsoft.com/ajax/jquery.validate/1.9/jquery.validate.min.js"></script>
        -->
        <!--[if lt IE 9]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
    <? close() ?>
  </body>
</html>