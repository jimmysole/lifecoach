<?php

namespace Application\Model\Filters;


use Laminas\InputFilter\Factory;
use Laminas\InputFilter;
use Laminas\InputFilter\InputFilterAwareInterface;
use Laminas\InputFilter\InputFilterInterface;
use Laminas\Filter\StripTags;
use Laminas\Filter\StringTrim;
use Laminas\Validator\StringLength;


class Login implements InputFilterAwareInterface
{
    public string $username;
    public string $password;
    public null|int $remember_me;
    
    public InputFilter\InputFilter $input_filter;
    
    
    public function exchangeArray($data)
    {
        $this->username = (!empty($data['username'])) ? $data['username'] : null;
        $this->password = (!empty($data['password'])) ? $data['password'] : null;
        $this->remember_me = (!empty($data['remember_me'])) ? $data['remember_me'] : null;
    }
    
    
    public function setInputFilter(InputFilterInterface $input_filter)
    {
        return;
    }
    
    
    public function getInputFilter(): InputFilter\InputFilter
    {

            $input_filter = new InputFilter\InputFilter();
            $factory      = new Factory();

            $input_filter->add($factory->createInput([
                'name'     => 'username',
                'required' => true,
                'filters'  => [
                    [ 'name' => StripTags::class ],
                    [ 'name' => StringTrim::class ],
                ],

                'validators' => [
                    [
                        'name'    => StringLength::class,
                        'options' => [
                            'encoding' => 'UTF-8',
                            'min'      => 6,
                            'max'      => 30,
                        ],
                    ],
                ],
            ]));


            $input_filter->add($factory->createInput([
                'name'     => 'password',
                'required' => true,
                'filters'  => [
                    [ 'name' => StripTags::class ],
                    [ 'name' => StringTrim::class ],
                ],

                'validators' => [
                    [
                        'name'    => StringLength::class,
                        'options' => [
                            'encoding' => 'UTF-8',
                            'min'      => 6,
                            'max'      => 15,
                        ],
                    ],
                ],
            ]));


            $input_filter->add($factory->createInput([
                'name'      => 'remember_me',
                'required'  => false,
            ]));

            $this->input_filter = $input_filter;


        return $this->input_filter;
    }
}