<?php
// Simple database viewer for administrators
// WARNING: This file should be removed or password-protected in a production environment

require_once '../../BD/db.php';

// Function to get all tables in the SQLite database
function getTables($pdo) {
    $tables = [];
    $result = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' ORDER BY name");
    while ($row = $result->fetch(\PDO::FETCH_ASSOC)) {
        $tables[] = $row['name'];
    }
    return $tables;
}

// Function to get table structure
function getTableStructure($pdo, $table) {
    return $pdo->query("PRAGMA table_info($table)")->fetchAll(\PDO::FETCH_ASSOC);
}

// Function to get table data
function getTableData($pdo, $table, $limit = 100) {
    return $pdo->query("SELECT * FROM $table LIMIT $limit")->fetchAll(\PDO::FETCH_ASSOC);
}

// Get selected table
$selectedTable = $_GET['table'] ?? '';
$tables = getTables($pdo);

// Default to Consumption table if available
if (empty($selectedTable) && in_array('Consumption', $tables)) {
    $selectedTable = 'Consumption';
}

// Get table structure and data if a table is selected
$structure = [];
$data = [];
if (!empty($selectedTable)) {
    $structure = getTableStructure($pdo, $selectedTable);
    $data = getTableData($pdo, $selectedTable);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Viewer</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .table-container {
            overflow-x: auto;
            max-width: 100%;
        }
        pre {
            white-space: pre-wrap;
            word-wrap: break-word;
        }
    </style>
</head>
<body>
    <div class="container my-4">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1>Database Viewer</h1>
                    <a href="../../IHM/client/client_new_consumption.php" class="btn btn-secondary">Return to Client Page</a>
                </div>

                <div class="alert alert-warning">
                    <strong>Warning:</strong> This page displays sensitive database information and should be secured or removed in a production environment.
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Select a table</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-2">
                            <?php foreach ($tables as $table): ?>
                                <a href="?table=<?php echo $table; ?>" class="btn <?php echo $table === $selectedTable ? 'btn-primary' : 'btn-outline-primary'; ?>">
                                    <?php echo $table; ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <?php if (!empty($selectedTable)): ?>
                    <h2>Table: <?php echo htmlspecialchars($selectedTable); ?></h2>
                    
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Table Structure</h5>
                        </div>
                        <div class="card-body table-container">
                            <table class="table table-bordered table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>CID</th>
                                        <th>Name</th>
                                        <th>Type</th>
                                        <th>Not Null</th>
                                        <th>Default Value</th>
                                        <th>Primary Key</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($structure as $column): ?>
                                        <tr>
                                            <td><?php echo $column['cid']; ?></td>
                                            <td><?php echo htmlspecialchars($column['name']); ?></td>
                                            <td><?php echo htmlspecialchars($column['type']); ?></td>
                                            <td><?php echo $column['notnull'] ? 'Yes' : 'No'; ?></td>
                                            <td><?php echo $column['dflt_value'] !== null ? htmlspecialchars($column['dflt_value']) : 'NULL'; ?></td>
                                            <td><?php echo $column['pk'] ? 'Yes' : 'No'; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Table Data</h5>
                            <span class="badge bg-info"><?php echo count($data); ?> rows</span>
                        </div>
                        <div class="card-body table-container">
                            <?php if (empty($data)): ?>
                                <div class="alert alert-info">No data found in this table.</div>
                            <?php else: ?>
                                <table class="table table-bordered table-striped table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <?php foreach (array_keys($data[0]) as $column): ?>
                                                <th><?php echo htmlspecialchars($column); ?></th>
                                            <?php endforeach; ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($data as $row): ?>
                                            <tr>
                                                <?php foreach ($row as $key => $value): ?>
                                                    <td>
                                                        <?php if ($key === 'photo' && !empty($value)): ?>
                                                            <div>
                                                                <small class="text-muted"><?php echo htmlspecialchars($value); ?></small>
                                                                <br>
                                                                <img src="../../<?php echo htmlspecialchars($value); ?>" style="max-width: 100px; max-height: 100px;" 
                                                                    onerror="this.onerror=null; this.src='../../assets/images/placeholder.png'; this.style.opacity=0.5;">
                                                            </div>
                                                        <?php elseif (is_null($value)): ?>
                                                            <em class="text-muted">NULL</em>
                                                        <?php elseif (is_array($value) || is_object($value)): ?>
                                                            <pre><?php echo htmlspecialchars(json_encode($value, JSON_PRETTY_PRINT)); ?></pre>
                                                        <?php else: ?>
                                                            <?php echo htmlspecialchars($value); ?>
                                                        <?php endif; ?>
                                                    </td>
                                                <?php endforeach; ?>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 