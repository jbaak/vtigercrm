<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once('include/utils/utils.php');
require 'Services/ClientConekta.php';

use Vtiger\Modules\Services\ClientConekta;

class CreateCustomerConektaHandler extends VTEventHandler 
{
	public function handleEvent($eventType, $entity)
	{
		$moduleName = $entity->getModuleName();
		if ($moduleName == 'Contacts' && $eventType == 'vtiger.entity.beforesave') {
			$idContacto = $entity->getId();
			$camposContacto = $entity->getData();

            if ($entity->get('firstname') && $entity->get('email')) {
                $phone = $entity->get('phone')? $entity->get('phone'): '';
                $data = [
                    "email" => $entity->get('email'),
                    "name" => $entity->get('firstname').' '.$entity->get('lastname'),
                    "phone" => $phone,
                    ];

                $clientConekta =  new ClientConekta('https://api.conekta.io/', 'key_rmyiaII7J2bHfsoztNmQ2me');
                
                try {
                    if ($entity->get('otherphone')) {
                        //update customer
                        $res = $clientConekta->updateRecord($entity->get('otherphone'), $data, 'customers');
                    } else {
                        //new customer
                        $res = $clientConekta->createRecord($data, 'customers');
    
                        if ($res['id']) {
                            $entity->set('otherphone', $res['id']);
                        }
                    }
                } catch (Exception $e) {
                    die($e->getMessage());
                }

            }
               
		}
	}
}