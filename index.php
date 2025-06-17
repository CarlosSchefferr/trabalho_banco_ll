<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$host = 'localhost';
$port = '5432';
$dbname = 'sistema_qualidade';
$user = 'postgres';
$password = 'senhacarlos';

$dsn = "pgsql:host=$host;port=$port;dbname=$dbname;user=$user;password=$password";

try {
    $pdo = new PDO($dsn);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Não foi possível conectar ao banco de dados: " . $e->getMessage());
}

function getTableData($pdo, $tableName) {
    $columnToSelect = 'nome';
    $orderByColumn = 'nome';

    if ($tableName === 'origem') {
        $columnToSelect = 'descricao';
    } elseif ($tableName === 'status_rnc') {
        $columnToSelect = 'status';
        $orderByColumn = 'id';
    }

    $sql = "SELECT id, $columnToSelect AS nome FROM $tableName ORDER BY $orderByColumn ASC";
    
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$action = $_POST['action'] ?? '';
$notification = '';

if ($action === 'create') {
    $sql = "INSERT INTO rnc (data_abertura, origem_id, responsavel_id, produto_id, cliente_id, status_id, descricao) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    try {
        $stmt->execute([
            $_POST['data_abertura'],
            $_POST['origem_id'],
            $_POST['responsavel_id'],
            $_POST['produto_id'],
            $_POST['cliente_id'],
            $_POST['status_id'],
            $_POST['descricao']
        ]);
        $notification = ['type' => 'success', 'message' => 'RNC criada com sucesso!'];
    } catch (PDOException $e) {
        $notification = ['type' => 'error', 'message' => 'Erro ao criar RNC: ' . $e->getMessage()];
    }
}

if ($action === 'update') {
    $sql = "UPDATE rnc SET data_abertura = ?, origem_id = ?, responsavel_id = ?, produto_id = ?, cliente_id = ?, status_id = ?, descricao = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    try {
        $stmt->execute([
            $_POST['data_abertura'],
            $_POST['origem_id'],
            $_POST['responsavel_id'],
            $_POST['produto_id'],
            $_POST['cliente_id'],
            $_POST['status_id'],
            $_POST['descricao'],
            $_POST['id']
        ]);
        $notification = ['type' => 'success', 'message' => 'RNC atualizada com sucesso!'];
    } catch (PDOException $e) {
        $notification = ['type' => 'error', 'message' => 'Erro ao atualizar RNC: ' . $e->getMessage()];
    }
}

if ($action === 'delete') {
    $sql = "DELETE FROM rnc WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    try {
        $stmt->execute([$_POST['id']]);
        $notification = ['type' => 'success', 'message' => 'RNC deletada com sucesso!'];
    } catch (PDOException $e) {
        $notification = ['type' => 'error', 'message' => 'Erro ao deletar RNC: ' . $e->getMessage()];
    }
}

$view = $_GET['view'] ?? 'list';
$id = $_GET['id'] ?? null;

$responsaveis = getTableData($pdo, 'responsavel');
$produtos = getTableData($pdo, 'produto');
$clientes = getTableData($pdo, 'cliente');
$origens = getTableData($pdo, 'origem');
$status_list = getTableData($pdo, 'status_rnc');
$rnc_to_edit = null;

if ($view === 'edit' && $id) {
    $stmt = $pdo->prepare("SELECT * FROM rnc WHERE id = ?");
    $stmt->execute([$id]);
    $rnc_to_edit = $stmt->fetch(PDO::FETCH_ASSOC);
}

