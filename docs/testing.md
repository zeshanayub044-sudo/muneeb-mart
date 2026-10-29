# Verification report · 29 October 2026

## Environment

Actual local WordPress **7.1.4**, WooCommerce **11.2.1**, PHP **8.3.36**, and MariaDB **11.4.13**. PHP and database bind to loopback only. The development store is available at `http://127.0.0.1:8088/`. This is a real PHP/MySQL-compatible application, not a static simulation. External HTTP is disabled by a local-only must-use plugin to avoid updater/telemetry timeouts during testing; this plugin is not packaged.

## MUNEEB MART 1.1.0 rebrand verification

The owner subsequently selected **localhost only**. The official brand is **MUNEEB MART**, the active theme directory is `muneeb-mart`, and the plugin is `muneeb-mart-core`. A private backup was taken before migrating the existing 52-table database. Product IDs, SKUs, stock, account IDs/password hashes, uploaded media and the original test order were retained. Product names/URLs, site title, email sender, WordPress menu, theme settings, page content, metadata, source paths and release filenames were corrected. A new SVG favicon and fresh screenshots use the corrected brand.

After the migration, the complete HTTP suite passed **69/69**, backend checks **17/17**, targeted checks **9/9**, saved order checks **6/6**, administrator content checks **6/6**, authenticated administration checks **6/6**, and branding checks **10/10**. Test order **#67** is unpaid/on hold, totals **PKR 15,000** for two watches, and reduced native inventory **10 → 8**. Original test order **#59** remains intact. The source audit found no previous spelling in the published custom text files. The renamed homepage was rechecked at all seven requested viewport widths with no document overflow or broken loaded images. See `branding-audit.json`, `responsive-rebrand.json`, `local-status.json` and the fresh screenshots.

