<?php
require_once '../src/Controllers/GameController.php';

$controller = new GameController();
$controller->load('sudoku');
