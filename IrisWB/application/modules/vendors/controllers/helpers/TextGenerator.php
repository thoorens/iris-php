<?php

namespace Iris\controllers\helpers;

/*
 * This file is part of IRIS-PHP distributed under the General Public License version 3.
 * 
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *
 * @copyright 2011-2016 Jacques THOORENS
 */


/**
 * Description of newIrisClassTextGenerator.php
 *
 * Project IRIS-PHP
 *
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version :$Id:
 */
class TextGenerator extends _ControllerHelper {

    private $_text = [
        2 => <<<TEXT1
        #Title 1

Here is my list:

  * an item
  * another

Title 2
-------
 1. item 1
 2. item 2
 2. item 3                


TEXT1
        ,
        1 => <<<TEXT2
```javascript
function colorationSyntaxique() {
  var n = 33;
  var t = "bonjour";
  console.log(t);
}
```

* plein
* *accentuation*
  * **forte accentuation**
    * ~~barré~~
* `code à l’intérieur d'une ligne de texte`

1. Liste numérotée
   1. Numbered sub-list
      1. Numbered sub-sub-list
2. [Link](https://www.google.com)


Une image : ![logo de Markdown Here](http://adam-p.github.io/markdown-here/img/icon24.png)

> Bloc de citation. 
> *Avec* **un peu de** `markdown`.

Si le support des **formules mathématiques TeX** est activé, l'équation du second degré est la suivante : 
$-b \pm \sqrt{b^2 - 4ac} \over 2a$

 # Titre 1 #
 ## Titre 2 ##
### Titre 3
#### Titre 4
##### Titre 5
###### Titre 6
 
| Les tableaux | sont | intéressants |
| ------------- |:-------------:| -----:|
| la colonne 3 | est alignée à droite | 1600 $ |
| la colonne 2 | est centrée | 12 $ |
| les rayures | sont élégantes | 1 $ |

Voici une ligne horizontale :

---

```
bloc de code
sans coloration syntaxique
```
                  
        
TEXT2
        ,
        
    ];

    public function help($number){
        return $this->_text[$number];
    }
}

