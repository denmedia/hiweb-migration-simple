<style>

	.wrap {
		text-align: center;
		color: #69b9fe;
		font-family: Tahoma, Monaco, monospace;
	}

	.loader {
		margin: 100px auto 10px auto;
		max-width: 600px; height: 300px;
		background: url(<?php echo hiweb_migration_simple()->get_plugin_url() ?>/img/migrate-ani-1.gif) 50% 50% no-repeat;
	}

	.button {
		color: #fff;
		background: #69b9fe;
		display: inline-block;
		padding: 8px 16px;
		border: none;
		border-bottom: 2px solid #477cac;
		-webkit-border-radius: 3px;
		-moz-border-radius: 3px;
		border-radius: 3px;
		text-decoration: none;
	}
</style>
<div class="wrap">
	<div class="loader"></div>
	<h3>Please wait, page is reload...</h3>
	<p><a class="button" href="<?php echo hiweb_migration_simple()->get_base_url() ?>">OR RELOAD PAGE MANUALLY</a></p>
</div>

<script>
    setTimeout(function () {
        location.reload(); //todo-
    }, 3000);
</script> 