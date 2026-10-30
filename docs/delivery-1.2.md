# MUNEEB MART · Version 1.2 delivery
Verified 30 October 2026 · English · Asia/Karachi · PKR

## A. Website and hosting

The real WordPress/WooCommerce installation runs at **http://127.0.0.1:8088/**. The native Journal is **http://127.0.0.1:8088/journal/**.

**A public website has not yet been deployed.** The owner requested genuinely free hosting and authorised account setup. InfinityFree registration is prepared in the browser; the owner must privately enter the password, accept the provider’s terms and verify email before provisioning can continue. No paid hosting, domain, add-on or subscription was purchased. A free subdomain and public HTTPS remain unverified; no live address is invented.

[The hosting assessment](hosting-setup.md) compares twenty requirements across InfinityFree, ByetHost, AwardSpace and GoogieHost. Actual GoogieHost checkout contradicted its free-SSL advertising by asking for a $9.99 SSL add-on. InfinityFree’s documented inbound API/webhook and cron restrictions prevent certification of online payments on its free tier. Any eventual deployment must first pass actual account tests and remain a clearly identified demonstration where required.

## B. WordPress and ownership

| Component | Verified installation |
|---|---|
| WordPress | 7.1.4 |
| WooCommerce | 11.2.1 |
| Theme | MUNEEB MART 1.2.0 |
| Companion plugin | MUNEEB MART Core 1.2.0 |
| PHP | 8.3.36 |
| MariaDB | 11.4.13 |
| Administration | http://127.0.0.1:8088/wp-admin/ |

The original administrator account, ID and role were preserved. Local credentials remain private in ignored `.local/access.txt`. The logo, menu, homepage text/images, colors, pages, products, collections and posts are editable through WordPress.

The local database initially failed to restart because of damaged Aria recovery logs. A stopped raw database backup preceded repair, all 52 store tables were recovered, the saved project database account was restored, and existing orders were preserved. The shutdown utility now flushes MariaDB gracefully. A clean stop and restart were verified.

## C. WooCommerce

- **20** fictional demonstration watches, **11** editable product collections, four global filter attributes and a variable product with two variations.
- Native WooCommerce product pages, galleries, stock, filters, sorting, pagination, cart, checkout, accounts, order administration and order tracking.
- Collections: Men’s, Women’s, Luxury-Inspired, Classic, Chronograph Style, Minimalist, Sports-Inspired, Executive, Gifts, New Arrivals and Budget.
- Latest verified checkout: **test order #101**, on hold and unpaid, two watches totalling **PKR 15,000**. Stock changed **6 → 4**, confirmation displayed and the cart emptied.
- Existing test orders #59 and #67 remain accessible; regression order #95 also remains. Test orders are never fulfilled and collect no payment.
- Browser testing also confirmed add-to-cart and native checkout. Removing the final browser cart item now refreshes the header badge from 1 to 0 through WooCommerce’s native fragments script.
- COD, bank transfer and online payments remain inactive pending approved real details and successful compatibility tests. Development shipping is explicitly labelled “no dispatch”; real rates, tax decisions and policies remain owner configuration.

Product names, specifications, prices, stock and imagery are sample content. There are no fabricated reviews, sales rankings, shipping guarantees or verified technical claims.

## D. The Watch Journal

Eight original articles are published using native WordPress posts, with ten managed categories. All eight exceed 1,000 words in the recorded check. The magazine archive includes a featured story, category links, search, images, author/date, excerpts and pagination. Articles include reading time, contents links, related stories, sharing and relevant WooCommerce recommendations.

The latest three articles are:

1. A Gift Worth Wearing: How to Choose a Watch for Someone Else
2. Inside the Watch: A Beginner’s Guide to Movements
3. Everyday Watch Care: A Gentle Cleaning and Maintenance Guide

Other articles cover men’s buying choices, timeless styles, analog/digital displays, outfit matching and formal/business occasions. Technical guidance cites official manufacturer sources and does not invent hands-on testing.

The ten categories are Watch Buying Guides, Men’s Watch Fashion, Women’s Watch Fashion, Watch Styling Tips, Watch Care and Maintenance, Watch Trends, Luxury Watch Inspiration, Product Comparisons, Gift Guides and MUNEEB MART Updates. Categories without a matching article have a useful empty state.

