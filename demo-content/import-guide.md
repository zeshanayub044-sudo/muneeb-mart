# Demo content

Preferred: activate WooCommerce, the MUNEEB MART theme, and MUNEEB MART Core. Open WooCommerce → MUNEEB MART setup → Prepare demonstration store. It uses plugin `data/products.json` and theme-local optimized imagery to create 20 products and nine categories, including a variable Finish product, an out-of-stock product, global filter attributes, pages, and a menu. Rerunning it does not overwrite existing SKUs or pages. It changes initial store settings and enables demo mode, so use it on development/staging only.

`products.csv` is a fallback for WooCommerce → Products → Import. It contains 20 simple products and local attributes without image URLs. Import it on a clean installation rather than combining it with the dashboard importer. Upload and assign theme imagery through the Media Library if using this route. The native dashboard setup is required for complete variation/global-filter demonstration.

Regenerate the JSON and CSV with `node scripts/prepare-data.mjs`. Currency defaults to PKR in the setup importer but is editable in WooCommerce. Sample descriptions, stock, prices and attributes are fictional. Images are illustrative. No real specifications or sales performance are asserted. Replace all demonstration data before accepting orders.
