<?php

namespace modules\helpers\controllers;

/**
 * srv_IrisWB
 * Created for IRIS-PHP 1.0 RC2
 * Description of crudlinks
 * 
 * @author 
 * @license 
 */
class crudlinks extends _helpers {

    /**
     * Show a standard crud icon with two more custom operation
     */
    public function indexAction() {
        // A customer sample (in an array to make it easy)
        $data = [
            'id' => 25,
            'Name' => 'Smith',
        ];
        /* @var $icon \Iris\Subhelpers\CrudIconManager */
        $icon = $this->callViewHelper('crudIcon')
                ->setController('demo')
                ->forceLanguage('english')
                ->setActionName('customer')
                ->setIdField('id')
                ->setDescField('Name')
                ->setEntity('N_customer')
                ->setData($data);
        $icon->setIconDir('/!documents/file/images/iconplus');
        // This new operation could have been placed in \models\crud\CrudIconManager as are action2 and action3
        $icon->insert(new \Iris\Subhelpers\Icon('action1', 'Execute action 1 on %O','%I'));
        $operations = ['create', 'update', 'delete', 'read', 'upload', 'create2',
            'first', 'previous', 'next', 'last', 'action1', 'action2', 'action3', 'action4'];
        $this->__operations = $operations;
        $this->__icon = $icon;
        //iris_debug($icon->makeReference('create', \TRUE));
    }

    /**
     * Show a standard crud icon with two more custom operation in French
     */
    public function frenchAction() {
        // A customer sample (in an array to make it easy)
        $data = [
            'id' => 25,
            'Name' => 'Dupont',
        ];
        /* @var $icon \Iris\Subhelpers\CrudIconManager */
        $icon = $this->callViewHelper('crudIcon')
                ->setController('demo')
                ->forceLanguage('french')
                ->setActionName('customer')
                ->setIdField('id')
                ->setDescField('Name')
                ->setEntity('M_client')
                ->setData($data);
        $icon->setIconDir('/!documents/file/images/iconplus');
        $icon->insert(new \Iris\Subhelpers\Icon('action1', "Exécuter l'action 1 sur %O",'%I'));
        $operations = ['create', 'update', 'delete', 'read', 'upload', 'create2',
            'first', 'previous', 'next', 'last', 'action1', 'action2','action3', 'action4'];
        $this->__operations = $operations;
        $this->__icon = $icon;
        $this->setViewScriptName('index');
    }

    /**
     * Show a standard crud icon with two more custom operation
     * with 
     */
    public function complexAction() {
        // A customer sample (in an array to make it easy)
        $data = [
            'id1' => 12,
            'id2' => 'B',
            'Name' => 'Smith',
        ];
        /* @var $icon \Iris\Subhelpers\CrudIconManager */
        $icon = $this->callViewHelper('crudIcon')
                ->setController('demo')
                ->forceLanguage('english')
                ->setActionName('customer')
                ->setIdField(['id1', 'id2'])
                ->setDescField('Name')
                ->setEntity('N_customer')
                ->setData($data);
        $icon->setIconDir('/!documents/file/images/iconplus');
        $icon->insert(new \Iris\Subhelpers\Icon('action1', 'Execute action 1 on %O','%I'));
        $operations = ['create', 'update', 'delete', 'read', 'upload', 'create2',
            'first', 'previous', 'next', 'last', 'action1', 'action2','action3', 'action4'];
        $this->__operations = $operations;
        $this->__icon = $icon;
        $this->setViewScriptName('index');
    }
    
}
