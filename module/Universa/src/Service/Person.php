<?php

namespace Universa\Service;

use Doctrine\ORM\EntityManager;
use DomainException;
use Universa\Entity\Person as EntityPerson;
use Universa\Form\PersonForm;
use Universa\Form\PersonRequest;

class Person
{
    /**
     * Gerenciador de entidade do Doctrine
     * @var EntityManager
     */
    private $em;

    /**
     * Nome da entidade que será usada para instanciar objetos
     * @var EntityManager
     */
    private $entity;

    private $error;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->entity = 'Universa\\Entity\\Person';
    }

    private function validate($person, $data)
    {
        
        $personRequest = new PersonRequest();
        $form = new PersonForm();
        $form->setInputFilter($personRequest->getInputFilter());
        $form->setData($data);

        if (! $form->isValid()) {
            $this->error = $form;
            return false;
        }
        return true;
    }

    public function getErro()
    {
        return $this->error;
    }

    public function getAll()
    {
        return $this->em->getRepository($this->entity)->findAll();
    }

    public function findById($id)
    {
        return $this->em->getRepository($this->entity)->find($id);
    }

    public function salvar($persons = null, $data = [])
    {
        try{
            if(!$this->validate($persons, $data)){
                
                return false;
            }
            
            $this->em->beginTransaction();
            
            if(!$person){
                $person = new EntityPerson();
            }

            $person->setName($data['name']);

            $this->em->persist($person);
            $this->em->flush($person);
            $this->em->commit();
            
        }catch(\Exception $exception){
            throw new DomainException(sprintf(
                $exception->getMessage(),
                __CLASS__
            ));
            return false;        
        }
        return true;
    }

    public function delete($id)
    {
        $this->em->beginTransaction();   
        $entity = $this->findById($id);
        $this->em->remove($entity);
        $this->em->flush($entity);
        $this->em->commit();
        return true;
    }
}