<?php
  
  // Default values for page variables
  $errors = new ValidationError(array(
    'credentials' => 'No user with that username and password exists.'
  ));

  // Process form submission
  if ($_POST) {
    $validation = Validate::input()
      ->rule('email', 'required | email')
      ->rule('password', 'required');

    if ($validation->passes()) {
      $user = Database::row('SELECT user_id FROM user WHERE email = :email AND password = :password', array(
        ':email'    => $_POST['email'],
        ':password' => make_hash($_POST['password'], $_POST['email'])
      ));

      if ($user->user_id) {
        Session::set('user', $user);
        redirect('home', 302);
      } else {
        $errors->addError('login', 'credentials');
      }
    } else {
      $errors = $validation->errors;
    }
  }

?>

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
    <link rel="stylesheet/less" href="<?php asset('css/style.css') ?>">
    <link rel="stylesheet/less" href="<?php asset('css/app.css') ?>">

    <script src="<?php asset('js/modernizr.foundation.js') ?>"></script>
  </head>
  <body>
    <form id="login-form" method="post" class="panel">
      <h1>Login</h1>

      <?php echo $errors->first('login', '<div class="alert-box alert">:message</div>') ?>

      <div class="<?php echo $errors->exist('email') ? 'error' : '' ?>">
        <input type="text" name="email" placeholder="Email" value="<?php echo array_get('email', $_POST); ?>">
        <?php echo $errors->first('email', '<small>:message</small>'); ?>
      </div>

      <div class="<?php echo $errors->exist('password') ? 'error' : '' ?>">
        <input type="password" name="password" placeholder="Password">
        <?php echo $errors->first('password', '<small>:message</small>'); ?>
      </div>
      <button type="submit" name="submit" class="button">Login</button>
    </form>
    
    <!-- Javascripts -->
    <script src="<?php asset('js/foundation.min.js') ?>"></script>
    <script src="<?php asset('js/app.js') ?>"></script>
    <script src="<?php asset('js/less-1.3.0.min.js') ?>"></script>  
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
