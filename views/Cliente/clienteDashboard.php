<?php
session_start();
include "headerCliente.php";

require_once __DIR__ . '/../../controllers/ProdutoController.php';
require_once __DIR__ . '/../../controllers/CategoriaController.php';

$produtos = ProdutoController::buscarComEstoque();
$categorias = CategoriaController::listar();

$categoria_filtro = $_GET['categoria'] ?? 'todas';
if ($categoria_filtro !== 'todas') {
    $produtos = ProdutoController::buscarPorCategoria((int)$categoria_filtro);
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Produtos - FUT STORE</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }

        .product-card {
            background: white;
            border-radius: 16px;
            padding: 20px;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            height: 100%;
            display: flex;
            flex-direction: column;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }

        .product-img {
            width: 100%;
            height: 200px;
            object-fit: contain;
            border-radius: 12px;
            background: #f8f9fa;
            padding: 10px;
            margin-bottom: 15px;
        }

        .btn-buy {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-weight: bold;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .btn-buy:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
            color: white;
            transform: scale(1.05);
        }

        .caracteristica-badge {
            background: #e9ecef;
            color: #495057;
            border: 1px solid #dee2e6;
            margin: 2px;
            padding: 4px 8px;
            font-size: 0.75rem;
            border-radius: 12px;
        }

        .caracteristicas-container {
            max-height: 150px;
            overflow-y: auto;
            padding: 8px;
            background: #f8f9fa;
            border-radius: 8px;
            margin-top: 10px;
        }

        .estoque-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            z-index: 2;
        }

        .filtro-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
        }

        .descricao-truncada {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            min-height: 40px;
        }

        .btn-toggle-caracteristicas {
            background: transparent;
            border: none;
            color: #6c757d;
            font-size: 0.8rem;
            padding: 0;
            text-decoration: underline;
        }

        .btn-toggle-caracteristicas:hover {
            color: #495057;
        }

        .caracteristica-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 4px 0;
            border-bottom: 1px solid #dee2e6;
        }

        .caracteristica-item:last-child {
            border-bottom: none;
        }

        .caracteristica-nome {
            font-weight: 500;
            color: #495057;
        }

        .caracteristica-valor {
            color: #6c757d;
        }
    </style>
</head>

