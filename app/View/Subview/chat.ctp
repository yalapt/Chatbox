<div id="chat">
	<div class="row">
		<div class="col-md-8 chatbox">
			<h4>Chatbox, raconte ta life !</h4>
			<hr>
			<div id="showChatMessages"></div>
			<div id="chatAlert" style="display: none;"></div>
			<textarea id="textChat" placeholder="Raconte ta life juste ici..."></textarea>​
			<button class="btn btn-lg btn-primary btn-block" onclick="chat();">Envoyer</button>
			<hr>
		</div>
		<div class="col-md-3 listUsers">
			<h4>Liste des utilisateurs connectés</h4>
			<hr>
			<div id="showLoggedUsers"><p class="text-muted">Aucun utilisateur n'est connecté en ce moment.</p></div>
		</div>
		<div id="userInfo" class="col-md-3" style="display: none;"></div>
		<div id="userHistorique" class="col-md-8" style="display: none;"></div>
	</div>
</div>