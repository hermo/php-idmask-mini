<?php
require("./main.php");

$key="7086e571d8dfb475f5c13afc0fceb1fe";
$ck=0xbaadf00d;

$testVector="_tUK-o3a4N713zdVj60yEQ";
$testValue=1234567901;

printf("Input:    %d\n", $testValue);
$masked = mask($key, $ck, $testValue);
printf("Masked:   %s\n", $masked);
printf("Expected: %s\n", $testVector);

$unmasked = unmask($key, $ck, $testVector);
printf("Unmasked: %d\n", $unmasked);
