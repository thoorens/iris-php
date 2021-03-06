<?php
// VERSION MARS
namespace models\crud;


/**
 * Project : srv_IrisWB
 * Created for IRIS-PHP 1.0 RC2
 *
 * CRUD operations for Customers generated by 
 * iris.php -- entitygenerate
 */
class Customers extends _localCrud {
    
    
    
    
    public function __construct($param = NULL) {
        static::$_EntityManager = \models\TInvoices::GetEM();
        parent::__construct($param);
        // where to go after CRUD
        $this->setEndUrl('customers/manage');
        // where to in case of error
        $this->setErrorURL('error');
    }

    protected function _makeDefaultForm() {
        print "Customers form";
        $ff = \Dojo\Forms\FormFactory::GetFormFactory();
        $form = $ff->createForm('client');
        $ff->createHidden('id')
                ->addTo($form);
        $ff->createText('Name')
                ->setLabel('Nom du client:')
                ->addTo($form);
        $ff->createText('Address')
                ->setLabel('Adresse:')
                ->addTo($form);
        $ff->createText('Email')
                ->setLabel('Adresse éléctronique:')
                ->addTo($form);
        $ff->createSubmit('Submit')
                ->setValue("Valider")
                ->addTo($form);
        $this->_form = $form;
        return $this->_form;
    }
    

    
}
