=== hiWeb Migration Simple ===
Contributors: Den Media
Donate link:
Tags: hosting, migrate, migration, server, domain, change, domain change, domain rename, site migration, site migrate, move, moving, admin site migration, free plugin, change post url, change page url, change images url, change image url, automatic url change, automatic url rename, automatic domain change, automatic domain rename, blog migrate, blog, free, free plugin, free plugins, pages url change, automatic pages move, domain move, blog move, site move, pages move, redirect to domain. redirect to url, simple domain migrate, simple domain change, simple domain rename
Requires at least: 4.0
Tested up to: 4.7.3
Stable tag: 4.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Plugin to automatically change the paths and links in the database of your site on wordpress. Just migrate files and the site database to a new hosting.

== Description ==

![How to migrate](https://plugins.svn.wordpress.org/hiweb-migration-simple/assets/animation-1.gif "how to migrate")
[youtube http://www.youtube.com/watch?v=gOfXiH4xiPs]

= English =
Plugin to automatically change the paths and links in the database of your site on wordpress. Just migrate files and the site database to a new hosting.

1. Download site & MySQL dump files via FTP or Client Panel by you'r hosting.
2. Upload this files on you'r new server.
3. Connect site to new MySQL server, change connect data in wp-config.php file
4. Go to new home page. Now you see message: "hiWeb Migrate Site Process". This message indicates the beginning of a process to automatically replace the old ways to the new database. Wait until the end, it should not take more than few easy seconds.
5. Done. Enjoy ;)

= Русский =
Плагин автоматически меняет пути и ссылки в базе данных вашего сайта в случае смены корневой папки сайта (смены хостинга и ли местарасположения). Просто перенесите файлы и базу данных на новый сервер.

1. Скачайте на локальный компьютер файлы сайта и базы данных посредством FTP или личной панеле на хостинге.
2. Загрузите эти файлы на новый сервер или поместите их в новую папку. Так же не забудьте загрузить базу данных.
3. Соедините свой загруженный сайт с базой данных, вписав новые данные в файле wp-config.php.
4. Перейдите в браузере на домашнюю страничу новозагруженного сайта. Вы увидите надпись "hiWeb Migrate Site Process". Это сообщение означает об автоматическом определении смены корневой папки сайта и следовательно замене в базе данных старых путей на новые. Подождите окончания процесса, это может занять несколько секунд.
5. Все. Приятного аппетита.

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload folder `hiweb-migration-simple` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'hiWeb Core' menu in WordPress


== Screenshots ==

1. Little instruction


== Changelog ==

= 2.0.0.0 =
Now support HTTPS!

= 1.5.0.0 =
ReCode replace Old urls to New urls, include serialize data!

= 1.4.1.0 =
FIX AUTO RE MIGRATE

= 1.4.0.0 =
Add Re-Migrate Tool, Redesigned the algorithm for changing url in the database

= 1.3.0.0 =
Redesigned the algorithm for changing url in the database

= 1.2.0.0 =
Add FORCE RE-MIGRATE BUTTON in tools→options page...

= 1.1.0.0 =
Now change URL and BaseDIR in all sql tables...

= 1.0.0.0 =
Alpha Simple Version Develop Bla-bla-bla....