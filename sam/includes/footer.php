    <footer>
        <div class="social-links">
            <a href="https://github.com/samledev" target="_blank" title="GitHub"><i class="fab fa-github"></i></a>
            <a href="#" target="_blank" title="WhatsApp"><i class="fab fa-whatsapp"></i></a>
            <a href="https://instagram.com/samledev" target="_blank" title="Instagram"><i class="fab fa-instagram"></i></a>
            <a href="#" target="_blank" title="Twitter"><i class="fab fa-twitter"></i></a>
            <a href="https://youtube.com/samledev" target="_blank" title="YouTube"><i class="fab fa-youtube"></i></a>
        </div>
        <p>&copy; <?php echo date('Y'); ?> sam le dev. Tous droits réservés.</p>
    </footer>

    <form action="theme.php" method="POST" id="themeForm" style="display: none;">
        <input type="hidden" name="theme" id="themeInput">
    </form>

    <button class="theme-toggle" onclick="document.getElementById('themeInput').value='<?php echo $theme === 'dark' ? 'light' : 'dark'; ?>';document.getElementById('themeForm').submit();" aria-label="Changer de thème">
        <i class="fas fa-<?php echo $theme === 'dark' ? 'sun' : 'moon'; ?>"></i>
    </button>

    <script>
        // Minimal JavaScript for hamburger menu
        document.getElementById('hamburger').addEventListener('click', function() {
            this.classList.toggle('active');
            document.querySelector('.nav-menu').classList.toggle('active');
        });
    </script>
</body>
</html> 