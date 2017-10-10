<html>
<title>Web Form</title>


<?php
if (isset($_POST['sub'])) {
	foreach($_FILES as $file) {
		if (strpos($file['name'], 'csv') !== false) {
			$csv = str_getcsv($file['tmp_name']);
			echo $csv;
		}
	}
}
?>

<form method="POST" action="">
<input type="file" name="csv1" accept="text/csv" /><br />
<input type="file" name="csv2" accept="text/csv" /><br />
<input type="file" name="csv3" accept="text/csv" /><br />
<input type="file" name="csv4" accept="text/csv" /><br />
<input type="submit" name="sub">
</form>
</html>