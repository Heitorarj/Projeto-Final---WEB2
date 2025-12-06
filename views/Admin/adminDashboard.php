<?php

    include "headerAdmin.php";
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Produto - Admin</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { background:#f4f4f4; padding:40px; }
        .card-form { background:white; padding:30px; border-radius:12px; }
    </style>
</head>

<body>

<div class="container mt-4">
    <h1 class="fw-bold mb-4">Cadastro de Produto</h1>

    <div class="card-form shadow">

        <form>

            <label class="fw-bold">Nome da Camisa</label>
            <input type="text" class="form-control mb-3" placeholder="Ex: Flamengo 2024">

            <label class="fw-bold">Preço</label>
            <input type="number" class="form-control mb-3" placeholder="Ex: 199.90">

            <label class="fw-bold">Descrição</label>
            <textarea class="form-control mb-3" rows="3"></textarea>

            <label class="fw-bold">Imagem do Produto</label>
            <input type="file" class="form-control mb-4">

            <button class="btn btn-dark w-100 fw-bold">Cadastrar Produto</button>
        </form>

    </div>
</div>

</body>
</html>
