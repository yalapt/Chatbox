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
	<body>
		<div id="container">
			<div class="container">
				<div id="generalAlert" style="display: none;"></div>
			<?php
				echo $this->Session->flash();
				echo $this->fetch('content');
			?>
			</div>
		</div>
	</body>
</html>