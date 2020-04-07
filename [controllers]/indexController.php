<?php
$progs[1] = new Program(5);
$progs[2] = new Program(6);
$progs[3] = new Program(7);
$progs[1]->getAsanas();
$progs[2]->getAsanas();
$progs[3]->getAsanas();

$MODEL['programs'] = $progs;


Slonne::view('index/index.php', $MODEL); 
?>