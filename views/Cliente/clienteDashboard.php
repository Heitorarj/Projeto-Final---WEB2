<?php
    include "headerCliente.php"; 
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Produtos - Modelo</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f6f6f6;
        }
        .product-card {
            background: white;
            border-radius: 12px;
            padding: 15px;
            transition: .2s;
        }
        .product-card:hover {
            transform: scale(1.02);
            box-shadow: 0px 0px 12px rgba(0,0,0,0.15);
        }
        .product-img {
            width: 100%;
            height: 260px;
            object-fit: cover;
            border-radius: 10px;
        }
        .btn-buy {
            background: #111;
            color: white;
            font-weight: bold;
        }
        .btn-buy:hover {
            background: #333;
        }
    </style>
</head>
<body>

<div style="margin-top:90px;"></div>

<div class="container">

    <h2 class="fw-bold text-center mb-4">Produtos Disponíveis</h2>


    <div class="row mb-4">
        <div class="col-md-4">
            <label class="fw-bold">Filtrar por categoria:</label>
            <select id="categoriaFiltro" class="form-select">
                <option value="todas">Todas</option>
                <option value="brasileirao">Brasileirão</option>
                <option value="europeus">Times Europeus</option>
                <option value="selecoes">Seleções</option>
            </select>
        </div>
    </div>

    <div class="row g-4">

        <!-- Produto 1 -->
        <div class="col-md-4 produto" data-cat="brasileirao">
            <div class="product-card">
                <img src="images/camisa1.jpg" class="product-img">
                <h4 class="mt-3">Flamengo 24/25</h4>
                <p class="text-success fw-bold">R$ 249,90</p>

                <button class="btn btn-buy w-100">Adicionar ao Carrinho</button>
            </div>
        </div>

        <!-- Produto 2 -->
        <div class="col-md-4 produto" data-cat="europeus">
            <div class="product-card">
                <img src="images/camisa2.jpg" class="product-img">
                <h4 class="mt-3">Real Madrid 24/25</h4>
                <p class="text-success fw-bold">R$ 299,90</p>

                <button class="btn btn-buy w-100">Adicionar ao Carrinho</button>
            </div>
        </div>

        <!-- Produto 3 -->
        <div class="col-md-4 produto" data-cat="selecoes">
            <div class="product-card">
                <img src="images/camisa3.jpg" class="product-img">
                <h4 class="mt-3">Brasil 2024</h4>
                <p class="text-success fw-bold">R$ 279,90</p>

                <button class="btn btn-buy w-100">Adicionar ao Carrinho</button>
            </div>
        </div>

    </div>
</div>

<script>
    document.getElementById("categoriaFiltro").addEventListener("change", function () {

        let categoria = this.value;
        let produtos = document.querySelectorAll(".produto");

        produtos.forEach(prod => {
            let cat = prod.getAttribute("data-cat");

            if (categoria === "todas" || categoria === cat) {
                prod.style.display = "block";
            } else {
                prod.style.display = "none";
            }
        });
    });
</script>

</body>
</html>
