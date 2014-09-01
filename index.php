<?php
$output = shell_exec("cd ./FedX && ./cli.sh -d ./../ubleipzig_config.ttl @q ./../query.txt");
print($output);

