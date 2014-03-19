<form class="form-compte" method="post" name="compteForm" onsubmit="return compteValidation();">
	<h2 class="form-compte-heading"><?php echo ucfirst(AuthComponent::user('username')); ?></h2>
	<input type="email" id="email" class="form-control modifEmail" name="email" placeholder="Adresse email" value="<?php echo AuthComponent::user('email'); ?>" onkeyup="call(readEmail, 'email', 'ajaxSigninEmail');" onchange="call(readEmail, 'email', 'ajaxSigninEmail');" autofocus>
	<input type="hidden" id="userEmail" value="<?php echo AuthComponent::user('email'); ?>">
	<span id="emailAlert"></span>
	<input type="password" id="password" class="form-control modifPass" name="password" placeholder="Nouveau mot de passe (Optionnel)">
	<input type="password" id="confirmPassword" class="form-control modifConfirmPass" name="confirmPassword" placeholder="Ancien mot de passe (Obligatoire)">
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
	<button class="btn btn-lg btn-info btn-block" type="submit" onclick="return confirmModifierCompte()">Modifier mes informations</button>
	<a class="btn btn-lg btn-danger btn-block" href="../user/delete" onclick="return confirmSupprimerCompte()">Supprimer mon compte</a>
</form>