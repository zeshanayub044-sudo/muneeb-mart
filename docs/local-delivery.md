# MUNEEB MART · Local delivery

## 1. Website information

Brand: **MUNEEB MART**. Status: **localhost only**, by the owner’s latest instruction. Store: <http://127.0.0.1:8088/>. Administration: <http://127.0.0.1:8088/wp-admin/>. There is no public live website URL, hosting subscription, or domain purchase.

## 2. GitHub information

Repository: [zeshanayub044-sudo/muneeb-mart](https://github.com/zeshanayub044-sudo/muneeb-mart), private, default branch `main`. The repository contains the custom PHP theme, companion plugin, original optimized imagery, demo data, local tools, documentation and validation workflow. GitHub Actions validates PHP/JavaScript and builds installable archives. It does not deploy to a public host or automatically access the local computer.

## 3. WordPress information

WordPress **7.1.4** and WooCommerce **11.2.1** are active. Theme: **MUNEEB MART 1.1.0**, directory `muneeb-mart`. The local administrator is `muneeb_admin`; the original administrator ID, role and password hash were preserved. The password is available only in the ignored private `.local/access.txt` file and is not in GitHub or the source packages. Local runtime: PHP **8.3.36** with opcode caching and MariaDB **11.4.13**, bound to loopback.

## 4. E-commerce information

The installed catalog contains **20 demonstration products**, **nine collections**, two Finish variations and an out-of-stock product. Currency is PKR and can be changed in WooCommerce. Native cart add/update/remove, totals, configured development shipping, guest checkout, required-field validation, stored order confirmation and stock reduction were verified. Development orders collect no money and are not fulfilled. COD, bank transfer and online gateways require owner configuration before real sales.

## 5. Testing results

The latest complete HTTP suite passed **69/69**. Backend **17/17**, targeted **9/9**, order **6/6**, content administration **6/6**, authenticated administration **6/6**, and branding **10/10** checks passed. Order **#67** is a real WooCommerce test record, unpaid and on hold, totaling PKR 15,000; stock changed 10 → 8. Order #59 was preserved. All seven requested homepage viewport widths passed overflow/image checks. See `testing.md` for scope, prior checks and production limitations.

## 6. Remaining requirements

No hosting action is pending for the chosen localhost scope. Real payments, business contact details, approved legal/shipping/return policies, shipping rates, SMTP and newsletter delivery remain unconfigured. These are required before accepting real orders or choosing a future public launch. No purchase or public deployment has been made.

## 7. Access and management

Run `npm start` if the local services are stopped. Sign in at `/wp-admin/` with the credentials from the private access file. Use Products to add/edit watches, prices, stock, images and collections; WooCommerce → Orders to review orders; Pages to edit content; Appearance → Customize for logo, colors and homepage imagery; Appearance → Menus for navigation. After reviewing source changes from `git pull --ff-only`, use `npm run sync` to update the local theme/plugin while retaining the database and media. Use `npm run package` to regenerate the installers. Further detail is in `admin-guide.md` and `deployment.md`.
