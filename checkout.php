<?php
if (isset($_GET['produto'])) {
    $produto = $_GET['produto'];
} else {
    $produto = "Produto não especificado"; // Valor padrão para evitar erro
}
?>



<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-light">
    <div class="container my-5">
        <div class="card p-4 shadow">
            <h3 class="text-center">Finalizar Compra</h3>
            <form id="checkout-form">
            <h4 class="text-center">Produto selecionado: <strong><?php echo htmlspecialchars($produto); ?></strong></h4>

                <div class="mb-3">
                    <label for="cep" class="form-label">CEP</label>
                    <input type="text" id="cep" class="form-control" placeholder="Digite seu CEP" required>
                </div>
                <div class="mb-3">
                    <label for="endereco" class="form-label">Endereço</label>
                    <input type="text" id="endereco" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="bairro" class="form-label">Bairro</label>
                    <input type="text" id="bairro" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="cidade" class="form-label">Cidade</label>
                    <input type="text" id="cidade" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="estado" class="form-label">Estado</label>
                    <input type="text" id="estado" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="nome" class="form-label">Nome Completo</label>
                    <input type="text" id="nome" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">E-mail</label>
                    <input type="email" id="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="pagamento" class="form-label">Método de Pagamento</label>
                    <select id="pagamento" class="form-control" required>
                        <option value="Debito">Débito</option>
                        <option value="Credito">Cartão de Crédito</option>
                        <option value="Boleto">Boleto</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-success w-100">Confirmar Compra</button>
            </form>
        </div>
    </div>
    
    <script>
        $(document).ready(function () {
            $("#cep").on("blur", function () {
                var cep = $(this).val().replace(/\D/g, '');
                if (cep.length === 8) {
                    $.getJSON("https://viacep.com.br/ws/" + cep + "/json/", function (data) {
                        if (!data.erro) {
                            $("#endereco").val(data.logradouro);
                            $("#bairro").val(data.bairro);
                            $("#cidade").val(data.localidade);
                            $("#estado").val(data.uf);
                        }
                    });
                }
            });

            $("#checkout-form").on("submit", function (e) {
                e.preventDefault();
                alert("Compra realizada com sucesso!");
                setTimeout(function () {
                    window.location.href = "loja.php";
                }, 3000);
            });
        });
    </script>
</body>
</html>
