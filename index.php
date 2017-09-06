<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Test</title>
	
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>
	<link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>
<body>

<?php
// sätter upp variabler för servername, username, password, databasnamn.
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "testtt";
$val = "";

// skapar filter för namn som skickas in och alla namn förslag.
$filtered = filter_input(INPUT_POST, "namn" , FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$filtered = trim($filtered);

$filteredradio = filter_input(INPUT_POST, "exempelRadios" , FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$filteredradio = trim($filteredradio);

// sätter upp databas och connection
$conn = new mysqli($servername, $username, $password, $dbname);

if($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

// spara i databas med prepare statements
$stmt = $conn->prepare("INSERT IGNORE INTO qwe (namn)  VALUES (?)");
$stmt->bind_param("s", $filtered);

// väljer data från databasen
$sql = "SELECT namn, votenum FROM qwe WHERE namn = '$filtered'";
$result = $conn->query($sql);

// kollar om $filtered är tom, om tom gör inget, om inte så går den igenom databasen och kollar om namnet finns redan
if(empty($filtered)) {
} else {
if($result->num_rows > 0) {
} else {
	$stmt->execute();
	if ($result === false) {
		echo "SQL error: " .$conn->error;
	}
}
}

$filtered = $conn->real_escape_string($filtered);
$filteredradio = $conn->real_escape_string($filteredradio);
// väljer data från databas
$sql = "SELECT namn, votenum FROM qwe";
$result = $conn->query($sql);

// kollar om en knapp är incheckad
if($_SERVER["REQUEST_METHOD"] == "POST") {
	if(empty($filteredradio)) {
	} else {
		$sql = "UPDATE qwe SET votenum = votenum + 1 WHERE namn = '$filteredradio'";
		if($conn->query($sql) == TRUE) {
		}
		}
	}

// väljer data från databas
$sql = "SELECT namn, votenum FROM qwe";
$result = $conn->query($sql);

$stmt->close();

$namn = "";

 ?>
<div class="container-fluid">

<div class="row">
	<div class="col">
	</div>
	<div id="title_div" class="col-6">
		<a href="index.html"><h1 id="title_main">Title</h1></a>
	</div>
	<div class="col">
	</div>
</div>

<!-- gör ett form -->
<form id="main_form" action="index.php" method="post">
	<fieldset class="form-group">
		<div class="row">
		<legend class="col-form-legend col-sm-3"></legend>
			<div class="col-sm-6">
			<div class="custom-control-stacked">
			<?php
			
			// skriver ut alla resultat
			if($result->num_rows > 0) {
					while($row = $result->fetch_assoc()){
						echo "<div class='form-check'>";
						echo "<label class='custom-control custom-radio'>";
						echo "<input class='custom-control-input' type='radio' name='exempelRadios' id='exempelRadios' value='".$row["namn"]."'>";
						echo "<span class='custom-control-indicator'></span>"; 
						$namn =  $row["namn"];
						echo $namn;
						echo " ".$row["votenum"];
						echo "</label>";
						echo " </div>";			
						}
				}
			
$conn->close(); ?>
			</div>
			</div>
		</div>
	</fieldset>
	<div class="form-group">
		<div class="row">
			<label for="exempelnamn" class="col-sm-3 col-form-label"></label>
			<div class="form-group col-md-5">
				<input type="text" class=" form-control" name="namn" id="exempelnamn" placeholder="Förlsag på namn">
			</div>
			<div class="form-group col-md-2">
				<button type="submit" class="btn btn-primary">Submit</button>
			</div>
		</div>
		<div class="row">
			<label for="exemeplhelp" class="col-sm-3 col-form-label"></label>
			<div class="col-sm-6">
				<small id="passwordHelpBlock" class="form-text text-muted">
				Har du ett förslag på ett namn? Skriv in det här då!
				</small>
			</div>
		</div>
	</div>
	<div class="form-group row">
		<label class="col-sm-3 col-form-label"></label>
		<div class="col-sm-6">
			<button type="submit" class="btn btn-primary">Skicka in svar</button>
		</div>
	</div>
</form>
</div>

</body>
</html>