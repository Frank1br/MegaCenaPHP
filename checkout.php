<?php
if (isset($_GET['produto'])) {
    $produto = $_GET['produto'];
} else {
    header("Location: loja.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container my-5 text-center">
        <h3>Você comprou: <?php echo $produto; ?></h3>
        <a href="loja.php" class="btn btn-primary">Voltar para a loja</a>
    </div>
</body>
</html>
