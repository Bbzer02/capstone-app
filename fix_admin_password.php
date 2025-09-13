<?php

use App\Models\AdminUser;

require 'vendor/autoload.php';

$admin = AdminUser::where('email', 'ctuAdmin@ctu.com')->first();
if ($admin) {
    $admin->password = bcrypt('ilovectu@@');
    $admin->save();
    echo "✅ Password updated successfully!\n";
} else {
    echo "❌ Admin user not found!\n";
}
