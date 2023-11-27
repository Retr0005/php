<?php

require 'vendor/autoload.php';

$valori = [
    'Black' => '0',
    'Brown' => '1',
    'Red' => '2',
    'Orange' => '3',
    'Yellow' => '4',
    'Green' => '5',
    'Blue' => '6',
    'Purple' => '7',
    'Grey' => '8',
    'White' => '9'
];

$colori = [
    'None',
    'Silver',
    'Gold',
    'Black',
    'Red',
    'Orange',
    'Yellow',
    'Green',
    'Blue',
    'Violet',
    'Grey',
    'White'
];

$moltiplicatore = [
    'Silver' => '10^-2',
    'Gold' => '10^-1',
    'Black' => '10^0',
    'Brown' => '10^1',
    'Red' => '10^2',
    'Orange' => '10^3',
    'Yellow' => '10^4',
    'Green' => '10^5',
    'Blue' => '10^6',
    'Purple' => '10^7'
];

$accuracy = [
    'None' => '±20',
    'Silver' => '±10',
    'Gold' => '±5',
    'Brown' => '±1',
    'Red' => '±2',
    'Green' => '±0.5',
    'Blue' => '±0.25',
    'Purple' => '±0.1'
];

$templates = new League\Plates\Engine('templates', 'tpl');

$primo = $_POST['prima_banda'];
$secondo = $_POST['seconda_banda'];
$terzo = $_POST['terza_banda'];
$quarto = $_POST['quarta_banda'];
$risultato = $valori[$primo] . $valori[$secondo] . ' x ' . $moltiplicatore[$terzo] . ' ' . $accuracy[$quarto];

echo $templates -> render('resistenza', [
    'colori' => $colori,
    'risultato' => $risultato
]);
