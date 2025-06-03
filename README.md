# PHP script error-based SQLi

## Este script:

- Modificado del curso de introducción al hacking de H4ck4u que soluciona el problema del error-based SQLI
- Muestra solo el mensaje SQL relevante, ocultando la traza interna del servidor

```php
$server = "localhost";
$username = "Chef";
$password = "MiCocina!";
$database = "puchero_relacional";
```
Define las credenciales necesarias para conectarse a tu base de datos MariaDB/MySQL:
- **localhost**: el servidor donde está la base de datos (en este caso, la misma máquina).
- **"[User]"** y **"[Password]"**: usuario y contraseña para autenticarse.
- **"[database name]"**: nombre de la base de datos.

___

```php
mysqli_report(MYSQLI_REPORT_STRICT | MYSQLI_REPORT_ERROR);
```
Establece el modo de reporte de errores de mysqli:
- **MYSQLI_REPORT_STRICT**: transforma los errores en excepciones mysqli_sql_exception, que puedes capturar con try-catch
- **MYSQLI_REPORT_ERROR**: informa solo de errores graves, omitiendo avisos molestos como «No index used»

<br>

> [!TIP]
> Así, puedes controlar exactamente qué mensaje se muestra cuando hay una SQLi, por ejemplo.

___

```php
try {
    $conn = new mysqli($server, $username, $password, $database);
```
Intenta establecer la conexión con la base de datos. Si falla (por ejemplo, si el servidor está caído o la contraseña es incorrecta), lanza una excepción que será capturada más abajo.

___

```php
$id = $_GET['id'];
```
Toma el valor del parámetro id desde la URL.
Por ejemplo:
http://localhost/SearchUsers.php?id=1
guardará '1' en la variable $id.

Esto es inseguro si no hay validación. Alguien podría pasar algo como: id=1' OR 1=1-- y alterar la consulta

___

```php
$query = "SELECT username FROM users WHERE id = '$id'";
```
Crea la consulta **SQL**, insertando el valor de $id sin escapar ni sanitizar, lo que la hace vulnerable a inyección SQL.

___

```php
$data = $conn->query($query);
$response = $data->fetch_assoc();
```
- Ejecuta la consulta
- Extrae el resultado como un array asociativo **(['username' => 'loquesea'])**
___

```php
$query = "SELECT username FROM users WHERE id = '$id'";
```
Muestra el nombre de usuario correspondiente al ID.

___

```php
} catch (mysqli_sql_exception $e) {
    echo $e->getMessage();
}
```
Si ocurre un **error SQL** (por ejemplo, por una inyección malformada), captura la excepción y muestra únicamente el mensaje de SQL. Así **evitas que PHP muestre un stack trace completo**, y simulas el comportamiento deseado para ataques SQLi basados en error.


