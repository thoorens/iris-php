<?php

namespace modules\format\controllers;

/**
 * Project : srv_www_IrisWB
 * Created for IRIS-PHP 1.0 RC2
 * Description of index
 * 
 * @author 
 * @license 
 */
class index extends _format {

    const FILENAME = 'application/config/base/textformat.sqlite';
    
    private $_entityManager;

    public function _init() {
        $options['fileName'] = 'application/config/base/textformat.sqlite';
        $this->_entityManager = \Iris\DB\_EntityManager::EMByNumber(-1,$options);
    }

    /**
     * In this simple demo, we use the standard Markdown mechanism
     * 
     */
    public function indexAction() {
        $EM = \Iris\DB\_EntityManager::EMFactory('sqlite:application/config/base/textformat.sqlite');
        $eData = \models\TData::GetEntity($EM);
        $this->_getData($EM);
    }
    
    private function _getData($EM){
        $eData = \models\TData::GetEntity($EM);
        $data = $eData->find('1');
        $this->__html = \Vendors\Markdown\MarkdownExtra::defaultTransform($data->Content);
    }
    

    /**
     * Now we begin to use a few extensions found in FormatText class
     */
    public function ownerAction() {
        $EM = 
        $eData = \models\TData::GetEntity($this->_entityManager);
        $data = $eData->find('2');
        $object = new \Iris\TextFormat\Object($data);
        $format = new Format();
        $this->__html = $format->convert($data->Content, $object);
    }

}
