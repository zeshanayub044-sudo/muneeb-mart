# Backups and migration

Source control preserves custom code and seed content. It does not back up live WordPress posts, media, settings, users, orders or stock.

## Current local environment

Run `npm run backup` while the local database is available. This creates a consistent SQL export, media copy and a private copy of `wp-config.php` under ignored `.local/backups/export-<timestamp>/`. The database password is passed to the dump process through its environment and is not printed or added to Git. Check the command’s exit status and the private export log. The export directory includes a copy of uploads. Back up custom source separately through Git or the source ZIP. A SQL export alone does not contain images.

Use `powershell -ExecutionPolicy Bypass -File scripts/start-local.ps1 -Stop` to stop the local environment. MariaDB now shuts down through `mariadb-admin shutdown` to flush its recovery logs. If graceful shutdown fails, the script leaves the database running instead of force-stopping it. Do not force-close database processes or move live database files.

The 30 October 2026 recovery was preceded by a stopped raw database copy and was followed by a successful SQL export. Those private files remain under `.local/backups/`; they are excluded from Git and installation ZIPs.

## Public hosting

Export the database with the host’s phpMyAdmin and download `wp-content/uploads/` plus the custom theme/plugin through authenticated, encrypted FTPS or a supported secure file manager. Keep `wp-config.php` private and store backups outside `htdocs`/`public_html`. Keep several dated copies outside the hosting account; a provider backup promise is not a restore test.

## Move to another host

1. Back up the source store and record its exact URL, WordPress/WooCommerce/PHP/database versions, permalinks and plugin settings. Pause real order acceptance during the final consistent copy.
2. Provision compatible PHP, a database and valid HTTPS. Install official WordPress and WooCommerce, then the custom theme and plugin.
3. Import the database and copy the uploads directory. Set private destination database credentials and fresh appropriate WordPress salts.
4. Use a WordPress-aware serialized-data search/replace tool, such as WP-CLI `search-replace`, for the old and new URLs. Do not do raw SQL string replacement of serialized options. Keep a backup and preview the replacement first.
5. Flush permalinks. Verify products/variations, images, all eleven collections, pages, eight Journal posts, category/tag archives, accounts, order records, inventory and SEO URLs. Redirect old public URLs where the old host/domain permits it.
6. Reconfigure host-specific shipping, tax, mail and payment integrations. Test notifications, password resets, scheduling and end-to-end checkout on the destination before real trading.

For a fresh public demonstration, the dashboard importer can create missing demo content without transferring local customer/order records. Code updates copy only the custom theme/plugin and must never replace the database, uploads or private configuration.
