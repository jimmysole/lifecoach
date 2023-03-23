<?php

namespace Application\Form;


use Laminas\Form\Form;
use Laminas\Form\Element\Csrf;


class LoginForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('kb-login');
        
        $this->setAttribute('method', 'post')
        ->setAttribute('data-role', 'form')
        ->setAttribute('autocomplete', false)
        ->setAttribute('class', 'w3-container');
        
        
        $this->add(array(
            'name' => 'username',
            'type' => 'text',
            'options' => array(
                'label' => 'Username',
                'label_attributes' => array(
                    'class' => 'w3-label w3-left',
                ),
            ),
            
            'attributes' => array(
                'id'    => 'username',
                'class' => 'w3-input',
            ),
        ));
        
        
        $this->add(array(
            'name' => 'password',
            'type' => 'password',
            'options' => array(
                'label' => 'Password',
                'label_attributes' => array(
                    'class' => 'w3-label w3-left',
                ),
            ),
            
            'attributes' => array(
                'id'           => 'password',
                'class'        => 'w3-input',
            ),
        ));
        
        $this->add(array(
            'name'    => 'remember_me',
            'type'    => 'checkbox',
            'options' => array(
                'label' => 'Remember Me',
                'label_attributes' => array(
                    'class' => 'w3-label w3-left w3-validate',
                ),
            ),
            
            'attributes' => array(
                'id'    => 'remember_me',
                'class' => 'w3-check w3-left',
                'style' => 'margin-left: 10px; margin-top: -6px;',
            ),
        ));
        
        $this->add(new Csrf('csrf_security'));
        
        
        $this->add(array(
            'name' => 'submit',
            'type' => 'submit',
            
            'attributes' => array(
                'id'    => 'submit',
                'class' => 'w3-btn w3-white w3-border w3-border-blue w3-round w3-left',
                'type'  => 'submit',
                'value' => 'Login',
            ),
        ));
    }
}