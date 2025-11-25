<?php
session_start();

// Caminho correto (pois conexao_login.php está dentro de /php/)
require_once __DIR__ . '/php/conexao_login.php';

// Proteção: somente ADMIN pode acessar
if (!isset($_SESSION['usuario_id']) || ($_SESSION['role'] ?? 'user') !== 'admin') {
    header('Location: login.php');
    exit;
}


// Gera CSRF token simples
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(24));
}
$csrf = $_SESSION['csrf_token'];

// Consulta usuários + lojas
$sql = "SELECT 
            u.id,
            u.nome,
            u.email,
            u.slug,
            u.role,
            l.nome_fantasia,
            l.data_criacao,
            u.created_at
        FROM usuarios u
        LEFT JOIN lojas l ON l.usuario_id = u.id
        ORDER BY u.id DESC";


$stmt = $conn->prepare($sql);
$stmt->execute();
$res = $stmt->get_result();
?>
<!doctype html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Painel Master - Admin</title>
    <link rel="stylesheet" href="css/global.css">

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f7fb;
            padding: 20px;
        }

        .card {
            background: #fff;
            padding: 18px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, .06);
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .logout {
            background: #ff4d4f;
            color: #fff;
            padding: 8px 12px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }

        th {
            background: #fafafa;
        }

        .actions button {
            margin-right: 6px;
        }

        .danger {
            background: #ff4d4f;
            color: #fff;
            border: none;
            padding: 6px 10px;
            border-radius: 4px;
        }

        .primary {
            background: #1677ff;
            color: #fff;
            border: none;
            padding: 6px 10px;
            border-radius: 4px;
        }

        .small {
            padding: 4px 8px;
            font-size: 13px;
        }
    </style>
</head>

<body>

    <div class="card">

        <div class="top-bar">
            <h1>Painel Administrativo — Master</h1>
            <a href="logout.php" class="logout">Sair</a>
        </div>

        <p>Logado como: <strong><?php echo htmlspecialchars($_SESSION['usuario']); ?></strong></p>

        <section style="margin-top:18px">
            <h2>Lista de Lojas / Usuários</h2>

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Slug</th>
                        <th>Loja</th>
                        <th>Template</th>
                        <th>Criado em</th>
                        <th>Ações</th>
                    </tr>
                </thead>

                <tbody>
                    <?php while ($u = $res->fetch_assoc()): ?>
                        <tr>
                            <td><?= $u['id'] ?></td>
                            <td><?= htmlspecialchars($u['nome']) ?></td>
                            <td><?= htmlspecialchars($u['email']) ?></td>
                            <td><?= htmlspecialchars($u['slug']) ?></td>
                            <td><?= htmlspecialchars($u['titulo_loja'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($u['template_id'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($u['created_at']) ?></td>

                            <td class="actions">

                                <!-- Abrir loja -->
                                <a class="primary small" target="_blank"
                                    href="https://<?= htmlspecialchars($u['slug']) ?>.sualoja.com">
                                    Abrir loja
                                </a>

                                <!-- Editar -->
                                <form style="display:inline" method="GET" action="edit_user.php">
                                    <input type="hidden" name="id" value="<?= $u['id'] ?>">
                                    <button class="small" type="submit">Editar</button>
                                </form>

                                <!-- Excluir -->
                                <form style="display:inline" method="POST" action="admin_actions.php"
                                    onsubmit="return confirm('Excluir usuário e TODOS os dados dele?');">
                                    <input type="hidden" name="action" value="delete_user">
                                    <input type="hidden" name="id" value="<?= $u['id'] ?>">
                                    <input type="hidden" name="csrf" value="<?= $csrf ?>">
                                    <button class="danger small" type="submit">Excluir</button>
                                </form>


                            </td>

                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

        </section>
    </div>

</body>

</html>

<?php
$stmt->close();
$conn->close();
?>