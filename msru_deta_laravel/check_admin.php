<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
foreach(\App\Models\AssessmentQuestion::select('category')->distinct()->get() as $q) {
    echo "CAT: " . $q->category . "\n";
}
echo "TOTAL: " . \App\Models\AssessmentQuestion::count() . "\n";
