<?php

    include "headerCliente.php";
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Dashboard - FUT STORE</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f4f4f4;
        }
        .product-card {
            background: white;
            border-radius: 14px;
            padding: 20px;
            transition: .2s;
        }
        .product-card:hover {
            transform: scale(1.02);
            box-shadow: 0px 0px 12px rgba(0,0,0,0.2);
        }
        .product-img {
            width: 100%;
            height: 270px;
            object-fit: cover;
            border-radius: 10px;
        }
        .btn-buy {
            background: #0a0a23;
            color: white;
            font-weight: bold;
        }
        .btn-buy:hover {
            background: #1b1b4d;
        }
    </style>
</head>

<body>

<div style="margin-top:90px;"></div>

<div class="container mt-4">
    <h1 class="fw-bold text-center mb-4">Loja de Camisas</h1>

    <div class="row g-4">

    <!-- temporario pra ajustes -->
        <div class="col-md-4">
            <div class="product-card shadow">
                <img src="images/camisa1.jpg" class="product-img" alt="Camisa 1">
                <h4 class="mt-3">Camisa Flamengo 24/25</h4>
                <p class="text-success fw-bold">R$ 249,90</p>

                <form action="adicionar_carrinho.php" method="POST">
                    <input type="hidden" name="produto_id" value="1">

                    <label class="fw-bold">Quantidade</label>
                    <input type="number" class="form-control mb-3" name="quantidade" min="1" value="1" required>

                    <button class="btn btn-buy w-100">Adicionar ao Carrinho</button>
                </form>
            </div>
        </div>

        <div class="col-md-4">
            <div class="product-card shadow">
                <img src="images/camisa2.jpg" class="product-img" alt="Camisa 2">
                <h4 class="mt-3">Camisa Real Madrid 24/25</h4>
                <p class="text-success fw-bold">R$ 299,90</p>

                <form action="adicionar_carrinho.php" method="POST">
                    <input type="hidden" name="produto_id" value="2">

                    <label class="fw-bold">Quantidade</label>
                    <input type="number" class="form-control mb-3" name="quantidade" min="1" value="1" required>

                    <button class="btn btn-buy w-100">Adicionar ao Carrinho</button>
                </form>
            </div>
        </div>

        <div class="col-md-4">
            <div class="product-card shadow">
                <img src="images/camisa3.jpg" class="product-img" alt="Camisa 3">
                <h4 class="mt-3">Camisa Manchester City 24/25</h4>
                <p class="text-success fw-bold">R$ 279,90</p>

                <form action="adicionar_carrinho.php" method="POST">
                    <input type="hidden" name="produto_id" value="3">

                    <label class="fw-bold">Quantidade</label>
                    <input type="number" class="form-control mb-3" name="quantidade" min="1" value="1" required>

                    <button class="btn btn-buy w-100">Adicionar ao Carrinho</button>
                </form>
            </div>
        </div>

    </div>
</div>

</body>
</html>
