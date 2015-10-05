<?php

// NOTHING IS GOOD HERE


switch (0) {
    // Entity tests
    case 1:
        //\Iris\DB\_EntityManager::SetInstance(
        //        \Iris\DB\_EntityManager::EMFactory('sqlite:application/config/base/invoice.sqlite'));
        echo "<h4>Beginning of entity tests</h4>";
        defined('RT') or define('RT', "<br>");
        for ($level = 1; $level < 4; $level++) {
            echo "<h5>Level $level</h5>";
            echo '<table border="1">';

          //  models\TCustomers::GetEntity();
          models_internal\TSections::GetEntity();
//            models\Anything::GetEntity();
//
//            models\VVcustomers::GetEntity();
//            models\Anything2::GetEntity();
//
//
//            \Iris\Admin\models\TActions::GetEntity();
//
//            \Iris\DB\TableEntity::GetEntity('customers');
//            \Iris\DB\TableEntity::GetEntity('', 'models\\TCustomers');
//            \Iris\DB\TableEntity::GetEntity('customers', 'models\\TCustomers');
//            \Iris\DB\TableEntity::GetEntity('customers', 'models\\Anything');
//
//            \Iris\DB\ViewEntity::GetEntity('vcustomers', 'customers');

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
        if (\Iris\Errors\Settings::HasLog() or \Iris\Errors\Settings::HasMail() or \Iris\Errors\Settings::HasKeep() or \Iris\Errors\Settings::HasHang()) {
            die('One bit');
        }
        else {
            echo "No bit <br>";
        }
        \Iris\Errors\Settings::$Mail = \TRUE;
        \Iris\Errors\Settings::$Hang = \TRUE;
        if (\Iris\Errors\Settings::$Mail)
            echo 'MAIL OK <br>';
        \Iris\Errors\Settings::$Mail = \FALSE;
        if (\Iris\Errors\Settings::$Hang)
            echo "HANG ok<br>";
        if (\Iris\Errors\Settings::$Mail)
            echo 'MAIL OK <br>';
        if (!\Iris\Errors\Settings::$Mail)
            echo "No mail defined";
        \Iris\Errors\Settings::$Hang = \FALSE;
        if (\Iris\Errors\Settings::$Hang)
            echo "No more hang";

        die('Test ok');
        break;
    case 4:
        echo \Iris\SysConfig\Settings::$Debug(\FALSE);
        \Dojo\Engine\Settings::GetInstance()->debug();
        break;
}



    