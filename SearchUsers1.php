
<?php
	$server = "localhost";
	$username = "Chef";
	$password = "MiCocina!";
	$database = "puchero_relacional";

	// Establecer estilo de error para que use excepciones
	mysqli_report(MYSQLI_REPORT_STRICT | MYSQLI_REPORT_ERROR);

	try {
		$conn = new mysqli($server, $username, $password, $database);

		$id = $_GET['id']; // Valor inyectado por el usuario desde la URL

		// Consulta vulnerable a SQLi
		$query = "SELECT username FROM users WHERE id = '$id'";

		$data = $conn->query($query);

		$response = $data->fetch_assoc();

		echo $response['username'];

	} catch (mysqli_sql_exception $e) { // Solo mostramos el mensaje de error SQL, sin stack trace ni detalles de PHP
		echo $e->getMessage();
	}
?>

