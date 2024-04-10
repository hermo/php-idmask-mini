<?php
# Minimalistic implementation of IDMask
# See
#   https://github.com/patrickfav/id-mask
#   https://github.com/matchory/php-id-mask
function mask($key, $checkValue, $valueToMask) {
    if (!is_int($valueToMask)) {
        throw new \InvalidArgumentException("valueToMask must be an integer");
    }
    $packed = pack("q*", $checkValue, $valueToMask);
    $encrypted = openssl_encrypt($packed, 'aes-128-ecb', hex2bin($key), OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING);
    $base64 = base64_encode($encrypted);
    $base64url = strtr(rtrim($base64, '='), '+/', '-_');
    return $base64url;
}

function unmask($key, $checkValue, $base64url) {
    if (strlen($base64url) != 22) {
        throw new \InvalidArgumentException("base64url input must be 22 bytes long");
    }
    $base64 = strtr($base64url, '-_', '+/');
    $padded = str_pad($base64, ceil(strlen($base64) / 4) * 4, '=', STR_PAD_RIGHT);
    $decoded = base64_decode($padded, true);
    if ($decoded === false || strlen($decoded) != 16) {
        throw new \RuntimeException("Decoding failed or decoded value is not 16 bytes");
    }
    $decrypted = openssl_decrypt($decoded, 'aes-128-ecb', hex2bin($key), OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING);
    if ($decrypted === false) {
        throw new \RuntimeException("Decryption failed");
    }
    list($ck, $val) = array_values(unpack("q*", $decrypted));
    if ($ck != $checkValue) {
        throw new \RuntimeException("Check value is incorrect");
    }
    return $val;
}
