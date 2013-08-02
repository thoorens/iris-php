<?php

switch (0) {
    // Entity tests
    case 1:
        \Iris\DB\_EntityManager::SetInstance(
                \Iris\DB\_EntityManager::EMFactory('sqlite:/application/config/base/invoice.sqlite'));
        echo "<h4>Beginning of entity tests</h4>";
        defined('RT') or define('RT', "<br>");
        for ($level = 1; $level < 4; $level++) {
            \Iris\DB\_Entity::SetDebugLevel($level);
            echo "<h5>Level $level</h5>";
            echo '<table border="1">';

            models\TCustomers::GetEntity();
            models\Anything::GetEntity();

            models\VVcustomers::GetEntity();
            models\Anything2::GetEntity();


            \Iris\Admin\models\TActions::GetEntity();

            \Iris\DB\TableEntity::GetEntity('customers');
            \Iris\DB\TableEntity::GetEntity('', 'models\\TCustomers');
            \Iris\DB\TableEntity::GetEntity('customers', 'models\\TCustomers');
            \Iris\DB\TableEntity::GetEntity('customers', 'models\\Anything');

            \Iris\DB\ViewEntity::GetEntity('vcustomers', 'customers');

            echo "</table>";
        }
        die('<h6>End of entity tests</h6>');
        break;
    case 2:
        $ticket = new \Payoff\Ticket('anticonstitutionnel');
        echo $ticket->getValue() . '<br>';
        if ($ticket->validate()) {
            echo "Yes!!!!";
        }
        else {
            echo "No";
        }
        die('End of Payoff test');
        break;
    // test bits in \Iris\Errors\Settings    
    case 3:

        /* @var $settings Iris\Errors\Settings */
        $settings = \Iris\Errors\Settings::GetInstance();
        if ($settings->hasLog() or $settings->hasMail() or $settings->hasKeep() or $settings->hasHang()) {
            die('One bit');
        }
        else {
            echo "No bit <br>";
        }
        $settings->setMail();
        $settings->setHang();
        if($settings->hasMail()) echo 'MAIL OK <br>';
        $settings->unsetMail();
        if($settings->hasHang()) echo "HANG ok<br>";
        if($settings->hasMail()) echo 'MAIL OK <br>';
        if(!$settings->hasMail()) echo "No mail defined";
        $settings->unsetHang();
        if(!$settings->hasHang()) echo "No more hang";
        
        die('Test ok');
        break;
}



    