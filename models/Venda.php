<?php

class Venda implements iDao
{
    private int $id;
    private string $data_venda;
    private float $valor_venda;
    private Cliente $cliente;
    private array $itens_venda = [];

    /**
     * Getters
     */

    public function getId(): int
    {
        return $this->id;
    }

    public function getDataVenda(): string
    {
        return $this->data_venda;
    }

    public function getValorVenda(): float
    {
        return $this->valor_venda;
    }

    public function getCliente(): Cliente
    {
        return $this->cliente;
    }

    public function getItensVenda(): array
    {
        return $this->itens_venda;
    }

    /**
     * Setters
     */

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setDataVenda(string $data_venda): void
    {
        $this->data_venda = $data_venda;
    }

    public function setValorVenda(float $valor_venda): void
    {
        $this->valor_venda = $valor_venda;
    }

    public function setCliente(Cliente $cliente): void
    {
        $this->cliente = $cliente;
    }

    public function setItensVenda(array $itens_venda): void
    {
        $this->itens_venda = $itens_venda;
    }

    /**
     * Methods
     */

    public function adicionarItemVenda(Produto $produto, int $quantidade): void
    {
        $item_venda = new ItemVenda();
        $item_venda->setProduto($produto);
        $item_venda->setQuantidade($quantidade);
        $this->itens_venda[] = $item_venda;
    }

    /**
     * Database Connection
     */

    private static function getPDO(): PDO
    {
        return Database::getInstance();
    }

    /**
     * iDao Methods
     */

    public static function create(array $data): int
    {
        if (empty($data['usuario_id']) || empty($data['valor_total'])) {
            throw new Exception("Usuário e valor total são obrigatórios");
        }

        $sql = "INSERT INTO vendas (usuario_id, data_venda, valor_total) 
                VALUES (:usuario_id, :data_venda, :valor_total)";

        try {
            $pdo = self::getPDO();
            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                ':usuario_id' => $data['usuario_id'],
                ':data_venda' => $data['data_venda'] ?? date('Y-m-d H:i:s'),
                ':valor_total' => $data['valor_total']
            ]);

            $venda_id = (int) $pdo->lastInsertId();

            if (!empty($data['itens']) && is_array($data['itens'])) {
                self::salvarItensVenda($venda_id, $data['itens']);
            }

            return $venda_id;
        } catch (PDOException $e) {
            throw new Exception("Erro ao criar venda: " . $e->getMessage());
        }
    }

    public static function read(int $id): ?array
    {
        $sql = "SELECT v.*, u.nome as usuario_nome, u.email as usuario_email
                FROM vendas v
                INNER JOIN usuarios u ON v.usuario_id = u.id
                WHERE v.id = :id";

        try {
            $pdo = self::getPDO();
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':id' => $id]);
            $venda = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$venda) {
                return null;
            }

            $venda['itens'] = self::buscarItensVenda($id);
            return $venda;
        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar venda: " . $e->getMessage());
        }
    }

    public static function update(int $id, array $data): bool
    {
        $fields = [];
        $params = [':id' => $id];

        if (isset($data['data_venda'])) {
            $fields[] = "data_venda = :data_venda";
            $params[':data_venda'] = $data['data_venda'];
        }

        if (isset($data['valor_total'])) {
            $fields[] = "valor_total = :valor_total";
            $params[':valor_total'] = $data['valor_total'];
        }

        if (isset($data['usuario_id'])) {
            $fields[] = "usuario_id = :usuario_id";
            $params[':usuario_id'] = $data['usuario_id'];
        }

        if (empty($fields)) {
            return false;
        }

        $sql = "UPDATE vendas SET " . implode(', ', $fields) . " WHERE id = :id";

        try {
            $pdo = self::getPDO();
            $stmt = $pdo->prepare($sql);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            throw new Exception("Erro ao atualizar venda: " . $e->getMessage());
        }
    }

    public static function delete(int $id): bool
    {
        $sql_itens = "DELETE FROM itens_venda WHERE venda_id = :venda_id";
        $sql_venda = "DELETE FROM vendas WHERE id = :id";

        try {
            $pdo = self::getPDO();
            $pdo->beginTransaction();

            $stmt_itens = $pdo->prepare($sql_itens);
            $stmt_itens->execute([':venda_id' => $id]);

            $stmt_venda = $pdo->prepare($sql_venda);
            $stmt_venda->execute([':id' => $id]);

            $pdo->commit();
            return true;
        } catch (PDOException $e) {
            if (isset($pdo) && $pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw new Exception("Erro ao deletar venda: " . $e->getMessage());
        }
    }

    public static function findAll(): array
    {
        $sql = "SELECT v.*, u.nome as usuario_nome, u.email as usuario_email
                FROM vendas v
                INNER JOIN usuarios u ON v.usuario_id = u.id
                ORDER BY v.data_venda DESC, v.id DESC";

        try {
            $pdo = self::getPDO();
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $vendas = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($vendas as &$venda) {
                $venda['itens'] = self::buscarItensVenda($venda['id']);
            }

            return $vendas;
        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar todas as vendas: " . $e->getMessage());
        }
    }

    public static function count(): int
    {
        $sql = "SELECT COUNT(*) as total FROM vendas";

        try {
            $pdo = self::getPDO();
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int) $result['total'];
        } catch (PDOException $e) {
            throw new Exception("Erro ao contar vendas: " . $e->getMessage());
        }
    }

    /**
     * Helper Methods
     */
    private static function salvarItensVenda(int $venda_id, array $itens): void
    {
        $sql = "INSERT INTO itens_venda (venda_id, produto_id, quantidade, preco_unit)
                VALUES (:venda_id, :produto_id, :quantidade, :preco_unit)";

        try {
            $pdo = self::getPDO();
            $stmt = $pdo->prepare($sql);

            foreach ($itens as $item) {
                $stmt->execute([
                    ':venda_id' => $venda_id,
                    ':produto_id' => $item['produto_id'],
                    ':quantidade' => $item['quantidade'],
                    ':preco_unit' => $item['preco_unit']
                ]);
            }
        } catch (PDOException $e) {
            throw new Exception("Erro ao salvar itens da venda: " . $e->getMessage());
        }
    }

    private static function buscarItensVenda(int $venda_id): array
    {
        $sql = "SELECT iv.*, p.nome as produto_nome, p.descricao as produto_descricao
                FROM itens_venda iv
                INNER JOIN produtos p ON iv.produto_id = p.id
                WHERE iv.venda_id = :venda_id";

        try {
            $pdo = self::getPDO();
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':venda_id' => $venda_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar itens da venda: " . $e->getMessage());
        }
    }
}
