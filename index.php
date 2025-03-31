<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['email']) && !isset($_COOKIE['email'])) {
    header("Location: login.php");
    exit;
}

$email = $_SESSION['email'] ?? $_COOKIE['email'];

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
    $registro = [
        'id' => uniqid(),
        'aposta' => $aposta,
        'sorteio' => $sorteio,
        'acertos' => $acertos,
        'data' => $data
    ];
    file_put_contents('historico_apostas.txt', json_encode($registro) . "\n", FILE_APPEND);
}

if (!file_exists('historico_apostas.txt')) {
    file_put_contents('historico_apostas.txt', "");
}

if (isset($_GET['logout'])) {
    setcookie('email', '', time() - 3600);
    session_destroy();
    header("Location: login.php");
    exit;
}

if (isset($_GET['excluir'])) {
    $id_excluir = $_GET['excluir'];
    $historico = file('historico_apostas.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $novo_historico = [];

    foreach ($historico as $linha) {
        $aposta = json_decode($linha, true);
        if (is_array($aposta) && isset($aposta['id']) && $aposta['id'] !== $id_excluir) {
            $novo_historico[] = $linha;
        }
    }

    file_put_contents('historico_apostas.txt', implode("\n", $novo_historico) . "\n");
    header("Location: index.php");
    exit;
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

        salvarAposta($meusNumeros, $sorteio, $acertos);
    } else {
        $resultado = "<span class='text-danger'>Escolha exatamente 6 números distintos entre 1 e 60.</span>";
    }
}

$historico = file('historico_apostas.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$historico = array_filter(array_map(function($linha) {
    $aposta = json_decode($linha, true);
    return is_array($aposta) && isset($aposta['aposta'], $aposta['sorteio']) ? $aposta : null;
}, $historico));
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mega-Sena - Apostas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container my-5">
        <div class="card shadow p-3">
            <div class="card-body text-center">
                <h3>Olá, <?php echo htmlspecialchars($email); ?>!</h3>
                <a href="?logout=true" class="btn btn-danger mb-3">Sair</a>
                <a href="loja.php" class="btn btn-primary mb-3">Ir para a Loja</a>
                
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

                <div id="result" class="mt-3">
                    <?php if ($resultado) echo "<div class='alert alert-info'>$resultado</div>"; ?>
                </div>

                <div class="mt-4">
                    <h3>Histórico de Apostas</h3>
                    <?php if (!empty($historico)): ?>
                        <ul class="list-group">
                            <?php foreach ($historico as $aposta): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <pre class="mb-0"><?php 
                                        echo "Aposta: " . implode(", ", (array) $aposta['aposta']) . "\n";
                                        echo "Sorteio: " . implode(", ", (array) $aposta['sorteio']) . "\n";
                                        echo "Acertos: " . $aposta['acertos'] . "\n";
                                        echo "Data: " . $aposta['data'];
                                    ?></pre>
                                    <a href="?excluir=<?php echo htmlspecialchars($aposta['id']); ?>" class="btn btn-danger btn-sm">Excluir</a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p>Você ainda não fez nenhuma aposta.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
