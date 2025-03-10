<?php
function gerarNumerosMegaSena() {
    $numeros = range(1, 60);
    shuffle($numeros);
    return array_slice($numeros, 0, 6);
}

function contarAcertos($aposta, $sorteio) {
    return count(array_intersect($aposta, $sorteio));
}

$resultado = "";
$meusNumeros = [];
$sorteio = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $meusNumeros = array_map('intval', $_POST['numeros']); // Pega os números digitados
    $meusNumeros = array_unique($meusNumeros); // Remove duplicados
    sort($meusNumeros); // Ordena os números
    
    if (count($meusNumeros) === 6) {
        $sorteio = gerarNumerosMegaSena();
        sort($sorteio);
        $acertos = contarAcertos($meusNumeros, $sorteio);

        $resultado = "Seus números: " . implode(", ", $meusNumeros) . "<br>";
        $resultado .= "Números sorteados: " . implode(", ", $sorteio) . "<br>";
        $resultado .= "Você acertou <strong>$acertos</strong> números!<br>";

        if ($acertos == 6) {
            $resultado .= "<span class='text-success'>Parabéns! Você ganhou na Mega-Sena!</span>";
        } elseif ($acertos == 5) {
            $resultado .= "<span class='text-warning'>Você fez uma quina!</span>";
        } elseif ($acertos == 4) {
            $resultado .= "<span class='text-info'>Você fez uma quadra!</span>";
        } else {
            $resultado .= "<span class='text-danger'>Infelizmente, não foi dessa vez.</span>";
        }
    } else {
        $resultado = "<span class='text-danger'>Escolha exatamente 6 números distintos entre 1 e 60.</span>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mega-Sena</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .mega-card { max-width: 400px; margin: auto; }
        .result-box { font-weight: bold; text-align: center; margin-top: 20px; }
    </style>
</head>
<body class="bg-light">
    <div class="container my-5">
        <div class="card shadow p-3 mega-card">
            <img src="img/megasena2.png" class="card-img-top" alt="Mega-Sena">
            <div class="card-body text-center">
                <p class="text-success">Escolha 6 números entre 1 e 60 e veja se você ganha!</p>
                
                <form method="POST">
                    <div class="row g-2">
                        <?php for ($i = 0; $i < 6; $i++): ?>
                            <div class="col-4">
                                <input type="number" name="numeros[]" min="1" max="60" class="form-control" required>
                            </div>
                        <?php endfor; ?>
                    </div>
                    <button type="submit" class="btn btn-success mt-3 w-100">Apostar</button>
                </form>

                <div id="result" class="result-box">
                    <?php if ($resultado) echo "<div class='alert alert-info'>$resultado</div>"; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
