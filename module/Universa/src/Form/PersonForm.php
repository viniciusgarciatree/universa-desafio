<?php

namespace Universa\Form;

use Zend\Form\Form;
use Zend\Form\Element\Link;

class PersonForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('person');

        $this->add([
            'name' => 'id',
            'type' => 'hidden',
        ]);

        $this->add([
            'name' => 'name',
            'type' => 'text',
            'options' => [
                'label' => 'Nome',
            ],
        ]);

        $this->add([
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => [
                'value' => 'Salvar',
                'id'    => 'submitbutton',
            ],
        ]);
    }
}