<?php


require 'vendor/autoload.php';
require 'Model/StudenteRepository.php';

$templates = new League\Plates\Engine('templates', 'tpl');

$ordina = 'cognome';

if (isset($_GET['ordina'])) {
    $ordina = $_GET['ordina'];
}
if ($ordina == 'cognome') {
    $result = \Model\StudenteRepository::listAllSur();
} else if ($ordina == 'nome') {
    $result = \Model\StudenteRepository::listAllName();
} else {
    $result = \Model\StudenteRepository::listAllClass();
}

echo $templates -> render('index', [
    'studenti' => $result
]);

