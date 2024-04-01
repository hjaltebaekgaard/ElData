<?php

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

use Google\Client as Google_Client;
use Google\Service\Sheets;
/*
if (!isset($_GET['code'])) {
    $authUrl = $client->createAuthUrl();
    printf("Open the following link in your browser:\n%s\n", $authUrl);
    print 'Enter verification code: ';
    $authCode = trim(fgets(STDIN));

    // Exchange authorization code for an access token
    $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
    $client->setAccessToken($accessToken);

    // Save the access token to a file or database for future use
    file_put_contents(base_path() . 'access_token.json', json_encode($accessToken));
} else {
    // Load previously authorized credentials from a file or database
    $accessToken = json_decode(file_get_contents(base_path() . 'access_token.json'), true);
    $client->setAccessToken($accessToken);
} */

$client = new Google_Client();
$client->setApplicationName('ElDataDesktop-1');
$client->setScopes(Sheets::SPREADSHEETS_READONLY);
$client->setAuthConfig(base_path() . "/credentials.json");
$client->setAccessType('offline');
// $client->setDeveloperKey("AIzaSyDCEcreatqJiO8bP5CgP-bJGD8LQlQQwhs");

if (isset($_GET['code'])) {
    $authCode = $_GET['code'];
    $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
    $client->setAccessToken($accessToken);

    // Save the access token to a file or database for future use
    file_put_contents(base_path() . 'access_token.json', json_encode($accessToken));
} else {
    // If the authorization code is not present, redirect the user to the authorization URL
    $authUrl = $client->createAuthUrl();
    header('Location: ' . $authUrl);
    exit;
}


$service = new Sheets($client);

// Set the authorized redirect URI. This should match the one set in the OAuth credentials.
$client->setRedirectUri("http://127.0.0.1:8000/google");

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


