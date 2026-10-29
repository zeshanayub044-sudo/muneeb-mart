# Deployment and GitHub

## Repository workflow

The new private repository is [zeshanayub044-sudo/muneeb-mart](https://github.com/zeshanayub044-sudo/muneeb-mart), with default branch `main`. The ignore rules exclude `.local/`, credentials, database files, WordPress core, third-party WooCommerce, uploads, and release ZIPs. Custom source, demo JSON/CSV, scripts and documentation are version controlled.

`scripts/package.ps1` creates installable theme/plugin ZIPs and a complete source bundle. CI in `.github/workflows/validate.yml` checks PHP and JavaScript syntax and packages the two artifacts. GitHub is version control and build distribution; it does not run WordPress PHP on GitHub Pages.

## Current localhost update workflow

The owner explicitly selected localhost and declined public hosting. On this configured computer, run `git pull --ff-only`, review the source changes, then `npm run sync`. The sync script updates only the custom theme and companion plugin in the private local WordPress installation. Run `npm start` if the local services are stopped. The database, users, orders, stock and uploaded media are preserved. A GitHub Actions run validates and packages a commit; it does not open inbound access to this computer or automatically modify the running local database.

Changes made in WP Admin remain in WordPress. Do not commit database dumps, uploaded customer documents, session cookies, or the local administrator credentials. To revert custom code, check out a reviewed earlier Git commit and run `npm run sync`; restore the database only when intentionally reversing content changes with a verified private backup.

## Hosting deployment

1. Create a staging environment with PHP/MySQL-compatible WordPress hosting and HTTPS.
2. Install/upgrade WordPress and WooCommerce, back up files/database, and install the two release ZIPs.
3. Store database credentials and salts in private `wp-config.php` or host environment. Do not copy this computer's `.local/` to a public host.
4. Import demo content only on staging. Replace samples with approved catalog data for production.
5. Configure permalinks, account settings, shipping zones, tax, payment gateway sandbox settings, and verified SMTP. Set both WordPress and store visibility to non-public during development.
6. Test the checklist in `testing.md` on that actual host, including a sandbox payment and real mailbox delivery. The development gateway is intentionally unavailable in production.
7. Approve policies and business details, replace all imagery/specifications as appropriate, remove test orders/customer data, turn off demo mode, set `WP_ENVIRONMENT_TYPE=production`, disable debug display/logging, and allow indexing when ready.

Use the host's staging deployment or SFTP from a reviewed release for code-only updates. Never overwrite `uploads/`, production configuration or the database during code deployment. A deployment workflow must be adapted to the chosen host; this source contains no made-up production endpoint or embedded credentials.

## Operations

Schedule database and uploads backups using the hosting provider; verify a restore on staging. Keep WordPress, WooCommerce, theme and plugin updated. Test checkout after upgrades. Use least-privilege store staff roles. Disable file editing in production; protect configuration, backups and logs from HTTP access. Serve immutable static assets with appropriate browser caching; exclude cart, checkout, account, `wc-ajax` and personalized/session responses from page caching. Do not minify or defer WooCommerce dependencies indiscriminately.

## Preview hosting boundary

This delivery provides an actual local WordPress preview. Sites' Cloudflare Worker runtime and GitHub Pages cannot run this PHP/MySQL application. No separate static imitation was deployed. A production URL requires suitable WordPress hosting and its deployment access.
