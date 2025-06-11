<?php

// Path to the FilamentAuthentication.php file
$filePath = __DIR__ . '/vendor/phpsa/filament-authentication/src/FilamentAuthentication.php';

// Read the file content
$content = file_get_contents($filePath);

// Replace the problematic lines
$content = str_replace(
    '$instance->overrideResources($config[\'resources\']);',
    '$instance->overrideResources($config[\'resources\'] ?? []);',
    $content
);

// Fix setPreload call
$content = str_replace(
    '$instance->setPreload($config[\'preload_roles\'], $config[\'preload_permissions\']);',
    '$instance->setPreload($config[\'preload_roles\'] ?? true, $config[\'preload_permissions\'] ?? true);',
    $content
);

// Fix setImpersonation call
$content = str_replace(
    '$instance->setImpersonation($config[\'impersonate\'][\'enabled\'], $config[\'impersonate\'][\'guard\'], $config[\'impersonate\'][\'redirect\']);',
    '$instance->setImpersonation($config[\'impersonate\'][\'enabled\'] ?? false, $config[\'impersonate\'][\'guard\'] ?? \'web\', $config[\'impersonate\'][\'redirect\'] ?? \'/\');',
    $content
);

// Fix withSoftDeletes call
$content = str_replace(
    '$instance->withSoftDeletes($config[\'soft_deletes\']);',
    '$instance->withSoftDeletes($config[\'soft_deletes\'] ?? false);',
    $content
);

// Write the modified content back to the file
file_put_contents($filePath, $content);

echo "FilamentAuthentication.php has been patched successfully.\n";
