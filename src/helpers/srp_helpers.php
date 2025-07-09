<?php
// src/helpers/srp_helpers.php

function calculateSRP6Verifier($username, $password, $salt) {
    // Constants
    $g = gmp_init(7);
    $N = gmp_init('894B645E89E1535BBDAD5B8B290650530801B18EBFBF5E8FAB3C82872A3E9BB7', 16);

    // Step 1: First hash calculation
    $h1 = hash('sha1', strtoupper($username) . ':' . strtoupper($password), true);

    // Step 2: Second hash calculation
    $h2 = hash('sha1', $salt . $h1, true);

    // Step 3: Import h2 into a big number
    $h2Int = gmp_import($h2, 1, GMP_LSW_FIRST);

    // Step 4: Calculate verifier
    $verifier = gmp_powm($g, $h2Int, $N);

    // Step 5: Export verifier as byte array
    $verifierBin = gmp_export($verifier, 1, GMP_LSW_FIRST);

    // Step 6: Ensure it's exactly 32 bytes long
    $verifierPadded = str_pad($verifierBin, 32, chr(0), STR_PAD_RIGHT);

    return $verifierPadded;
}

// Helper function to generate random salt
function generateSalt() {
    return random_bytes(32);
}

// Функция VerifySRP6Login
function VerifySRP6Login($username, $password, $salt, $verifier)
{
    // Пересчитываем верификатор с использованием предоставленного имени пользователя + пароль и сохранённой соли
    $checkVerifier = calculateSRP6Verifier($username, $password, $salt);

    // Сравниваем его с сохранённым верификатором
    return ($verifier === $checkVerifier);
}