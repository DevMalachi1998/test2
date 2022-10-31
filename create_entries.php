<?php
    $data = array ("Id, Name, Surname, Initials, Age, DateOfBirth");

    $names = array('Donovan', 'Andre', 'Stuart', 'Kevin', 'Ruan', 'Jacques', 'Peter', 'Mark', 'Jamie', 'Liam', 'Arnold', 'Duane', 'Bronwynn', 'Jacqui', 'Bridget', 'Sosethu', 'Yanga', 'Primrose', 'Zukisani', 'Liyabonga');

    $surnames = array('Ngcayisa', 'Smith', 'Mpongoshe', 'Pakathi', 'Britz', 'Japie', 'Govender', 'Potgieter', 'van Eck', 'Kula', 'Bezuidenhout', 'Kruger', 'Siqiyiya', 'Hall', 'Connacher', 'Snyman', 'Mcdermot', 'Kenzington', 'McGruber', 'McGillivray');
    
    $numRecords = $_POST['numEntries'];
    
    for($counter = 0; $counter < $numRecords; $counter++){
      $name = $names[rand(0, count($names) - 1)];
      $surname = $surnames[rand(0, count($names) - 1)];
      $age = rand(18,90);
      $day = rand(1,30);
      $month = rand(1,12);
      if(Strlen ($day) == 1){
        $day = "0".strval($day);
      }
      if(Strlen ($month) == 1){
        $month = "0".strval($month);
      }
      $year = idate ("Y")-$age;
      $initials = substr($name,0,1);
      $dob = $day.'/'.$month.'/'. $year;
      $id = substr( $year,2,2).$month.$day.rand(0000000, 9999999);
      array_push($data, $id.','.$name.','.$surname.','.$initials.','.$age.','.$dob);
    }

    $fp = fopen('output.csv', 'w');
      foreach($data as $line){
        $val = explode(",",$line);
        fputcsv($fp, $val);
      }
    
    fclose($fp);

    // array to hold all "seen" lines
    $lines = array();

    // open the csv file
    if (($handle = fopen("output.csv", "r")) !== false) {
        // read each line into an array
        while (($data = fgetcsv($handle, 8192, ",")) !== false) {
            // build a "line" from the parsed data
            $line = join(",", $data);

            // if the line has been seen, skip it
            if (isset($lines[$line])) continue;

            // save the line
            $lines[$line] = true;
        }
        fclose($handle);
    }

    // build the new content-data
    $contents = '';
    foreach ($lines as $line => $bool) $contents .= $line . "\r\n";

    // save it to a new file
    file_put_contents("unique_output.csv", $contents);
    
    header("Location: ./db/create_database.php");  
?>