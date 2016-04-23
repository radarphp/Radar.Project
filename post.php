<?php
// @codingStandardsIgnoreFile


// Create env file
touch('.env');
echo "- Created .env\n";

// Remove Radar meta
array_map(
    'unlink',
    [
        'CONTRIBUTING.md',
        'LICENSE',
        'CHANGES.md',
        'README.md',
        'src/.placeholder'
    ]
);
echo "- Removed Radar meta\n";


// Remove Radar docs
array_map('unlink', glob('docs/*'));
rmdir('docs');
echo "- Removed Radar docs\n";

// Keep composer.lock for projects
// http://getcomposer.org/doc/01-basic-usage.md#composer-lock-the-lock-file
file_put_contents(
    '.gitignore',
    '/.env' . PHP_EOL . '/vendor' . PHP_EOL
);
echo "- Initializeid .gitignore\n";

// Cleanup composer.json
$composerJson = json_decode(file_get_contents('composer.json'), true);

unset(
    $composerJson['name'],
    $composerJson['description'],
    $composerJson['license'],
    $composerJson['license'],
    $composerJson['scripts']
);

file_put_contents(
    'composer.json',
    json_encode($composerJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
);
echo "-  Cleaned composer.json\n";


// Remove post install command
unlink('post.php');
echo "-  Removed post.php command\n";

