<?php

namespace Universa\Controller;

use Universa\Form\PersonForm;
use Universa\Service\Person;

use Zend\Mvc\Controller\AbstractActionController,
    Zend\View\Model\ViewModel,
    Doctrine\ORM\EntityManager;

class PersonController extends AbstractActionController
{
    private $person;

    public function __construct($class = null, EntityManager $em)
    {
        $this->person = new Person($em);
    }

    public function indexAction()
    {
        $persons = $this->person->getAll();
        return new ViewModel([
            'persons' => $persons
        ]);
    }

    public function addAction()
    {
        $form = new PersonForm();
        $form->get('submit')->setValue('Salvar');

        if (! $this->getRequest()->isPost()) {
            return ['form' => $form];
        }

        if (!$this->person->salvar(null, $this->getRequest()->getPost())) {
            return ['form' => $this->person->getErro()];
        }

        return $this->redirect()->toRoute(
            'universa/default', 
            [
                'controller' => 'person', 
                'action' => 'index'
            ]
        );
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        if (0 === $id) {
            return $this->redirect()->toRoute(
                'universa/default', 
                [
                    'controller' => 'person', 
                    'action' => 'add'
                ]
            );
        }

        try {
            $person = $this->person->findById($id);
        } catch (\Exception $e) {
            return $this->redirect()->toRoute('universa/default', ['controller' => 'person', 'action' => 'index']);
        }

        if ($this->getRequest()->isPost()) {
            if(!$this->person->salvar($person, $this->getRequest()->getPost())){
                return ['form' => $this->person->getErro()];
            }
            
            return $this->redirect()->toRoute('universa/default', ['controller' => 'person', 'action' => 'index']);
        }

        $form = new PersonForm();
        $form->bind($person);
        
        $form->get('submit')->setAttribute('value', 'Salvar');

        return ['id' => $id, 'form' => $form];
    }

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('universa/default', ['controller' => 'person', 'action' => 'index']);
        }

        $person = $this->person->findById($id);

        $request = $this->getRequest();

        if ($request->isPost()) {
            
            $del = $request->getPost('del', 'Não');
            if ($del == 'Sim') {
                $this->person->delete($id);
            }
            return $this->redirect()->toRoute('universa/default', ['controller' => 'person', 'action' => 'index']);
        }

        
        $form = new PersonForm();
        $form->bind($person);
        return ['id' => $id, 'form' => $form];

        return $this->redirect()->toRoute('universa/default', ['controller' => 'person', 'action' => 'index']);
    }

}
