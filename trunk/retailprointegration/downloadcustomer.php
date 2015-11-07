<?php 
echo('
<table>
<thead>
<tr><td><h4>Customer Files</h4></td></tr>
</thead>
<tbody>
');
$filesInFolder = scandir('customers');
foreach ($filesInFolder as $sourceFile) {
	if (strstr($sourceFile, '.xml'))
	echo('<tr><td><a href="customers/'.$sourceFile.'">'.$sourceFile.'</a></td></tr>');
}
echo('
</tbody>
</table>
	');
?>