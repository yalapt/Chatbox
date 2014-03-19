<!DOCTYPE html>
<html>
	<head>
		<?php echo $this->Html->charset('utf8'); ?>
		<title>
			<?php echo $title; ?>
		</title>
		<?php
			echo $this->Html->meta('icon', $this->Html->url('../favicon.png'));
			echo $this->Html->css('bootstrap.css');
			echo $this->Html->css('style.css');
			echo $this->Html->script('jquery.js');
			echo $this->Html->script('script.js');
		?>
	</head>
	<body onload="ini(); loggedIn(); listLoggedUsers(); listChatMessages();">
		<div id="header">
			<div class="navbar navbar-inverse navbar-static-top">
			    <div class="container">
			        <div class="navbar-header">
			            <a class="navbar-brand" href="../chat">Web@cademie</a>
			        </div>
			        <div class="navbar-collapse">
			            <ul class="nav navbar-nav">
			                <li><a href="../chat">ChatBox</a></li>
			                <li><a href="../user">Mon compte</a></li>
			                <li><a href="../user/logout">Déconnexion</a></li>
			            </ul>
			        </div>
			    </div>
			</div>
		</div>
		<div id="container">
			<div class="container">
				<div id="generalAlert" style="display: none;"></div>
			<?php
				echo $this->Session->flash();
				echo $this->fetch('content');
			?>
			</div>
		</div>
		<div id="footer">
			<div class="container">
				<hr>
				<p class="text-muted credit">Site développé par Thomas Yalap.</p>
			</div>
		</div>
	</body>
</html>