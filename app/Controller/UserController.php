<?php


class UserController extends AppController 
{
	public function index()
	{
		$this->compte();
	}

	private function listDepartement()
	{
		$this->loadModel('Ville');
		$departement = $this->Ville->find('all', array('fields' => 'DISTINCT departement'));
		
		foreach ($departement as $value) 
		{
			foreach ($value as $dep) 
			{
				$listDepartement[] = $dep;
			}
		}

		$this->set('listDepartement', $listDepartement);
	}

	public function compte()
	{
		if(!$this->Auth->loggedIn())
		{
			$this->redirect('/user/login');
		}

		if($this->request->is('post'))
		{
			$this->request->data['email'] = htmlspecialchars(strtolower(trim($this->request->data['email'])));
			$password = Security::hash(htmlspecialchars(trim($this->request->data['confirmPassword'])), 'sha1', true);

			if(!empty($this->request->data['confirmPassword']))
			{
				if($password == AuthComponent::user('password'))
				{
					if($this->request->data['email'] == AuthComponent::user('email'))
					{
						unset($this->User->validate['email']);
						if(!empty($this->request->data['password']))
						{
							$this->request->data['password'] = Security::hash(htmlspecialchars(trim($this->request->data['password'])), 'sha1', true);
							$data = array('id' => AuthComponent::user('id'), 'password' => $this->request->data['password']);
						}
						else
						{
							unset($this->User->validate['password']);
						}
					}
					else
					{
						if(!empty($this->request->data['password']))
						{
							$this->request->data['password'] = Security::hash(htmlspecialchars(trim($this->request->data['password'])), 'sha1', true);
							$data = array('id' => AuthComponent::user('id'), 'email' => $this->request->data['email'], 'password' => $this->request->data['password']);
						}
						else
						{
							$data = array('id' => AuthComponent::user('id'), 'email' => $this->request->data['email']);
							unset($this->User->validate['password']);
						}
					}

					unset($this->User->validate['username']);
					unset($this->User->validate['confirmPassword']);

					if(isset($data))
					{
						$this->User->set($data);
					}

					if($this->request->data['ville'] != '0' and $this->request->data['ville'] != AuthComponent::user('ville'))
					{
						$ville = array('id' => AuthComponent::user('id'), 'ville' => $this->request->data['ville']);
						if($this->User->save($ville, false))
						{
							$this->Session->setFlash('Des modifications ont été apportées.', 'success');
						}
					}
					else
					{
						$this->Session->setFlash('Aucune modifications apportées.', 'error');
					}

					if($this->User->validates()) 
					{
						if(isset($data))
						{
							if($this->User->save($data, false))
							{
								$this->Session->setFlash('Des modifications ont été apportées.', 'success');
							}
						}
					}
					else
					{
						$errors = $this->User->validationErrors;
					    $this->Session->setFlash($errors, 'tab_error');
					}

					if(!empty($this->request->data['password']))
					{
						$user = $this->User->find('first', array(
						'conditions' => array('username' => AuthComponent::user('username'), 'password' => $this->request->data['password'])));
					}
					else
					{
						$user = $this->User->find('first', array(
						'conditions' => array('username' => AuthComponent::user('username'), 'password' => AuthComponent::user('password'))));
					}

					$this->Auth->login($user['User']);
					$this->redirect('/user');
				}
				else
				{
					$this->Session->setFlash('Ancien mot de passe incorrect.', 'error');
				}
			}
			else
			{
				$this->Session->setFlash('Veuillez entrer votre ancien mot de passe pour appliquer les modifications.', 'error');
			}
        }
  	
        $this->set('title', 'Mon compte');
        $this->listDepartement();
		$this->layout = 'template';
		$this->render('/subview/compte');
	}

    public function delete()
    {   
    	if(!$this->Auth->loggedIn())
		{
			$this->redirect('/user/login');
		}

        App::uses('Folder', 'Utility');
		App::uses('File', 'Utility');
					
		$dir = new Folder(WWW_ROOT . 'files' . DS . AuthComponent::user('username'));
        $dir->delete();
        $this->User->delete(AuthComponent::user('id'));
		$this->redirect('/user/logout');
    }

	public function signin()
	{
		if($this->Auth->loggedIn())
		{
			$this->redirect('/');
		}

		if($this->request->is('post'))
		{
			$this->request->data['username'] = htmlspecialchars(strtolower(trim($this->request->data['username'])));
			$this->request->data['email'] = htmlspecialchars(strtolower(trim($this->request->data['email'])));

			$this->User->set($this->request->data);

			if($this->User->validates()) 
			{
				if($this->request->data['ville'] != '0')
				{
					$this->request->data['password'] = Security::hash(htmlspecialchars(trim($this->request->data['password'])), 'sha1', true);

				 	if($this->User->save($this->request->data, false, array('username', 'email', 'password', 'ville')))
					{
						App::uses('Folder', 'Utility');
						App::uses('File', 'Utility');
						
						$dir = new Folder(WWW_ROOT . 'files' . DS . $this->request->data['username'], true);

						$user = $this->User->find('first', array(
							'conditions' => array('username' => $this->request->data['username'], 'password' => $this->request->data['password'])));

			        	$this->Auth->login($user['User']);
			            $this->redirect('/');
					}
					else
					{
						$this->Session->setFlash('L\'inscription à échoué.', 'error');
					}
				}
				else
				{
					$this->Session->setFlash('Vous devez choisir une ville.', 'error');
				}
			} 
			else 
			{
			    $errors = $this->User->validationErrors;
			    $this->Session->setFlash($errors, 'tab_error');
			}
		}

		$this->set('title', 'Inscription');
		$this->listDepartement();
		$this->layout = 'login_signin_default';
		$this->render('/subview/signin');
	}

	public function login()
	{
		if($this->Auth->loggedIn())
		{
			$this->redirect('/');
		}

		if($this->request->is('post')) 
		{
			$this->request->data['username'] = htmlspecialchars(strtolower(trim($this->request->data['username'])));
			$this->request->data['password'] = Security::hash(htmlspecialchars(trim($this->request->data['password'])), 'sha1', true);

			$user = $this->User->find('first', array(
				'conditions' => array('username' => $this->request->data['username'], 'password' => $this->request->data['password'])));

        	if(!empty($user))
        	{
        		$this->Auth->login($user['User']);
        		$Log = array('id' => AuthComponent::user('id'), 'logged' => '1');
				$this->User->save($Log, false);
            	$this->redirect('/');
        	} 
        	else 
        	{
            	$this->Session->setFlash('Nom d\'utilisateur ou mot de passe invalide.', 'error');
        	}
    	}

		$this->set('title', 'Connexion');
		$this->layout = 'login_signin_default';
		$this->render('/subview/login');
	}

	public function logout()
	{
		if($this->Auth->loggedIn())
		{
			$date = date('Y-m-d-H-i-s');
			$dateLog = array('id' => AuthComponent::user('id'), 'lastlogin' => $date, 'logged' => '0');
			$this->User->save($dateLog, false);
			$this->Auth->logout();
		}
		$this->redirect('/user/login');
	}
}