<?php
session_start();
include "headerAdmin.php";

require_once __DIR__ . '/../../controllers/ProdutoController.php';

$produtos = ProdutoController::listar();
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Produtos Cadastrados - Admin</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        body {
            background: #f4f4f4;
            padding: 40px;
        }

        .produto-card {
            border-radius: 12px;
            transition: transform 0.2s, box-shadow 0.2s;
            height: 100%;
        }

        .produto-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15) !important;
        }

        .produto-img {
            height: 250px;
            object-fit: cover;
            border-bottom: 1px solid #eee;
        }

        .badge-estoque {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 0.8rem;
        }

        .caracteristica-badge {
            background: #e9ecef;
            color: #495057;
            border: 1px solid #dee2e6;
            margin: 2px;
            padding: 4px 8px;
            font-size: 0.75rem;
        }

        .caracteristicas-container {
            max-height: 100px;
            overflow-y: auto;
        }

        .descricao-truncada {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
</head>

<body>

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="fw-bold">Produtos Cadastrados</h1>

            <div>
                <a href="adminDashboard.php" class="btn btn-primary fw-bold px-4 me-2">
                    <i class="bi bi-plus-circle"></i> Cadastrar Novo
                </a>
                <span class="badge bg-secondary"><?php echo count($produtos); ?> produtos</span>
            </div>
        </div>

        <?php if (isset($_SESSION['sucesso'])): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?php echo $_SESSION['sucesso'];
                unset($_SESSION['sucesso']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['erro'])): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <?php echo $_SESSION['erro'];
                unset($_SESSION['erro']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (empty($produtos)): ?>
            <div class="alert alert-info text-center py-5">
                <h4 class="mb-3"><i class="bi bi-inbox"></i> Nenhum produto cadastrado</h4>
                <p class="mb-0">Comece cadastrando seu primeiro produto</p>
                <a href="adminDashboard.php" class="btn btn-primary mt-3">Cadastrar Primeiro Produto</a>
            </div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($produtos as $produto): ?>
                    <div class="col-md-4 col-lg-3">
                        <div class="card shadow produto-card">
                            <div class="position-relative">
                                <?php if (!empty($produto['imagem'])): ?>
                                    <img src="<?php echo htmlspecialchars($produto['imagem']); ?>"
                                        class="card-img-top produto-img"
                                        alt="<?php echo htmlspecialchars($produto['nome']); ?>"
                                        onerror="this.src='https://via.placeholder.com/300x250?text=Imagem+Indispon%C3%ADvel'">
                                <?php else: ?>
                                    <img src="https://via.placeholder.com/300x250?text=Sem+Imagem"
                                        class="card-img-top produto-img"
                                        alt="Sem imagem">
                                <?php endif; ?>

                                <!-- Badge de Estoque -->
                                <span class="badge <?php echo $produto['estoque'] > 10 ? 'bg-success' : ($produto['estoque'] > 0 ? 'bg-warning' : 'bg-danger'); ?> badge-estoque">
                                    <?php echo $produto['estoque']; ?> em estoque
                                </span>
                            </div>

                            <div class="card-body d-flex flex-column">
                                <h6 class="card-title mb-2 fw-bold"><?php echo htmlspecialchars($produto['nome']); ?></h6>

                                <!-- Descrição -->
                                <?php if (!empty($produto['descricao'])): ?>
                                    <p class="card-text text-muted small descricao-truncada mb-2">
                                        <?php echo htmlspecialchars($produto['descricao']); ?>
                                    </p>
                                <?php endif; ?>

                                <!-- Preços -->
                                <div class="mb-2">
                                    <span class="text-success fw-bold h5">
                                        R$ <?php echo number_format($produto['preco_venda'], 2, ',', '.'); ?>
                                    </span>
                                    <?php if ($produto['preco_custo'] > 0): ?>
                                        <small class="text-muted d-block">
                                            Custo: R$ <?php echo number_format($produto['preco_custo'], 2, ',', '.'); ?>
                                        </small>
                                    <?php endif; ?>
                                </div>

                                <!-- Categoria e Fabricante -->
                                <div class="mb-3">
                                    <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle">
                                        <?php echo htmlspecialchars($produto['categoria_nome'] ?? 'Sem categoria'); ?>
                                    </span>
                                    <span class="badge bg-secondary-subtle text-secondary-emphasis border border-secondary-subtle">
                                        <?php echo htmlspecialchars($produto['fabricante_nome'] ?? 'Sem fabricante'); ?>
                                    </span>
                                </div>

                                <!-- Características -->
                                <?php if (!empty($produto['caracteristicas'])): ?>
                                    <div class="mt-auto mb-3">
                                        <small class="fw-bold text-muted d-block mb-1">Características:</small>
                                        <div class="caracteristicas-container">
                                            <?php
                                            $caracteristicasExibidas = 0;
                                            foreach ($produto['caracteristicas'] as $caracteristica):
                                                if ($caracteristicasExibidas < 3): // Limita a 3 características por card
                                            ?>
                                                    <div class="caracteristica-badge rounded-pill">
                                                        <?php echo htmlspecialchars($caracteristica['nome']); ?>:
                                                        <strong><?php echo htmlspecialchars($caracteristica['valor']); ?></strong>
                                                    </div>
                                            <?php
                                                    $caracteristicasExibidas++;
                                                endif;
                                            endforeach;
                                            ?>
                                            <?php if (count($produto['caracteristicas']) > 3): ?>
                                                <small class="text-muted">
                                                    +<?php echo count($produto['caracteristicas']) - 3; ?> mais
                                                </small>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="mt-auto mb-3">
                                        <small class="text-muted">Sem características cadastradas</small>
                                    </div>
                                <?php endif; ?>

                                <!-- Botões de Ação -->
                                <div class="d-flex gap-2 mt-auto">
                                    <a href="produtoEditar.php?id=<?php echo $produto['id']; ?>"
                                        class="btn btn-outline-primary btn-sm flex-fill">
                                        <i class="bi bi-pencil"></i> Editar
                                    </a>
                                    <a href="../../actions/produto/deletar.php?id=<?php echo $produto['id']; ?>"
                                        class="btn btn-outline-danger btn-sm flex-fill"
                                        onclick="return confirm('Tem certeza que deseja excluir o produto \'<?php echo addslashes($produto['nome']); ?>\'?')">
                                        <i class="bi bi-trash"></i> Excluir
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>

</body>

</html>