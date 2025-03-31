<?php
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
</head>
<body class="bg-light">
    <div class="container my-5">
        <h3 class="text-center">Loja de Produtos</h3>
        <div class="row">
            <?php foreach ($produtos as $produto): ?>
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <img src="<?php echo $produto['image']; ?>" class="card-img-top" alt="<?php echo $produto['title']; ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $produto['title']; ?></h5>
                            <p class="card-text"><?php echo $produto['description']; ?></p>
                            <p class="card-text"><strong>Preço:</strong> $<?php echo $produto['price']; ?></p>
                            <a href="checkout.php?produto=<?php echo urlencode($produto['title']); ?>" class="btn btn-primary">Comprar</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
