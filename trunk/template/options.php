<h1>hiWeb Site Migration Simple</h1>
<div class="card pressthis">
	<h2><?php _e('Site ready to mirgate...','hw-migration-simple') ?></h2>
	<ol>
		<li><?php _e('Download site & MySQL dump files via FTP or Client Panel by you\'r hosting.','hw-migration-simple') ?></li>
		<li><?php _e('Upload this files on you\'r new server.','hw-migration-simple') ?></li>
		<li><?php _e('Connect site to new MySQL server, change connect data in wp-config.php file','hw-migration-simple') ?></li>
		<li><?php _e('Go to new home page. Now you see message: "hiWeb Migrate Site Process". This message indicates the beginning of a process to automatically replace the old ways to the new database. Wait until the end, it should not take more than few easy
			seconds.','hw-migration-simple') ?>
		</li>
		<li><?php _e('Done. Enjoy ;)','hw-migration-simple') ?></li>
	</ol>
</div><br><br>

<h3>Force Re-Migrate</h3>

<form action="<?php hiweb_migration_simple()->the_request_url(); ?>" method="post">
	<p><?php _e('This option is useful if the domain has changed, but the hosting site and the folder is not changed','hw-migration-simple') ?></p>
	<p>
		<strong>From Old Domain... (select one or more)</strong><br/>
		<select name="old_domain" multiple size="8">
			<option value="<?php echo get_option( 'siteurl' ) ?>" selected><?php echo get_option( 'siteurl' ).' (current URL)' ?></option>
		<?php
			$urls = hiweb_migration_simple()->get_DB_urls();
			foreach($urls as $url => $times){
				?>
				<option value="<?php echo $url ?>"><?php echo $url.' (count: '.$times.')' ?></option>
				<?php
			}
		?>
		</select>
	</p>
	<p>
		<strong>To New Domain...</strong><br/>
		<input placeholder="<?php hiweb_migration_simple()->the_base_url() ?>" name="new_domain" size="36"/>
		<button type="submit" class="button button-primary button-large"><?php _e('RE-MIGRATE to New Domain','hw-migration-simple') ?></button>
	</p>
	<div class="describe">
		<?php _e('Enter new domain (or stay them clear) and click this button to force a re-migrate procedure to new domain.<br>
		Don\'t forget about <b>http://</b> prefix.','hw-migration-simple') ?>
	</div>
</form>