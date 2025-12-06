<?php

    include "headerAdmin.php";
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Produtos Cadastrados - Admin</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { background:#f4f4f4; padding:40px; }
        .produto-card { border-radius:12px; }
    </style>
</head>

<body>

<div class="container mt-4">
    <h1 class="fw-bold mb-4">Produtos Cadastrados</h1>

    <div class="row g-4">

        <!-- Produto exemplo -->
        <div class="col-md-4">
            <div class="card shadow produto-card">
                <img src="camisa1.jpg" class="card-img-top" alt="Camisa">
                <div class="card-body">
                    <h5 class="card-title">Camisa Flamengo 2024</h5>
                    <p class="card-text text-muted">R$ 199,90</p>
                    <button class="btn btn-danger w-100 fw-bold">Excluir</button>
                </div>
            </div>
        </div>


    </div>
</div>

</body>
</html>
