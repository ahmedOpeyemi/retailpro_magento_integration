<?php
set_time_limit(0);
function replaceEmptySpace($source){
	return str_replace(' ', '-', $source);
}

function removeMinus($source){
    return str_replace('-', '', $source);
}
include('connect_magento.php');
$filesInFolder = scandir('inventory-uploads');
$count=0;
foreach ($filesInFolder as $sourceFile) {   
   /* $sourceFile = "inventory.xml";*/
   if (strstr($sourceFile, '.xml')){
    $xmlDoc = simplexml_load_file('inventory-uploads/'.$sourceFile);
//iterate over the number of products

// get attribute set
    try{
    $attributeSets = $client->call($session, 'product_attribute_set.list');
    $attributeSet = current($attributeSets);
} catch(Exception $e){logMessage('Error: ',$e->getMessage());
logMessage('Retrying..');
include('connect_magento.php');
$attributeSets = $client->call($session, 'product_attribute_set.list');
$attributeSet = current($attributeSets);
}


/*$result = $client->call($session, 'store.info', '1');
var_dump($result); exit();*/
/*var_dump($session); exit();*/
$allSavedItems = array();

foreach ($xmlDoc->INVENTORYS->INVENTORY as $inventory){		
    $item_sid = $inventory->INVN['item_sid'];
$sku=removeMinus($inventory->INVN['item_sid']);//.rand(1,10);
$name = $inventory->INVN_SBS['description1'];
$description=$inventory->INVN_SBS['long_description'];
$short_description = $inventory->INVN_SBS['description2'];
$weight = $inventory->INVN_SBS['siz'];
$url_key=replaceEmptySpace($inventory->INVN_SBS['description1']).'-'.$inventory->INVN['item_sid'];
$url_path=replaceEmptySpace($inventory->INVN_SBS['description1']);
$price=$inventory->INVN_SBS->INVN_SBS_PRICES->INVN_SBS_PRICE[0]['price'];
$meta_title=$inventory->INVN_SBS['description1'];
$meta_description=$inventory->INVN_SBS['long_description'];
$qty = $inventory->INVN_SBS->INVN_SBS_QTYS->INVN_SBS_QTY['qty'];

try{
    $result = $result = $client->call($session, 'catalog_product.create', array('simple', $attributeSet['set_id'], $sku,array(
        'categories' => array(2),
        'websites' => array(1),
        'name' => "$name",
        'description' =>"$description",
        'short_description' => "$short_description",
        'weight' => "$weight",
        'status' => '1',
        'url_key' => "$url_key",
        'url_path' => "$url_path",
        'visibility' => '4',
        'price' => "$price",
        'tax_class_id' => 1,
        'meta_title' => "$meta_title",
        'meta_keyword' => '',
        'meta_description' => "$meta_description",
        'stock_data'=>array('qty' => "$qty") 
        )));
    /*$savedItem = */
    $allSavedItems[] = array("sid"=>"$item_sid","mangentoId"=>"$result");
    ++$count;
}
catch(Exception $e) {
  logMessage('Error: ' .$e->getMessage());
}
logMessage($count." Items are saved");
}
//delete the file
//new version - move the file to a backup folder
try{
    $hasMoved = rename('inventory-uploads/'.$sourceFile, 'backups/'.$sourceFile);
//$hasDeleted = unlink('inventory-uploads/'.$sourceFile);
if ($hasMoved) logMessage($sourceFile.' processed and moved to the backups folder');
else logMessage($sourceFile.' File could not be moved');
}catch(Exception $e){logMessage('Error: ',$e->getMessage());}
}
}
?>