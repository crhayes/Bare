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
    <link rel="icon" type="image/png" href="<?= asset('images/favicon.png') ?>">
    
    <!-- Stylesheets -->
    <link rel="stylesheet" href="<?= asset('css/foundation.min.css') ?>">
    <link rel="stylesheet/less" href="<?= asset('css/style.css') ?>">

    <script src="<?= asset('js/modernizr.foundation.js') ?>"></script>
  </head>
  <body>
    <!-- Header and Nav -->
    <div class="contain-to-grid fixed">
      <nav class="top-bar">
        <ul>
          <!-- Title Area -->
          <li class="name">
            <h1>
              <a href="<?= url('home') ?>">
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
            <li class="has-dropdown">
              <a class="active" href="#">Main Item 1</a>
              <ul class="dropdown">
                <li><label>Section Name</label></li>
                <li><a href="#" class="">Dropdown Level 1</a></li>
                <li><a href="#">Dropdown Option</a></li>
                <li><a href="#">Dropdown Option</a></li>
                <li class="divider"></li>
                <li><label>Section Name</label></li>
                <li><a href="#">Dropdown Option</a></li>
                <li><a href="#">Dropdown Option</a></li>
                <li><a href="#">Dropdown Option</a></li>
                <li class="divider"></li>
                <li><a href="#">See all &rarr;</a></li>
              </ul>
            </li>
            <li class="divider"></li>
            <li><a href="#">Main Item 2</a></li>
            <li class="divider"></li>
          </ul>

          <!-- Right Nav Section -->
          <ul class="right">
            <li class="divider"></li>
            <li><a href="<?= url('logout') ?>">Logout</a></li>
          </ul>
        </section>
      </nav>
    </div>
    <!-- End Header and Nav -->

    <div id="page-container" class="row">
      <? show('content') ?>
    </div>

    <!-- Javascripts -->
    <script src="<?= asset('js/foundation.min.js') ?>"></script>
    <script src="<?= asset('js/app.js') ?>"></script>
    <script src="<?= asset('js/less-1.3.0.min.js') ?>"></script> 
  </body>
</html>