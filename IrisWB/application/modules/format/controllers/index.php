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

    private $_entity;

    public function _init(){
        $this->_entity = \Iris\DB\_EntityManager::EMFactory('sqlite:application/config/base/textformat.sqlite');
    }
    
    /**
     * In this simple demo, we use the standard Markdown mechanism
     * 
     */
    public function indexAction() {
        $eData = \models\TData::GetEntity($this->_entity);
        $data = $eData->find('1');
        $this->__html = \Vendors\Markdown\MarkdownExtra::defaultTransform($data->Content);
       
    }
    
    /**
     * Now we begin to use a few extensions found in FormatText class
     */
    public function ownerAction(){
        $eData = \models\TData::GetEntity($this->_entity);
        $data = $eData->find('2');
        $object = new \Iris\TextFormat\Object($data);
        $format = new Format();
        $this->__html = $format->convert($data->Content, $object);
    }

}
