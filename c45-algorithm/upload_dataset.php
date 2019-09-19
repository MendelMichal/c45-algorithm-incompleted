<form action="" method="post" accept-charset="utf-8" enctype="multipart/form-data">
<input type="file" name="file">
<input type="submit" name="btn_submit" value="Upload File" />

<?php
$fh = fopen($_FILES['file']['tmp_name'], 'r+');
$lines = array();
while( ($row = fgetcsv($fh, 8192)) !== FALSE ) {
	$lines[] = $row;
}
var_dump($lines);
?>

</form>