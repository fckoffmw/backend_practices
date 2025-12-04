<?php
header('Content-Type: text/css');
session_start();

$theme = $_SESSION['theme'] ?? $_COOKIE['user_theme'] ?? 'light';

$themes = [
    'light' => [
        'bg' => '#ffffff',
        'text' => '#333333',
        'nav_bg' => '#f8f9fa',
        'nav_text' => '#333333',
        'link' => '#007bff',
        'border' => '#dee2e6'
    ],
    'dark' => [
        'bg' => '#1a1a1a',
        'text' => '#e0e0e0',
        'nav_bg' => '#2d2d2d',
        'nav_text' => '#e0e0e0',
        'link' => '#4da6ff',
        'border' => '#444444'
    ],
    'colorblind' => [
        'bg' => '#f5f5dc',
        'text' => '#000080',
        'nav_bg' => '#e6e6c8',
        'nav_text' => '#000080',
        'link' => '#8b4513',
        'border' => '#a0a080'
    ]
];

$colors = $themes[$theme];
?>

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Arial, sans-serif;
    background-color: <?= $colors['bg'] ?>;
    color: <?= $colors['text'] ?>;
    line-height: 1.6;
}

.navbar {
    background-color: <?= $colors['nav_bg'] ?>;
    border-bottom: 2px solid <?= $colors['border'] ?>;
    padding: 1rem 0;
}

.nav-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.nav-brand {
    font-size: 1.5rem;
    font-weight: bold;
    color: <?= $colors['nav_text'] ?>;
    text-decoration: none;
}

.nav-menu {
    display: flex;
    list-style: none;
    gap: 2rem;
}

.nav-menu a {
    color: <?= $colors['link'] ?>;
    text-decoration: none;
    transition: opacity 0.3s;
}

.nav-menu a:hover {
    opacity: 0.7;
}

.container {
    max-width: 1200px;
    margin: 2rem auto;
    padding: 0 2rem;
    min-height: calc(100vh - 200px);
}

.footer {
    background-color: <?= $colors['nav_bg'] ?>;
    border-top: 2px solid <?= $colors['border'] ?>;
    padding: 1rem;
    text-align: center;
    margin-top: 2rem;
}

.card {
    background-color: <?= $colors['nav_bg'] ?>;
    border: 1px solid <?= $colors['border'] ?>;
    border-radius: 8px;
    padding: 2rem;
    margin-bottom: 2rem;
}

.btn {
    display: inline-block;
    padding: 0.5rem 1rem;
    background-color: <?= $colors['link'] ?>;
    color: <?= $colors['bg'] ?>;
    text-decoration: none;
    border-radius: 4px;
    border: none;
    cursor: pointer;
    transition: opacity 0.3s;
}

.btn:hover {
    opacity: 0.8;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin: 1rem 0;
}

table th, table td {
    padding: 0.75rem;
    border: 1px solid <?= $colors['border'] ?>;
    text-align: left;
}

table th {
    background-color: <?= $colors['nav_bg'] ?>;
    font-weight: bold;
}

.chart-container {
    margin: 2rem 0;
    text-align: center;
}

.chart-container img {
    max-width: 100%;
    height: auto;
    border: 1px solid <?= $colors['border'] ?>;
    border-radius: 8px;
}

form {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    max-width: 500px;
}

form label {
    font-weight: bold;
}

form input, form select {
    padding: 0.5rem;
    border: 1px solid <?= $colors['border'] ?>;
    border-radius: 4px;
    background-color: <?= $colors['bg'] ?>;
    color: <?= $colors['text'] ?>;
}