<body>

    <div style="margin-top:90px;"></div>

    <div class="container">

        <h2 class="fw-bold text-center mb-4" style="color: #333;">Produtos Disponíveis</h2>

        <?php if (isset($_SESSION['sucesso'])): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle me-2"></i>
                <?php echo $_SESSION['sucesso'];
                unset($_SESSION['sucesso']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['erro'])): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-exclamation-triangle me-2"></i>
                <?php echo $_SESSION['erro'];
                unset($_SESSION['erro']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Filtro de Categorias -->
        <div class="filtro-card">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <label class="fw-bold mb-2" style="color: #555;">Filtrar por categoria:</label>
                    <select id="categoriaFiltro" class="form-select" onchange="filtrarCategoria(this.value)">
                        <option value="todas" <?php echo $categoria_filtro === 'todas' ? 'selected' : ''; ?>>Todas as Categorias</option>
                        <?php foreach ($categorias as $cat): ?>
                            <option value="<?php echo $cat['id']; ?>" <?php echo $categoria_filtro == $cat['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cat['nome']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-8">
                    <div class="d-flex flex-wrap gap-2 mt-3 mt-md-0">
                        <span class="badge bg-primary-subtle text-primary-emphasis border border-primary-subtle p-2">
                            <i class="bi bi-grid-3x3-gap"></i> <?php echo count($produtos); ?> produtos encontrados
                        </span>
                        <span class="badge bg-success-subtle text-success-emphasis border border-success-subtle p-2">
                            <i class="bi bi-box-seam"></i> Em estoque
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <?php if (empty($produtos)): ?>
            <div class="alert alert-info text-center py-5">
                <i class="bi bi-search display-4 mb-3"></i>
                <h4>Nenhum produto encontrado</h4>
                <p class="mb-0">Tente selecionar outra categoria ou volte mais tarde.</p>
            </div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($produtos as $produto): ?>
                    <div class="col-md-4 col-lg-3">
                        <div class="product-card">
                            <div class="position-relative">
                                <?php if (!empty($produto['imagem'])): ?>
                                    <img src="<?php echo htmlspecialchars($produto['imagem']); ?>"
                                        class="product-img"
                                        alt="<?php echo htmlspecialchars($produto['nome']); ?>"
                                        onerror="this.src='https://via.placeholder.com/300x200?text=<?php echo urlencode(substr($produto['nome'], 0, 20)); ?>'">
                                <?php else: ?>
                                    <img src="https://via.placeholder.com/300x200?text=<?php echo urlencode(substr($produto['nome'], 0, 20)); ?>"
                                        class="product-img"
                                        alt="<?php echo htmlspecialchars($produto['nome']); ?>">
                                <?php endif; ?>

                                <!-- Badge de Estoque -->
                                <span class="badge <?php echo $produto['estoque'] > 10 ? 'bg-success' : ($produto['estoque'] > 0 ? 'bg-warning' : 'bg-danger'); ?> estoque-badge">
                                    <?php echo $produto['estoque']; ?> uni.
                                </span>
                            </div>

                            <h5 class="fw-bold mt-2 mb-2" style="color: #333;"><?php echo htmlspecialchars($produto['nome']); ?></h5>

                            <!-- Descrição -->
                            <?php if (!empty($produto['descricao'])): ?>
                                <p class="text-muted small descricao-truncada mb-3">
                                    <?php echo htmlspecialchars($produto['descricao']); ?>
                                </p>
                            <?php endif; ?>

                            <!-- Categoria e Fabricante -->
                            <div class="mb-3">
                                <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle">
                                    <?php echo htmlspecialchars($produto['categoria_nome'] ?? 'Sem categoria'); ?>
                                </span>
                                <?php if (!empty($produto['fabricante_nome'])): ?>
                                    <span class="badge bg-secondary-subtle text-secondary-emphasis border border-secondary-subtle">
                                        <?php echo htmlspecialchars($produto['fabricante_nome']); ?>
                                    </span>
                                <?php endif; ?>
                            </div>

                            <!-- Características -->
                            <?php if (!empty($produto['caracteristicas'])): ?>
                                <div class="mt-auto mb-3">
                                    <button type="button"
                                        class="btn-toggle-caracteristicas mb-2"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#caracteristicas<?php echo $produto['id']; ?>">
                                        <i class="bi bi-chevron-down"></i>
                                        <span id="caracteristicasCount<?php echo $produto['id']; ?>">
                                            <?php echo count($produto['caracteristicas']); ?> características
                                        </span>
                                    </button>

                                    <div class="collapse" id="caracteristicas<?php echo $produto['id']; ?>">
                                        <div class="caracteristicas-container">
                                            <?php foreach ($produto['caracteristicas'] as $caracteristica): ?>
                                                <div class="caracteristica-item">
                                                    <span class="caracteristica-nome">
                                                        <?php echo htmlspecialchars($caracteristica['nome']); ?>:
                                                    </span>
                                                    <span class="caracteristica-valor">
                                                        <?php echo htmlspecialchars($caracteristica['valor']); ?>
                                                    </span>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="mt-auto mb-3">
                                    <small class="text-muted">Sem características cadastradas</small>
                                </div>
                            <?php endif; ?>

                            <div class="mt-auto">
                                <!-- Preço -->
                                <div class="mb-3">
                                    <span class="text-success fw-bold h4">
                                        R$ <?php echo number_format($produto['preco_venda'], 2, ',', '.'); ?>
                                    </span>
                                    <?php if ($produto['preco_custo'] > 0): ?>
                                        <small class="text-muted d-block">
                                            Valor custo: R$ <?php echo number_format($produto['preco_custo'], 2, ',', '.'); ?>
                                        </small>
                                    <?php endif; ?>
                                </div>

                                <!-- Formulário de Compra -->
                                <form method="POST" action="../../actions/carrinho/adicionar.php">
                                    <input type="hidden" name="produto_id" value="<?php echo $produto['id']; ?>">
                                    <div class="input-group mb-2">
                                        <input type="number" name="quantidade"
                                            class="form-control border-end-0"
                                            value="1"
                                            min="1"
                                            max="<?php echo $produto['estoque']; ?>"
                                            style="border-radius: 8px 0 0 8px;">
                                        <button type="submit" class="btn btn-buy" style="border-radius: 0 8px 8px 0;">
                                            <i class="bi bi-cart-plus"></i> Adicionar
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function filtrarCategoria(categoriaId) {
            if (categoriaId === 'todas') {
                window.location.href = 'clienteDashboard.php';
            } else {
                window.location.href = 'clienteDashboard.php?categoria=' + categoriaId;
            }
        }

        // Adicionar evento para alternar o ícone do botão de características
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.btn-toggle-caracteristicas').forEach(function(button) {
                button.addEventListener('click', function() {
                    const icon = this.querySelector('i');
                    const countSpan = this.querySelector('span');
                    const targetId = this.getAttribute('data-bs-target');
                    const targetElement = document.querySelector(targetId);

                    if (targetElement.classList.contains('show')) {
                        icon.classList.remove('bi-chevron-up');
                        icon.classList.add('bi-chevron-down');
                        countSpan.textContent = this.getAttribute('data-count') + ' características';
                    } else {
                        icon.classList.remove('bi-chevron-down');
                        icon.classList.add('bi-chevron-up');
                        countSpan.textContent = 'Ocultar características';
                    }
                });
            });

            // Inicializar contagem nos botões
            document.querySelectorAll('.btn-toggle-caracteristicas').forEach(function(button) {
                const count = button.querySelector('span').textContent.split(' ')[0];
                button.setAttribute('data-count', count);
            });
        });
    </script>

</body>

</html>