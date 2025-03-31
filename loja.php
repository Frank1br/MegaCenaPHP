<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['email']) && !isset($_COOKIE['email'])) {
    header("Location: login.php");
    exit;
}



// Carregar produtos da Fake Store API
$produtos = json_decode(file_get_contents("https://fakestoreapi.com/products"), true);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Loja</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }
        .container {
            max-width: 1200px;
        }
        .card {
            transition: transform 0.3s ease-in-out;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .card:hover {
            transform: scale(1.05);
        }
        .card-img-top {
            height: 250px;
            object-fit: contain;
            background: #fff;
            padding: 15px;
        }
        .card-title {
            font-size: 1.2rem;
            font-weight: bold;
            color: #333;
        }
        .card-text {
            font-size: 0.95rem;
            color: #555;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
            transition: background 0.3s;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container my-5">
        <div class="d-flex justify-content-between mb-4">
            <a href="index.php" class="btn btn-secondary">Voltar para a Página Inicial</a>
            <a href="logout.php" class="btn btn-danger">Sair</a>
        </div>
        <h3 class="text-center mb-4 text-primary">Loja de Produtos</h3>
        <div class="row">
            <?php foreach ($produtos as $produto): ?>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100">
                        <img src="<?php echo $produto['image']; ?>" class="card-img-top" alt="<?php echo $produto['title']; ?>">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"> <?php echo $produto['title']; ?> </h5>
                            <p class="card-text flex-grow-1"> <?php echo substr($produto['description'], 0, 100) . '...'; ?> </p>
                            <p class="card-text text-success"><strong>Preço:</strong> $<?php echo number_format($produto['price'], 2, ',', '.'); ?></p>
                            <a href="checkout.php?produto=<?php echo urlencode($produto['title']); ?>" class="btn btn-primary mt-auto">Comprar</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
