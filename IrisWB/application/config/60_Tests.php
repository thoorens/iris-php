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
        echo $ticket->getValue().'<br>';
        if($ticket->validate()){
            echo "Yes!!!!";
        }
        else{
            echo "No";
        }
        die('End of Payoff test');
        break;
}



    