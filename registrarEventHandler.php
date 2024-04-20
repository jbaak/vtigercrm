<?php

require_once 'vtlib/Vtiger/Event.php';

Vtiger_Event::register(
    'Contacts', 
    'vtiger.entity.beforesave', 
    'CreateCustomerConektaHandler', 
    'modules/Contacts/CreateCustomerConektaHandler.php'
);