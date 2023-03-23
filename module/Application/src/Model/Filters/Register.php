<?php

namespace Application\Model\Filters;



use Laminas\InputFilter\InputFilter;
use Laminas\InputFilter\InputFilterAwareInterface;
use Laminas\InputFilter\InputFilterInterface;

use Laminas\Filter\StripTags;
use Laminas\Filter\StringTrim;
use Laminas\Validator\StringLength;

use Laminas\Validator\EmailAddress;


class Register implements InputFilterAwareInterface
{
 
    public $username;
    public $password;
    public $email_address;
    public $first_name;
    public $last_name;
    public $address;
    public $city;
    public $state;
    public $zipcode;
    public $country;
    
    protected $input_filter;
    
    
    public function exchangeArray($data)
    {
        $this->username      = (!empty($data['username']))      ? $data['username']      : null;
        $this->password      = (!empty($data['password']))      ? $data['password']      : null;
        $this->email_address = (!empty($data['email_address'])) ? $data['email_address'] : null;
        $this->first_name    = (!empty($data['first_name']))    ? $data['first_name']    : null;
        $this->last_name     = (!empty($data['last_name']))     ? $data['last_name']     : null;
        $this->address       = (!empty($data['address']))       ? $data['address']       : null;
        $this->city          = (!empty($data['city']))          ? $data['city']          : null;
        $this->state         = (!empty($data['state']))         ? $data['state']         : null;
        $this->zipcode       = (!empty($data['zipcode']))       ? $data['zipcode']       : null;
        $this->country       = (!empty($data['country']))       ? $data['country']       : null;
    }
    
    
    public function setInputFilter(InputFilterInterface $input_filter)
    {
        return;
    }
    
    
    public function getInputFilter()
    {
        if (!$this->input_filter) {
            $input_filter = new InputFilter();
            
            $input_filter->add([
                'name'      => 'username',
                'required'  => true,
                'filters'   => [
                    [ 'name' => StripTags::class ],
                    [ 'name' => StringTrim::class ],
                ],
                
                'validators' => [
                    [
                        'name' => StringLength::class,
                        'options' => [
                            'encoding' => 'UTF-8',
                            'min' => 6,
                            'max' => 30,
                        ],
                    ],
                ],
            ]);
            
            
            $input_filter->add([
                'name'      => 'password',
                'required'  => true,
                'filters'   => [
                    [ 'name' => StripTags::class ],
                    [ 'name' => StringTrim::class ],
                ],
                
                'validators' => [
                    [
                        'name' => StringLength::class,
                        'options' => [
                            'encoding' => 'UTF-8',
                            'min' => 6,
                            'max' => 15,
                        ],
                    ],
                ],
            ]);
            
            
            $input_filter->add([
                'name'      => 'email_address',
                'required'  => true,
                'filters'   => [
                    [ 'name' => StripTags::class ],
                    [ 'name' => StringTrim::class ],
                ],
                
                'validators' => [
                    [
                        'name'    => StringLength::class,
                        'options' => [
                            'encoding' => 'UTF-8',
                            'min'      => 10,
                            'max'      => 75,
                        ],
                    ],
                    
                    
                    [
                        'name'    => EmailAddress::class,
                        'options' => [
                            'domain'   => true,
                            'hostname' => true,
                            'mx'       => true,
                            'deep'     => true,
                            'message'  => 'Invalid Email Address',
                        ],
                    ],
                ],
            ]);
            
            
            $input_filter->add([
                'name'      => 'first_name',
                'required'  => true,
                'filters'   => [
                    [ 'name' => StripTags::class ],
                    [ 'name' => StringTrim::class ],
                ],
                
                'validators' => [
                    [
                        'name' => StringLength::class,
                        'options' => [
                            'encoding' => 'UTF-8',
                            'min' => 2,
                            'max' => 10,
                        ],
                    ],
                ],
            ]);
            
            
            $input_filter->add([
                'name'      => 'last_name',
                'required'  => true,
                'filters'   => [
                    [ 'name' => StripTags::class ],
                    [ 'name' => StringTrim::class ],
                ],
                
                'validators' => [
                    [
                        'name' => StringLength::class,
                        'options' => [
                            'encoding' => 'UTF-8',
                            'min' => 3,
                            'max' => 30,
                        ],
                    ],
                ],
            ]);
            
            $input_filter->add([
                'name'      => 'password',
                'required'  => true,
                'filters'   => [
                    [ 'name' => StripTags::class ],
	                [ 'name' => StringTrim::class ],
                ],
                
                'validators' => [
                    [
                        'name' => StringLength::class,
                        'options' => [
                            'encoding' => 'UTF-8',
                            'min' => 6,
                            'max' => 500,
                        ],
                    ],
                ],
            ]);
            
            $input_filter->add([
                'name'      => 'city',
                'required'  => true,
                'filters'   => [
                    [ 'name' => StripTags::class ],
                ],
                
                'validators' => [
                    [
                        'name' => StringLength::class,
                        'options' => [
                            'encoding' => 'UTF-8',
                            'min' => 5,
                            'max' => 100,
                        ],
                    ],
                ],
            ]);
            
            $input_filter->add([
                'name'      => 'state',
                'required'  => true,
                'filters'   => [
                    [ 'name' => StripTags::class ],
                    [ 'name' => StringTrim::class ],
                ],
                
                'validators' => [
                    [
                        'name' => StringLength::class,
                        'options' => [
                            'encoding' => 'UTF-8',
                            'min' => 2,
                            'max' => 50,
                        ],
                    ],
                ],
            ]);
            
            $input_filter->add([
                'name'      => 'zipcode',
                'required'  => true,
                'filters'   => [
                    [ 'name' => StripTags::class ],
                    [ 'name' => StringTrim::class ],
                ],
                
                'validators' => [
                    [
                        'name' => StringLength::class,
                        'options' => [
                            'encoding' => 'UTF-8',
                            'min' => 5,
                            'max' => 5,
                        ],
                    ],
                ],
            ]);
            
            $input_filter->add([
                'name'      => 'country',
                'required'  => true,
                'filters'   => [
                    [ 'name' => StripTags::class ],
                ],
                
                'validators' => [
                    [
                        'name' => StringLength::class,
                        'options' => [
                            'encoding' => 'UTF-8',
                            'min' => 6,
                            'max' => 75,
                        ],
                    ],
                ],
            ]);
            
            
            $this->input_filter = $input_filter;
        }
        
        return $this->input_filter;
    }
}