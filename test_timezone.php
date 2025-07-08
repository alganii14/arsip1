<?php

// Test timezone configuration
echo "Current timezone: " . config('app.timezone') . "\n";
echo "Carbon now(): " . \Carbon\Carbon::now()->format('Y-m-d H:i:s T') . "\n";
echo "Carbon now() WIB: " . \Carbon\Carbon::now()->format('Y-m-d H:i:s') . " WIB\n";
echo "PHP date_default_timezone_get(): " . date_default_timezone_get() . "\n";
echo "Current date and time: " . date('Y-m-d H:i:s T') . "\n";