$refreshToken = [
    "last_refresh" => 0,
    "token" => "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ0b2tlblR5cGUiOiJDdXN0b21lckFQSV9EYXRhQWNjZXNzIiwidG9rZW5pZCI6IjhkZTkxOGFiLTM1MjAtNDU3OC05MjM3LWUxYTMxZTFkM2U5YSIsIndlYkFwcCI6IkN1c3RvbWVyQXBwIiwidmVyc2lvbiI6IjIiLCJpZGVudGl0eVRva2VuIjoiTmdTRTVMcy8wOHo2OHh0TXZYdlQxRUNtc0lVczlaUXVoOHZRUG00Lyt3MzFFUENWa2xaRFIrakpYVEM2YzhVRWswWDJ6RUlVbFd1alp6cVNZeWlXZCs2RldCaTg3UXJQN0NFcGU5Z3pCR21jMWZyUm1QK0pHVlNjVjRtZWpuWmNrYVRkNDFLcFZiVVZuRk1naVFFOW96UTQ0b3FVeHBYdDBaRjgzTXBKMHVQTG1NT3N3L3FmZXJiZTZGTU9QdlZZaWZmcWQ4Y2p1OEpqKzZNR1VwU1JEaDdhQ3ZMMTJYbzU4WUdpdTlURTNnTEhzNWxockJhVURRd1lsdUNYeHNxbXNnWU51c0E2RXpRR1NwTHdZZVV5c1QvV295N0VPNlpaaThCQ0MxN1hyQVZDNEVrcmlGWWJIYkx3TUpRTHgwb0RvWC9QVkYvNzFycC9obFp5YlU3b2s4cHlDY3FzN0M0TzQxOFZsWHh5M3FPc0NFSUM0TDFFY3E1VEc1enVpOFNjaWVtS2MxWjVlNy95aHI3Ky9XRlMvc29BQnY1NkdhNWFsRWcvMzN0SVpKL1ppVFlYa1hZMUlSRVhHRVNxQzVWTXJ3SFlUU3VNR2VPZEJvaGFhVmg2Z3Rtb2dycmdzSUxIMG9ScHJoUFNYa0M1Z25tdW91WVJSQUVFd3Nab2syeFlHQTloZ2drd044YXd3ckdZY2xTMVVvL3FxZ2R6ZVZGTVRHa1pUSWRPS3FUZFBLS0dtVFRwcmJVcERKT1F0ck5keitwME9PZkUrdURVbnNaTWNiM0RrOE0yNWZHUm95a2xhT1VPb2IvN0pzZEtGc0hZM3lrcllxUStDNHJtSVpRRHVWTm1RWFlVR1pSc0FNQ3Zjb3VVRzhsRERQdlljdWRoL1N0aDYza29hUm5YTG1uSTk0UmovZGN1Z2gzZFVGcnRCbWtGNHVzQ01FcE5HWVJGaWtNbjVtUHBodmlnczFKRVExck1HM3AyaW9uajF5ZWVhcTVKUkdUMjJBY3NyUHdlQXdIM2pGRjlBWXVUQ1RlWWJHTmViU3gweWZ1K0dscTVLRkRlS0MyVDdLYU5kQVgwV3lXTWpCTlR4QXhwZzVXaHpqRDJqdW5DWFlaZ1dsbVJ6eFd2UXZydTgyTTVpTStBQUlKMytzeEZxU1VlYjMwdjQ0WnpPSHlJUlJXUEIzbUVCbFgwUm1sUUFpWFROU3pjMWVGbmpYaTR4L2tVaUdtSmVreVozQnhIV2lmMzdlZFlQUkI4QWF3N2UyZFVSL2p3cEIxb0ZLTlVjajBEK3FJRDJMdyttYmFsa2JYdXNscG1kQldMOUR4bThHOE04SmZaUk9LeDliSUFuclJmZjNIeWp3WHRUQmRIaXo2Ymc3aVErMzd6QjgvWmxQaUd0Y21aTEZKZHNhTERYdm5UQ01IOEJxVDAvOVpkb1Btem43aG0zY0ZVTWk2eGJidG8xcGlWYm53SWFnTWZLYThXK1FpblRIRnVVTWdTNnVTOHNidXRMMGxBNFJEckdxYjBSVTdPY291c3ZwdjdvN1d6dzhwU1ROeEpvb2NpY0VGZndrUGlJVnBlMFpwelhBaFlTT1hEYWp4WnVjQkNWTFFTQTc2VlM3QThDRy9RTXZydGpKYTNMckJnbFJUZEU1U0ZLUkpZbHJxQnV0VDJLK0R4UndlN0pTTlkwSHRxbUFSSUhjeFRhbTA0M0JXbzZtWGdHQXdnWWN5bGZLQTdLUmZaMkxHZFlMSHh2Y1dCWjhFcnBGTFZCUU1zRFlXN01WdTVzc3k5aEw3aWtjeTdFektpejgrUm16RkJLcW5uRjN0NTRmak1GZDFhRnJvc0h5bGcraTVRdVlFQzFhTDMvZVpVNFRXKzdUZmdkOG9CTDY1WnlwenQvd2ZJdnVicVpKa1c4cXRGdVgwRnJTc3A0SmdlcFBSSlNXTzBKVThLTFFqL2w3MHROblZsT2RaejExZDhKMnpXeDJoTTF3TTNNa1JXcGJoWjVmTkJTSStHTEtWeVpTd0xjbWsyZjg1RmYzcmhoY1puM2ZLZml5bE9mQTE5MGdqeFZvOWlaUVo2U0c2R1dyMXJDZURveFU4SFIrS0VqWTIxcWpVYjdhSjZjL1k4RU12YzFGcDdQcHZLYkdZOGVNcGIwNVpJQWoySG9PNEdYQXJQcEg2VFAxR0tOVGI0Zjc3clNUTHRLRzNDazVXamN5a1ZHU0RKdHoxcHBTazltOTEyZzMzaWxzb0ZnTEplMklpSFZWeEorYXdoWE9xbEV6dzFicmNpRFZXdDN3QUZ2Y2Y5QXEyR0I3Yml4WnhVQVBOTldxenlIMEJ2S0pxbjRaVzJLTjZwcUhmaTlnbWNkeWwwMnpGbDhDWVRMeXlKZ3JFQUpSVTRCVWtKc1BBbUdxMm9FZXVuOEIzTTk5YlJKRldLWUR3cHA0dEN4d0FMdFA0eXVJUDhHcjZ6dEtrR29KaTRzZWlOUDd0WGlRL2EwTmxwWFJqVmtYeXBqRGgrN2ZOVFJITFJDQ0F4ZHBJQjJDSFdPSUUvSlV2MS9RNFM2RXdXIiwiaHR0cDovL3NjaGVtYXMueG1sc29hcC5vcmcvd3MvMjAwNS8wNS9pZGVudGl0eS9jbGFpbXMvZ2l2ZW5uYW1lIjoiTWljaGFlbCBCw6ZrZ2FhcmQgTmllbHNlbiIsImxvZ2luVHlwZSI6IktleUNhcmQiLCJiM2YiOiIvbmhXMGVQWG9NOEc5NnNacHhnTy9KTXJQMXY4SXljbFp6UXN0VGRlZS9ZPSIsInBpZCI6IlBJRDo5MjA4LTIwMDItMi00OTU3OTk1MTcyODYiLCJ1c2VySWQiOiIxOTc0NzkiLCJodHRwOi8vc2NoZW1hcy54bWxzb2FwLm9yZy93cy8yMDA1LzA1L2lkZW50aXR5L2NsYWltcy9uYW1laWRlbnRpZmllciI6IlBJRDo5MjA4LTIwMDItMi00OTU3OTk1MTcyODYiLCJleHAiOjE3MTE3MzE0NjUsImlzcyI6IkVuZXJnaW5ldCIsImp0aSI6IjhkZTkxOGFiLTM1MjAtNDU3OC05MjM3LWUxYTMxZTFkM2U5YSIsInRva2VuTmFtZSI6ImhqYWx0ZSIsImF1ZCI6IkVuZXJnaW5ldCJ9.43gaNR9jXUMsEzIUjsUuXAIechJXFtGYsKhTaq9y1iM"
];

