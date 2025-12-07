<?php
require_once __DIR__ . '/../interfaces/iDao.php';
class Fabricante implements iDao
{
    private int $id;
    private string $nome;
    private string $site;

    /**
     * Getters
     */

    public function getId(): int
    {
        return $this->id;
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function getSite(): string
    {
        return $this->site;
    }

    /**
     * Setters
     */

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setNome(string $nome): void
    {
        $this->nome = $nome;
    }

    public function setSite(string $site): void
    {
        $this->site = $site;
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
        if (empty($data['nome'])) {
            throw new Exception("Nome do fabricante é obrigatório");
        }

        $sql = "INSERT INTO fabricantes (nome, site) 
                VALUES (:nome, :site)";

        try {
            $pdo = self::getPDO();
            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                ':nome' => $data['nome'],
                ':site' => $data['site'] ?? ''
            ]);

            return (int) $pdo->lastInsertId();
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                throw new Exception("Fabricante com este nome já existe");
            }
            throw new Exception("Erro ao criar fabricante: " . $e->getMessage());
        }
    }

    public static function read(int $id): ?array
    {
        $sql = "SELECT id, nome, site 
                FROM fabricantes 
                WHERE id = :id";

        try {
            $pdo = self::getPDO();
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':id' => $id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result ?: null;
        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar fabricante: " . $e->getMessage());
        }
    }

    public static function update(int $id, array $data): bool
    {
        $fields = [];
        $params = [':id' => $id];

        if (isset($data['nome'])) {
            $fields[] = "nome = :nome";
            $params[':nome'] = $data['nome'];
        }

        if (isset($data['site'])) {
            $fields[] = "site = :site";
            $params[':site'] = $data['site'];
        }

        if (empty($fields)) {
            return false;
        }

        $sql = "UPDATE fabricantes SET " . implode(', ', $fields) . " WHERE id = :id";

        try {
            $pdo = self::getPDO();
            $stmt = $pdo->prepare($sql);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                throw new Exception("Fabricante com este nome já existe");
            }
            throw new Exception("Erro ao atualizar fabricante: " . $e->getMessage());
        }
    }

    public static function delete(int $id): bool
    {
        $sql_check = "SELECT COUNT(*) as total FROM produtos WHERE fabricante_id = :id";
        $sql_delete = "DELETE FROM fabricantes WHERE id = :id";

        try {
            $pdo = self::getPDO();

            $stmt_check = $pdo->prepare($sql_check);
            $stmt_check->execute([':id' => $id]);
            $result = $stmt_check->fetch(PDO::FETCH_ASSOC);

            if ((int)$result['total'] > 0) {
                throw new Exception("Não é possível excluir o fabricante pois existem produtos associados a ele.");
            }

            $stmt = $pdo->prepare($sql_delete);
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            throw new Exception("Erro ao deletar fabricante: " . $e->getMessage());
        }
    }

    public static function findAll(): array
    {
        $sql = "SELECT id, nome, site 
                FROM fabricantes 
                ORDER BY nome ASC";

        try {
            $pdo = self::getPDO();
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar todos os fabricantes: " . $e->getMessage());
        }
    }

    public static function count(): int
    {
        $sql = "SELECT COUNT(*) as total FROM fabricantes";

        try {
            $pdo = self::getPDO();
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int) $result['total'];
        } catch (PDOException $e) {
            throw new Exception("Erro ao contar fabricantes: " . $e->getMessage());
        }
    }
}
