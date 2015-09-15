<!DOCTYPE html>
<?php
session_start ();
?>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
<meta name="description" content="">
<meta name="author" content="">
<link rel="icon" href="../../favicon.ico">


<title>Kickstarter Project</title>



<!-- Jquery core CSS -->
<script	src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="js/project_functions.js"></script>


<!-- Bootstrap core CSS -->
<link href="css/bootstrap.min.css" rel="stylesheet">
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
 
<!-- Navbar -->
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.3.15/angular.min.js"></script>
<script src="js/navbar.js"></script>
<script src="js/modal.js"></script>
<link href="css/navbar.css" rel="stylesheet">
<script>
	var projectId;
	var userName;
 	var user_id;
	var userType;
	
	var app = angular.module('myApp', []);
	app.controller('searchCtrl', function($scope, $http) {
		$http.get("php/getProjects.php").success(function(response) {
			$scope.names = response;
			$scope.getURL = function(id) {
				return "project.php?id=" + id;
			}
		});
	});

	$(document).ready(function() {
		userType = <?php  echo isset($_SESSION['is_auth']) ? $_SESSION['is_auth'] : -1; ?>;
		userName = <?php echo isset($_SESSION['name']) ? "'".$_SESSION['name']."'" : "''" ?>;
		user_id = <?php  echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0; ?>;
		projectId = <?php echo  $_GET['id']; ?>;

		var link = "project.php?id="+projectId;
	    if(userType != -1)
        	onLogIn(userName, link);
	    else
	    	onLogOut(link);

      	//get project info
		getProjectPageInfo(projectId, userType);
	});
</script>

<script>
    $(document).ready(function() {
        //$("[rel='tooltip']").tooltip();    
     
        $('.donate').hover(
            function(){
                $('.caption').slideDown(250); //.fadeIn(250)
            },
            function(){
                $('.caption').slideUp(250); //.fadeOut(205)
            }
        ); 
    });
</script>

<style type="text/css">
	.caption {
	    position:absolute;
	    top:0;
	    right:0;
	    background:rgba(43,222,115,0.9);
	    width:100%;
	    height:100%;
	    padding:2%;
	    display: none;
	    text-align:center;
	    color:#fff !important;
	    z-index:2;
	}
	
	.rewardDonate:hover {
		background-color: #CDCDFD;
		cursor: pointer;
	}

</style>

<!-- Custom styles for this template 
    <link href="jumbotron.css" rel="stylesheet">-->

