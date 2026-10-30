# Free hosting assessment

Research date: **30 October 2026 (Asia/Karachi)**. Budget: **USD 0 / PKR 0**. This report separates provider claims from tests on an actual MUNEEB MART hosting account.

**Status:** no provider deployment, public store address, hosting account access, or hosted checkout has been verified at the time of this report. The working WordPress store remains on localhost. Account preparation is in progress; this document contains no owner email, passwords, database contents, or hosting credentials.

## Decision

**InfinityFree is the next candidate for a public demonstration**, subject to successful account setup, HTTPS, installation and browser shopping tests. It is not certified for live payments. Its mandatory browser challenge blocks external REST clients and payment webhooks, and its free tier does not provide system cron. These restrictions must remain visible in the delivery report. Do not bypass them. [API restrictions](https://forum.infinityfree.com/t/why-isnt-api-access-working-on-my-website/115198), [cron policy](https://forum.infinityfree.com/t/cron-job-feature-is-now-disabled/80495).

**AwardSpace is rejected for this store:** its official knowledge base gives a fixed 32 MB free PHP memory limit and says free hosting does not support SSL; outgoing HTTP and SMTP connections also require a paid plan. [Memory](https://www.awardspace.com/kb/maximum-php-memory-size-limit-that-awardspace/), [SSL](https://www.awardspace.com/kb/how-to-get-ssl-certificate/), [outgoing connections](https://www.awardspace.com/kb/enable-outgoing-connections/), [SMTP](https://www.awardspace.com/kb/cannot-send-emails-via-smtp/).

**GoogieHost is rejected pending clarification of zero-cost HTTPS.** Public marketing advertises free SSL and a subdomain, but the actual configuration form observed on 30 October 2026 offered optional SSL at **USD 9.99 annually**, with text saying SSL must be bought to enable HTTPS and referring to limited Let's Encrypt issuance. No payment was made. This conflict prevents treating its free HTTPS claim as verified. The configuration link is session dependent and is not a purchased order or public website. [Advertised free tier](https://googiehost.com/freephphosting), [observed configuration form](https://client.googiehost.com/cart.php?a=confproduct&i=0).

**ByetHost remains an untested fallback.** Its advertised PHP/MySQL/SSL features do not establish WooCommerce checkout, callback, email, or scheduling compatibility. [Free plan](https://byet.host/free-hosting).

## The 20 requested compatibility checks

**A** = provider advertisement or documentation, not a test of this store. **U** = unresolved without an actual account test. **R** = documented restriction. No column represents a fully verified hosting installation.

| # | Check | InfinityFree | ByetHost | AwardSpace | GoogieHost |
|---|---|---|---|---|---|
| 1 | PHP version | A: 8.4 | A: 8.3; actual server U | Actual free account version U | A: selectable PHP 8.3; account U |
| 2 | MySQL/MariaDB | A: MySQL 8 / MariaDB 11.4 | A: MySQL 8 / MariaDB 11.4 | A: one MySQL database; version U | A: MySQL; version U |
| 3 | WordPress requirements | Advertised versions fit; account U | Advertised versions fit; account U | SSL and memory fail target | Advertised stack promising; HTTPS conflict unresolved |
| 4 | WooCommerce requirements | Browser demo candidate; actual WC status U | U; cannot infer from PHP/MySQL | Reject for this build | U; cannot infer from installer claim |
| 5 | PHP/WP memory | A: 512 MB PHP per Admin; actual WP limit U | U | R: fixed 32 MB PHP | U |
| 6 | HTTPS/SSL | A: free certificates/subdomain; actual certificate U | A: free subdomain SSL; account U | R: paid hosting required for SSL | A conflicts with observed paid SSL option |
| 7 | WordPress installation | A: Softaculous or file upload; not installed remotely | A: Softaculous; untested | A: Zacky installer; rejected | A: Softaculous; untested |
| 8 | WooCommerce installation | U: install/activate then inspect status | U | Rejected; not attempted | A: installer support; activation U |
| 9 | File upload limits | R: PHP/HTML/JS 1 MB; other files 10 MB; .htaccess separately limited | A: 10 MB maximum upload | R: 15 MB upload; archive restrictions | U; use actual panel values |
| 10 | Database storage | A: 400 databases; no hard size quota documented; fair use applies | A: database support; per-database quota U | R: 30 MB total free DB in terms | U; phpMyAdmin advertised |
| 11 | WordPress REST API | R: external clients blocked by JS/cookie challenge; browser same-origin behavior U | U; do not assume sibling-host rules | U; outbound restrictions also apply | U |
| 12 | Pretty permalinks | A: full .htaccess; actual routes U | U | U | A: .htaccess/mod_rewrite; actual routes U |
| 13 | PHP extensions | U: inspect mysqli,curl,openssl,mbstring,DOM/XML,GD/Imagick,zip,fileinfo | U | R: several network/system functions disabled | U |
| 14 | Email delivery | R: no native mail service; third-party SMTP STARTTLS 587 permitted, delivery U | R: mail filtering/hosted-domain terms; delivery U | R: free outbound SMTP/HTTP blocked | A: one mailbox; sending U and terms restrictions |
| 15 | Background tasks/cron | R: no system/external cron; page-load WP scheduling U | Conflicting/detail-poor cron marketing; account U | U | A: cron; actual job execution U |
| 16 | Payment webhooks | R: external callback requests blocked; live gateways disabled | U | U; outbound API blocked | U |
| 17 | Free subdomain | A: supplied; requested name availability U | A: supplied; name availability U | A: free subdomains; SSL rejected | A: supplied; actual name/HTTPS U |
| 18 | Backup/export | Manual file and phpMyAdmin export path; restore test U | A: FTP/phpMyAdmin; backup service may cost; restore U | Manual backup responsibility in terms | Manual backups required; provider guarantees absent |
| 19 | Usage restrictions | Fair-use resource/file limits; no API/background-service use | Shared resource, script, mail and account limits | Query/connection/file/function quotas in terms | Inactivity/resource/mail limits; marketing conflicts |
| 20 | E-commerce terms | No blanket storefront ban found; callback limits constrain operation | No blanket storefront ban found; actual suitability U | Paid store options exist; free tier technically rejected | E-stores advertised; HTTPS and operational suitability unresolved |

The version comparison uses current [WooCommerce server recommendations](https://woocommerce.com/document/server-requirements/): WordPress 6.9+, PHP 8.3+, MySQL 8+ or MariaDB 10.6+, HTTPS, and at least 256 MB WordPress memory. The installed local versions are recorded separately; remote versions must be measured after installation.

## Supporting official sources

- InfinityFree advertises 5 GB, PHP 8.4, MySQL 8 / MariaDB 11.4, free SSL/subdomains and .htaccess, with no card or trial: [plan](https://www.infinityfree.com/).
- InfinityFree Admin reports 512 MB PHP memory, with an account-level check advised: [memory](https://forum.infinityfree.com/t/128mb/104724/6).
- InfinityFree documents per-file restrictions and unpacking a large installer locally before transferring its ordinary contents: [file limits](https://forum.infinityfree.com/t/download-suddenly-stopped/117121), [large package installation](https://forum.infinityfree.com/t/how-to-upload-big-files-archives/49305). No file splitting or other restriction bypass is proposed.
- InfinityFree Admin permits external SMTP on port 587 with STARTTLS; it is not an included mailbox or a verified delivery service: [SMTP](https://forum.infinityfree.com/t/how-can-i-fix-emailing-issue/88291). [Service terms](https://www.infinityfree.com/terms/).
- ByetHost advertises 5 GB, PHP 8.3, MySQL 8 / MariaDB 11.4, SSL, subdomains, installer and a 10 MB upload limit. Its homepage also mentions cron; the detailed free plan does not establish its behavior: [free plan](https://byet.host/free-hosting), [homepage](https://byet.host/index.php). Its terms restrict bulk email and certain script uses and reserve a charge for requested provider backups: [terms](https://byet.host/terms-of-service).
- AwardSpace free quota, archive, function and connection restrictions: [terms](https://www.awardspace.com/terms-of-services/). Do not use its generic paid-hosting feature descriptions as evidence of free-plan support.
- GoogieHost advertises PHP, MySQL, 1 GB, free SSL/subdomain, .htaccess and cron: [PHP plan](https://googiehost.com/freephphosting). Its terms specify inactivity suspension after 15 days and termination after 22 days, despite different homepage wording, and do not guarantee backups: [terms](https://googiehost.com/terms). The stricter terms must inform any use.
- An additional option, alwaysdata Free, explicitly prohibits profit purposes and therefore is not selected for this business store: [official free-plan restriction](https://help.alwaysdata.com/en/docs/admin-billing/billing/public-cloud-prices/).

## Secure source deployment

InfinityFree officially supports and recommends **FTP over explicit TLS (FTPS)**. It is different from SSH-based SFTP, which the free tier does not provide. The official Admin states that explicit TLS is the supported encryption mode: [recommended FTPS connection](https://forum.infinityfree.com/t/can-i-use-ftps/99359), [protocol clarification](https://forum.infinityfree.com/t/error-massage-502-please-contact-your-network-administrator-to-enable-ftps-service/103878).

Require explicit TLS for authentication and encrypted data transfers, validate the hostname/certificate and use the account's documented server/port. Verify the actual connection before uploading credentials or source. Do not downgrade to cleartext FTP, disable certificate verification, weaken cipher policy, or suppress a TLS failure. An Admin explicitly warns against continuing after a TLS downgrade error: [security guidance](https://forum.infinityfree.com/t/troubles-with-ftp/86764). If secure transfer is unavailable, deployment remains unverified.

The existing GitHub Actions workflow validates PHP/JavaScript and packages custom theme/plugin artifacts. **It does not deploy to a host.** A manual, authenticated FTPS upload is a separate step and must not be described as automatic deployment. A future Actions-to-FTPS workflow needs the actual target directory, encrypted GitHub secrets, certificate validation and a successful observed deployment before it can be claimed to work. Upload only the custom theme/plugin directories; never replace the live database, uploads, wp-config.php, orders, customers or editorial content during code updates.

## Tests still required before a hosted demonstration can be accepted

1. Complete the provider's legitimate signup and verification flow. Confirm $0 total, free subdomain availability, account ownership and administrative access. Keep credentials private.
2. Check actual PHP/DB versions, WordPress memory and extensions in WooCommerce System Status. Verify HTTPS chain, redirects, secure cookies, media uploads and all permalink routes.
3. Test native browser catalog/search/filters, wishlist, variations, stock rejection, cart quantity/removal, checkout validation and a clearly labelled unpaid demonstration order. Confirm saved order, totals, stock change and confirmation screen in WP Admin.
4. Test contact/newsletter storage and privacy controls. Do not claim email delivery until a configured service successfully delivers to an authorized test inbox. Keep campaigns inactive.
5. Observe WordPress scheduling and Action Scheduler behavior within provider rules. Document failures; do not introduce external cron workarounds.
6. Record API and callback restrictions. Keep live payment gateways disabled unless the provider permits callbacks and gateway-specific end-to-end testing succeeds. A successful browser test alone does not establish webhook support.
7. Export files/database privately and verify restoration in an isolated environment. Record the actual deployed revision and test results without publishing customer data or secrets.

Until these tests pass, the public website and hosted checkout remain **not deployed / not verified**. A provider supporting only browser shopping may be used for a prominently labelled demonstration with no live payment or fulfilment promises. No hosting purchase, domain purchase, paid SSL order or subscription is authorized by this report.
