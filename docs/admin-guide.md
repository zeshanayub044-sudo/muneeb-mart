# Owner management guide

## Products, collections and orders

- **Add a watch:** Products → Add New. Enter name, descriptions, verified price and specifications, SKU, product image and gallery. Assign collections under Product categories. Publish when reviewed.
- **Edit price or stock:** open the product, use Product data → General / Inventory, then Update. Variable products manage price, SKU and stock under Variations.
- **Change pictures:** replace Product image and Product gallery. Add descriptive alternative text in the Media Library. Use original or licensed imagery.
- **Create a collection:** Products → Categories. Add a name, slug, meaningful description, and thumbnail. Assign products. Collection navigation on the Collections page uses this data.
- **New arrivals:** the homepage always queries recently published products. The New Arrivals collection lists products by publication date.
- **Orders:** WooCommerce → Orders. Test orders say “Development test order” and are on hold. Never fulfill them. Cancellation through WooCommerce restores stock according to WooCommerce settings. Use only approved payment providers for real orders.
- **Coupons and reports:** WooCommerce's Marketing / Analytics screens. Popularity and customer ratings are not fabricated by the theme.

## Brand and content

Appearance → Customize → MUNEEB MART · Store design manages the announcement, hero headline and description, CTA, photography, collection and featured headings, brand story, campaign heading, footer description, accent color, and published contact/social details. Site Identity replaces the text logo. Appearance → Menus controls primary navigation. Images are replaceable through the Media Library.

Pages → All Pages manages About, Contact, FAQ and all policy text in the WordPress editor. Contact uses `[mm_contact]`, Collections uses `[mm_collections]`, and Wishlist uses `[mm_wishlist]`. Preserve commerce shortcodes on cart, checkout and account pages. Home has custom theme sections plus optional editor content appended below them. Default section copy can be changed with the Customizer, rather than editing PHP.

WooCommerce → Settings controls currency, customer accounts, stock, shipping zones, taxes, payments and transactional emails. Review the country, currency, tax and shipping settings for the real business before launch. COD and bank transfer are native WooCommerce options; configure their instructions and limits intentionally. Online gateway availability must be checked for the business and country.

## Enquiries, consent and privacy

Store enquiries and Subscribers appear as private admin-only records. Contact messages are saved even if email notifications fail. To receive notifications, configure a support recipient under WooCommerce → MUNEEB MART setup, connect SMTP, and verify delivery. Delivery is not claimed when an enquiry is merely saved.

The newsletter records email, explicit consent, and timestamp. This version does not send campaigns. Connect an approved email provider, transfer only consented addresses, and include working unsubscribe links in each email. Process unsubscribe requests received through Contact promptly by removing the subscriber record. Set a documented retention schedule. WordPress Tools → Export / Erase Personal Data includes custom enquiries and subscribers. WooCommerce separately handles order privacy.

## Launch

Replace every demo product, verify images and claims, approve the policy drafts and business details, configure shipping/tax/payments, test transactional mail and a sandbox transaction, make backups, then disable demonstration mode. Change `WP_ENVIRONMENT_TYPE` to `production`, disable debug logging/display, enable HTTPS and caching exclusions for commerce/session pages, and allow indexing only after final review.
