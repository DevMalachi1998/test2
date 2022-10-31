<?php
function import_csv_to_sqlite(&$pdo, $csv_path, $options = array())
{
	extract($options);
	
	if (($csv_handle = fopen($csv_path, "r")) === FALSE)
		throw new Exception('Cannot open CSV file');
		

		$delimiter = ',';
		
		$table = 'csv_import';
	
		$fields = array_map(function ($field){
			return strtolower(preg_replace("/[^A-Z0-9]/i", '', $field));
		}, fgetcsv($csv_handle, 0, $delimiter));

	
	$create_fields_str = join(', ', array_map(function ($field){
		return "$field TEXT NULL";
	}, $fields));
	
	$pdo->beginTransaction();
	
	$create_table_sql = "CREATE TABLE IF NOT EXISTS $table ($create_fields_str)";
	$pdo->exec($create_table_sql);

	$insert_fields_str = join(', ', $fields);
	$insert_values_str = join(', ', array_fill(0, count($fields),  '?'));
	$insert_sql = "INSERT INTO $table ($insert_fields_str) VALUES ($insert_values_str)";
	$insert_sth = $pdo->prepare($insert_sql);
	
	$inserted_rows = 0;
	while (($data = fgetcsv($csv_handle, 0, $delimiter)) !== FALSE) {
		$insert_sth->execute($data);
		$inserted_rows++;
	}
	
	$pdo->commit();
	
	fclose($csv_handle);
	
	return array(
    'table' => $table,
    'fields' => $fields,
    'insert' => $insert_sth,
    'inserted_rows' => $inserted_rows
  );

}

if(isset($_FILES['csv_import'])){
  if (($_FILES['csv_import']['name']!="")){
  // Where the file is going to be stored
    $target_dir = "upload/";
    $file = $_FILES['csv_import']['name'];
    $path = pathinfo($file);
    $filename = $path['filename'];
    $ext = $path['extension'];
    $temp_name = $_FILES['csv_import']['tmp_name'];
    $path_filename_ext = $target_dir.$filename.".".$ext;
    
  // Check if file already exists
  if (file_exists($path_filename_ext)) {
    echo "Sorry, file already exists.";
    }else{
    move_uploaded_file($temp_name,$path_filename_ext);
    echo "Congratulations! File Uploaded Successfully.";
    }
  }
  import_csv_to_sqlite(new PDO('sqlite:users.sqlite'), './upload/output.csv', array());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <style>
    form {
      display: flex;
      height: 100vh;
      width: 100%;
      align-items: center;
      justify-content: center;
      text-align: center;
    }
    li {
      display: flex;
      flex-wrap: wrap;
      width: 450px;
      max-width: 100%;
      gap: 8px;
      justify-content: center;
    }
    li > label h1 {
      width: 100%;
    }
  </style>
</head>
<body>
  <form method="post"  enctype="multipart/form-data">
    <ul>
      <li>
        <h1>HELLO!</h1>
        <label>Please upload a csv file.</label>
        <input type="file" name="csv_import">
        <input type="submit">
      </li>
    </ul>
  </form>
</body>
</html>
