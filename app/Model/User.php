<?php

App::uses('AppModel', 'Model');

class User extends AppModel 
{
    public $validate = array(
        'username' => array(
            array(
                'rule'=>array('custom','/^[a-z\d_-]{3,25}$/i'),
                'rule'=>array('minLength',3),
                'rule'=>array('maxLength',25),
                'required' => true,
                'allowEmpty' => false,
                'message' => 'Nom d\'utilisateur invalide.'
            ),
            array(
                'rule' => 'isUnique',
                'message' => 'Nom d\'utilisateur déjà utilisé.'
            )
        ),
        'email' => array(
            array(
                'rule' => 'email',
                'rule'=>array('minLength',3),
                'rule'=>array('maxLength',50),
                'required' => true,
                'allowEmpty' => false,
                'message' => 'Email invalide.'
            ),
            array(
                'rule' => 'isUnique',
                'message' => 'Email déjà utilisé.'
            )
        ),
        'password' => array(
            array(
                'rule'=>array('minLength',3),
                'rule'=>array('maxLength',50),
                'rule' => 'notEmpty',
                'allowEmpty' => false,
                'message' => 'Vous devez entrer un mot de passe.'
            )
        )
    );
}