<?php

namespace modules\subhelpers\controllers;

use \Iris\Time\Date;

/**
 * 
 * Created for IRIS-PHP 0.9 - beta
 * Description of index
 * 
 * @author jacques
 * @license not defined
 */
class index extends _subhelpers {

    public function indexAction() {
        // these parameters are only for demonstration purpose
        $this->__(NULL, array(
            'Title' => '<h1>subhelpers - index - index</h1> ',
            'buttons' => 1 + 4,
            'logoName' => 'mainLogo'));
    }

    public function monthDemoAction($type = \NULL, $offset = 0) {
        $date = \Iris\Time\_Schedule::ParameterAnalysis($type = \NULL, $offset = 0);
        $monthManager = $this->callViewHelper('monthDemo');
        $monthManager->init($date, 1);
        $i = 0;

        foreach ($this->_generateEvents('m') as $event) {
            $monthManager->put($event->getDate(), $event);
        }
        $monthManager->setLinkToDay('/subhelpers/index/monthDemo/day/');
        $this->__month = $monthManager;
    }

    public function weekDemoAction($type = \NULL, $offset = 0) {
        $date = \Iris\Time\_Schedule::ParameterAnalysis($type = \NULL, $offset = 0);
        $weekManager = $this->callViewHelper('weekDemo');
        $weekManager->init($date, 1);
        $i = 0;

        foreach ($this->_generateEvents('w') as $event) {
            $weekManager->put($event->getDate(), $event);
        }
        $weekManager->setLinkToDay('/subhelpers/index/weekDemo/day/');
        $this->__week = $weekManager;
    }

    /**
     * 
     * @return array(Event)
     */
    private function _generateEvents($option) {
        $events[] = new EventTest(1, 2, '2012-10-01', 'Write a demo for Month');
        $events[] = new EventTest(2, 2, '2012-10-01', 'Write a demo for Week');
        $events[] = new EventTest(3, 4, '2012-10-02', 'Call the doctor');
        $events[] = new EventTest(4, 2, '2012-10-04', 'Write a new doc for Iris');

        if ($option == 'w') {
            $events[] = new EventTest(11, 1, '2012-10-06', 'Buy new pants');
            $events[] = new EventTest(12, 1, '2012-10-06', 'Test Week');
            $events[] = new EventTest(13, 1, '2012-10-06', "Buy flowers for my wife's birthday");
            $events[] = new EventTest(14, 2, '2012-10-06', 'Try to invent a new task');
        }
        else { //option m
            $events[] = new EventTest(5, 2, '2012-10-11', 'Rewrite Loader class');
            $events[] = new EventTest(6, 3, '2012-10-12', 'Go to the movies');
            $events[] = new EventTest(7, 2, '2012-10-13', 'Test this s...');
            $events[] = new EventTest(8, 4, '2012-10-14', 'Go swimming');
            $events[] = new EventTest(9, 3, '2012-10-17', 'Write a poem');
            $events[] = new EventTest(10, 1, '2012-10-18', 'Call mother');
            $events[] = new EventTest(11, 1, '2012-10-20', 'Buy new pants');
            $events[] = new EventTest(12, 1, '2012-10-22', 'Test Week');
            $events[] = new EventTest(13, 1, '2012-10-24', "Buy flowers for my wife's birthday");
            $events[] = new EventTest(14, 2, '2012-10-26', 'Try to invent a new task');
        }
        return $events;
    }

}
