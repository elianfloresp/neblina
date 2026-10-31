<?php
/**
 * Footer global — estrutura idêntica ao protótipo v14.
 * Fixo no código.
 */
?>
</main><!-- /#main -->

<footer class="footer">
    <div class="shell">
        <div class="footer-top">
            <div>
                <p><?php echo esc_html(neblina_institutional()[0]); ?></p>
            </div>
            <div class="footer-links">
                <a href="<?php echo esc_url(neblina_url('livros')); ?>"><?php echo neblina_i('nav_books'); ?></a>
                <a href="<?php echo esc_url(neblina_url('sobre')); ?>"><?php echo neblina_i('nav_about'); ?></a>
                <a href="<?php echo esc_url(neblina_url('contato')); ?>"><?php echo neblina_i('nav_contact'); ?></a>
                <a href="https://www.instagram.com/neblinaeditorial/" target="_blank" rel="noopener noreferrer">Instagram</a>
            </div>
        </div>
        <div class="footer-bottom">
            <span>Neblina Editorial · Brasil / España</span>
            <span>© <?php echo esc_html(date('Y')); ?> · <?php echo neblina_i('footer_rights'); ?></span>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
