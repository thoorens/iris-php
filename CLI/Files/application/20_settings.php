<?php
// If Dojo is used, you must provide a source. In development it is used in error display.
// The first 3 sources need an access to Internet to function
\Dojo\Manager::SetSource(\Dojo\Manager::GOOGLE);
//\Dojo\Manager::SetSource(\Dojo\Manager::AOL);
//\Dojo\Manager::SetSource(\Dojo\Manager::YANDEX);
// if LOCAL, you must copy or link dojo, dojox and dijit folders in /public/js
//\Dojo\Manager::SetSource(\Dojo\Manager::LOCAL);