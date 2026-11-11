<?php

/*
|--------------------------------------------------------------------------
| Ignition overrides
|--------------------------------------------------------------------------
|
| Merged over the package defaults in vendor/spatie/laravel-ignition.
|
| Runnable solutions expose unauthenticated HTTP endpoints
| (/_ignition/execute-solution, /_ignition/update-config) that can run
| migrations and rewrite files. They are enabled whenever APP_DEBUG=true
| and APP_ENV=local, which is how production was misconfigured. Keep them
| off everywhere, regardless of what .env says.
|
*/

return [

    'enable_runnable_solutions' => false,

    'enable_share_button' => false,

];
