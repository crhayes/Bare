<!DOCTYPE html>
  <!--[if IE 8]><html class="no-js lt-ie9" lang="en"><![endif]-->
  <!--[if gt IE 8]><!--><html class="no-js" lang="en"><!--<![endif]-->
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title><?= $title ?></title>
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="viewport" content="width=device-width" />
    <link rel="icon" type="image/png" href="<?= asset('images/favicon.png') ?>">
    
    <!-- Stylesheets -->
    <link rel="stylesheet" href="<?= asset('css/foundation.min.css') ?>">
    <link rel="stylesheet/less" href="<?= asset('css/style.css') ?>">

    <script src="<?= asset('js/modernizr.foundation.js') ?>"></script>
  </head>
  <body>
    
    <? show('content') ?>

    <!-- Javascripts -->
    <script src="<?= asset('js/foundation.min.js') ?>"></script>
    <script src="<?= asset('js/app.js') ?>"></script>
    <script src="<?= asset('js/less-1.3.0.min.js') ?>"></script>
  </body>
</html>