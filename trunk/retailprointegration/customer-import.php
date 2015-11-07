<?php 
include('connect_magento.php');

//util function - Bad Code!, Refactor if there is time - different from the function in customer.php
function addCustomerToIndex($customerId, $suid){	
	$dom=new DOMDocument();
	$dom->load("customer-index.xml");	
	$customerElement = $dom->createElement('customer');
	$custIdAttr = $dom->createAttribute('customer_id');
	$custIdVal = $dom->createTextNode($customerId);
	$custIdAttr->appendChild($custIdVal);
	$customerElement->appendChild($custIdAttr);
	$suIdAttr = $dom->createAttribute('cust_sid');
	$suIdVal = $dom->createTextNode($suid);
	$suIdAttr->appendChild($suIdVal);
	$customerElement->appendChild($suIdAttr);
	$dom->getElementsByTagName('customers')->item(0)->appendChild($customerElement);
	$dom->save('customer-index.xml');
	return $suid;
}

$filesInFolder = scandir('customer-uploads');
$count=0;
foreach ($filesInFolder as $sourceFile) {
	if (strstr($sourceFile, '.xml')){
		$xmlDoc = simplexml_load_file('customer-uploads/'.$sourceFile);
		foreach ($xmlDoc->CUSTOMERS->CUSTOMER as $customer){	
		$email = $customer['email_addr'];			
			if (isset($email) && strlen($email) > 0){
				//build the customer object
				
				$firstName = $customer['first_name'];
				$lastName = $customer['last_name'];

				$magentoCustomer = array('email' => "$email", 'firstname'=>"$firstName",
					'lastname'=>"$lastName",'password'=>'%hj88256jkl','website_id'=>1,'store_id' => 1, 'group_id' => 1);
				
				//push the customer via the API
				try{
					$result = $client->call($session,'customer.create',array($magentoCustomer));
					$result = intval($result);
				//if result is an Integer i.e ID
					if ($result != 0){
						$count++;
						logMessage($count.' customers imported');
						addCustomerToIndex($result, $customer['cust_sid']);
					}
				}
				catch(Exception $e) {
					logMessage('Error: ' .$e->getMessage());
				}
			}else logMessage('Customer '.$customer['first_name'].' has no email address, cannot import');
			
		}
	}
	try{
		$hasMoved = rename('customer-uploads/'.$sourceFile, 'backups/'.$sourceFile);
		if ($hasMoved) logMessage($sourceFile.' processed and moved to the backups folder');
		else logMessage($sourceFile.' File could not be moved');
	}catch(Exception $e){logMessage('Error: ',$e->getMessage());}
}
?>
