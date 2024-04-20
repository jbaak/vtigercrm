<?php

require_once('include/utils/utils.php');

class CreateCustomerConektaHandler extends VTEventHandler 
{
	public function handleEvent($eventType, $entity)
	{
		$moduleName = $entity->getModuleName();
		if ($moduleName == 'Contacts' && $eventType == 'vtiger.entity.beforesave') {
			$idContacto = $entity->getId();
			$camposContacto = $entity->getData();
			
			//Estos son los campos del contacto:
			echo '<pre>';
			print_r($camposContacto);
			echo '</pre>';
			
			//$entity->get('nombre_campo') para obtener el valor de un campo.
			//$entity->set('nombre_campo', 'valor') para establecer el valor de un campo.
			
			//Este exit sirve para ver en el navegador el output (echos) de arriba. 
			//Es necesario quitarlo para que al guardar el contacto en la vista de edición, regrese al usuario a la vista de detalle.
			exit; 
		}
	}
}