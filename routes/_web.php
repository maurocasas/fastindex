<?php

use App\Jobs\Pages\RequestIndex;
use Google\Client;
use Google\Service\SearchConsole;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/google/login', fn() => Socialite::driver('google')
    ->scopes([
        'https://www.googleapis.com/auth/webmasters',
        'https://www.googleapis.com/auth/webmasters.readonly',
    ])
    ->with([
        'access_type' => 'offline'
    ])
    ->redirect());

Route::get('/google/redirect', function () {
    dd(Socialite::driver('google')->user());
});

Route::get('/sites', function () {
    $client = new Client();
    $client->setAuthConfig(json_decode(file_get_contents(app_path('/../credentials.json')), true));
    $client->addScope('https://www.googleapis.com/auth/webmasters.readonly');

    $service = new SearchConsole($client);
    //ya29.a0AcM612zqx3F46MF2A9nF7vfW4JRsUgQuA9ViHxalvb6Wa-4ICXaKsQVGNncgksi0DZ5BOn83KxmQsejJE-38yEivSd5d68l1LbGxsnhvPlrVOjDGTKmYj-Ev-oXZGJ_mpU_Xjrk9TZftso4gRWiNL1axJxvJJ-QyBj_PjFXsaCgYKAU0SARESFQHGX2Mivt3CYL-Ta_DphwJDZog3vA0175

    $sites = $service->sites->listSites();

    $mappedSites = array_map(fn($site) => $site->getSiteUrl(), $sites->getSiteEntry());

    $urls = [];

    foreach($mappedSites as $site) {
        $sitemaps = $service->sitemaps->listSitemaps($site);

        foreach($sitemaps->getSitemap() as $item) {
            $xmlData = file_get_contents($item->getPath());

            $xml = simplexml_load_string($xmlData);

            foreach ($xml->url as $url) {
                $urls[] = (string)$url->loc;
            }
        }
    }

    foreach ($urls as $url) {
        dispatch(new RequestIndex($mappedSites[0], $url));
    }

    return $responses;
});
