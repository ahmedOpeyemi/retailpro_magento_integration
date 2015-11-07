<?php 
function logMessage($message){
	$fp = fopen('montage.log', 'a');
	fwrite($fp, date('Y-m-d H:i:s').': '.$message."\n");
	fclose($fp);
}

if (isset($_POST['btnUpload'])){
	/*move_uploaded_file($_FILES["file"]["tmp_name"],
		"inventory-uploads/" . $_FILES["file"]["name"]);*/
if (copy($_FILES["file"]["tmp_name"],
		"inventory-uploads/" . $_FILES["file"]["name"])){
	echo "File has been uploaded. The Cron Job will do the rest!";
	logMessage($_FILES["file"]["name"].' was uploaded.');
}else {
	echo "File failed to upload";
	logMessage($_FILES["file"]["name"].' failed to upload.');
}
	
}
echo('<form method="post" action="upload.php" enctype="multipart/form-data">
	<h3>Inventory File Upload</h3>
	<input type="file" name="file" id="file" required />
	<input type="submit" name="btnUpload" value="Upload!" />                   
	</form>')
?>