if ($view === 'list') {
    $stmt = $pdo->query("
        SELECT 
            rnc.id, 
            rnc.data_abertura, 
            rnc.descricao,
            o.descricao as origem,
            p.nome as produto,
            resp.nome as responsavel,
            c.nome as cliente,
            s.status as status
        FROM rnc
        LEFT JOIN origem o ON rnc.origem_id = o.id
        LEFT JOIN produto p ON rnc.produto_id = p.id
        LEFT JOIN responsavel resp ON rnc.responsavel_id = resp.id
        LEFT JOIN cliente c ON rnc.cliente_id = c.id
        LEFT JOIN status_rnc s ON rnc.status_id = s.id
        ORDER BY rnc.data_abertura DESC
    ");
    $rncs = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Qualidade - RNC</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <script src="https://unpkg.com/feather-icons"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #111827;
            color: #d1d5db;
        }
        .form-control, .form-select {
            background-color: #374151;
            color: #f3f4f6;
            border: 1px solid #4b5563;
        }
        .form-control:focus, .form-select:focus {
            background-color: #4b5563;
            color: #fff;
            border-color: #3b82f6;
            box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.25);
        }
        .form-label {
            color: #9ca3af;
        }
        .table {
            --bs-table-bg: #1f2937;
            --bs-table-color: #d1d5db;
            --bs-table-border-color: #374151;
            --bs-table-striped-bg: #374151;
            --bs-table-striped-color: #f3f4f6;
            --bs-table-hover-bg: #4b5563;
            --bs-table-hover-color: #fff;
        }
        .card {
            background-color: #1f2937;
            border: 1px solid #374151;
        }
        .btn-primary {
            background-color: #3b82f6;
            border-color: #3b82f6;
        }
        .btn-primary:hover {
            background-color: #2563eb;
            border-color: #2563eb;
        }
        .modal-content {
            background-color: #1f2937;
            border: 1px solid #4b5563;
        }
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1050;
        }
    </style>
