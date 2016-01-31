<html>
	<head>
	<title>Localchat</title>
	<link rel="stylesheet" href="css/main.css">
	<link rel="stylesheet" href="css/bootstrap.css">
	<script src="js/jquery.js"></script>
	<script src="js/angular.min.js"></script>
	<script src="js/main.js"></script>

<?php
	
if(isset($_COOKIE["localchat"]))
{
	
}
else
{
	if(!setcookie("localchat",uniqid()))
	{
		die("Cookies must be allowed");
	}
}


?>

	</head>
	<body>
		<div id="header"><div ng-app=""></div></div>
		<div class="text-center bg-primary rights">
			<h1>Classroom Chat</h1>
		</div>
		<div class="container" style="background-color: #fff">
			<div id="chat">
				<div class="row">
					<div class="col-sm-8">
						<div id="chatMsg" class="chat">
						</div>
						<div id="input">
							<input id="msg" type="text"/>
							<button class="btn btn-primary" onclick="send()">Send</button>
						</div>
						<div id="user">
							<table>
								<tr>
									<td>
										Status
									</td>
									<td>
										Name
									</td>
								</tr>
							</table>
						</div>
					</div>
					<div class="col-sm-4">
						<h4 class="text-center bg-primary rights">Commands und Rechte</h4>
						<button id="show" type="button" class="btn btn-info nopadding">show</button>
						<button id="hide" type="button" class="btn btn-info nopadding">hide</button>
						<div id="help" class="box bg-info rights">
							<h4>WORKING!!</h4>
							!command [must be set] (can be set)
							<br><br>
							!login [username (regex:/[-_a-z0-9]{4,20}/i)] rights:0
							<br>
							!eval [PHP command (no spaces)] rights:10000000
							<br>
							!me [message] rights:0
							<br>
							!invite [modulename] [user] [...] : (option) (...) rights:0
							<br>
							!rights (user) (new_rights) rights:0
							<br>
							!kick [user] rights:10000
							<br>
							!ban [user] (user|ip) rights:20000
							<br> 
							!unban [user] rights:20000
							<br>
							<br>
							<h4>NOT WORKING!!!!!</h4>
							!group [group name] [user] [...]
							<br>
							!block request
							<br>
							!unblock request
							<br>
							<br>
							!block msg 
							<br>
							!unblock msg
							<br>
							<br>
							liste der commands
						</div>
					</div>
				</div>
			</div>
		</div>
		<div id="modules"></div>
		<script>
			$(document).ready(function(){
		    $("#show").click(function(){
	        $("#help").show();
	      });
	    	$("#hide").click(function(){
	        $("#help").hide();
	    	});
			});
		</script>
	</body>
</html>