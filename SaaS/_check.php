<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$c = Illuminate\Support\Facades\DB::table('rent_control_zones')->count();
echo "Zones: $c\n";
$c2 = Illuminate\Support\Facades\DB::table('rent_control_compliance')->count();
echo "Compliance: $c2\n";
