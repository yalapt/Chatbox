<?php


class AjaxController extends AppController 
{
	/*----- AJAX -----*/

	public function ajaxLoggedIn()
	{
		if($this->Auth->loggedIn())
		{
			$this->autoRender = false;
			$this->layout = 'ajax';
			$date = date('Y-m-d-H-i-s');
			$this->loadModel('User');
			$dateLog = array('id' => AuthComponent::user('id'), 'lastlogin' => $date, 'logged' => '1');
			$this->User->save($dateLog, false);
		}
	}

	public function ajaxLoggedUsers()
	{
		$this->autoRender = false;
		$this->layout = 'ajax';
		$date = date("Y-m-d-H-i-s");
		$dateTimer = date("Y-m-d-H-i-s", strtotime('-5 seconds'));
		
		$this->loadModel('User');
		$listUsers = $this->User->find('all', array(
			'conditions' => array('lastlogin >=' => $dateTimer),
			'order' => array('username ASC')));

		$nbUsers = $this->User->find('count', array(
			'conditions' => array('lastlogin >=' => $dateTimer)));

		if($nbUsers > 1)
		{
			echo '<div>'.$nbUsers.' utilisateurs connectés</div>';
		}
		else
		{
			echo '<div>'.$nbUsers.' utilisateur connecté</div>';
		}

		foreach($listUsers as $value) 
		{
			foreach($value as $user)
			{
				echo '<div class="lien" onclick="callUserInfo(\''.$user['username'].'\');"><p><img src="../img/users_32px.png">'.ucfirst($user['username']).'</p></div>';
			}
		}
		echo '<hr>';
	}

	public function ajaxUserInfo()
	{
		$this->autoRender = false;
		$this->layout = 'ajax';
		$username = $this->request->data['username'];
		$this->loadModel('User');
		$this->loadModel('Message');
		$this->loadModel('Ville');
		$user = $this->User->find('first', array(
			'conditions' => array('username' => $username)));

		echo '<div class="fermer" onclick="fermer(\'userInfo\')">Fermer</div>';

		foreach($user as $userInfo) 
		{
			$nbMessages = $this->Message->find('count', array(
				'conditions' => array('id_sender' => $userInfo['id'])));

			$ville = $this->Ville->find('first', array(
				'conditions' => array('id' => $userInfo['ville'])));

			echo '<h4>'.ucfirst($userInfo['username']).'</h4>';
			echo '<hr>';
			echo '<div>Adresse email : '.$userInfo['email'].'</div>';
			foreach ($ville as $villeInfo) 
			{
				echo '<div>Ville : '.$villeInfo['nom_ville'].' ('.$villeInfo['departement'].')</div>';
			}
			echo '<div>Nombre de message envoyé : '.$nbMessages.'</div>';
			echo '<div class="lien" onclick="callUserHistorique(\''.$userInfo['username'].'\');">Afficher l\'historique</div>';
		}
		echo '<hr>';
	}

	public function ajaxUserHistorique()
	{
		$this->autoRender = false;
		$this->layout = 'ajax';
		$username = $this->request->data['username'];
		$this->loadModel('Message');
		$this->loadModel('User');

		$user = $this->User->find('first', array(
					'conditions' => array('username' => $username)));

		foreach($user as $userInfo) 
		{
			$id = $userInfo['id'];
			$username = $userInfo['username'];
			$useremail = $userInfo['email'];
		}

		$listChatMessages = $this->Message->find('all', array(
			'conditions' => array('id_sender' => $id),
			'order' => array('date DESC')));

		echo '<div class="fermer" onclick="fermer(\'userHistorique\')">Fermer</div>';
		echo '<h4>Historique de '.ucfirst($userInfo['username']).'</h4>';
		echo '<hr>';
		foreach($listChatMessages as $value)
		{
			foreach($value as $message)
			{
				$message['content'] = $message['content'];
				$message['content'] = str_replace('[_#-AND-#_]', '&', $message['content']);
				$message['content'] = str_replace('[_#-PLUS-#_]', '+', $message['content']);

				echo '<div class="italic">Le '.ChatController::formateDate($message['date']).' :</div>';
				echo '<div class="chatMessage">'.$message['content'].'</div>';
				echo '<hr>';
			}
		}
	}

	public function ajaxSigninVilles()
	{
		$this->autoRender = false;
		$this->layout = 'ajax';
		$departement = $this->request->data['departement'];
		$this->loadModel('Ville');
		$listVille = $this->Ville->find('all', array(
			'conditions' => array('departement' => $departement),
			'order' => array('nom_ville ASC')));

		echo '<option value="0" class="text-muted">Ville</option>';

		foreach($listVille as $value) 
		{
			foreach($value as $ville)
			{
				echo '<option value="'.$ville['id'].'">'.$ville['nom_ville'].'</option>';
			}
		}
	}

	public function ajaxSigninName()
	{
		$this->autoRender = false;
		$this->layout = 'ajax';
		$name = htmlspecialchars(strtolower(trim($this->request->data['username'])));
		$this->loadModel('User');
		$username = $this->User->find('count', array(
			'conditions' => array('username' => $name)));

		if($username != '0')
		{
			echo 'indisponible';
		}
		else
		{
			echo 'disponible';
		}
	}

	public function ajaxSigninEmail()
	{
		$this->autoRender = false;
		$this->layout = 'ajax';
		$email = htmlspecialchars(strtolower(trim($this->request->data['email'])));
		$this->loadModel('User');
		$useremail = $this->User->find('count', array(
			'conditions' => array('email' => $email)));

		if($useremail != '0')
		{
			echo 'indisponible';
		}
		else
		{
			echo 'disponible';
		}
	}

	public function ajaxChatMessages()
	{
		$this->autoRender = false;
		$message = nl2br($this->request->data['textChat']);
		$date = date("Y-m-d-H-i-s");
		$data = array('id_sender' => AuthComponent::user('id'), 'content' => $message, 'date' => $date);
		$this->loadModel('Message');
		$verif = str_replace(' ','',$message);
		if($verif != '')
		{
			$this->Message->save($data, false);
		}

		$this->ajaxListChatMessages();
	}

	public function ajaxListChatMessages()
	{
		$this->autoRender = false;
		$this->layout = 'ajax';
		$this->loadModel('Message');
		$this->loadModel('User');
		$listChatMessages = $this->Message->find('all', array(
			'limit' => 5,
			'order' => array('date DESC')));

		foreach($listChatMessages as $value) 
		{
			foreach($value as $message)
			{
				$user = $this->User->find('first', array(
					'conditions' => array('id' => $message['id_sender'])));

				foreach($user as $userInfo) 
				{
					$username = $userInfo['username'];
					$useremail = $userInfo['email'];
				}

				$message['content'] = $message['content'];
				$message['content'] = str_replace('[_#-AND-#_]', '&', $message['content']);
				$message['content'] = str_replace('[_#-PLUS-#_]', '+', $message['content']);

				echo '<div class="lien" onclick="callUserInfo(\''.$userInfo['username'].'\');"><img src="../img/users_32px.png">'.ucfirst($userInfo['username']).'</div>';
				echo '<div class="italic">Le '.ChatController::formateDate($message['date']).' :</div>';
				echo '<div class="chatMessage">'.$message['content'].'</div>';
				echo '<hr>';
			}
		}
	}
}
