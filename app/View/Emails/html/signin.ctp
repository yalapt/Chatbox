<p>
	Bonjour, <?php echo $username; ?>
</p>
<p>
	Pour activer votre compte cliquez ici : <?php echo $this->html->link('Activer votre compte', $this->html->url($link, true)); ?>
</p>