$test = "Test";

Route::get('/', function () use ($refreshToken) {
    dd("BEARER {$refreshToken['token']}");
    $start = date('Y-m-d', now()->subDays(2)->timestamp);
    $end = date('Y-m-d', now()->subDay()->timestamp);
    return [$start, $end];
    // return view('welcome');
});

/* response from /token request {"result":"eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ0b2tlblR5cGUiOiJDdXN0b21lckFQSV9EYXRhQWNjZXNzIiwidG9rZW5pZCI6IjA4YTg1N2I4LWFhMmEtNDg5MS05MWM5LTc3ODQ4YzgzNGQ5NCIsIndlYkFwcCI6IkN1c3RvbWVyQXBwIiwidmVyc2lvbiI6IjIiLCJpZGVudGl0eVRva2VuIjoiTmdTRTVMcy8wOHo2OHh0TXZYdlQxRUNtc0lVczlaUXVoOHZRUG00Lyt3MzFFUENWa2xaRFIrakpYVEM2YzhVRWswWDJ6RUlVbFd1alp6cVNZeWlXZCs2RldCaTg3UXJQN0NFcGU5Z3pCR21jMWZyUm1QK0pHVlNjVjRtZWpuWmNrYVRkNDFLcFZiVVZuRk1naVFFOW96UTQ0b3FVeHBYdDBaRjgzTXBKMHVQTG1NT3N3L3FmZXJiZTZGTU9QdlZZaWZmcWQ4Y2p1OEpqKzZNR1VwU1JEaDdhQ3ZMMTJYbzU4WUdpdTlURTNnTEhzNWxockJhVURRd1lsdUNYeHNxbXNnWU51c0E2RXpRR1NwTHdZZVV5c1QvV295N0VPNlpaaThCQ0MxN1hyQVZDNEVrcmlGWWJIYkx3TUpRTHgwb0RvWC9QVkYvNzFycC9obFp5YlU3b2s4cHlDY3FzN0M0TzQxOFZsWHh5M3FPc0NFSUM0TDFFY3E1VEc1enVpOFNjaWVtS2MxWjVlNy95aHI3Ky9XRlMvc29BQnY1NkdhNWFsRWcvMzN0SVpKL1ppVFlYa1hZMUlSRVhHRVNxQzVWTXJ3SFlUU3VNR2VPZEJvaGFhVmg2Z3Rtb2dycmdzSUxIMG9ScHJoUFNYa0M1Z25tdW91WVJSQUVFd3Nab2syeFlHQTloZ2drd044YXd3ckdZY2xTMVVvL3FxZ2R6ZVZGTVRHa1pUSWRPS3FUZFBLS0dtVFRwcmJVcERKT1F0ck5keitwME9PZkUrdURVbnNaTWNiM0RrOE0yNWZHUm95a2xhT1VPb2IvN0pzZEtGc0hZM3lrcllxUStDNHJtSVpRRHVWTm1RWFlVR1pSc0FNQ3Zjb3VVRzhsRERQdlljdWRoL1N0aDYza29hUm5YTG1uSTk0UmovZGN1Z2gzZFVGcnRCbWtGNHVzQ01FcE5HWVJGaWtNbjVtUHBodmlnczFKRVExck1HM3AyaW9uajF5ZWVhcTVKUkdUMjJBY3NyUHdlQXdIM2pGRjlBWXVUQ1RlWWJHTmViU3gweWZ1K0dscTVLRkRlS0MyVDdLYU5kQVgwV3lXTWpCTlR4QXhwZzVXaHpqRDJqdW5DWFlaZ1dsbVJ6eFd2UXZydTgyTTVpTStBQUlKMytzeEZxU1VlYjMwdjQ0WnpPSHlJUlJXUEIzbUVCbFgwUm1sUUFpWFROU3pjMWVGbmpYaTR4L2tVaUdtSmVreVozQnhIV2lmMzdlZFlQUkI4QWF3N2UyZFVSL2p3cEIxb0ZLTlVjajBEK3FJRDJMdyttYmFsa2JYdXNscG1kQldMOUR4bThHOE04SmZaUk9LeDliSUFuclJmZjNIeWp3WHRUQmRIaXo2Ymc3aVErMzd6QjgvWmxQaUd0Y21aTEZKZHNhTERYdm5UQ01IOEJxVDAvOVpkb1Btem43aG0zY0ZVTWk2eGJidG8xcGlWYm53SWFnTWZLYThXK1FpblRIRnVVTWdTNnVTOHNidXRMMGxBNFJEckdxYjBSVTdPY291c3ZwdjdvN1d6dzhwU1ROeEpvb2NpY0VGZndrUGlJVnBlMFpwelhBaFlTT1hEYWp4WnVjQkNWTFFTQTc2VlM3QThDRy9RTXZydGpKYTNMckJnbFJUZEU1U0ZLUkpZbHJxQnV0VDJLK0R4UndlN0pTTlkwSHRxbUFSSUhjeFRhbTA0M0JXbzZtWGdHQXdnWWN5bGZLQTdLUmZaMkxHZFlMSHh2Y1dCWjhFcnBGTFZCUU1zRFlXN01WdTVzc3k5aEw3aWtjeTdFektpejgrUm16RkJLcW5uRjN0NTRmak1GZDFhRnJvc0h5bGcraTVRdVlFQzFhTDMvZVpVNFRXKzdUZmdkOG9CTDY1WnlwenQvd2ZJdnVicVpKa1c4cXRGdVgwRnJTc3A0SmdlcFBSSlNXTzBKVThLTFFqL2w3MHROblZsT2RaejExZDhKMnpXeDJoTTF3TTNNa1JXcGJoWjVmTkJTSStHTEtWeVpTd0xjbWsyZjg1RmYzcmhoY1puM2ZLZml5bE9mQTE5MGdqeFZvOWlaUVo2U0c2R1dyMXJDZURveFU4SFIrS0VqWTIxcWpVYjdhSjZjL1k4RU12YzFGcDdQcHZLYkdZOGVNcGIwNVpJQWoySG9PNEdYQXJQcEg2VFAxR0tOVGI0Zjc3clNUTHRLRzNDazVXamN5a1ZHU0RKdHoxcHBTazltOTEyZzMzaWxzb0ZnTEplMklpSFZWeEorYXdoWE9xbEV6dzFicmNpRFZXdDN3QUZ2Y2Y5QXEyR0I3Yml4WnhVQVBOTldxenlIMEJ2S0pxbjRaVzJLTjZwcUhmaTlnbWNkeWwwMnpGbDhDWVRMeXlKZ3JFQUpSVTRCVWtKc1BBbUdxMm9FZXVuOEIzTTk5YlJKRldLWUR3cHA0dEN4d0FMdFA0eXVJUDhHcjZ6dEtrR29KaTRzZWlOUDd0WGlRL2EwTmxwWFJqVmtYeXBqRGgrN2ZOVFJITFJDQ0F4ZHBJQjJDSFdPSUUvSlV2MS9RNFM2RXdXIiwiaHR0cDovL3NjaGVtYXMueG1sc29hcC5vcmcvd3MvMjAwNS8wNS9pZGVudGl0eS9jbGFpbXMvZ2l2ZW5uYW1lIjoiTWljaGFlbCBCw6ZrZ2FhcmQgTmllbHNlbiIsImxvZ2luVHlwZSI6IktleUNhcmQiLCJiM2YiOiIvbmhXMGVQWG9NOEc5NnNacHhnTy9KTXJQMXY4SXljbFp6UXN0VGRlZS9ZPSIsInBpZCI6IlBJRDo5MjA4LTIwMDItMi00OTU3OTk1MTcyODYiLCJ1c2VySWQiOiIxOTc0NzkiLCJodHRwOi8vc2NoZW1hcy54bWxzb2FwLm9yZy93cy8yMDA1LzA1L2lkZW50aXR5L2NsYWltcy9uYW1laWRlbnRpZmllciI6IlBJRDo5MjA4LTIwMDItMi00OTU3OTk1MTcyODYiLCJleHAiOjE3MTE1NTYyNTcsImlzcyI6IkVuZXJnaW5ldCIsImp0aSI6IjA4YTg1N2I4LWFhMmEtNDg5MS05MWM5LTc3ODQ4YzgzNGQ5NCIsInRva2VuTmFtZSI6ImhqYWx0ZSIsImF1ZCI6IkVuZXJnaW5ldCJ9.BrYwWrl-DSGHE0vPiVV3KobYT0KdZYHD2hDMLls1udc"} */

