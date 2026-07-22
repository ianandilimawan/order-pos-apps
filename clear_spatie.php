<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
echo "Cache cleared.";
