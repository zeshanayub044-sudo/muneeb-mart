# Installation and development

## Hosting installation

Use a WordPress host with HTTPS, a supported PHP version and MySQL/MariaDB. The initial local setup uses PHP 8.3 and MariaDB 11.4. Check the host's current WordPress/WooCommerce requirements and extension support (mysqli, mbstring, curl, gd or Imagick, intl, zip, openssl). Enable pretty permalinks. Put secrets only in the host's private configuration or environment, never source control.

Install WordPress, then WooCommerce from its official plugin listing. Upload the two ZIP files from `releases/`, activate the theme and companion plugin, and visit WooCommerce → MUNEEB MART setup. The importer intentionally creates demonstration content only when clicked. Take a database backup before using it in an existing store. It preserves existing SKUs, existing pages, and an existing primary menu. Initial configuration selects PKR, guest checkout, sample stock management, draft policies, and non-indexing. Review these in an existing store before import.

After import, check Settings → Reading (Home), WooCommerce → Settings → Products (Catalog), Advanced (cart, checkout, account, terms), and Settings → Permalinks. Product URLs use `/product/` and collections use `/collections/`.

For development, add `define('WP_ENVIRONMENT_TYPE', 'development');` to private `wp-config.php` before WordPress loads. Demo mode is managed under WooCommerce → MUNEEB MART setup. Both development/local environment and demo mode are required for the test gateway. Production must not use this gateway. Set appropriate shipping zones and tax settings before testing your actual market.

## Existing portable installation

The delivered computer has an ignored portable environment under `.local/`. Start with `npm start`. Database binds to `127.0.0.1:3308`, PHP binds to `127.0.0.1:8088`. Open the local URL and use `.local/access.txt` for the generated admin credentials. Stop with `powershell -ExecutionPolicy Bypass -File scripts/start-local.ps1 -Stop`.

Source edits are synchronized with `npm run sync`. The portable environment is not part of the distributable packages. Do not expose PHP's development server publicly. The install utility `scripts/bootstrap.php` is for a clean first installation only. Do not run it to restart the store. Local opcode caching is enabled; timestamp validation stays on so source updates are detected.

## Reproducible alternative

The theme and plugin also work with Local, DDEV, wp-env, or a standard hosting development environment. Install official WordPress and WooCommerce, then install the packaged theme/plugin and use the dashboard importer. This avoids dependence on Windows-specific portable paths. `compose.yaml` is provided for Docker-capable environments; it has not been used on this computer. Supply private `.env` values based on `.env.example`, then run `docker compose up -d`. Complete WordPress setup in the browser. Upload WooCommerce and run the importer.

## CSV fallback

Use the native WooCommerce product importer with `demo-content/products.csv` only when not using the dashboard importer. The dashboard importer is preferred: it attaches local imagery, global attributes, and the variable product. The CSV is a simple-product fallback without uploaded media; see its import guide.
