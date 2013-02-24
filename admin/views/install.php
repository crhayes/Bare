<?php

if (array_get('user', $_POST)) {
	User::install();
}

?>

<?php load_view('partials/header') ?>

    <div id="page-container" class="row">
      <div class="twelve columns">
        <h1>Install Database Tables</h1>
        <form method="post">
	        <ul class="block-grid four-up">
	        	<li><button type="submit" name="user" value="user" class="button">User &amp; Roles Tables</button></li>
	        </ul>
	    </form>
      </div>
    </div>
    
<?php load_view('partials/footer') ?>
