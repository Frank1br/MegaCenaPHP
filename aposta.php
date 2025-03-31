<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['email']) && !isset($_COOKIE['email'])) {
    header("Location: login.php"); // Redireciona para login se não estiver logado
    exit;
}

// Recuperar o e-mail
$email = $_SESSION['email'] ?? $_COOKIE['email'];

// Função para gerar números aleatórios da Mega-Sena
function gerarNumerosMegaSena() {
    $numeros = range(1, 60);
    shuffle($numeros);
    return array_slice($numeros, 0, 6);
}

// Função para contar os acertos
function contarAcertos($aposta, $sorteio) {
    return count(array_intersect($aposta, $sorteio));
}

// Função para salvar a aposta
function salvarAposta($aposta, $sorteio, $acertos) {
    $data = date('Y-m-d H:i:s');
    $resultado = "Aposta: " . implode(", ", $aposta) . "\n";
    $resultado .= "Sorteio: " . implode(", ", $sorteio) . "\n";
    $resultado .= "Acertos: " . $acertos . "\n";
    $resultado .= "Data: " . $data . "\n";
    $resultado .= "----------------------------\n\n"; // Linha em branco para separar as apostas
    file_put_contents('historico_apostas.txt', $resultado, FILE_APPEND);
}

// Logout
if (isset($_GET['logout'])) {
    setcookie('email', '', time() - 3600); // Exclui o cookie
    session_destroy(); // Destroi a sessão
    header("Location: login.php");
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

// Ler o histórico de apostas
$historico = file('historico_apostas.txt');
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
                <h3>Olá, <?php echo $email; ?>!</h3>
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
                            <?php 
                            $apostas = explode("\n\n", trim(implode('', $historico)));
                            foreach ($apostas as $aposta): ?>
                                <li class="list-group-item">
                                    <pre><?php echo nl2br($aposta); ?></pre>
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
