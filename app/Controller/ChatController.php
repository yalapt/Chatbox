<?php

class ChatController extends AppController
{
	public function index()
	{
		if(!$this->Auth->loggedIn())
		{
			$this->redirect('/user/login');
		}

		$this->show();
	}

	public function show()
	{
		$this->set('title', 'Chat');
		$this->layout = 'template';
		$this->render('/subview/chat');
	}

	static function formateDate($string)
    {
        $tab = explode(' ', $string);
        $dateTab = explode('-', $tab[0]);
        $i = count($dateTab)-1;
        while($i >= 0)
        {
            $newTab[] = $dateTab[$i];
            $i--;
        }
        $date = implode('/', $newTab);
        $formated = $date.' à '.$tab[1];
        return $formated;
    }
}
