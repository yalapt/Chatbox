<form class="form-login-signin" method="post" name="signinForm" onsubmit="return signinValidation();">
	<h2 class="form-login-signin-heading">Inscription</h2>
	<input type="text" id="username" class="form-control" name="username" placeholder="Nom d'utilisateur" onkeyup="call(readName, 'username', 'ajaxSigninName');" onchange="call(readName, 'username', 'ajaxSigninName');" autofocus>
	<span id="usernameAlert"></span>
	<input type="email" id="email" class="form-control" name="email" placeholder="Adresse email" onkeyup="call(readEmail, 'email', 'ajaxSigninEmail');" onchange="call(readEmail, 'email', 'ajaxSigninEmail');">
	<span id="emailAlert"></span>
	<input type="password" id="password" class="form-control" name="password" placeholder="Mot de passe">
	<select id="departement" class="form-control" onchange="call(readVilles, 'departement', 'ajaxSigninVilles');">
		<option value="0" class="text-muted">Département</option>
		<?php
			foreach($listDepartement as $value):
		?>
		<option value="<?php echo $value['departement']; ?>"><?php echo $value['departement']; ?></option>
		<?php
			endforeach;
		?>
	</select>
	<select name ="ville" id="showVilles" class="form-control">
			<option value="0" class="text-muted">Ville</option>
	</select>
	<br />
	<button class="btn btn-lg btn-primary btn-block" type="submit">Inscription</button>
	<br />
	<div>
	    <a href="/user/login">Déjà membre ?</a>
	</div>
</form>