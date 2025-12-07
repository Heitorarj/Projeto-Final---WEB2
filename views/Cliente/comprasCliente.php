<?php
session_start();
include "headerCliente.php";

require_once __DIR__ . '/../../controllers/VendaController.php';
require_once __DIR__ . '/../../controllers/AuthController.php';

$usuario = AuthController::getUsuarioLogado();
$vendas = VendaController::buscarPorUsuario($usuario['id']);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Minhas Compras - FUT STORE</title>

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
        .table-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
        }
        .venda-detalhes {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 8px;
            margin-top: 10px;
        }
    </style>
</head>

<body>

<div class="container mt-4">
    <h2 class="fw-bold text-center mb-4">Minhas Compras</h2>

    <?php if (isset($_SESSION['sucesso'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?php echo $_SESSION['sucesso']; unset($_SESSION['sucesso']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="table-card shadow">

        <?php if (empty($vendas)): ?>
            <div class="text-center py-5">
                <h4 class="text-muted">Você ainda não realizou nenhuma compra</h4>
                <a href="clienteDashboard.php" class="btn btn-dark mt-3">Começar a Comprar</a>
            </div>
        <?php else: ?>
            <table class="table table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>ID da Compra</th>
                        <th>Data</th>
                        <th>Valor Total</th>
                        <th>Itens</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($vendas as $venda): ?>
                        <tr>
                            <td><strong>#<?php echo $venda['id']; ?></strong></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($venda['data_venda'])); ?></td>
                            <td class="text-success fw-bold">R$ <?php echo number_format($venda['valor_total'], 2, ',', '.'); ?></td>
                            <td>
                                <button class="btn btn-sm btn-info" type="button" 
                                        data-bs-toggle="collapse" 
                                        data-bs-target="#detalhes<?php echo $venda['id']; ?>">
                                    Ver Detalhes
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4" class="p-0">
                                <div class="collapse" id="detalhes<?php echo $venda['id']; ?>">
                                    <div class="venda-detalhes">
                                        <h6 class="fw-bold mb-2">Itens da Compra:</h6>
                                        <ul class="mb-0">
                                            <?php if (!empty($venda['itens'])): ?>
                                                <?php foreach ($venda['itens'] as $item): ?>
                                                    <li>
                                                        <strong><?php echo htmlspecialchars($item['produto_nome']); ?></strong> - 
                                                        Quantidade: <?php echo $item['quantidade']; ?> - 
                                                        Preço unitário: R$ <?php echo number_format($item['preco_unitario'], 2, ',', '.'); ?> - 
                                                        Subtotal: R$ <?php echo number_format($item['preco_unit'] * $item['quantidade'], 2, ',', '.'); ?>
                                                    </li>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

    </div>
</div>

</body>
</html>
