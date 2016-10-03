<?php
namespace modules\db\controllers;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2016 Jacques THOORENS
 */

/**
 * 
 * Test of basic database operations
 * 
 * @author jacques
 * @license not defined
 */
class show extends _db {

    protected function _init() { 
        $this->_entityManager = \models\_invoiceEntity::DefaultEntityManager();
        $this->__action = "show";
        //$this->dbState()->validateDB();
        $this->setDefaultScriptDir('show');
    }

    /**
     * Displays all the invoices (with full details)
     * 
     * @param type $dbType
     */
    public function invoicesAction() {
        $container = $this->callViewHelper('dojo_tabContainer', 'container');
        $container->setDim(300, 700);
        $tInvoices = \models\TInvoices::GetEntity($this->_entityManager);
        $invoices = $tInvoices->fetchAll();
        foreach ($invoices as $invoice) {
            $invs[] = $this->_readInvoice($invoice);
            $container->addItem($invoice->id, "Invoice #" . $invoice->id);
        }
        $this->__invoices = $invs;
        /* @var $container \Dojo\views\helpers\TabContainer */
    }

    /**
     * Converts an invoice to an array, reading the orders and 
     * products corresponding to them
     * 
     * @param \Iris\DB\Object $invoice
     * @return type
     */
    private function _readInvoice($invoice) {
        $inv = [];
        $inv['id'] = $invoice->id;
        $inv['Date'] = $invoice->InvoiceDate;
        $inv['CustName'] = $invoice->_at_customer_id->Name;
        // same
        $inv['CustName'] = $invoice->getParent('customer_id')->Name;
        $targets = $invoice->_bridge_orders;
        // same
//      $targets = $invoice->fromBridge('orders');
        $ord = [];
        foreach($targets as $target){
            list($product, $order) = $target;
            $ord['Qty'] = $order->Quantity;
            $ord['Description'] = $product->Description;
            $ord['Description'] = $product->Description;
            $ord['UP'] = $product->Price;
            $inv['Orders'][] = $ord;
        }
//        $orders = $invoice->_children_orders__invoice_id;
//        foreach ($orders as $order) {
//            $ord['Qty'] = $order->Quantity;
//            $product = $order->_at_product_id;
//            $ord['Description'] = $product->Description;
//            $ord['UP'] = $product->Price;
//            $inv['Orders'][] = $ord;
//        }
        return $inv;
    }

    /**
     * Displays all the customers
     */
    public function customersAction() {
        $tCustomers = \models\TCustomers::GetEntity($this->_entityManager);
        $customers = $tCustomers->fetchAll();
        foreach ($customers as $customer) {
            $cust['Name'] = $customer->Name;
            $invoices = $customer->_children_invoices__customer_id;
            // using an equivalent method
            $invoices = $customer->getChildren(['invoices','customer_id']);
            // foreign key not necessary here
            $invoices = $customer->_children_invoices;
            $invoices = $customer->getChildren(['invoices']);
            $invs = [];
            foreach ($invoices as $invoice) {
                $date = new \Iris\Time\Date($invoice->InvoiceDate);
                $invs[] = array('Number' => $invoice->id, 'Date' => $date->toString('d M Y'));
            }
            $cust["Inv"] = $invs;
            $custs[] = $cust;
        }
        $this->__customers = $custs;
    }

    /**
     * Displays all the customers
     */
    public function productsAction() {
        $container = $this->callViewHelper('dojo_tabContainer', 'container');
        $container->setDim(300, 700);
        $tProducts = \models\TProducts::GetEntity($this->_entityManager);
        $products = $tProducts->fetchAll();
        foreach ($products as $product) {
            $prods[$product->id] = $this->_readProduct($product);
            $container->addItem($product->id, "Product n# " . $product->id);
        }
        $this->__products = $prods;
    }

    /**
     * 
     * @param \Iris\DB\Object $product
     * @return type
     */
    private function _readProduct($product) {
        $prod['id'] = $product->id;
        $prod['Description'] = $product->Description;
        $prod['Price'] = $product->Price;
        $targets = $product->_bridge_orders;
        // same
//      $targets = $product->fromBridge('orders');
        $invs = [];
        foreach($targets as $target){
            list($invoice,$order) = $target;
            $invs[] = [
                'Quantity' => $order->Quantity,
                'Number' => $invoice->id,
                'Date' => $invoice->InvoiceDate,
                'CustomerName' => $invoice->_at_customer_id->Name,
            ];
        }
//        $orders = $product->_children_orders__product_id;
//        $invs = [];
//        foreach ($orders as $order) {
//            $invoice = $order->_at_invoice_id;
//            $invs[] = [
//                'Quantity' => $order->Quantity,
//                'Number' => $invoice->id,
//                'Date' => $invoice->Date,
//                'CustomerName' => $invoice->_at_customer_id->Name,
//            ];
//        }
        $prod['Invoices'] = $invs;
        return $prod;
    }

    public function eventsAction() {
        $tEvents = \models\TEvents::GetEntity($this->_entityManager);
        $events = $tEvents->fetchAll();
        foreach ($events as $event) {
            $ord = [];
            $ev['Key1'] = $event->invoice_id;
            $ev['Key2'] = $event->product_id;
            $ev['Moment'] = $event->Moment;
            $ev['Description'] = $event->Description;
            $order = $event->_at_invoice_id__product_id;
            //may change the order of the keys
            //$order = $event->_at_product_id__invoice_id;
            // using an equivalent method with both orders of parameters
            //$order = $event->getParent(['product_id','invoice_id']);
            //$order = $event->getParent(['invoice_id','product_id']);
            $ord['Qty'] = $order->Quantity;
            $ord['Product'] = $order->_at_product_id->Description;
            $ord['Invoice'] = $order->_at_invoice_id->id;
            $ev['Order'] = $ord;
            $evs[] = $ev;
        }
        $this->__events = $evs;
    }

    public function ordersAction() {
        $tOrders = \models\TOrders::GetEntity($this->_entityManager);
        $orders = $tOrders->fetchAll();
        $container = $this->callViewHelper('dojo_tabContainer', 'container');
        $container->setDim(300, 700);
        foreach ($orders as $order) {
            $evs = [];
            $invoice = $order->_at_invoice_id;
            // the double primary key
            $ord['InvNum'] = $order->invoice_id . '/' . $order->product_id;
            $ord['Quantity'] = $order->Quantity;
            $ord['UnitPrice'] = $order->UnitPrice;
            $date = new \Iris\Time\Date($invoice->InvoiceDate);
            $ord['Date'] = $date;
            $ord['Description'] = $order->_at_product_id->Description;
            $events = $order->_children_events__invoice_id__product_id;
            $container->addItem($ord['InvNum'],'Order # '.$ord['InvNum']);
            foreach ($events as $event) {
                $ev = $event->Moment . ': ' . $event->Description;
                $evs[] = $ev;
            }
            $ord['Events'] = $evs;
            $ords[] = $ord;
        }
        $this->__orders = $ords;
        /* @var $container \Dojo\views\helpers\TabContainer */
    }

    public function databaseAction() {
        // these parameters are only for demonstration purpose
        $this->__(NULL, [
            'Title' => "<h1>db - show - database</h1>",
            'buttons' => 1+4,
            'logoName' => 'mainLogo']);
    }
}