Publishing a disposable article was verified to update the homepage automatically; changing it to draft removed it. The disposable article was deleted afterward. Native future-post scheduling registered its WordPress event; exact-time public execution still needs host validation.

SEO titles and descriptions are editable on posts, pages and products with permission/nonce protection. Canonicals, Open Graph and BlogPosting schema are supplied. Demo mode retains noindex and suppresses unsupported Product schema. Native XML sitemap availability depends on enabling search visibility after launch preparation. No analytics, search-console account, ranking or indexing result is claimed.

## E. GitHub and deployment

Repository: **https://github.com/zeshanayub044-sudo/muneeb-mart** · private · branch **main**.

The source contains the custom theme/plugin, original assets, sample products/articles, local tools, QA and documentation. WordPress core, third-party WooCommerce, private runtime, database exports, credentials, uploaded customer content and orders stay outside Git.

GitHub Actions validates PHP/JavaScript, checks sample content and packages installable archives. This is **validation and packaging**, not automatic deployment. Secure FTPS deployment remains conditional on the actual host account and verification; database/uploads must never be overwritten. For this local installation, review/pull code and run `npm run sync`.

## F. Actual validation

| Suite | Result |
|---|---:|
| Shopping HTTP regression | 71/71 passed |
| Journal content, permissions, import preservation and publishing | 28/28 passed |
| Journal HTTP, metadata, archives, categories, search and tracking | 65/65 passed |
| Store backend and administration features | 17/17 passed |
| Authenticated WP Admin screens | 6/6 passed |
| Branding and original ownership/order preservation | 10/10 passed |
| Saved test order, stock, totals and form persistence | 6/6 passed |

Custom PHP syntax, twelve JavaScript files and demonstration JSON content were checked. [Responsive measurements](responsive-journal.json) record Home, Journal, article, Catalog and Checkout at 320, 375, 430, 768, 1024, 1440 and 1920 pixels: no document overflow or broken loaded images. Desktop/mobile screenshots are in `docs/screenshots/`.

Representative final local timings were homepage 2,066 ms, catalog 689 ms, cart 427 ms and checkout 448 ms. These are local HTTP measurements, not Lighthouse or Core Web Vitals scores. Cold local startup intermittently caused initial HTTP timeouts. After warming, four fresh restart smoke checks passed. Slow cold startup remains a local performance limitation; timed-out attempts are not presented as passing tests.

Contact enquiries and newsletter consent were saved privately and inspected through authenticated administration. Email sending, password reset delivery, real payment callbacks, public SSL, public-host Action Scheduler/cron and unattended order notifications remain **unverified**.

## G. Owner operations

Run `npm start`, then use WP Admin:

1. Products → Add New: add watches, prices, images, categories, attributes and stock.
2. Products → Categories: edit collection names, descriptions, thumbnails and membership.
3. Posts → Add New: write, preview, draft or publish Journal articles; set category, tags, excerpt and featured image.
4. Use **MUNEEB MART · Search appearance** for SEO metadata.
5. Appearance → Customize: change logo, hero/story images, headings, accent, contacts and social links.
6. Appearance → Menus and Pages: edit navigation and customer guides/policy drafts.
7. WooCommerce: manage orders, shipping, payments, taxes and coupons using approved real information.
8. Keep source changes reviewed in GitHub and sync code without replacing content.

See [owner guide](admin-guide.md) and [blog management](blog-management.md). The separate importer preserves existing posts, stock and orders.

## H. Backup, migration and remaining work

A successful private export includes SQL, configuration and **57 media files** under ignored `.local/backups/`. Raw recovery backups also remain private. No secret or database export belongs in release ZIPs or Git.

[Backup and migration instructions](backups.md) cover database/media/code, private configuration, serialized URL replacement, permalinks and verification of posts, products, orders, users, inventory and SEO URLs on a destination host.

Before public launch, complete owner registration and actual free-host preflight, choose an available subdomain, activate valid HTTPS and deploy/test the fresh demonstration. Before real trading, replace fictional inventory, approve business/contact/legal/shipping/tax details, verify secure payments, mail, password resets and background processing. The current result is a functioning local development store with documented deployment prerequisites, **not a certified production checkout**.
