<html>
	<head>
	<title>Localchat</title>
	<link rel="stylesheet" href="style/main.css">

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
		<div id="chat">
			<div id="chatMsg"></div>
			<div id="input">
				<input id="msg" type="text"/>
				<button onclick="send()">Send</button>
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
			<div id="help"><a href="help.php">Commands und Rechte</a></div>
		</div id="modules">
	</body>
</html>