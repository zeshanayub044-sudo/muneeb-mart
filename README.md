# MUNEEB MART · The Watch Edit

An installable WordPress theme and WooCommerce companion plugin for an editorial watch boutique. The storefront uses native WooCommerce products, inventory, cart, checkout, customer accounts, and orders. It is not a static storefront or a React substitute.

**Current delivery: localhost only**, as requested by the owner. Source repository: [zeshanayub044-sudo/muneeb-mart](https://github.com/zeshanayub044-sudo/muneeb-mart), private, branch `main`. Version 1.1.0 uses **MUNEEB MART** throughout the custom source and the installed WordPress store.

## Try the installed local store

On this computer, run `npm start`, then open <http://127.0.0.1:8088>. Administration is at `/wp-admin/`. Random local credentials are in `.local/access.txt`; this ignored file must stay private. Stop the owned local processes with `powershell -ExecutionPolicy Bypass -File scripts/start-local.ps1 -Stop`.

The portable environment lives entirely in ignored `.local/`: PHP 8.3 with opcode caching, MariaDB 11.4, WordPress, and runtime content. It binds to loopback only. No system service is installed. Custom, version-controlled source lives in `wordpress/wp-content/`. After source edits, run `npm run sync` to refresh the local copy. Do not rerun `bootstrap.php` on an existing store; it is a first-install utility.

## Install on WordPress hosting

1. Use PHP 8.3+, MySQL 8+ or MariaDB 10.6+, HTTPS, and at least 256 MB WordPress memory. Follow current [WooCommerce server recommendations](https://woocommerce.com/document/server-requirements/).
2. Install WordPress and WooCommerce. Upload `releases/muneeb-mart-theme.zip` under Appearance → Themes and activate it.
3. Upload `releases/muneeb-mart-core.zip` under Plugins → Add New and activate it.
4. On a development store, go to WooCommerce → MUNEEB MART setup → **Prepare demonstration store**. This imports missing pages, 20 fictional watches, original generated imagery, global attributes, a variation product, and nine collections. Existing SKUs and pages are preserved.
5. Set the WordPress environment to `development` in private `wp-config.php` to make the development test gateway available. The gateway also requires demo mode. Production environments never offer it.
6. Configure shipping zones separately. The local-only seeding script creates an explicitly labelled development delivery method; the dashboard importer does not create shipping promises.

See [installation](docs/installation.md), [deployment](docs/deployment.md), [owner guide](docs/admin-guide.md), and [testing](docs/testing.md).

## Source layout

```
wordpress/wp-content/themes/muneeb-mart/       Installable classic theme + editor support
wordpress/wp-content/plugins/muneeb-mart-core/ Store features independent of presentation
demo-content/                               CSV fallback and import guide
scripts/                                    Local runtime, data preparation, QA, packaging
docs/                                       Installation, operations, testing, release notes
releases/                                   Generated installation ZIPs (ignored in Git)
.local/                                     Private local runtime and credentials (ignored)
```

## Architecture and design

- Classic PHP theme with `theme.json`, Gutenberg content, custom logo, editable menu, Customizer branding, headings, images, contact details, and accent color.
- Native WooCommerce product templates, gallery/zoom, variation selection, quantity, related products, checkout, account endpoints, and real order confirmation.
- Lightweight server-side collection, attribute, price and stock filters with shareable URLs, sorting and pagination. No large filter plugin.
- A browser-local wishlist, accessible search dialog and mobile menu, reduced-motion support, and local optimized WebP imagery.
- Private enquiry and subscriber records, explicit consent, form validation, nonces, honeypot, rate limiting, and WordPress privacy export/erasure integration.
- Development gateway creates **on-hold TEST orders**, reduces inventory, and collects no money. It never fabricates a payment success.

## Boundaries before production

All 20 watches, specifications, inventory and prices are demonstration content. The original generated photographs illustrate style and do not verify any product attribute. No ratings, sales totals, warranty, water resistance, delivery timeframe, free delivery or discounts are claimed. Policy pages are editable owner-review drafts. Published support details and social links are empty until configured. Newsletter consent is stored; connect a mailing service and implement its unsubscribe links before sending campaigns. Enquiries are saved; optional notification email requires verified SMTP. Local checkout is a test, not payment-gateway certification.

GitHub stores and validates the custom source. The owner requested localhost instead of public hosting, so no public deployment is configured. Products, orders, accounts, uploaded media and content remain in the local WordPress database and media library; source updates do not replace them.

## License

Custom theme and plugin: GPL-2.0-or-later. WordPress, WooCommerce, PHP and MariaDB retain their respective licenses. Generated asset provenance is in `docs/assets.md`.
