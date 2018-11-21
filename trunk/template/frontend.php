<!DOCTYPE html>
<html>

<head>
	<title>...in progress, please wait...</title>
	<style>

		.wrap {
			text-align: center;
			color: #69b9fe;
			font-family: Tahoma, Monaco, monospace;
		}

		.loader {
			margin: 100px auto 10px auto;
			max-width: 600px; height: 300px;
			background: url(<?php echo hiweb_migration_simple\tools::get_plugin_url() ?>/img/migrate-ani-1.gif) 50% 50% no-repeat;
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
		[data-button-reload] {
			display: none;
		}
	</style>
</head>

<body>
<div class="wrap">
	<div class="loader"></div>
	<h3>Please wait for the page to reload after the process is over...</h3>
	<p data-button-reload><a class="button" href="<?php echo hiweb_migration_simple\tools::get_base_url() ?>">Done! Manual reload</a></p>
</div>
</body>
<script>
	var wp_ajax_url = "<?=hiweb_migration_simple\tools::get_base_url()?>/wp-admin/admin-ajax.php?action=hiweb_migration_simple";
</script>
<script src="<?=hiweb_migration_simple\tools::get_base_url()?>/wp-includes/js/jquery/jquery.js" defer></script>
<script src="<?=hiweb_migration_simple\tools::get_plugin_url()?>/inc/hiweb_migration_simple.js" defer></script>
</html>




