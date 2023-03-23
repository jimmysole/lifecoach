<?php

namespace Application\Form;


use Laminas\Form\Form;
use Laminas\Form\Element;


class RegisterForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('kblifecoach_register');
        
        // set the attributes for the form
        $this->setAttribute('method', 'post')
        ->setAttribute('data-role', 'form')
        ->setAttribute('autocomplete', false)
        ->setAttribute('class', 'w3-container');
        
        
        // make the form elements
        $this->add(array(
            'name' => 'username',
            'type' => Element\Text::class,
            'options' => array(
                'label' => 'Username',
                'label_attributes' => array(
                    'class' => 'w3-label w3-left',   
                ),
            ),
            
            'attributes' => array(
                'id'     => 'username',
                'class'  => 'w3-input w3-border w3-round-large',
            ),
        ));
        
        
        $this->add(array(
            'name' => 'password',
            'type' => Element\Password::class,
            'options' => array(
                'label' => 'Password',
                'label_attributes' => array(
                    'class' => 'w3-label w3-left',
                ),
            ),
            
            'attributes' => array(
                'id'     => 'password',
                'class'  => 'w3-input w3-border w3-round-large',
            ),
        ));
        
        
        $this->add(array(
            'name' => 'email_address',
            'type' => Element\Text::class,
            'options' => array(
                'label' => 'Email Address',
                'label_attributes' => array(
                    'class' => 'w3-label w3-left',
                ),
            ),
            
            'attributes' => array(
                'id'     => 'email-address',
                'class'  => 'w3-input w3-border w3-round-large',
            ),
        ));
        
        
        $this->add(array(
            'name' => 'first_name',
            'type' => Element\Text::class,
            'options' => array(
                'label' => 'First Name',
                'label_attributes' => array(
                    'class' => 'w3-label w3-left',
                ),
            ),
            
            'attributes' => array(
                'id'     => 'first-name',
                'class'  => 'w3-input w3-border w3-round-large',
            ),
        ));
        
        
        $this->add(array(
            'name' => 'last_name',
            'type' => Element\Text::class,
            'options' => array(
                'label' => 'Last Name',
                'label_attributes' => array(
                    'class' => 'w3-label w3-left',
                ),
            ),
            
            'attributes' => array(
                'id'     => 'last-name',
                'class'  => 'w3-input w3-border w3-round-large',
            ),
        ));
        
        
        $this->add(array(
            'name' => 'address',
            'type' => Element\Text::class,
            'options' => array(
                'label' => 'Address',
                'label_attributes' => array(
                    'class' => 'w3-label w3-left',
                ),
            ),
            
            'attributes' => array(
                'id'     => 'address',
                'class'  => 'w3-input w3-border w3-round-large',
            ),
        ));
        
        
        $this->add(array(
            'name' => 'city',
            'type' => Element\Text::class,
            'options' => array(
                'label' => 'City',
                'label_attributes' => array(
                    'class' => 'w3-label w3-left',
                ),
            ),
            
            'attributes' => array(
                'id'     => 'city',
                'class'  => 'w3-input w3-border w3-round-large',
            ),
        ));
        
        
        $this->add(array(
            'name' => 'state',
            'type' => Element\Text::class,
            'options' => array(
                'label' => 'State',
                'label_attributes' => array(
                    'class' => 'w3-label w3-left',
                ),
            ),
            
            'attributes' => array(
                'id'        => 'state',
                'class'     => 'w3-input w3-border w3-round-large',
            ),
        ));
        
       
        $this->add(array(
            'name' => 'zipcode',
            'type' => Element\Text::class,
            'options' => array(
                'label' => 'Zipcode',
                'label_attributes' => array(
                    'class' => 'w3-label w3-left',
                ),
            ),
            
            'attributes' => array(
                'id'     => 'zipcode',
                'class'  => 'w3-input w3-border w3-round-large',
            ),
        ));
        
        
        $this->add(array(
            'name' => 'country',
            'type' => Element\Text::class,
            'options' => array(
                'label' => 'Country',
                'label_attributes' => array(
                    'class' => 'w3-label w3-left',
                ),
            ),
            
            'attributes' => array(
                'id'     => 'country',
                'class'  => 'w3-input w3-border w3-round-large',
            ),
        ));
        
        
        $this->add(new Element\Csrf('csrf_security'));
        
        
        
        $this->add(array(
            'name' => 'submit',
            'type' => Element\Submit::class,
            
            'attributes' => array(
                'id'    => 'submit',
                'class' => 'w3-btn w3-white w3-border w3-border-blue w3-round w3-left',
                'value' => 'Register',
            ),
        ));
    }
}