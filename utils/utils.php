<?php

function formatJoueurs($minJ, $maxJ){
    if($maxJ == 1){
        return 'Solo';
    } else if ($minJ == $maxJ) {
        return "$minJ joueurs";
    } else {
        return "$minJ à $maxJ joueurs";
    }
}

function categorieDuree($temps) {
    if($temps < 30){
        return 'Partie rapide';
    } else if($temps <= 90){
        return 'Partie moyenne';
    } else {
        return 'Partie longue';
    }
}


?>