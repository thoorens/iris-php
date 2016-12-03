<?php
// ==========================================================================================================================================
// Fragments of code
// ==========================================================================================================================================

\CLI\Project::$Fragments['dojo_tab'] = <<<DTAB
        \$default = 'first';
        \$position = \Dojo\\views\helpers\TabContainer::TOP; //or BOTTOM|LEFT|RIGHT
        \$this->callViewHelper('dojo_tabContainer', "container")
                ->setDefault(\$default)
                ->setPosition(\$position)
                ->setItems([
                    "first" => 'First label',
                    "second" => 'Second label',
        ]);       
µ---------------------------------------------------------------------------------------------µ        
<div id="content" {wbBG()}>
    {container->setDim(250, 450)}    
    {container->divMaster()}
    <div {container->item("first")}> 
        {loremIpsum([0,1,3,5,7,9,11])}
    </div>
    <div {container->item("second")}> 
        {loremIpsum([2,4,6,8,10,12,14])}
    </div>
    {container->endMaster()}
</div>
DTAB;

      