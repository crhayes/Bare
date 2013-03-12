<? extend('layouts/login') ?>

<? $title = 'Admin Login' ?>

<?php
  // Set up validation rules
  $validation = Validation::make($_POST, array('credentials' => 'No user with that username and password exists.'))
    ->rule('email', 'required | email')
    ->rule('password', 'required');

  // Process form submission and validate
  if ($_POST && $validation->passes()) {
    $user = Database::row('SELECT user_id FROM user WHERE email = :email AND password = :password', array(
      ':email'    => $_POST['email'],
      ':password' => make_hash($_POST['password'], $_POST['email'])
    ));

    if ($user->user_id) {
      Session::set('user', $user);
      redirect('index', 302);
    } else {
      $validation->errors->addError('login', 'credentials');
    }
  }
?>

<? section('content') ?>
  <form id="login-form" method="post" class="panel">
    <h1>Login</h1>

    <?= $validation->errors->first('login', '<div class="alert-box alert">:message</div>') ?>

    <div class="<?= $validation->errors->exist('email') ? 'error' : '' ?>">
      <input type="text" name="email" placeholder="Email" value="<?= array_get('email', $_POST); ?>">
      <?= $validation->errors->get('email', '<small>:message</small>', true); ?>
    </div>

    <div class="<?= $validation->errors->exist('password') ? 'error' : '' ?>">
      <input type="password" name="password" placeholder="Password">
      <?= $validation->errors->get('password', '<small>:message</small>', true); ?>
    </div>
    <button type="submit" name="submit" class="button">Login</button>
  </form>
<? close() ?>
