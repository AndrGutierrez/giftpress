<footer>
    <div class="container py-5">
        <?php
        //we created a drag-and-drop footer widget in wordpress admin
        //now we are telling it where it should be
        //the string must be the same we setted in sidebar (functions.php)
        dynamic_sidebar('footer')
        ?>
    </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>

