<?php

//      URL                           HTTP Method  Operation
//      ---                           -----------  ---------
//      /api/contacts                 GET          Returns an array of contacts
//      /api/contacts/:id             GET          Returns the contact with id of :id
//      /api/contacts                 POST         Adds a new contact and return it with an id attribute added
//      /api/contacts/:id             PUT          Updates the contact with id of :id
//      /api/contacts/:id             PATCH        Partially updates the contact with id of :id
//      /api/contacts/:id             DELETE       Deletes the contact with id of :id

// Set include path; require connection strings
set_include_path( get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] . "/LastMileData/src/php/includes" );

require_once("../../LastMileData/lib/Slim-2.6.2/Slim/cxn_delete.php");

echo $host;