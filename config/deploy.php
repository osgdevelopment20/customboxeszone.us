<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Deploy Secret
    |--------------------------------------------------------------------------
    |
    | Shared secret required to trigger the deploy endpoint. The incoming
    | request must provide this value via the "X-Deploy-Secret" header or a
    | "secret" query/body parameter. If empty, the endpoint is disabled.
    |
    */

    'secret' => env('DEPLOY_SECRET'),

    /*
    |--------------------------------------------------------------------------
    | Git Settings
    |--------------------------------------------------------------------------
    |
    | Credentials and target used to run "git pull". The token should be a
    | GitHub Personal Access Token stored ONLY in your .env file.
    |
    */

    'github_username' => env('DEPLOY_GITHUB_USERNAME'),
    'github_token' => env('DEPLOY_GITHUB_TOKEN'),
    'remote' => env('DEPLOY_GIT_REMOTE', 'origin'),
    'branch' => env('DEPLOY_GIT_BRANCH', 'main'),

];
