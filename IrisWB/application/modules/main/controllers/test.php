<?php

namespace modules\main\controllers;

class test extends _main {

    /**
     * Set a layout at controller level
     */
    public function _init() {
        $this->_setLayout('controller');
    }

    public function metadataAction($entityName = 'customers') {
        $this->_setLayout('color');
        $this->__bodyColor = 'BLUE3';
        $em = \models\_invoiceEntity::GetEM();
        $entityName = 'models\\'.$entityName;
        $entity = \Iris\DB\TableEntity::GetEntity($entityName);
        i_d($entity);
    }

    
}
