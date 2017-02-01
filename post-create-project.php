<?php
// @codingStandardsIgnoreFile


// Create env file
touch('.env');
echo "- Created .env" . PHP_EOL;

// Remove Radar meta
array_map(
    'unlink',
    [
        'CONTRIBUTING.md',
        'LICENSE',
        'CHANGELOG.md',
        'README.md',
        'src/.placeholder'
    ]
);
echo "- Removed Radar meta" . PHP_EOL;

// Remove Radar docs
array_map('unlink', glob('docs/*'));
rmdir('docs');
echo "- Removed Radar docs" . PHP_EOL;

// Keep composer.lock for projects
// http://getcomposer.org/doc/01-basic-usage.md#composer-lock-the-lock-file
file_put_contents(
    '.gitignore',
    implode(PHP_EOL, [
        '/.env',
        '/vendor',
    ])
);
echo "- Initialized .gitignore" . PHP_EOL;

// Cleanup composer.json
$composerJson = json_decode(file_get_contents('composer.json'), true);

unset(
    $composerJson['name'],
    $composerJson['description'],
    $composerJson['license'],
    $composerJson['scripts']
);

file_put_contents(
    'composer.json',
    json_encode($composerJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
);
echo "- Cleaned composer.json" . PHP_EOL;

// Remove post install command
unlink('post-create-project.php');
echo "-  Removed post-create-project.php command\n";