<!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
<!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]
    <script src="../../assets/js/ie-emulation-modes-warning.js"></script>-->

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>
	<!-- Navbar -->
	<nav class="navbar navbar-inverse navbar-fixed-top">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed"
					data-toggle="collapse" data-target="#navbar" aria-expanded="false"
					aria-controls="navbar">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
				 	<span class="icon-bar"></span> 
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="index.php">Kickstarter Project</a>
			</div>

			<div class="nav navbar-nav navbar-form">
				<div ng-app="myApp" ng-controller="searchCtrl">
					<div class="form-group col-md-12">
						<div class="searchBoxDropdown input-group col-md-12" style="padding-left: 20px; padding-right: 20px; width:150%;">
							<input type="text" ng-model="test" placeholder="Search" id="searchbox" class="form-control dropdown-toggle" data-toggle="dropdown" />
							<span class="input-group-btn">
		                        <button class="btn btn-info searchBT" style="border-radius: 4px; margin-left: 0px; cursor: default;">
		                            <i class="glyphicon glyphicon-search"></i>
		                        </button>
		                    </span>
							<ul class="dropdown-menu col-md-9" style="padding: 2px; margin-left: 20px;">
								<li ng-repeat="x in names | filter:test" style="padding-bottom: 2px;">
									<a class="well searchDiv" href={{getURL(x.id)}} style="padding: 5px; margin: 0px;">
										<img width="50px" src={{x.picture}} /> {{x.name}}
									</a>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>

			<div id="navbar" class="navbar-collapse collapse">
				<form class="navbar-form navbar-right">
					<div class="form-group">
						<input type="email" id="logInEmail" placeholder="Email" class="form-control">
					</div>
					<div class="form-group">
						<input type="password" id="logInPassword" placeholder="Password" class="form-control">
					</div>
					<button type="submit" class="btn btn-success" onclick="submitLogIn()">Sign in</button>
					<a href="register.html" class="btn btn-success">Register</a>
				</form>
			</div>
		</div>
	</nav>


	<!-- Main title -->
	<div class="jumbotron">
		<div class="container viewDetails"></div>
	</div>

	<!-- Description -->
	<div class="container">
		<div class="col-md-8" style="margin-top: 15px;">
			<h3>About this project</h3>
			<br> <br>
			<p id="about"></p>
			<br>
		</div>
		<div class="col-md-4" style="margin-top: 15px;">
			<h3>Rewards</h3>
			<br> <br>
			<div class="rewards">
				<div class="jumbotron" style="padding-left: 0; padding-top: 17px; padding-right: 5px;">
					<div class="container">
						<div class="col-md-12">
							<p style="font-size: 15px;"><b>Pledge $5 or more</b></p>
							<p style="font-size: 12px;">
								<span class="glyphicon glyphicon-heart"></span>
								<b>13 backers</b>
							</p>
							<p class="donate_description" style="font-size: 15px;">
								OUR DEAREST THANKS
								<br><br>
								Your name will be featured in the Kickstarter thanks list on our website and in our print program. We, and possibly others, will read it with gratitude in our hearts. (NOTE: This reward will be included with all others).
							</p>
						</div>
					</div>
				</div>
				
				<div class="jumbotron" style="padding-left: 0; padding-top: 17px; padding-right: 5px;">
					<div class="container">
						<div class="col-md-12">
							<p style="font-size: 15px;"><b>Pledge $20 or more</b></p>
							<p style="font-size: 12px;">
								<span class="glyphicon glyphicon-heart"></span>
								<b>10 backers</b>
							</p>
							<p class="donate_description" style="font-size: 15px;">
								OUR DEAREST THANKS
								<br><br>
								Your name will be featured in the Kickstarter thanks list on our website and in our print program. We, and possibly others, will read it with gratitude in our hearts. (NOTE: This reward will be included with all others).
							</p>
						</div>
					</div>
				</div>
				
				<div class="jumbotron" style="padding-left: 0; padding-top: 17px; padding-right: 5px;">
					<div class="container donate">
						<div class="col-md-12">
						
							<div class="caption" style="vertical-align: middle;">
			                    <div class="h3 white">Select this reward</div>
		                	</div>
		                	          
							<p style="font-size: 15px;"><b>Pledge $50 or more</b></p>
							<p style="font-size: 12px;">
								<span class="glyphicon glyphicon-heart"></span>
								<b>5 backers</b>
							</p>
							<p class="donate_description" style="font-size: 15px;">
								OUR DEAREST THANKS
								<br><br>
								Your name will be featured in the Kickstarter thanks list on our website and in our print program. We, and possibly others, will read it with gratitude in our hearts. (NOTE: This reward will be included with all others).
							</p>
						</div>
					</div>
				</div>
				
				<div class="jumbotron" style="padding-left: 0; padding-top: 17px; padding-right: 5px;">
					<div class="container">
						<div class="col-md-12">
							<p style="font-size: 15px;"><b>Pledge $100 or more</b></p>
							<p style="font-size: 12px;">
								<span class="glyphicon glyphicon-heart"></span>
								<b>2 backers</b>
							</p>
							<p class="donate_description" style="font-size: 15px;">
								OUR DEAREST THANKS
								<br><br>
								Your name will be featured in the Kickstarter thanks list on our website and in our print program. We, and possibly others, will read it with gratitude in our hearts. (NOTE: This reward will be included with all others).
							</p>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<!-- Modal -->
		<div class="modal fade" id="myModal" role="dialog"></div>
		
		<hr>
		<footer>
			<p>&copy; Shulu, Oren</p>
		</footer>
	</div>
	

	<!-- Bootstrap core JavaScript
    ================================================== -->
	<!-- Placed at the end of the document so the pages load faster 
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="../../dist/js/bootstrap.min.js"></script> -->
	<!-- IE10 viewport hack for Surface/desktop Windows 8 bug 
    <script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>
    -->
</body>
</html>
