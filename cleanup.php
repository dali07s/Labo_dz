<?php
$files = [
    '.dockerignore',
    '.env.docker',
    'docker-compose.yml',
    'docker/entrypoint.sh',
    'docker/nginx.conf',
    'docker/nginx/default.conf',
];

foreach ($files as $file) {
    if (file_exists($file)) {
        if (unlink($file)) {
            echo "Deleted $file\n";
        } else {
            echo "Failed to delete $file\n";
        }
    } else {
        echo "$file does not exist\n";
    }
}

function deleteDirectory($dir) {
    if (!file_exists($dir)) return true;
    if (!is_dir($dir)) return unlink($dir);
    foreach (scandir($dir) as $item) {
        if ($item == '.' || $item == '..') continue;
        if (!deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) return false;
    }
    return rmdir($dir);
}

if (deleteDirectory('docker')) {
    echo "Deleted docker directory\n";
} else {
    echo "Failed to delete docker directory\n";
}
?>