Route::get('/data/{meteringpointid}', function ($id, $start = NULL, $end = NULL) use ($refreshToken) {
    if (!$start) $start = date('Y-m-d', now()->subDays(2)->timestamp);
    if (!$end) $end = date('Y-m-d', now()->subDay()->timestamp);
    // Make a GET request to the REST API endpoint
    $url = "https://api.eloverblik.dk/customerapi/api/meterdata/gettimeseries/{$start}/{$end}/Hour";
    // dd($url);
    $headers = [
        "Content-Type" => 'application/json',
        "Authorization" => "BEARER {$refreshToken['token']}",
        "accept" => 'application/json'
    ];

    // $headers->accept;
    $postData =
        [
            'meteringPoints' => [
                'meteringPoint' => [
                    "$id"
                ]
            ]
        ];
    // dd([$id, $headers, $postData]);
    $response = Http::withHeaders($headers)->post($url, $postData);

    // Check if request was successful
    if ($response->successful()) {
        // Get JSON data from the response

        $data = $response->json();
        return $data['result'][0]['MyEnergyData_MarketDocument']['TimeSeries'][0]['Period'];
    } else {
        // Handle error
        // dd($response);
        return response()->json(['error' => 'Failed to fetch data from API'], $response->status());
    }
});

Route::get('/getmeteringpoints', function () use ($refreshToken, $test) {

    // Make a GET request to the REST API endpoint
    $headers = [
        "Authorization" => "BEARER {$refreshToken['token']}",
        "accept" => 'application/json'
    ];

    $response = Http::withHeaders($headers)->get('https://api.eloverblik.dk/customerapi/api/meteringpoints/meteringpoints');

    // Check if request was successful
    if ($response->successful()) {
        // Get JSON data from the response

        $data = $response->json();
        // $test = $data["result"][0]["streetCode"];
        // dd($test);
        return $data;
    } else {
        // Handle error
        return response()->json(['error' => 'Failed to fetch data from API'], $response->status());
    }
});


