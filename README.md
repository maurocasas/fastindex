# FastIndex

FastIndex is a must-have for SEO marketers who want to have their pages indexed faster by Google Search Console.
Spin up a VPS and install FastIndex within a minutes, get a complete indexing toolkit entirely free.

### Looking for a managed version?

Visit [https://fastindex.app/cloud](https://fastindex.app/cloud) and subscribe to get a fully managed installation without hassling with servers or configurations.

----------------------------------------------------------------------------------------------------

### Stack

* Laravel 11
* TailwindCSS
* Laravel Livewire
* Laravel Horizon for queue management (Redis required)
* SQLite/MySQL (your choice)
* Redis (optional, required for Horizon)
* Vite

----------------------------------------------------------------------------------------------------

### Features

1. Manage unlimited Google Search Console accounts through Service Accounts
2. Manage unlimited sites
3. Manage all your sites pages under the same roof and get insights on their indexing status
4. Team management with `admin` and `member` roles.
5. Automated backups powered by `spatie/laravel-backup`
6. Check for 404 pages automatically

----------------------------------------------------------------------------------------------------

### Requirements

* PHP >8.0
* NodeJS >18
* Composer
* NPM

----------------------------------------------------------------------------------------------------

### Stack

FastIndex has been built using Laravel, Livewire and Tailwind.

----------------------------------------------------------------------------------------------------

### Requirements

1. PHP >8.0
2. Google Search Console service account

----------------------------------------------------------------------------------------------------

### How to install FastIndex

1. Clone the repo
2. Run `composer install`
3. Copy `.env.example` to `.env` and setup your environment.
4. If you're using SQLite, you need to create `database/database.sqlite`
5. Run `php artisan migrate --seed`
6. Run `npm i && npm run build` to compile assets
7. Visit your fresh installation
8. Login using `user@user.com` `password`
9. Enjoy :)

----------------------------------------------------------------------------------------------------

### How to create a service account for GSC and sync with FastIndex

1. Visit `https://console.cloud.google.com/`
2. Create a new project if you don't already have one, otherwise skip this step
3. Enable `Google Search Console API` through `APIs & Services` page
4. Navigate to `APIs & Services > Credentials`, click `+ Create Credentials > Service Account`
5. Complete the required information and create your credentials
6. Navigate to your new credentials details page and go to `Keys` on the tabs menu
7. Click `Add key > Create new key > JSON`, a JSON file will be downloaded automatically 
8. Navigate to `FastIndex > Service Accounts`, click `Link service account` and upload your JSON
9. Copy-paste your `Client ID` from your `Service Accounts` page
10. Navigate to `https://search.google.com/search-console/` and visit the property you'd like to index
11. Go to `Settings > Users and permissions`
12. Click `Add User` and paste your `Client ID` as e-mail, grant `Owner`
13. Navigate to `FastIndex > Sites` and click `Sync sites`
