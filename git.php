<?php

$output = [];
exec('git pull origin master', $output);
echo implode('', $output) . '<b r/>done !';
