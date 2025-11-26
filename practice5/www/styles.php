<?php
/**
 * Динамическая генерация CSS стилей на основе темы пользователя
 */

header('Content-Type: text/css; charset=utf-8');

$theme = $_GET['theme'] ?? 'light';

// Цветовые схемы
$themes = [
    'light' => [
        'bg' => '#f5f5f5',
        'bg_secondary' => '#ffffff',
        'text' => '#333333',
        'text_secondary' => '#666666',
        'primary' => '#4a90d9',
        'primary_hover' => '#357abd',
        'border' => '#ddd',
        'success' => '#28a745',
        'error' => '#dc3545',
        'warning' => '#ffc107',
        'header_bg' => '#4a90d9',
        'header_text' => '#ffffff',
        'card_shadow' => 'rgba(0,0,0,0.1)',
    ],
    'dark' => [
        'bg' => '#1a1a2e',
        'bg_secondary' => '#16213e',
        'text' => '#eaeaea',
        'text_secondary' => '#b0b0b0',
        'primary' => '#0f3460',
        'primary_hover' => '#e94560',
        'border' => '#0f3460',
        'success' => '#00d25b',
        'error' => '#fc424a',
        'warning' => '#ffab00',
        'header_bg' => '#0f3460',
        'header_text' => '#ffffff',
        'card_shadow' => 'rgba(0,0,0,0.4)',
    ],
    'colorblind' => [
        // Тема для людей с дальтонизмом (высокий контраст, без красно-зелёных оттенков)
        'bg' => '#ffffff',
        'bg_secondary' => '#f0f0f0',
        'text' => '#000000',
        'text_secondary' => '#444444',
        'primary' => '#0077bb',
        'primary_hover' => '#005588',
        'border' => '#000000',
        'success' => '#009988',
        'error' => '#cc3311',
        'warning' => '#ee7733',
        'header_bg' => '#0077bb',
        'header_text' => '#ffffff',
        'card_shadow' => 'rgba(0,0,0,0.2)',
    ],
];

$colors = $themes[$theme] ?? $themes['light'];
?>

:root {
    --bg: <?= $colors['bg'] ?>;
    --bg-secondary: <?= $colors['bg_secondary'] ?>;
    --text: <?= $colors['text'] ?>;
    --text-secondary: <?= $colors['text_secondary'] ?>;
    --primary: <?= $colors['primary'] ?>;
    --primary-hover: <?= $colors['primary_hover'] ?>;
    --border: <?= $colors['border'] ?>;
    --success: <?= $colors['success'] ?>;
    --error: <?= $colors['error'] ?>;
    --warning: <?= $colors['warning'] ?>;
    --header-bg: <?= $colors['header_bg'] ?>;
    --header-text: <?= $colors['header_text'] ?>;
    --card-shadow: <?= $colors['card_shadow'] ?>;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: var(--bg);
    color: var(--text);
    line-height: 1.6;
    min-height: 100vh;
}

/* Header */
.header {
    background: var(--header-bg);
    color: var(--header-text);
    padding: 1rem 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 2px 10px var(--card-shadow);
}

.header h1 {
    font-size: 1.5rem;
}

.header nav {
    display: flex;
    gap: 1rem;
    align-items: center;
}

.header nav a {
    color: var(--header-text);
    text-decoration: none;
    padding: 0.5rem 1rem;
    border-radius: 4px;
    transition: background 0.3s;
}

.header nav a:hover {
    background: rgba(255,255,255,0.2);
}

.user-info {
    display: flex;
    align-items: center;
    gap: 1rem;
    font-size: 0.9rem;
}

/* Main container */
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem;
}

/* Cards */
.card {
    background: var(--bg-secondary);
    border-radius: 8px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 4px 15px var(--card-shadow);
    border: 1px solid var(--border);
}

.card h2 {
    margin-bottom: 1rem;
    color: var(--primary);
    border-bottom: 2px solid var(--primary);
    padding-bottom: 0.5rem;
}

/* Tables */
table {
    width: 100%;
    border-collapse: collapse;
    margin: 1rem 0;
}

th, td {
    padding: 0.75rem;
    text-align: left;
    border: 1px solid var(--border);
}

th {
    background: var(--primary);
    color: var(--header-text);
}

tr:nth-child(even) {
    background: var(--bg);
}

tr:hover {
    background: var(--bg);
    opacity: 0.9;
}

/* Forms */
.form-group {
    margin-bottom: 1rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: var(--text-secondary);
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid var(--border);
    border-radius: 4px;
    font-size: 1rem;
    background: var(--bg-secondary);
    color: var(--text);
}

.form-group input:focus,
.form-group select:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 5px rgba(74, 144, 217, 0.3);
}

/* Buttons */
.btn {
    display: inline-block;
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 4px;
    font-size: 1rem;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.3s;
    text-align: center;
}

.btn-primary {
    background: var(--primary);
    color: white;
}

.btn-primary:hover {
    background: var(--primary-hover);
}

.btn-success {
    background: var(--success);
    color: white;
}

.btn-danger {
    background: var(--error);
    color: white;
}

.btn-warning {
    background: var(--warning);
    color: #333;
}

/* Grid */
.grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
}

/* Services grid */
.services-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
    margin-top: 1rem;
}

.service-card {
    background: var(--bg-secondary);
    border: 2px solid var(--border);
    border-radius: 8px;
    padding: 1.5rem;
    text-align: center;
    transition: all 0.3s;
    text-decoration: none;
    color: var(--text);
}

.service-card:hover {
    border-color: var(--primary);
    transform: translateY(-5px);
    box-shadow: 0 10px 20px var(--card-shadow);
}

.service-card h3 {
    color: var(--primary);
    margin-bottom: 0.5rem;
}

/* Messages */
.alert {
    padding: 1rem;
    border-radius: 4px;
    margin-bottom: 1rem;
}

.alert-success {
    background: var(--success);
    color: white;
}

.alert-error {
    background: var(--error);
    color: white;
}

.alert-warning {
    background: var(--warning);
    color: #333;
}

/* File list */
.file-list {
    list-style: none;
}

.file-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    border-bottom: 1px solid var(--border);
}

.file-item:last-child {
    border-bottom: none;
}

.file-info {
    flex: 1;
}

.file-actions {
    display: flex;
    gap: 0.5rem;
}

/* Login form */
.login-container {
    max-width: 400px;
    margin: 4rem auto;
}

/* Settings form */
.settings-form {
    max-width: 500px;
}

.settings-form .btn {
    margin-top: 1rem;
}

/* Footer */
.footer {
    text-align: center;
    padding: 1rem;
    margin-top: 2rem;
    color: var(--text-secondary);
    font-size: 0.9rem;
}

/* Responsive */
@media (max-width: 768px) {
    .header {
        flex-direction: column;
        gap: 1rem;
    }
    
    .header nav {
        flex-wrap: wrap;
        justify-content: center;
    }
    
    .container {
        padding: 1rem;
    }
}

