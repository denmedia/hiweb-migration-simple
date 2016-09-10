<h1>hiWeb Site Migration Simple</h1>
<div class="card pressthis">
	<h2>Site ready to mirgate...</h2>
	<ol>
		<li>Download site & MySQL dump files via FTP or Client Panel by you'r hosting.</li>
		<li>Upload this files on you'r new server.</li>
		<li>Connect site to new MySQL server, change connect data in wp-config.php file</li>
		<li>Go to new home page. Now you see message: "hiWeb Migrate Site Process". This message indicates the beginning of a process to automatically replace the old ways to the new database. Wait until the end, it should not take more than few easy
			seconds.
		</li>
		<li>Done. Enjoy ;)</li>
	</ol>
</div><br><br>

<h3>Force Re-Migrate</h3>

<form action="<?php echo _hw_migrate_simple_getStrRequestUrl() ?>" method="post">
	<p>This option is useful if the domain has changed, but the hosting site and the folder is not changed</p>
	<p>
		<input placeholder="<?php echo _hw_migrate_simple_getBaseUrl(); ?>" name="new_domain" size="36"/>
		<button type="submit" class="button button-primary button-large">RE-MIGRATE to New Domain</button>
	</p>
	<div class="describe">
		Enter new domain (or stay them clear) and click this button to force a re-migrate procedure to new domain.<br>
		Don't forget about <b>http://</b> prefix.
	</div>
</form>