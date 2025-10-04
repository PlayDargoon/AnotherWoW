<?php

/**
 * Проверяет, входит ли IP в подсеть CIDR
 */
function ipInCidr(string $ip, string $cidr): bool {
    if (strpos($cidr, '/') === false) {
        return $ip === $cidr;
    }
    list($subnet, $mask) = explode('/', $cidr);
    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
        // Простая проверка IPv6 префикса
        $binIp = inet_pton($ip);
        $binSubnet = inet_pton($subnet);
        $bytes = intdiv((int)$mask, 8);
        $bits = (int)$mask % 8;
        if (strncmp($binIp, $binSubnet, $bytes) !== 0) return false;
        if ($bits === 0) return true;
        $maskByte = ~((1 << (8 - $bits)) - 1) & 0xFF;
        return ((ord($binIp[$bytes]) & $maskByte) === (ord($binSubnet[$bytes]) & $maskByte));
    } else {
        $ipLong = ip2long($ip);
        $subnetLong = ip2long($subnet);
        $mask = -1 << (32 - (int)$mask);
        $subnetLong &= $mask;
        return ($ipLong & $mask) === $subnetLong;
    }
}
