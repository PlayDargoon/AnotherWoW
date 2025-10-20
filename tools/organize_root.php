<?php
// tools/organize_root.php
// Безопасно пересортировать файлы из корня в соответствующие каталоги

chdir(dirname(__DIR__)); // перейти в корень проекта

function moveFile($src, $dstDir) {
    if (!file_exists($src)) return [false, "not-found"];
    if (!is_dir($dstDir)) mkdir($dstDir, 0777, true);
    $base = basename($src);
    $dst = rtrim($dstDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $base;
    // если уже существует, добавим суффикс -backup
    if (file_exists($dst)) {
        $dst = rtrim($dstDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . pathinfo($base, PATHINFO_FILENAME) . '.backup.' . date('Ymd_His') . '.' . pathinfo($base, PATHINFO_EXTENSION);
    }
    if (@rename($src, $dst)) {
        return [true, $dst];
    }
    return [false, "rename-failed"];
}

$plan = [
    // Диагностические и отладочные в tools/
    'tools' => [
        'check_headers.php',
        'simple_check_headers.php',
        'find_bonus_table.php',
        'check_admin_records.php',
        'check_toxa65_records.php',
        'check_user_balance.php',
        'check_vote_discrepancy.php',
        'simple_vote_check.php',
        'test_captcha.php',
        'test_character_vote_search.php',
        'test_character_voting.php',
        'test_vote_top_monthly.php',
    ],
    // Разовые миграции/фиксы в scripts/maintenance
    'scripts/maintenance' => [
        'add_missing_to_210.php',
        'add_missing_votes.php',
        'add_real_votes.php',
        'add_test_votes.php',
        'fix_admin_records.php',
        'fix_to_210_votes.php',
        'fix_vote_data.php',
    ],
    // Документация и прототипы в docs/
    'docs' => [
        'CAPTCHA_IMPLEMENTATION_SUMMARY.md',
        'simple-wireframe.html',
        'README_old.txt',
        'README.txt',
    ],
];

echo "=== Организация корня проекта ===\n";
$moved = 0; $skipped = 0; $errors = 0;
foreach ($plan as $dst => $files) {
    foreach ($files as $fname) {
        [$ok, $info] = moveFile($fname, $dst);
        if ($ok) { echo "✔ Перемещено: $fname -> $dst\n"; $moved++; }
        else {
            if ($info === 'not-found') { $skipped++; }
            else { echo "✖ Ошибка перемещения $fname ($info)\n"; $errors++; }
        }
    }
}

echo "\nИтог: перемещено $moved, пропущено $skipped, ошибок $errors\n";
echo "Готово.\n";