Route::get('/gettoken', function () use ($refreshToken) {
    // Make a GET request to the REST API endpoint
    $headers = [
        "Authorization" => 'BEARER eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ0b2tlblR5cGUiOiJDdXN0b21lckFQSV9SZWZyZXNoIiwidG9rZW5pZCI6ImZhZDc4ZjAyLWFlNzctNDNlNS05MjRmLTM3MjNiMjE5MjliYSIsIndlYkFwcCI6IkN1c3RvbWVyQXBwIiwidmVyc2lvbiI6IjIiLCJpZGVudGl0eVRva2VuIjoiTmdTRTVMcy8wOHo2OHh0TXZYdlQxRUNtc0lVczlaUXVoOHZRUG00Lyt3MzFFUENWa2xaRFIrakpYVEM2YzhVRWswWDJ6RUlVbFd1alp6cVNZeWlXZCs2RldCaTg3UXJQN0NFcGU5Z3pCR21jMWZyUm1QK0pHVlNjVjRtZWpuWmNrYVRkNDFLcFZiVVZuRk1naVFFOW96UTQ0b3FVeHBYdDBaRjgzTXBKMHVQTG1NT3N3L3FmZXJiZTZGTU9QdlZZaWZmcWQ4Y2p1OEpqKzZNR1VwU1JEaDdhQ3ZMMTJYbzU4WUdpdTlURTNnTEhzNWxockJhVURRd1lsdUNYeHNxbXNnWU51c0E2RXpRR1NwTHdZZVV5c1QvV295N0VPNlpaaThCQ0MxN1hyQVZDNEVrcmlGWWJIYkx3TUpRTHgwb0RvWC9QVkYvNzFycC9obFp5YlU3b2s4cHlDY3FzN0M0TzQxOFZsWHh5M3FPc0NFSUM0TDFFY3E1VEc1enVpOFNjaWVtS2MxWjVlNy95aHI3Ky9XRlMvc29BQnY1NkdhNWFsRWcvMzN0SVpKL1ppVFlYa1hZMUlSRVhHRVNxQzVWTXJ3SFlUU3VNR2VPZEJvaGFhVmg2Z3Rtb2dycmdzSUxIMG9ScHJoUFNYa0M1Z25tdW91WVJSQUVFd3Nab2syeFlHQTloZ2drd044YXd3ckdZY2xTMVVvL3FxZ2R6ZVZGTVRHa1pUSWRPS3FUZFBLS0dtVFRwcmJVcERKT1F0ck5keitwME9PZkUrdURVbnNaTWNiM0RrOE0yNWZHUm95a2xhT1VPb2IvN0pzZEtGc0hZM3lrcllxUStDNHJtSVpRRHVWTm1RWFlVR1pSc0FNQ3Zjb3VVRzhsRERQdlljdWRoL1N0aDYza29hUm5YTG1uSTk0UmovZGN1Z2gzZFVGcnRCbWtGNHVzQ01FcE5HWVJGaWtNbjVtUHBodmlnczFKRVExck1HM3AyaW9uajF5ZWVhcTVKUkdUMjJBY3NyUHdlQXdIM2pGRjlBWXVUQ1RlWWJHTmViU3gweWZ1K0dscTVLRkRlS0MyVDdLYU5kQVgwV3lXTWpCTlR4QXhwZzVXaHpqRDJqdW5DWFlaZ1dsbVJ6eFd2UXZydTgyTTVpTStBQUlKMytzeEZxU1VlYjMwdjQ0WnpPSHlJUlJXUEIzbUVCbFgwUm1sUUFpWFROU3pjMWVGbmpYaTR4L2tVaUdtSmVreVozQnhIV2lmMzdlZFlQUkI4QWF3N2UyZFVSL2p3cEIxb0ZLTlVjajBEK3FJRDJMdyttYmFsa2JYdXNscG1kQldMOUR4bThHOE04SmZaUk9LeDliSUFuclJmZjNIeWp3WHRUQmRIaXo2Ymc3aVErMzd6QjgvWmxQaUd0Y21aTEZKZHNhTERYdm5UQ01IOEJxVDAvOVpkb1Btem43aG0zY0ZVTWk2eGJidG8xcGlWYm53SWFnTWZLYThXK1FpblRIRnVVTWdTNnVTOHNidXRMMGxBNFJEckdxYjBSVTdPY291c3ZwdjdvN1d6dzhwU1ROeEpvb2NpY0VGZndrUGlJVnBlMFpwelhBaFlTT1hEYWp4WnVjQkNWTFFTQTc2VlM3QThDRy9RTXZydGpKYTNMckJnbFJUZEU1U0ZLUkpZbHJxQnV0VDJLK0R4UndlN0pTTlkwSHRxbUFSSUhjeFRhbTA0M0JXbzZtWGdHQXdnWWN5bGZLQTdLUmZaMkxHZFlMSHh2Y1dCWjhFcnBGTFZCUU1zRFlXN01WdTVzc3k5aEw3aWtjeTdFektpejgrUm16RkJLcW5uRjN0NTRmak1GZDFhRnJvc0h5bGcraTVRdVlFQzFhTDMvZVpVNFRXKzdUZmdkOG9CTDY1WnlwenQvd2ZJdnVicVpKa1c4cXRGdVgwRnJTc3A0SmdlcFBSSlNXTzBKVThLTFFqL2w3MHROblZsT2RaejExZDhKMnpXeDJoTTF3TTNNa1JXcGJoWjVmTkJTSStHTEtWeVpTd0xjbWsyZjg1RmYzcmhoY1puM2ZLZml5bE9mQTE5MGdqeFZvOWlaUVo2U0c2R1dyMXJDZURveFU4SFIrS0VqWTIxcWpVYjdhSjZjL1k4RU12YzFGcDdQcHZLYkdZOGVNcGIwNVpJQWoySG9PNEdYQXJQcEg2VFAxR0tOVGI0Zjc3clNUTHRLRzNDazVXamN5a1ZHU0RKdHoxcHBTazltOTEyZzMzaWxzb0ZnTEplMklpSFZWeEorYXdoWE9xbEV6dzFicmNpRFZXdDN3QUZ2Y2Y5QXEyR0I3Yml4WnhVQVBOTldxenlIMEJ2S0pxbjRaVzJLTjZwcUhmaTlnbWNkeWwwMnpGbDhDWVRMeXlKZ3JFQUpSVTRCVWtKc1BBbUdxMm9FZXVuOEIzTTk5YlJKRldLWUR3cHA0dEN4d0FMdFA0eXVJUDhHcjZ6dEtrR29KaTRzZWlOUDd0WGlRL2EwTmxwWFJqVmtYeXBqRGgrN2ZOVFJITFJDQ0F4ZHBJQjJDSFdPSUUvSlV2MS9RNFM2RXdXIiwiaHR0cDovL3NjaGVtYXMueG1sc29hcC5vcmcvd3MvMjAwNS8wNS9pZGVudGl0eS9jbGFpbXMvZ2l2ZW5uYW1lIjoiTWljaGFlbCBCw6ZrZ2FhcmQgTmllbHNlbiIsImxvZ2luVHlwZSI6IktleUNhcmQiLCJiM2YiOiIvbmhXMGVQWG9NOEc5NnNacHhnTy9KTXJQMXY4SXljbFp6UXN0VGRlZS9ZPSIsInBpZCI6IlBJRDo5MjA4LTIwMDItMi00OTU3OTk1MTcyODYiLCJ1c2VySWQiOiIxOTc0NzkiLCJodHRwOi8vc2NoZW1hcy54bWxzb2FwLm9yZy93cy8yMDA1LzA1L2lkZW50aXR5L2NsYWltcy9uYW1laWRlbnRpZmllciI6IlBJRDo5MjA4LTIwMDItMi00OTU3OTk1MTcyODYiLCJleHAiOjE3NDA3NzIxMDksImlzcyI6IkVuZXJnaW5ldCIsImp0aSI6ImZhZDc4ZjAyLWFlNzctNDNlNS05MjRmLTM3MjNiMjE5MjliYSIsInRva2VuTmFtZSI6ImhqYWx0ZSIsImF1ZCI6IkVuZXJnaW5ldCJ9.H8I6OlM03iRUf23GZZ8LfYUwQ-XcBvDb9_o4TBJ_VkA',
        "accept" => 'application/json'
    ];

    $response = Http::withHeaders($headers)->get('https://api.eloverblik.dk/customerapi/api/token');

    // Check if request was successful
    if ($response->successful()) {
        // Get JSON data from the response

        $data = $response->json();
        $refreshToken['token'] = $data['result'];
        return $data;
    } else {
        // Handle error
        return response()->json(['error' => 'Failed to fetch data from API'], $response->status());
    }
});

Route::get('/google', function () use ($service) {

    $spreadsheetId = '1uHk2hL2MgDKjUhNIpo2G99RS9ORxEye4tjV1ZMAiuXc';
    $range = 'Sheet3!A1';
    $response = $service->spreadsheets_values->get($spreadsheetId, $range);
    $values = $response->getValues();

    if (empty($values)) {
        print "No data found.\n";
    } else {

        var_dump($response);
    }
});
