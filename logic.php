<?php
// Output the numbers from 1 to 100
// Where the number is divisible by three (3) output the word “foo”
// Where the number is divisible by five (5) output the word “bar”
// Where the number is divisible by three (3) and (5) output the word “foobar”
// Only be a single PHP file
$result = [];
$num = 1;
for ($i = 0; $i < 100; $i++) {
    if ($num % 3 != 0) {
        $result[$i] = $num;
        if ($num % 5 == 0) {
            $result[$i] = "bar";
        }
    } else {
        $result[$i] = "foo";
        if ($num % 5 == 0) {
            $result[$i] .= "bar";
        }
    }
    $num++;
}
echo join(",", $result);
