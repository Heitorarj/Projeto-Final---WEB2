<?php
    include "headerAdmin.php";
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f4f6f9;
        }

        .admin-container {
            margin-top: 120px; /* espaço abaixo do header */
        }

        .admin-card {
            border-radius: 15px;
            padding: 35px 25px;
            transition: 0.3s;
            cursor: pointer;
        }

        .admin-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .admin-icon {
            font-size: 60px;
            color: #4A90E2;
            margin-bottom: 20px;
        }

        .btn-dashboard {
            margin-top: 15px;
            padding: 12px;
            font-size: 18px;
            border-radius: 10px;
        }
    </style>

    <!-- Ícones -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

</head>

<body>

    <div class="container admin-container">

        <h2 class="text-center mb-5">Painel Administrativo</h2>

        <div class="row justify-content-center">

            <div class="col-md-4 mb-4">
                <div class="card admin-card shadow-sm text-center">
                    <i class="fas fa-box-open admin-icon"></i>
                    <h4>Gerenciar Produtos</h4>
                    <p>Cadastrar, editar e visualizar produtos.</p>

                    <a href="produtoCadastro.php" class="btn btn-primary w-100 btn-dashboard">
                        Acessar Produtos
                    </a>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card admin-card shadow-sm text-center">
                    <i class="fas fa-shopping-cart admin-icon"></i>
                    <h4>Relatório de Vendas</h4>
                    <p>Consultar vendas realizadas e seus detalhes.</p>

                    <a href="adminVendas.php" class="btn btn-success w-100 btn-dashboard">
                        Ver Vendas
                    </a>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card admin-card shadow-sm text-center">
                    <i class="fas fa-tags admin-icon"></i>
                    <h4>Gerenciar Categorias</h4>
                    <p>Criar, editar e administrar categorias de produtos.</p>

                    <a href="categoriaCadastro.php" class="btn btn-warning w-100 btn-dashboard">
                        Acessar Categorias
                    </a>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card admin-card shadow-sm text-center">
                    <i class="fas fa-industry admin-icon"></i>
                    <h4>Gerenciar Fabricantes</h4>
                    <p>Adicionar e editar fabricantes cadastrados.</p>

                    <a href="fabricanteCadastro.php" class="btn btn-dark w-100 btn-dashboard">
                        Acessar Fabricantes
                    </a>
                </div>
            </div>

        </div>

    </div>

</body>
</html>
