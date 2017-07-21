<?php

namespace models_internal;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2017 Jacques THOORENS
 */

/**
 * Internal DB of IrisWB (see config/base/config.sqlite)
 * 
 * A wrapper class for \Iris\Structure\_TSequence: it permits instanciation
 * and naming and connexion to database definition. The sequence table contains
 * all the screens of the show.
 * 
 * This class is used as a 
 * 
 * Project IRIS-PHP
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.thoorens.net
 */
class TSequences extends \Iris\Structure\_TSequence {
    
    /*
     * CREATE TABLE "sequences" (
     * "id" INTEGER PRIMARY KEY  NOT NULL , -- the id in 5 digits: the first 2 are the section_id + a 3 digit sequential number
     * "URL"VARCHAR NOT NULL, -- in strict form /module/controller/action with leading /
     * "Description" VARCHAR NOT NULL , -- a small description to be used as title for the screen (in english)
     * "Error" BOOL DEFAULT False, -- if true, the screen contains an intentional error to test error trapping
     * "EN" TEXT,  -- an english description of the tested mechanism 
     * "FR" TEXT, -- a french description of the tested mechanism
     * "section_id" INTEGER, -- a foreign key to the section containing the screen (the same as the 3 first digits of the id
     * "Label" VARCHAR DEFAULT Null, -- presently no used
     * "Md5" VARCHAR, -- an MD5 signature of the screen (except all ajax stuff)
     * "Params" TEXT, -- some tests may required a mandatory parameter which is not passed in the URL due to the internal design of IrisWB
     * FOREIGN KEY (section_id) REFERENCES sections(id)
     * );
     */
    
    protected static $_MainField = "Description";
}


