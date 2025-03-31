<?php
function gerarNumerosMegaSena() {
    $numeros = range(1, 60);
    shuffle($numeros);
    return array_slice($numeros, 0, 6);
}

function contarAcertos($aposta, $sorteio) {
    return count(array_intersect($aposta, $sorteio));
}

function salvarAposta($aposta, $sorteio, $acertos) {
    $data = date('Y-m-d H:i:s');
    $resultado = "Aposta: " . implode(", ", $aposta) . "\n";
    $resultado .= "Sorteio: " . implode(", ", $sorteio) . "\n";
    $resultado .= "Acertos: " . $acertos . "\n";
    $resultado .= "Data: " . $data . "\n";
    $resultado .= "----------------------------\n\n"; // Linha em branco para separar as apostas

    file_put_contents('historico_apostas.txt', $resultado, FILE_APPEND);
}

function excluirAposta($linha) {
    $linhas = file('historico_apostas.txt');
    
    // Cada aposta tem um bloco com pelo menos 5 linhas (4 informações + 1 linha em branco).
    $start = $linha * 5; 
    $end = $start + 4; 
    
    // Remove o bloco correspondente à aposta
    array_splice($linhas, $start, 5);

    file_put_contents('historico_apostas.txt', implode('', $linhas));
}

$resultado = "";
$meusNumeros = [];
$sorteio = [];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['numeros'])) {
    $meusNumeros = array_map('intval', $_POST['numeros']);
    $meusNumeros = array_unique($meusNumeros);
    sort($meusNumeros);

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

        // Salvar a aposta no histórico
        salvarAposta($meusNumeros, $sorteio, $acertos);
    } else {
        $resultado = "<span class='text-danger'>Escolha exatamente 6 números distintos entre 1 e 60.</span>";
    }
}

// Excluir aposta do histórico
if (isset($_GET['excluir'])) {
    $linhaExcluir = $_GET['excluir'];
    excluirAposta($linhaExcluir);
    header("Location: " . $_SERVER['PHP_SELF']); // Redireciona para atualizar a página
    exit;
}

// Ler o histórico de apostas
$historico = file('historico_apostas.txt');

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
        .historico { margin-top: 30px; }
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

                <!-- Exibir histórico de apostas -->
                <div class="historico">
                    <h3>Histórico de Apostas</h3>
                    <?php if (!empty($historico)): ?>
                        <ul class="list-group">
                            <?php 
                            // Dividir o histórico em blocos de apostas
                            $apostas = explode("\n\n", trim(implode('', $historico)));
                            foreach ($apostas as $index => $aposta): ?>
                                <li class="list-group-item">
                                    <pre><?php echo nl2br($aposta); ?></pre>
                                    <a href="?excluir=<?php echo $index; ?>" class="btn btn-danger btn-sm float-end">Excluir</a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p>Você ainda não fez nenhuma aposta.</p>
                    <?php endif; ?>
                </div>

                <!-- Botão para download do histórico -->
                <div class="mt-4">
                    <a href="historico_apostas.txt" class="btn btn-primary" download>Baixar Histórico de Apostas</a>
                </div>

            </div>
        </div>
    </div>
</body>
</html>
