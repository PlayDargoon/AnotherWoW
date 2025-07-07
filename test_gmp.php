<?php
// test_gmp.php

if (function_exists('gmp_strval')) {
    echo "GMP works!";
} else {
    echo "GMP is not available.";
}