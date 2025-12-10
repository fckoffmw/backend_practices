        </div>
    </main>
    
    <footer style="text-align: center; padding: 20px; color: #666; border-top: 1px solid #ddd; margin-top: 40px;">
        <div class="container">
            <p>&copy; 2024 Practice 7 - Clean Architecture & MVC</p>
            <?php if (isset($_SESSION['user_id'])): ?>
                <p style="font-size: 12px; margin-top: 5px;">
                    Session ID: <?= session_id() ?> | 
                    User ID: <?= $_SESSION['user_id'] ?> |
                    Theme: <?= $_SESSION['user_theme'] ?? 'light' ?> |
                    Language: <?= $_SESSION['user_language'] ?? 'ru' ?>
                </p>
            <?php endif; ?>
        </div>
    </footer>
</body>
</html>