<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$user = \App\Models\User::create([
    'name' => 'Admin',
    'email' => 'admin@beasiswaapp.com',
    'password' => bcrypt('password123'),
    'role' => 'admin'
]);

echo "Admin user created successfully!\n";
echo "Email: " . $user->email . "\n";
echo "Password: password123\n";

