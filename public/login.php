<?php
require_once __DIR__ . '/../app/Core/Autoload.php';
require_once __DIR__ . '/../app/Core/Env.php';

Env::load(__DIR__ . '/../.env');

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $auth = new AuthController();

    $response = $auth->login($_POST['username'], $_POST['password']);

    if (isset($response['error'])) {
        $error = $response['error'];
    } else {
        header("Location: dashboard.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>

<h2>Login</h2>

<?php if ($error): ?>
<p style="color:red;"><?php echo $error; ?></p>
<?php endif; ?>

<form method="POST">
    <input type="text" name="username" placeholder="Usuario" required><br><br>
    <input type="password" name="password" placeholder="Contraseña" required><br><br>
    <button type="submit">Ingresar</button>
</form>

</body>
</html>