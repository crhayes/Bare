<!DOCTYPE html>
  <!--[if IE 8]><html class="no-js lt-ie9" lang="en"><![endif]-->
  <!--[if gt IE 8]><!--><html class="no-js" lang="en"><!--<![endif]-->
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Welcome to Foundation</title>
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="viewport" content="width=device-width" />
    <link rel="icon" type="image/png" href="<?php asset('images/favicon.png') ?>">
    
    <!-- Stylesheets -->
    <link rel="stylesheet" href="<?php asset('css/foundation.min.css') ?>">
    <link rel="stylesheet/less" href="<?php asset('css/app.css') ?>">

    <script src="<?php asset('js/modernizr.foundation.js') ?>"></script>
  </head>
  <body>
    <!-- Header and Nav -->
    <div class="contain-to-grid fixed">
      <nav class="top-bar">
        <ul>
          <!-- Title Area -->
          <li class="name">
            <h1>
              <a href="#">
                Admin
              </a>
            </h1>
          </li>
          <li class="toggle-topbar"><a href="#"></a></li>
        </ul>
        <section>
          <!-- Left Nav Section -->
          <ul class="left">
            <li class="divider"></li>
          </ul>
          <!-- Right Nav Section -->
          <ul class="right">
            <li class="divider"></li>
          </ul>
        </section>
      </nav>
    </div>

    <div id="page-container" class="row ">
      <h1>Login</h1>
      <?php echo Session::get('name'); ?>
    </div>
    
    <!-- Included JS Files (Uncompressed) -->
    <!--
    <script src="<?php asset('js/jquery.js') ?>"></script>
    <script src="<?php asset('js/jquery.foundation.mediaQueryToggle.js') ?>"></script>
    <script src="<?php asset('js/jquery.foundation.forms.js') ?>"></script>
    <script src="<?php asset('js/jquery.event.move.js') ?>"></script>
    <script src="<?php asset('js/jquery.event.swipe.js') ?>"></script>
    <script src="<?php asset('js/jquery.foundation.reveal.js') ?>"></script>
    <script src="<?php asset('js/jquery.foundation.orbit.js') ?>"></script>
    <script src="<?php asset('js/jquery.foundation.navigation.js') ?>"></script>
    <script src="<?php asset('js/jquery.foundation.buttons.js') ?>"></script>
    <script src="<?php asset('js/jquery.foundation.tabs.js') ?>"></script>
    <script src="<?php asset('js/jquery.foundation.tooltips.js') ?>"></script>
    <script src="<?php asset('js/jquery.foundation.accordion.js') ?>"></script>
    <script src="<?php asset('js/jquery.placeholder.js') ?>"></script>
    <script src="<?php asset('js/jquery.foundation.alerts.js') ?>"></script>
    <script src="<?php asset('js/jquery.foundation.topbar.js') ?>"></script>
    <script src="<?php asset('js/jquery.foundation.joyride.js') ?>"></script>
    <script src="<?php asset('js/jquery.foundation.clearing.js') ?>"></script>
    <script src="<?php asset('js/jquery.foundation.magellan.js') ?>"></script>
    
    -->
    
    <!-- Included JS Files (Compressed) -->
    <script src="<?php asset('js/foundation.min.js') ?>"></script>
    
    <!-- Initialize JS Plugins -->
    <script src="<?php asset('js/app.js') ?>"></script>
    <script src="<?php asset('js/less-1.3.0.min.js') ?>"></script>
    
    <script>
      $(window).load(function(){
        $("#featured").orbit();
      });
    </script>  
  </body>
</html>
