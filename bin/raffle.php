<?php
require __DIR__ . '/../vendor/autoload.php';

use Duodraco\ProportionalRaffle\RaffleManager;
use Duodraco\ProportionalRaffle\Transformation\CSV2Candidate;

$fileName = __DIR__ . '/../contacts.csv';
echo "Using {$fileName} as source", PHP_EOL;
$transformer = new CSV2Candidate($fileName);
$candidates = $transformer();
$manager = new RaffleManager($candidates);
$urn = $manager->getUrn();
$winnerTicket = $urn[rand(0, count($urn) - 1)];
$winner = $manager->getWinner($winnerTicket);
echo "{$winner->getName()} [{$winner->getEmail()}]", PHP_EOL;