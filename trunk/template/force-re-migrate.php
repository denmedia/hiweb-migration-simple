<form action="<?php echo _hw_migrate_simple_getStrRequestUrl(); ?>" method="post">
    <h1>Force Re-Migrate to...</h1>
    <p>Do you want to re-migrate your site to a new domain [<b><?php echo $_POST['new_domain'] ?></b>] ?</p>

    <input type="hidden" name="new_domain" value="<?php echo $_POST['new_domain'] ?>">
    <input type="hidden" name="confirm" value="true">
    <p>
        <button type="submit" class="button button-primary">YES, Migrate...</button>
        <a class="button" href="<?php echo _hw_migrate_simple_getStrRequestUrl() ?>">No, Cancel</a>
    </p>
</form>