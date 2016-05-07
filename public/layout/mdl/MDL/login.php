<html>
  <head>
    <!-- Material Design Lite -->
    <script src="https://storage.googleapis.com/code.getmdl.io/1.0.5/material.min.js"></script>
    <link rel="stylesheet" href="https://storage.googleapis.com/code.getmdl.io/1.0.5/material.indigo-pink.min.css">
    <!-- Material Design icon font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <style type="text/css">
		body										{ display:flex!important; align-items:center!important; justify-content:center; }
		.mdl-layout__container						{ margin-left:30px; width:400px; height:495px; position:relative; }
		#button-login								{ top:0; left:237px; }
	</style>
  </head>
  <body>
    <!-- Uses a header that scrolls with the text, rather than staying
      locked at the top -->
    <div class="mdl-layout mdl-js-layout">
    	<main class="mdl-layout__content">
    	 <div class="page-content">
		<form method="post" action="index.php" id="login-form">
		  <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
		  	<img src="public/images/logo-login.png" alt="login" />
		  </div>
		  <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
		    <input class="mdl-textfield__input" type="text" id="sample3" />
		    <label class="mdl-textfield__label" for="sample3">Login</label>
		  </div>
		  <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
		    <input class="mdl-textfield__input" type="text" id="sample3" />
		    <label class="mdl-textfield__label" for="sample3">Password</label>
		  </div>
		  <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
			<label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="checkbox-1">
			  <input type="checkbox" id="checkbox-1" class="mdl-checkbox__input" checked />
			  <span class="mdl-checkbox__label">Stay me connected.</span>
			</label>		  
		  </div>
		  <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="padding-top: 0px;">
		  	<p>If you forgot password, click <a href="#" alt="Password Recovery">here</a>.</p>
		  </div>
		  <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
			<button class="mdl-button mdl-js-button mdl-button--raised" id="button-login">
			  Login
			</button>
		  </div>		  
		</form>
		</div>
		</main>
    </div>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script type="text/javascript">
    $(document).ready(function() {
        $('#button-login').click(function(e) {
            $('#login-form').submit();
        });
    });
    </script>
  </body>
</html>	