<?php 
function logMessage($message){
	$fp = fopen('montage.log', 'a');
	fwrite($fp, date('Y-m-d H:i:s').': '.$message."\n");
	fclose($fp);
}
try{
$address='http://www.montaigneplace.com/api/soap/?wsdl';// http://www.montaigneplacespa.com/api/?wsdl
///$address='http://www.montaigneplace.com/api/v2_soap?wsdl=1';
$apiUser="devpoint";
$apiKey="devpointr3tailpr0";
$client = new SoapClient($address);
// If somestuff requires api authentification,
// then get a session token
$session = $client->login($apiUser, $apiKey);
}
//catch exception
catch(Exception $e) {
  logMessage('Error: ' .$e->getMessage());
}
/*$result = $client->call($session, 'somestuff.method');
$result = $client->call($session, 'somestuff.method', 'arg1');
$result = $client->call($session, 'somestuff.method', array('arg1', 'arg2', 'arg3'));
$result = $client->multiCall($session, array(
     array('somestuff.method'),
     array('somestuff.method', 'arg1'),
     array('somestuff.method', array('arg1', 'arg2'))
));*/


// If you don't need the session anymore
//$client->endSession($session);
?>