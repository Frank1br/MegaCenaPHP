<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Verificar e-mail e senha
    if ($email == "aluno@fatec.edu.br" && $senha == "alunoweb2") {
        // Criar cookie e variável de sessão
        setcookie('email', $email, time() + 3600); // O cookie dura 1 hora
        $_SESSION['email'] = $email;
        header("Location: index.php"); // Redireciona para a página de apostas
        exit;
    } else {
        $erro = "E-mail ou senha incorretos!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Mega-Sena</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container my-5">
        <div class="card shadow p-3" style="max-width: 400px; margin: auto;">
            <div class="card-body text-center">
                <h3 class="card-title">Login</h3>
                <?php if (isset($erro)) echo "<div class='alert alert-danger'>$erro</div>"; ?>
                <form method="POST">
                    <div class="mb-3">
                        <input type="email" name="email" class="form-control" placeholder="E-mail" required>
                    </div>
                    <div class="mb-3">
                        <input type="password" name="senha" class="form-control" placeholder="Senha" required>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Entrar</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
