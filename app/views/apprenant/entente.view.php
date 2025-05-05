<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des apprenants en attente</title>
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #f7f9fc;
            --border-color: #e0e6ed;
            --text-color: #2c3e50;
            --accent-color: #2980b9;
            --danger-color: #e74c3c;
            --success-color: #2ecc71;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text-color);
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
            line-height: 1.6;
        }
        
        .containere {
            max-width: 1200px;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin-top: 10%;
        }
        
        h2 {
            color: var(--primary-color);
            margin-top: 0;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--border-color);
            font-weight: 600;
        }
        
        .empty-state {
            text-align: center;
            padding: 40px 0;
            color: #6c757d;
            font-style: italic;
        }
        
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }
        
        .data-table th {
            background-color: var(--secondary-color);
            color: var(--text-color);
            font-weight: 600;
            text-align: left;
            padding: 12px 15px;
            border-bottom: 2px solid var(--border-color);
        }
        
        .data-table td {
            padding: 12px 15px;
            border-bottom: 1px solid var(--border-color);
        }
        
        .data-table tbody tr:hover {
            background-color: #f5f8fa;
        }
        
        .data-table tbody tr:last-child td {
            border-bottom: none;
        }
        
        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 0.85em;
            font-weight: 500;
        }
        
        .status-badge.rejected {
            background-color: #feeaea;
            color: var(--danger-color);
        }
        
        .status-badge.no-reason {
            background-color: #f3f4f6;
            color: #6c757d;
        }
        
        .action-button {
            display: inline-block;
            padding: 8px 12px;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.2s;
        }
        
        .action-button:hover {
            background-color: var(--accent-color);
        }
        
        .action-button.reject {
            background-color: var(--danger-color);
        }
        
        .action-button.reject:hover {
            background-color: #c0392b;
        }
        
        .action-button.approve {
            background-color: var(--success-color);
        }
        
        .action-button.approve:hover {
            background-color: #27ae60;
        }
        
        .search-container {
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
        }
        
        .search-input {
            flex: 1;
            padding: 10px 15px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            font-size: 14px;
        }
        
        .search-input:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.2);
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .data-table {
                display: block;
                overflow-x: auto;
            }
            
            .search-container {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="containee">
        <h2>Liste des apprenants en attente</h2>
        
        <!-- Barre de recherche et filtres -->
        <div class="search-container">
            <input type="text" class="search-input" placeholder="Rechercher un apprenant...">
            <select class="search-input">
                <option value="">Tous les référentiels</option>
                <option value="dev-web">Développement Web</option>
                <option value="data">Data Science</option>
                <option value="cybersecu">Cybersécurité</option>
            </select>
        </div>

        <?php if (empty($apprenants)): ?>
            <div class="empty-state">
                <p>Aucun apprenant en attente pour le moment.</p>
            </div>
        <?php else: ?>
            <div style="overflow-x: auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Nom complet</th>
                            <th>Login</th>
                            <th>Référentiel</th>
                            <th>Motif de rejet</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($apprenants as $a): ?>
                            <tr>
                                <td><?= htmlspecialchars($a['nom_complet'] ?? '') ?></td>
                                <td><?= htmlspecialchars($a['login'] ?? '') ?></td>
                                <td><?= htmlspecialchars($a['referenciel'] ?? '') ?></td>
                                <td>
                                    <?php if(!empty($a['motif_rejet'])): ?>
                                        <span class="status-badge rejected"><?= htmlspecialchars($a['motif_rejet']) ?></span>
                                    <?php else: ?>
                                        <span class="status-badge no-reason">Non précisé</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <button class="action-button approve">Approuver</button>
                                    <button class="action-button reject">Rejeter</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>