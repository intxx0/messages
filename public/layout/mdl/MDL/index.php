<html>
  <head>
    <!-- Material Design Lite -->
    <script src="https://storage.googleapis.com/code.getmdl.io/1.0.5/material.min.js"></script>
    <link rel="stylesheet" href="https://storage.googleapis.com/code.getmdl.io/1.0.5/material.indigo-pink.min.css">
    <!-- Material Design icon font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <style type="text/css">
		.mdl-layout__drawer-button i.material-icons { margin-top:11px; }
		.mdl-navigation__link						{ display:flex!important; align-items:center!important; }
		.mdl-navigation__link i						{ margin-right:40px; }
	</style>
  </head>
  <body>
    <!-- Uses a header that scrolls with the text, rather than staying
      locked at the top -->
    <div class="mdl-layout mdl-js-layout">
      <header class="mdl-layout__header mdl-layout__header--scroll">
        <div class="mdl-layout__header-row">
          <!-- Title -->
          <span class="mdl-layout-title">Title</span>
          <!-- Add spacer, to align navigation to the right -->
          <div class="mdl-layout-spacer"></div>
          <!-- Navigation -->
          <nav class="mdl-navigation">
            
            <a class="mdl-navigation__link" href="">Administrator</a>
          </nav>
        </div>
      </header>
      <div class="mdl-layout__drawer">
        <span class="mdl-layout-title">Title</span>
        <nav class="mdl-navigation">
            <a class="mdl-navigation__link" href="">
            	<i role="presentation" class="mdl-color-text--blue-grey-400 material-icons">dashboard</i>
            	Dashboard
            </a>
            <a class="mdl-navigation__link" href="">
            	<i role="presentation" class="mdl-color-text--blue-grey-400 material-icons">people</i>
            	Users
            </a>
            <a class="mdl-navigation__link" href="">
            	<i role="presentation" class="mdl-color-text--blue-grey-400 material-icons">extension</i>
            	Modules
            </a>
            <a class="mdl-navigation__link" href="">
            	<i role="presentation" class="mdl-color-text--blue-grey-400 material-icons">settings</i>
            	Settings
            </a>
            <a class="mdl-navigation__link" href="">
            	<i role="presentation" class="mdl-color-text--blue-grey-400 material-icons">power_settings_new</i>
            	Logout
            </a>
        </nav>
      </div>
      <main class="mdl-layout__content">
        <div class="page-content"><!-- Your content goes here --></div>
      </main>
    </div>
  </body>
</html>	