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
		.mdl-data-table								{ width:100%; }
		.mdl-layout__header							{
-webkit-box-shadow: 0px 5px 5px 0px rgba(0,0,0,0.75);
-moz-box-shadow: 0px 5px 5px 0px rgba(0,0,0,0.75);
box-shadow: 0px 5px 5px 0px rgba(0,0,0,0.75);
}		
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
            <a class="mdl-navigation__link" href="#" id="user-button">Administrator</a>
          </nav>
		  <button id="demo-menu-lower-right" class="mdl-button mdl-js-button mdl-button--icon">
		  	<i class="material-icons">more_vert</i>
		  </button>
		  <ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect" for="demo-menu-lower-right">
		    <li class="mdl-menu__item">Profile</li>
		    <li class="mdl-menu__item">Logout</li>
		  </ul>          
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
        <div class="page-content"><!-- Your content goes here -->
        
			<div class="mdl-grid" style="display:flex!important; align-items:center!important; justify-content:center;">
			  <div class="mdl-cell mdl-cell--4-col">
			  
				<h4>User Access</h4>
				
				<table class="mdl-data-table mdl-js-data-table">
				    <thead>
				        <tr>
				            <th class="mdl-data-table__cell--non-numeric">Text</th>
				            <th class="mdl-data-table__cell--non-numeric">Text</th>
				            <th>Numbers</th>
				            <th class="mdl-data-table__cell--non-numeric">Text</th>
				        </tr>
				    </thead>
				    <tbody>
				        <tr>
				            <td class="mdl-data-table__cell--non-numeric">Table cell</td>
				            <td class="mdl-data-table__cell--non-numeric">Table cell</td>
				            <td>40</td>
				            <td class="mdl-data-table__cell--non-numeric">Table cell</td>
				        </tr>
				        <tr>
				            <td class="mdl-data-table__cell--non-numeric">Table cell</td>
				            <td class="mdl-data-table__cell--non-numeric">Table cell</td>
				            <td>122</td>
				            <td class="mdl-data-table__cell--non-numeric">Table cell</td>
				        </tr>
				        <tr>
				            <td class="mdl-data-table__cell--non-numeric">Table cell</td>
				            <td class="mdl-data-table__cell--non-numeric">Table cell</td>
				            <td>144</td>
				            <td class="mdl-data-table__cell--non-numeric">Table cell</td>
				        </tr>
				    </tbody>
				</table>
			  
			  </div>
			  <div class="mdl-cell mdl-cell--4-col">
			  
				<h4>System Logs</h4>
				
				<table class="mdl-data-table mdl-js-data-table">
				    <thead>
				        <tr>
				            <th class="mdl-data-table__cell--non-numeric">Text</th>
				            <th class="mdl-data-table__cell--non-numeric">Text</th>
				            <th>Numbers</th>
				            <th class="mdl-data-table__cell--non-numeric">Text</th>
				        </tr>
				    </thead>
				    <tbody>
				        <tr>
				            <td class="mdl-data-table__cell--non-numeric">Table cell</td>
				            <td class="mdl-data-table__cell--non-numeric">Table cell</td>
				            <td>40</td>
				            <td class="mdl-data-table__cell--non-numeric">Table cell</td>
				        </tr>
				        <tr>
				            <td class="mdl-data-table__cell--non-numeric">Table cell</td>
				            <td class="mdl-data-table__cell--non-numeric">Table cell</td>
				            <td>122</td>
				            <td class="mdl-data-table__cell--non-numeric">Table cell</td>
				        </tr>
				        <tr>
				            <td class="mdl-data-table__cell--non-numeric">Table cell</td>
				            <td class="mdl-data-table__cell--non-numeric">Table cell</td>
				            <td>144</td>
				            <td class="mdl-data-table__cell--non-numeric">Table cell</td>
				        </tr>
				    </tbody>
				</table>
			  
			  </div>
			  <div class="mdl-cell mdl-cell--6-col">
			  	<h4>PageRank</h4>
			  	<div id="div-chart"></div>
			  </div>
			</div>
        
        </div>
      </main>
    </div>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script type="text/javascript">
    $(document).ready(function() {
        $('#user-button').click(function(e) {
            $('#demo-menu-lower-right').click();
        });
    });
    </script>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
  <script type="text/javascript">
    google.load('visualization', '1.1', {packages: ['line']});
    google.setOnLoadCallback(drawChart);

    function drawChart() {

      var data = new google.visualization.DataTable();
      data.addColumn('number', 'Day');
      data.addColumn('number', 'Guardians of the Galaxy');
      data.addColumn('number', 'The Avengers');
      data.addColumn('number', 'Transformers: Age of Extinction');

      data.addRows([
        [1,  37.8, 80.8, 41.8],
        [2,  30.9, 69.5, 32.4],
        [3,  25.4,   57, 25.7],
        [4,  11.7, 18.8, 10.5],
        [5,  11.9, 17.6, 10.4],
        [6,   8.8, 13.6,  7.7],
        [7,   7.6, 12.3,  9.6],
        [8,  12.3, 29.2, 10.6],
        [9,  16.9, 42.9, 14.8],
        [10, 12.8, 30.9, 11.6],
        [11,  5.3,  7.9,  4.7],
        [12,  6.6,  8.4,  5.2],
        [13,  4.8,  6.3,  3.6],
        [14,  4.2,  6.2,  3.4]
      ]);

      var options = {
        chart: {
          title: 'Box Office Earnings in First Two Weeks of Opening',
          subtitle: 'in millions of dollars (USD)'
        },
        width: 900,
        height: 500
      };

      var chart = new google.charts.Line(document.getElementById('div-chart'));

      chart.draw(data, options);
    }
  </script>
    
  </body>
</html>	