</head>
<body class="p-3 md:p-5">

    <div class="container mx-auto">
        
        <header class="flex justify-between items-center mb-5">
            <h1 class="text-2xl md:text-3xl font-bold text-white">
                <i data-feather="shield" class="inline-block align-middle mr-2"></i>
                Gestão de RNCs
            </h1>
            <?php if ($view !== 'list'): ?>
                <a href="index.php" class="btn btn-secondary">
                    <i data-feather="arrow-left" class="inline-block h-4 w-4"></i>
                    Voltar para a Lista
                </a>
            <?php endif; ?>
        </header>

        <?php if ($notification): ?>
        <div id="notification-alert" class="alert alert-<?php echo $notification['type'] === 'success' ? 'success' : 'danger'; ?> alert-dismissible fade show notification" role="alert">
            <?php echo htmlspecialchars($notification['message']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif; ?>

        <main>
            <?php if ($view === 'list'): ?>
                <div class="card shadow-lg">
                    <div class="card-header flex justify-between items-center">
                        <h2 class="text-xl font-semibold text-gray-200">Registros de Não Conformidade</h2>
                        <a href="index.php?view=create" class="btn btn-primary">
                            <i data-feather="plus" class="inline-block h-4 w-4"></i>
                            Nova RNC
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover align-middle">
                                <thead class="text-xs text-gray-400 uppercase">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">ID</th>
                                        <th scope="col" class="px-6 py-3">Abertura</th>
                                        <th scope="col" class="px-6 py-3">Origem</th>
                                        <th scope="col" class="px-6 py-3">Produto</th>
                                        <th scope="col" class="px-6 py-3">Responsável</th>
                                        <th scope="col" class="px-6 py-3">Status</th>
                                        <th scope="col" class="px-6 py-3">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($rncs)): ?>
                                        <tr>
                                            <td colspan="7" class="text-center py-4">Nenhuma RNC encontrada.</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($rncs as $rnc): ?>
                                        <tr class="border-b border-gray-700">
                                            <th scope="row" class="px-6 py-4 font-medium text-white whitespace-nowrap">#<?php echo htmlspecialchars($rnc['id']); ?></th>
                                            <td class="px-6 py-4"><?php echo htmlspecialchars(date('d/m/Y', strtotime($rnc['data_abertura']))); ?></td>
                                            <td class="px-6 py-4"><?php echo htmlspecialchars($rnc['origem']); ?></td>
                                            <td class="px-6 py-4"><?php echo htmlspecialchars($rnc['produto']); ?></td>
                                            <td class="px-6 py-4"><?php echo htmlspecialchars($rnc['responsavel']); ?></td>
                                            <td class="px-6 py-4">
                                                <span class="badge text-bg-primary rounded-pill"><?php echo htmlspecialchars($rnc['status']); ?></span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <a href="index.php?view=edit&id=<?php echo $rnc['id']; ?>" class="btn btn-sm btn-outline-warning me-2">
                                                    <i data-feather="edit-2" class="h-4 w-4"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-outline-danger delete-btn" data-bs-toggle="modal" data-bs-target="#deleteModal" data-id="<?php echo $rnc['id']; ?>">
                                                    <i data-feather="trash-2" class="h-4 w-4"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            <?php elseif ($view === 'create' || $view === 'edit'): ?>
                <?php $is_edit = $view === 'edit'; ?>
                <div class="card shadow-lg max-w-4xl mx-auto">
                    <div class="card-header">
                        <h2 class="text-xl font-semibold text-gray-200">
                            <?php echo $is_edit ? 'Editar RNC #' . htmlspecialchars($rnc_to_edit['id']) : 'Criar Nova RNC'; ?>
                        </h2>
                    </div>
                    <div class="card-body p-4 md:p-6">
                        <form action="index.php" method="POST">
                            <input type="hidden" name="action" value="<?php echo $is_edit ? 'update' : 'create'; ?>">
                            <?php if ($is_edit): ?>
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($rnc_to_edit['id']); ?>">
                            <?php endif; ?>
                            
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="data_abertura" class="form-label">Data de Abertura</label>
                                    <input type="date" class="form-control" id="data_abertura" name="data_abertura" value="<?php echo htmlspecialchars($rnc_to_edit['data_abertura'] ?? date('Y-m-d')); ?>" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="status_id" class="form-label">Status</label>
                                    <select id="status_id" name="status_id" class="form-select" required>
                                        <?php foreach ($status_list as $status): ?>
                                            <option value="<?php echo $status['id']; ?>" <?php echo (($rnc_to_edit['status_id'] ?? '') == $status['id']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($status['nome']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="origem_id" class="form-label">Origem</label>
                                    <select id="origem_id" name="origem_id" class="form-select" required>
                                        <?php foreach ($origens as $origem): ?>
                                            <option value="<?php echo $origem['id']; ?>" <?php echo (($rnc_to_edit['origem_id'] ?? '') == $origem['id']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($origem['nome']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="produto_id" class="form-label">Produto</label>
                                    <select id="produto_id" name="produto_id" class="form-select" required>
                                        <?php foreach ($produtos as $produto): ?>
                                            <option value="<?php echo $produto['id']; ?>" <?php echo (($rnc_to_edit['produto_id'] ?? '') == $produto['id']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($produto['nome']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="cliente_id" class="form-label">Cliente</label>
                                    <select id="cliente_id" name="cliente_id" class="form-select" required>
                                        <?php foreach ($clientes as $cliente): ?>
                                            <option value="<?php echo $cliente['id']; ?>" <?php echo (($rnc_to_edit['cliente_id'] ?? '') == $cliente['id']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($cliente['nome']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                
                                <div class="col-12">
                                    <label for="responsavel_id" class="form-label">Responsável pela Análise</label>
                                    <select id="responsavel_id" name="responsavel_id" class="form-select" required>
                                        <?php foreach ($responsaveis as $responsavel): ?>
                                            <option value="<?php echo $responsavel['id']; ?>" <?php echo (($rnc_to_edit['responsavel_id'] ?? '') == $responsavel['id']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($responsavel['nome']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-12">
                                    <label for="descricao" class="form-label">Descrição da Não Conformidade</label>
                                    <textarea class="form-control" id="descricao" name="descricao" rows="4" required><?php echo htmlspecialchars($rnc_to_edit['descricao'] ?? ''); ?></textarea>
                                </div>
                            </div>
                            
                            <div class="mt-4 pt-4 border-t border-gray-700 text-end">
                                <a href="index.php" class="btn btn-secondary me-2">Cancelar</a>
                                <button type="submit" class="btn btn-primary">
                                    <?php echo $is_edit ? 'Salvar Alterações' : 'Criar RNC'; ?>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
        </main>
    </div>

    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirmar Exclusão</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Tem certeza que deseja deletar este registro de RNC? Esta ação não pode ser desfeita.</p>
                </div>
                <div class="modal-footer">
                    <form id="deleteForm" action="index.php" method="POST">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" id="deleteId">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger">Sim, Deletar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        feather.replace();

        const deleteModal = document.getElementById('deleteModal');
        if (deleteModal) {
            deleteModal.addEventListener('show.bs.modal', event => {
                const button = event.relatedTarget;
                const rncId = button.getAttribute('data-id');
                const deleteIdInput = deleteModal.querySelector('#deleteId');
                deleteIdInput.value = rncId;
            });
        }

        const notificationAlert = document.getElementById('notification-alert');
        if(notificationAlert) {
            setTimeout(() => {
                const bsAlert = new bootstrap.Alert(notificationAlert);
                bsAlert.close();
            }, 5000);
        }
    </script>
</body>
</html>