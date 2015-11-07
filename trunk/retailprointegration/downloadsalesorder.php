<?php 
echo('
<table>
<thead>
<tr><td><h4>Sales Order Files</h4></td></tr>
</thead>
<tbody>
');
$filesInFolder = scandir('salesorders');
foreach ($filesInFolder as $sourceFile) {
	if (strstr($sourceFile, '.xml'))
	echo('<tr><td><a href="salesorders/'.$sourceFile.'">'.$sourceFile.'</a></td></tr>');
}
echo('
</tbody>
</table>
	');
?>