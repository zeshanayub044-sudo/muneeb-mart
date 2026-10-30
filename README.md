# MUNEEB MART · The Watch Edit

An original WordPress theme and WooCommerce companion plugin for a premium watch boutique, with a native WordPress Watch Journal. Products, inventory, cart, checkout, accounts and orders use WooCommerce. Version **1.2.0** adds eight original articles, ten blog categories, editorial templates, SEO editing, additional collections and customer guides.

Source: [zeshanayub044-sudo/muneeb-mart](https://github.com/zeshanayub044-sudo/muneeb-mart), private, branch `main`.

## Run the installed store

Run `npm start`, then open <http://127.0.0.1:8088/>. The Journal is at `/journal/` and administration at `/wp-admin/`. Local credentials are in ignored `.local/access.txt`; keep that file private. The portable PHP/MariaDB/WordPress environment lives in `.local/`, binds to loopback and installs no Windows service.

After source changes, run `npm run sync` to copy only custom code into the local WordPress runtime. Content, orders, users, stock and uploaded media remain in the database/uploads. Run `npm run backup` for a private SQL/config/media export. Stop gracefully with `powershell -ExecutionPolicy Bypass -File scripts/start-local.ps1 -Stop`. Do not rerun `bootstrap.php` on an existing store.

## Included

- Original classic PHP theme, `theme.json`, block editor support, replaceable logo, menus, Customizer text/images/colors and responsive store layouts.
- Twenty fictional watch products, eleven editable product collections, four global attributes, two variations, inventory and native WooCommerce shopping/account endpoints.
- Native Journal with eight original articles of approximately 1,060–1,150 words each, ten categories, featured story, search, pagination, author/date/reading time, contents links, sharing, related articles and product recommendations. The homepage queries the three latest published posts.
- Editable SEO title/description for posts, pages and products, Open Graph, canonical URLs, BlogPosting schema and native WordPress sitemap readiness. Demo products suppress unsupported Product schema.
- Catalog search/filter/sort/pagination, original optimized WebP photography, browser-local wishlist, mobile menu, keyboard focus and reduced-motion support.
- Privately stored consent-based enquiries/subscriptions with validation, nonces, honeypot, rate limits and WordPress privacy export/erasure.
- Development-only gateway creates on-hold TEST orders without collecting payment. Native WooCommerce administration manages those orders.
- Size/care/gift/support/payment guides and native order tracking. Policy pages remain owner-review drafts.

## Install elsewhere

Use a host compatible with the current [WooCommerce server requirements](https://woocommerce.com/document/server-requirements/), HTTPS and sufficient memory. Install official WordPress and WooCommerce, then upload the theme/core ZIPs from `releases/`. In WooCommerce → MUNEEB MART setup, prepare demonstration content. The Journal/support importer preserves existing posts and products. On the existing local store, `scripts/expand-local.php` adds only the extension.

The test gateway requires both demo mode and a private `WP_ENVIRONMENT_TYPE` of `development` or `local`; it never activates for production. Configure shipping separately. Native COD, bank transfer and online gateways remain inactive until their real settings and tests are complete.

## Hosting and deployment status

The working installation is currently on localhost. The owner requested a $0 public deployment. [Hosting research](docs/hosting-setup.md) records free-tier limits and actual signup observations. Owner registration/verification and real-host tests are required before claiming a public URL or certified checkout. No paid plan, domain or service is authorised.

GitHub Actions validates PHP/JavaScript and packages installable artifacts. It does not deploy automatically. Secure FTPS deployment must target only custom code, preserve the database/uploads, and be verified before being described as automatic. Never commit credentials, `wp-config.php`, database exports or customer data.

## Documentation

- [Owner guide](docs/admin-guide.md) · [Journal management](docs/blog-management.md)
- [Installation](docs/installation.md) · [Deployment](docs/deployment.md)
- [Free hosting assessment](docs/hosting-setup.md) · [Backups and migration](docs/backups.md)
- [Testing history](docs/testing.md) · [Original local delivery](docs/local-delivery.md)

## Source layout

```
wordpress/wp-content/themes/muneeb-mart/       Theme and optimized assets
wordpress/wp-content/plugins/muneeb-mart-core/ Custom commerce/forms/editorial setup
demo-content/                               Product CSV and import guidance
scripts/                                    Local tools, QA and packaging
docs/                                       Operations, reports and screenshots
releases/                                   Generated installation ZIPs (ignored)
.local/                                     Private runtime, database and credentials (ignored)
```

## Before real orders

Replace fictional inventory and demonstration photographs with verified product information. Approve business identity, contacts, shipping/taxes, returns/privacy/terms, secure payments and reliable mail. Verify notification delivery, password resets and scheduling. Newsletter consent is stored; campaigns require delivery and unsubscribe configuration. Development checkout does not certify a live payment gateway.

Custom code is GPL-2.0-or-later. WordPress, WooCommerce and runtime tools retain their licences. See [asset provenance](docs/assets.md).
