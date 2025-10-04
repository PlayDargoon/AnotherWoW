<?php
// database/migrations/CreatePaymentsTable.php

require_once __DIR__ . '/../../bootstrap.php';
require_once __DIR__ . '/../../src/services/DatabaseConnection.php';
require_once __DIR__ . '/../../src/models/Payment.php';

echo "Выполнение миграции: CreatePaymentsTable...\n";

try {
    $siteDb = DatabaseConnection::getSiteConnection();
    Payment::migrate($siteDb);
    echo "✓ Миграция выполнена успешно\n";
} catch (Exception $e) {
    echo "Ошибка миграции: " . $e->getMessage() . "\n";
    exit(1);
}