The new private source repository is [zeshanayub044-sudo/muneeb-mart](https://github.com/zeshanayub044-sudo/muneeb-mart), branch `main`. GitHub Actions validates and packages the custom code; current run results are available on the repository’s Actions tab. No public deployment or hosting purchase is configured, per the owner’s latest instruction.

## Executed checks

| Area | Result and evidence |
| --- | --- |
| Backend setup | 17/17 assertions passed: 20 published products, two variations, nine collections, global attributes, attached images, configured commerce pages, PKR, stock status, and demo-gateway availability checks. |
| HTTP storefront and commerce | Latest complete run passed 69/69 assertions after the rebrand. All primary/support pages, 20 product pages, nine collection pages, catalog/search/filter states, forms, and the native shopping journey were exercised. |
| Targeted verification | 9/9 assertions passed: published privacy page, one-result search grid, matching default sort order, native cart removal, Gold variation at PKR 13,000, out-of-stock rejection, log protection, and suppression of unverified demo Product schema. |
| Saved order and form records | 6/6 assertions passed. WooCommerce order **#59** exists, is **on hold**, marked **TEST**, unpaid, with two Heritage Classic watches totaling **PKR 15,000**. Native inventory decreased from **12 to 10**. Enquiry and newsletter consent records persisted privately. |
| Admin management APIs | 6/6 assertions passed: create/edit a collection, edit its description, set a Media Library logo, verify editable primary navigation, edit/restore About content, and publish required support pages. Temporary test objects/settings were removed or restored. These are WordPress API/capability checks, not a claim that every dashboard screen was manually clicked. |
| Authenticated administration | 6/6 HTTP checks passed using the generated local administrator credentials: actual sign-in/dashboard, product management, store setup/settings, WooCommerce orders, private enquiries, and private subscribers screens. Credentials and authenticated cookies were kept out of reports and packages. Demo setup/settings require administrator capability. |
| Full catalog | All 20 product URLs returned successfully. All nine collection URLs contain products. Catalog pagination, attribute and price filters, combined stock filters, price sorting, search, empty results, branded 404, and configuration protection were checked over HTTP. |
| Cart and checkout | Real native add-to-cart, quantity update, subtotal recalculation, shipping display, required-field rejection, checkout order creation, real order confirmation, empty-cart state and item removal were tested. A selected variation was additionally added/removed through the browser. No payment was processed. |
| Forms | Valid enquiry and newsletter submissions saved records. Invalid email, missing consent, invalid nonce, and filled honeypot were rejected. Tests used fictional `.invalid` email addresses; no message to an external recipient was sent. |
| Browser interaction | Mobile menu opens/closes, search dialog submits, wishlist saves/renders/removes a watch, Gold variation updates image/price/stock, product gallery initializes, and the shopping bag removes its test item. Browser bag and wishlist were cleared afterward. |
| Responsive layout | Homepage, catalog, product page, and populated checkout checked at viewport widths **320, 375, 430, 768, 1024, 1440, 1920px**. No horizontal document overflow was detected. Browser content widths are 15px smaller where a vertical scrollbar is present. The mobile collection tabs intentionally swipe within their own area. |
| Images and console | Homepage/catalog checks found no broken loaded images. Key desktop/mobile screens were visually inspected and saved under `screenshots/`. No browser console errors were observed in the inspected views. |
| Source and archives | Custom PHP and local helper PHP syntax checked; storefront JavaScript syntax checked. Installation ZIPs are tested with Python's ZIP integrity check, verified for correct root folders, and screened to exclude runtime/configuration/database secrets. |

## Performance and accessibility observations

Theme JavaScript is dependency-free except native WooCommerce's own scripts. Product media uses optimized local WebP files and WordPress responsive thumbnails; below-fold content is lazy-loaded. There are no remote font requests, analytics scripts, fake reviews, or animation libraries. Motion preferences, visible focus styles, labelled form fields, native dialog focus behavior, skip link, and accessible native controls are included.

The initial uncached, single-threaded Windows development-server HTTP checks ranged approximately **2.9–12.0 seconds** for representative pages. PHP opcode caching was then enabled and measured: a pre-change homepage sample was **5,267 ms**; after restarting, the cold compilation sample was **9,242 ms** and the following warm samples were **1,140 ms** and **1,648 ms**. These are local document-response timings, not production page-load or Core Web Vitals scores. No Lighthouse score, load-test capacity, or automated WCAG certification is claimed. Production hosting must be measured with realistic caching, network conditions, and payment integrations before launch. Native gallery/checkout scripts can finish after the initial document in this development server; the product image remains visible while those scripts initialize.

## Outstanding production verification

- Choose and deploy to real WordPress hosting; verify HTTPS, caching exclusions, backup restore, update workflow, and error monitoring.
- Replace fictional products, stock, prices, attributes and illustrative photographs with approved catalog data.
- Approve legal/business identity, contact details, shipping fees/destinations, delivery estimates, return/refund terms and all policy drafts.
- Configure taxes and a country-appropriate payment provider; run its real sandbox checkout, failure, cancellation, and refund tests. The development gateway is unavailable in production.
- Connect and verify SMTP and transactional email delivery. Connect a consent-aware mailing provider with unsubscribe links before newsletter campaigns. Current newsletter functionality records consent; it does not send campaigns.
- Perform owner acceptance testing of staff workflows and any host-specific integrations. Run production Lighthouse/accessibility checks and real-device testing. Docker Compose and the GitHub Actions workflow are supplied but were not executed here.

Local readiness: **ready for demonstration and continued testing**. Public production readiness: **requires the configuration and verification above**.

## Re-running checks

`node scripts/local.mjs lint` checks PHP syntax. `node --check wordpress/wp-content/themes/muneeb-mart/assets/js/store.js` checks JavaScript. `.local/php.exe scripts/qa-backend.php` exercises setup/CRUD and records fixture IDs and baseline stock. `node scripts/qa-http.mjs` exercises the full shopping journey and creates another clearly labelled test order. `node scripts/qa-targeted.mjs` checks boundary cases. `.local/php.exe scripts/qa-order.php` verifies the latest test order and its stock reduction against the recorded baseline. `.local/php.exe scripts/qa-admin.php` checks and restores dashboard-managed settings. `node scripts/qa-admin-http.mjs` verifies actual authenticated administration screens. `.local/php.exe scripts/qa-brand.php` verifies the official branding and preserved account/order identities. Raw reports are kept privately in `.local/`.
