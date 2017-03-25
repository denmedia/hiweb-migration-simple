<form action="<?php hiweb_migration_simple()->the_request_url(); ?>" method="post">
	<h1>Force Re-Migrate to...</h1>
	<p>Do you want to re-migrate your site from [<b><?php echo $_POST['old_domain'] ?></b>] to a new domain [<b><?php echo $_POST['new_domain'] ?></b>] ?</p>

	<input type="hidden" name="old_domain" value="<?php echo $_POST['old_domain'] ?>">
	<input type="hidden" name="new_domain" value="<?php echo $_POST['new_domain'] ?>">
	<input type="hidden" name="confirm" value="true">
	<p>
		<button type="submit" class="button button-primary">YES, Migrate...</button>
		<a class="button" href="<?php hiweb_migration_simple()->the_request_url(); ?>">No, Cancel</a>
	</p>
</form>