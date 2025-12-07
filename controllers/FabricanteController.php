<?php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../interfaces/iDao.php';
require_once __DIR__ . '/../models/Fabricante.php';

class FabricanteController
{
    private static function startSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function listar(): array
    {
        try {
            return Fabricante::findAll();
        } catch (Exception $e) {
            self::startSession();
            $_SESSION['erro'] = $e->getMessage();
            return [];
        }
    }

    public static function buscarPorId(int $id): ?array
    {
        try {
            return Fabricante::read($id);
        } catch (Exception $e) {
            self::startSession();
            $_SESSION['erro'] = $e->getMessage();
            return null;
        }
    }

    public static function criar(): void
    {
        self::startSession();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ../views/Admin/fabricanteCadastro.php');
            exit();
        }

        try {
            $nome = $_POST['nome'] ?? '';
            $site = $_POST['site'] ?? '';

            if (empty($nome)) {
                throw new Exception("Nome do fabricante é obrigatório");
            }

            $data = [
                'nome' => $nome,
                'site' => $site
            ];

            Fabricante::create($data);

            $_SESSION['sucesso'] = "Fabricante cadastrado com sucesso!";
            header('Location: ../../views/Admin/fabricanteCadastro.php');
            exit();

        } catch (Exception $e) {
            $_SESSION['erro'] = $e->getMessage();
            header('Location: ../../views/Admin/adminDashboard.php');
            exit();
        }
    }

    public static function atualizar(int $id): void
    {
        self::startSession();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ../views/Admin/adminDashboard.php');
            exit();
        }

        try {
            $data = [];

            if (isset($_POST['nome'])) {
                $data['nome'] = $_POST['nome'];
            }

            if (isset($_POST['site'])) {
                $data['site'] = $_POST['site'];
            }

            Fabricante::update($id, $data);

            $_SESSION['sucesso'] = "Fabricante atualizado com sucesso!";
            header('Location: ../views/Admin/adminDashboard.php');
            exit();

        } catch (Exception $e) {
            $_SESSION['erro'] = $e->getMessage();
            header('Location: ../views/Admin/adminDashboard.php');
            exit();
        }
    }

    public static function deletar(int $id): void
    {
        self::startSession();

        try {
            Fabricante::delete($id);

            $_SESSION['sucesso'] = "Fabricante excluído com sucesso!";
            header('Location: ../views/Admin/adminDashboard.php');
            exit();

        } catch (Exception $e) {
            $_SESSION['erro'] = $e->getMessage();
            header('Location: ../views/Admin/adminDashboard.php');
            exit();
        }
    }
}