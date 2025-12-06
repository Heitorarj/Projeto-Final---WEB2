<?php

class Usuario implements iDao
{
    private int $id;
    private int $tipo;
    private string $nome;
    private string $email;
    private string $senha;

    private static function getPDO(): PDO
    {
        return Database::getInstance();
    }

    /** 
     * Getters 
     */

    public function getId(): int
    {
        return $this->id;
    }

    public function getTipo(): int
    {
        return $this->tipo;
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getSenha(): string
    {
        return $this->senha;
    }

    /** 
     * Setters 
     */

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setTipo(int $tipo): void
    {
        $this->tipo = $tipo;
    }

    public function setNome(string $nome): void
    {
        $this->nome = $nome;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setSenha(string $senha): void
    {
        $this->senha = $senha;
    }

    /**
     * DAO Methods
     */

    public static function create(array $data): int
    {
        // Validações
        if (empty($data['email']) || empty($data['senha'])) {
            throw new Exception("Email e senha são obrigatórios");
        }

        $sql = "INSERT INTO usuarios (nome, email, senha, data_criacao) 
                VALUES (:nome, :email, :senha, NOW())";

        try {
            $pdo = self::getPDO();
            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                ':nome' => $data['nome'] ?? '',
                ':email' => $data['email'],
                ':senha' => password_hash($data['senha'], PASSWORD_DEFAULT)
            ]);

            return (int) $pdo->lastInsertId();
        } catch (PDOException $e) {
            // Verifica se é erro de duplicado (email único)
            if ($e->getCode() == 23000) {
                throw new Exception("Email já cadastrado");
            }
            throw new Exception("Erro ao criar usuário: " . $e->getMessage());
        }
    }

    public static function read(int $id): ?array
    {
        $sql = "SELECT id, nome, email, data_criacao 
                FROM usuarios 
                WHERE id = :id";

        try {
            $pdo = self::getPDO();
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':id' => $id]);
            $result = $stmt->fetch();

            return $result ?: null;
        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar usuário: " . $e->getMessage());
        }
    }

    public static function update(int $id, array $data): bool
    {
        // Construir query dinamicamente para não sobrescrever senha
        $fields = [];
        $params = [':id' => $id];

        if (isset($data['nome'])) {
            $fields[] = "nome = :nome";
            $params[':nome'] = $data['nome'];
        }

        if (isset($data['email'])) {
            $fields[] = "email = :email";
            $params[':email'] = $data['email'];
        }

        if (isset($data['senha'])) {
            $fields[] = "senha = :senha";
            $params[':senha'] = password_hash($data['senha'], PASSWORD_DEFAULT);
        }

        if (empty($fields)) {
            return false; // Nada para atualizar
        }

        $sql = "UPDATE usuarios SET " . implode(', ', $fields) . " WHERE id = :id";

        try {
            $pdo = self::getPDO();
            $stmt = $pdo->prepare($sql);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            throw new Exception("Erro ao atualizar usuário: " . $e->getMessage());
        }
    }

    public static function delete(int $id): bool
    {
        $sql = "DELETE FROM usuarios WHERE id = :id";

        try {
            $pdo = self::getPDO();
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            throw new Exception("Erro ao deletar usuário: " . $e->getMessage());
        }
    }

    public static function findAll(): array
    {
        $sql = "SELECT id, nome, email, data_criacao 
                FROM usuarios 
                ORDER BY id DESC";

        try {
            $pdo = self::getPDO();
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar todos os usuários: " . $e->getMessage());
        }
    }

    public static function count(): int
    {
        $sql = "SELECT COUNT(*) as total FROM usuarios";

        try {
            $pdo = self::getPDO();
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch();
            return (int) $result['total'];
        } catch (PDOException $e) {
            throw new Exception("Erro ao contar usuários: " . $e->getMessage());
        }
    }

    /**
     * Additional Methods
     */

    public static function login(string $email, string $senha): ?array
    {
        $sql = "SELECT id, nome, email, senha, criado_em 
                FROM usuario 
                WHERE email = :email";

        try {
            $pdo = self::getPDO();
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':email' => $email]);
            $usuario = $stmt->fetch();

            if (!$usuario || !password_verify($senha, $usuario['senha'])) {
                return null;
            }

            // Remove a senha do retorno
            unset($usuario['senha']);
            return $usuario;
        } catch (PDOException $e) {
            throw new Exception("Erro ao fazer login: " . $e->getMessage());
        }
    }
}
