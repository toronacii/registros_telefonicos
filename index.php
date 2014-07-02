<!doctype html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Listado telefónico</title>
</head>
<body>
	<form action="process.php" method="POST" target="_blank">
		<label for="keyword">Apellidos</label><br>
		Ingrese los apellidos separados por coma <br>
		<textarea name="keyword" cols="30" rows="10"></textarea><br>

		<label for="ubicacion">Ciudad</label><br>
		<input type="text" name="ubicacion" value="caracas"><br><br>
		<input type="submit" value="Enviar">
	</form>
</body>
</html>