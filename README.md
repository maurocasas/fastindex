# About the project

FastIndex is an open-source and self-hostable alternative to TagParrot, Omega Indexer, URLMonitor, and similar tools. It helps your sites get crawled & indexed faster by Google Search Console.

![frame_generic_light(4)](https://github.com/user-attachments/assets/8960bfce-ca08-4bf6-890d-b98205fb35b5)

Google Search Console crawling can take weeks or months to reach your site; even after all this waiting, it can still fail. FastIndex leverages Search API to push your pages directly to their crawlers, prioritizing them.

Instead of paying for tiered software, install FastIndex on any VPS and run an uncapped solution. Enjoy unlimited sites, sitemaps, pages - unlimited everything.

No vendor lock-in means all your data is yours and will always be yours.

# Features

1. Connects with your Search Console through Service Accounts, allowing auto-indexing of up to 2,000 pages per day.
2. Manages unlimited service accounts and sites, with no limits on the number of pages.
3. Monitors sitemaps & sitemap indexes daily, auto-indexing new pages and de-indexing broken pages.
4. Checks for broken links daily and de-indexes them to avoid harming your searchability.
5. Implements automatic quota management to avoid abusing Search API.
6. Supports creation of multiple user accounts for team management.
7. Warns if a site has more than one service account linked, which violates GSC Terms of Service.

# System Requirements

* PHP 8.0 or higher
* Node 18 or higher
* MySQL or SQLite _MySQL recommended for larger sites_
* Composer

# Quick Start

1. `composer create-project maurocasas/fastindex`
2. `npm i`
3. `npm run build`
4. `php artisan db:seed`
5. `php artisan serve`
6. Log-in using `user@user.com` `password`

For more detailed instructions please refer to the Wiki.

# Support

* Twitter/X: [@maurohouseless](https://x.com/maurohouseless)

# Documentation

Please refer to the [Wiki](https://github.com/maurocasas/fastindex/wiki)

# Managed Cloud

If running servers, configuring databases, backups, cronjobs and everything here is not for you take a look at the [Managed Cloud](https://github.com/maurocasas/fastindex/wiki/0.-Managed-Cloud)

# Donations/Sponsorship

To stay completely free and open-source, without any feature limitation or paywall I need your support.
If FastIndex is saving you money and helping you index your sites faster, please consider Sponsoring me and the project.

[https://github.com/sponsors/maurocasas](https://github.com/sponsors/maurocasas)

Thank you so much!
