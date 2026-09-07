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
    | Repository
    |--------------------------------------------------------------------------
    |
    | The repository to fetch from, as "owner/repo" or a full URL. Setting this
    | is what lets the deploy work on a server checkout that has no "origin"
    | remote configured. When empty, the remote named below is looked up with
    | "git config --get remote.<name>.url" instead.
    |
    */

    'repository' => env('DEPLOY_GITHUB_REPOSITORY'),

    /*
    |--------------------------------------------------------------------------
    | Git Settings
    |--------------------------------------------------------------------------
    |
    | Credentials and target used to fetch the code. The token should be a
    | GitHub Personal Access Token stored ONLY in your .env file. It is passed
    | to git through the process environment, never on the command line.
    |
    */

    'github_username' => env('DEPLOY_GITHUB_USERNAME'),
    'github_token' => env('DEPLOY_GITHUB_TOKEN'),
    'remote' => env('DEPLOY_GIT_REMOTE', 'origin'),
    'branch' => env('DEPLOY_GIT_BRANCH', 'main'),

    /*
    |--------------------------------------------------------------------------
    | Post-deploy
    |--------------------------------------------------------------------------
    |
    | Clear the compiled config, routes, views and the response cache after a
    | successful pull. Without this the server keeps serving the previous
    | release from cache.
    |
    */

    'clear_cache' => (bool) env('DEPLOY_CLEAR_CACHE', true),

    /*
    | Seconds a single git command may run before it is killed.
    */

    'timeout' => (int) env('DEPLOY_TIMEOUT', 300),

];
