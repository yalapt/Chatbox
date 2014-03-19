<form class="form-login-signin" method="post" onsubmit="return loginValidation();">
	<h2 class="form-login-signin-heading">Connexion</h2>
	<input type="text" id="username" class="form-control" name="username" placeholder="Nom d'utilisateur" autofocus>
	<input type="password" id="password" class="form-control" name="password" placeholder="Mot de passe">
	<button class="btn btn-lg btn-primary btn-block" type="submit">Connexion</button>
	<br />
    <div>
        <a href="/user/signin">Pas encore membre ?</a>
    </div>
</form>