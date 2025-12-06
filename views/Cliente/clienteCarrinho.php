<?php
session_start();
include "headerCliente.php";

?>

<!-- carrinho.php -->
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Carrinho - FUT STORE</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f4f4f4;
            padding-top: 70px;
        }
        nav {
            background: #0a0a23 !important;
        }
        .navbar-brand, .nav-link {
            color: white !important;
            font-weight: bold;
        }
        .cart-card {
            background: white;
            padding: 20px;
            border-radius: 12px;
        }
    </style>
</head>

<body>

<div class="container mt-4">
    <h2 class="fw-bold text-center mb-4">Meu Carrinho</h2>

    <div class="cart-card shadow">

        <!-- Produto 1 - Exemplo visual -->
        <div class="row mb-3 border-bottom pb-3">
            <div class="col-md-2">
                <img src="img/camisa1.jpg" class="img-fluid rounded">
            </div>

            <div class="col-md-6">
                <h5 class="fw-bold">Camisa do Barcelona 2024</h5>
                <p class="text-muted">Tamanho: M</p>
            </div>

            <div class="col-md-2">
                <input type="number" min="1" class="form-control" value="1">
            </div>

            <div class="col-md-2 text-end">
                <p class="fw-bold text-success">R$ 279,90</p>
            </div>
        </div>

        <!-- Produto 2 Exemplo -->
        <div class="row mb-3 border-bottom pb-3">
            <div class="col-md-2">
                <img src="img/camisa2.jpg" class="img-fluid rounded">
            </div>

            <div class="col-md-6">
                <h5 class="fw-bold">Camisa do Real Madrid 2024</h5>
                <p class="text-muted">Tamanho: G</p>
            </div>

            <div class="col-md-2">
                <input type="number" min="1" class="form-control" value="2">
            </div>

            <div class="col-md-2 text-end">
                <p class="fw-bold text-success">R$ 299,90</p>
            </div>
        </div>

        <h4 class="text-end mt-4 fw-bold">Total: R$ 879,70</h4>

        <button class="btn btn-success mt-3 w-100 fw-bold">Confirmar Compra</button>

    </div>
</div>

</body>
</html>
