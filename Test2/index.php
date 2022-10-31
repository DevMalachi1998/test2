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
  <form action="create_entries.php" method="post">
    <ul>
      <li>
        <h1>HELLO!</h1>
        <label>Please enter the amount of entries you want to generate.</label>
        <input type="number" name="numEntries">
      </li>
    </ul>
  </form>
</body>
</html>
