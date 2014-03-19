<div id="alert" class="alert alert-danger">
	<?php foreach ($message as $error): ?>
		<?php foreach ($error as $value): ?>
		<p><?php echo $value; ?></p>
		<?php endforeach; ?>
	<?php endforeach; ?>
</div>
<script>$("#alert").delay(5000).fadeOut( 1000 );</script>