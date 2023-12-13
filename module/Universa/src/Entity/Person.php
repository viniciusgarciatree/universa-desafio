<?php

namespace Universa\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Person
 * @ORM\Entity
 * @ORM\Table(name="person")
 */
class Person
{
    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     * */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=false)
     */
    private $name;

    public function __construct($data = array())
    {
        (new \Zend\Hydrator\ClassMethods())->hydrate($data, $this);
    }
    /**
     * @return array
     */
    public function toArray()
    {
        return (new \Zend\Hydrator\ClassMethods())->extract($this);
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Person
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Person
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function exchangeArray($data)
    {
        $this->id   = isset($data['id']) ? $data['id'] : null;
        $this->name = isset($data['name']) ? $data['name'] : null;
    }

    public function getArrayCopy()
    {
        return [
            'id'   => $this->id,
            'name' => $this->name,
        ];
    }
}