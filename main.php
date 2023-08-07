<!DOCTYPE html>
<html>
<head>
    <title>Processar Cadastro</title>
</head>
<body>
<?php
// Função para limpar o CEP removendo caracteres especiais
function limparCEP($cep) {
    return preg_replace('/[^0-9]/', '', $cep);
}

// Verificar se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obter os dados do formulário
    $nome = $_POST["nome"];
    $idade = $_POST["idade"];
    $email = $_POST["email"];
    $cpf = $_POST["cpf"];
    $cep = $_POST["cep"];

    // Validar os dados (você pode adicionar mais validações aqui)
    if (empty($nome) || empty($idade) || empty($email) || empty($cpf) || empty($cep)) {
        echo "<p>Todos os campos são obrigatórios.</p>";
    } else {
        // Limpar o CEP removendo caracteres especiais
        $cep = limparCEP($cep);

        // Fazer a requisição HTTP ao serviço ViaCEP para validar o CEP
        $url = "https://viacep.com.br/ws/{$cep}/json/";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        // Decodificar o JSON da resposta
        $endereco = json_decode($response, true);

        // Verificar se o CEP é válido (ViaCEP retorna o campo 'erro' como true para CEPs inválidos)
        if (isset($endereco["erro"]) && $endereco["erro"] === true) {
            echo "<p>CEP inválido. Certifique-se de digitar um CEP válido.</p>";
        } else {
            // Aqui você pode utilizar os dados do endereço obtidos para salvar no banco de dados ou realizar outras ações
            echo "<h2>Conta criada com sucesso!</h2>";
            echo "<p><strong>Nome:</strong> $nome</p>";
            echo "<p><strong>Idade:</strong> $idade</p>";
            echo "<p><strong>E-mail:</strong> $email</p>";
            echo "<p><strong>CPF:</strong> $cpf</p>";
            echo "<p><strong>CEP:</strong> $cep</p>";
            echo "<p><strong>Endereço:</strong> {$endereco['logradouro']}, {$endereco['bairro']}, {$endereco['localidade']}, {$endereco['uf']}</p>";
        }
    }
}
?>
</body>
</html>
