<? extend('layouts/default') ?>

<? $title = 'Install' ?>

<?php
if (array_get('user', $_POST)) {
	User::install();
}
?>

<? section('content') ?>
  <div class="twelve columns">
    <h1>Install Database Tables</h1>
    <form method="post">
        <ul class="block-grid four-up">
        	<li><button type="submit" name="user" value="user" class="button">User &amp; Roles Tables</button></li>
        </ul>
    </form>
  </div>
<? close() ?>