<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DungeonJS - level 4</title>
    <link href="https://fonts.googleapis.com/css2?family=Slabo+27px&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
</head>

<body>

    <div class="corps">

        <h1>DungeonJS - Level 4</h1>

        <p id="haut">
            You are at the top left in a black 4x4 dungeon room (<span class="orange">ORANGE</span>)
            and you can only move on <span class="vert">GREEN</span> squares.<br>
            In the room, you can find a <span class="jaune">STAIRCASE</span> (maybe a path to an exit),
            three <span class="rouge">MONSTERS</span> (each of them takes 10 Life Points from you) and a
            <span class="bleu eblanc">TREASURE</span>.<br>
            <br>
            You get 5 more Life Points and you start this level with 15 points.<br>
            <br>
            ATTENTION, on the stairs, you find a red POTION (you read on the label +30) that you put immediately in your pocket and a short sword (to defend yourself, of course) that you keep in your hand.
        </p>

        <div class="grille">
            <div>
                <button id="b0" class="bout4 orange"></button>
                <button id="b1" class="bout4 vert"></button>
                <button id="b2" class="bout4"></button>
                <button id="b3" class="bout4"></button>
            </div>
            <div>
                <button id="b4" class="bout4 vert"></button>
                <button id="b5" class="bout4"></button>
                <button id="b6" class="bout4"></button>
                <button id="b7" class="bout4"></button>
            </div>
            <div>
                <button id="b8" class="bout4"></button>
                <button id="b9" class="bout4"></button>
                <button id="b10" class="bout4"></button>
                <button id="b11" class="bout4"></button>
            </div>
            <div>
                <button id="b12" class="bout4"></button>
                <button id="b13" class="bout4"></button>
                <button id="b14" class="bout4"></button>
                <button id="b15" class="bout4"></button>
            </div>
            <div>
                <button id="lapoche">YOUR POCKET</button>
                <button id="lavie">LIFE POINTS</button>
            </div>
        </div>

        <div class="texte">
            <h2></h2>
            <p id="remplir"></p>
        </div>

        <span id="sp0"></span><span id="sp1"></span><span id="sp2"></span><span id="sp3"></span><br>
        <span id="sp4"></span><span id="sp5"></span><span id="sp6"></span><span id="sp7"></span><br>
        <span id="sp8"></span><span id="sp9"></span><span id="sp10"></span><span id="sp11"></span><br>
        <span id="sp12"></span><span id="sp13"></span><span id="sp14"></span><span id="sp15"></span><br>
    </div>

    <script>
        $(document).ready(function() {

            const DECOMPTE = 3000; // deplacements - retour orange pour les differentes couleurs

            /*
                création du tableau jesuis[] qui permet de savoir où se trouve le héros
                (utilisé pour la potion uniquement en ce moment)
            */

            var jesuis = new Array();
            jesuis[0] = "vide";

            /*
                tableau divers :
                0 = vie
                1 = potion
                2 = parchemin / scroll
            */

            var divers = new Array();

            divers[0] = 15;
            divers[1] = 1;
            divers[2] = 0;

            var vie = divers[0];

            /* 
                Génération du niveau

                Tableau avec toutes les cases à 0 (0 = rien)

                -> le héros (1) est automatiquement en haut à gauche
                -> position du 2 (escalier), 3 (x3 monstres) et 4 (trésor) aléatoirement
                en évitant d'être sur une case déjà prise

            */

            const NIVEAU = 16;

            var zone = new Array();

            for (var i = 0; i < NIVEAU; i++) {
                zone[i] = 0;
            }

            zone[0] = 1;


            var hasard2 = entierAleatoire(1, (NIVEAU - 1));
            zone[hasard2] = 2;


            var hasard31 = entierAleatoire(1, (NIVEAU - 1));
            while (hasard31 == hasard2) {
                var hasard31 = entierAleatoire(1, (NIVEAU - 1));
            }
            zone[hasard31] = 3;

            var hasard32 = entierAleatoire(1, (NIVEAU - 1));
            while ((hasard32 == hasard2) || (hasard32 == hasard31)) {
                var hasard32 = entierAleatoire(1, (NIVEAU - 1));
            }
            zone[hasard32] = 3;

            var hasard33 = entierAleatoire(1, (NIVEAU - 1));
            while ((hasard33 == hasard2) || (hasard33 == hasard31) || (hasard33 == hasard32)) {
                var hasard33 = entierAleatoire(1, (NIVEAU - 1));
            }
            zone[hasard33] = 3;


            var hasard4 = entierAleatoire(1, (NIVEAU - 1));
            while ((hasard4 == hasard2) || (hasard4 == hasard31) || (hasard4 == hasard32) || (hasard4 == hasard33)) {
                var hasard4 = entierAleatoire(1, (NIVEAU - 1));
            }
            zone[hasard4] = 4;

            // affichage des cases dans le html (plan du niveau)

            /*
            $("#sp0").text(zone[0]);
            $("#sp1").text(zone[1]);
            $("#sp2").text(zone[2]);
            $("#sp3").text(zone[3]);
            $("#sp4").text(zone[4]);
            $("#sp5").text(zone[5]);
            $("#sp6").text(zone[6]);
            $("#sp7").text(zone[7]);
            $("#sp8").text(zone[8]);
            $("#sp9").text(zone[9]);
            $("#sp10").text(zone[10]);
            $("#sp11").text(zone[11]);
            $("#sp12").text(zone[12]);
            $("#sp13").text(zone[13]);
            $("#sp14").text(zone[14]);
            $("#sp15").text(zone[15]);
            */




            /*
            Logique :

            La variable situation permet de savoir ce qu'il y a sur la case

            Avec la boussole (haut, droite, bas, gauche), on définit ceux qui peuvent cliquer
            sur le bouton et cela selon la case d'origine du joueur. 
            Suppression des anciennes possibilités de placement et la position

            Ensuite, la variable laici permet de définir là où se trouve le héros

            Exécution de la fonction mouv(situation, laici) pour résoudre ce qui se passe pour le 
            héros et définir les nouveaux déplacements.

            */




            $("#b0").click(function() {
                var droite = zone[1];
                var bas = zone[4];

                if ((droite == 1) || (bas == 1)) {

                    var situation = zone[0];

                    if (droite == 1) {
                        $("#b1").removeClass("orange");
                        zone[1] = 0;
                        $("#b2").removeClass("vert");
                        $("#b5").removeClass("vert");
                    } else if (bas == 1) {
                        $("#b4").removeClass("orange");
                        zone[4] = 0;
                        $("#b5").removeClass("vert");
                        $("#b8").removeClass("vert");
                    }

                    if (situation == 0) {
                        $("#b0").removeClass("vert").addClass("orange");
                        $("#b1").addClass("vert");
                        $("#b4").addClass("vert");
                        zone[0] = 1;
                    }

                    jesuis[0] = "#b0";

                }
            });


            $("#b1").click(function() {
                var gauche = zone[0];
                var droite = zone[2];
                var bas = zone[5];

                if ((droite == 1) || (gauche == 1) || (bas == 1)) {

                    var situation = zone[1];

                    if (gauche == 1) {
                        $("#b0").removeClass("orange");
                        zone[0] = 0;
                        $("#b4").removeClass("vert");
                    } else if (droite == 1) {
                        $("#b2").removeClass("orange");
                        zone[2] = 0;
                        $("#b3").removeClass("vert");
                        $("#b6").removeClass("vert");
                    } else if (bas == 1) {
                        $("#b5").removeClass("orange");
                        zone[5] = 0;
                        $("#b4").removeClass("vert");
                        $("#b6").removeClass("vert");
                        $("#b9").removeClass("vert");
                    }

                    var laici = "#b1";
                    mouv(situation, laici);

                }
            });


            $("#b2").click(function() {
                var droite = zone[3];
                var gauche = zone[1];
                var bas = zone[6];

                if ((droite == 1) || (gauche == 1) || (bas == 1)) {

                    var situation = zone[2];

                    if (droite == 1) {
                        $("#b3").removeClass("orange");
                        zone[3] = 0;
                        $("#b7").removeClass("vert");
                    } else if (gauche == 1) {
                        $("#b1").removeClass("orange");
                        zone[1] = 0;
                        $("#b0").removeClass("vert");
                        $("#b5").removeClass("vert");
                    } else if (bas == 1) {
                        $("#b6").removeClass("orange");
                        zone[6] = 0;
                        $("#b5").removeClass("vert");
                        $("#b7").removeClass("vert");
                        $("#b10").removeClass("vert");
                    }

                    var laici = "#b2";
                    mouv(situation, laici);

                }
            });


            $("#b3").click(function() {
                var gauche = zone[2];
                var bas = zone[7];

                if ((gauche == 1) || (bas == 1)) {

                    var situation = zone[3];

                    if (gauche == 1) {
                        $("#b2").removeClass("orange");
                        zone[2] = 0;
                        $("#b1").removeClass("vert");
                        $("#b6").removeClass("vert");
                    } else if (bas == 1) {
                        $("#b7").removeClass("orange");
                        zone[7] = 0;
                        $("#b6").removeClass("vert");
                        $("#b11").removeClass("vert");
                    }

                    var laici = "#b3";
                    mouv(situation, laici);

                }
            });


            $("#b4").click(function() {
                var haut = zone[0];
                var droite = zone[5];
                var bas = zone[8];

                if ((haut == 1) || (droite == 1) || (bas == 1)) {

                    var situation = zone[4];

                    if (haut == 1) {
                        $("#b0").removeClass("orange");
                        zone[0] = 0;
                        $("#b1").removeClass("vert");
                    } else if (droite == 1) {
                        $("#b5").removeClass("orange");
                        zone[5] = 0;
                        $("#b1").removeClass("vert");
                        $("#b6").removeClass("vert");
                        $("#b9").removeClass("vert");
                    } else if (bas == 1) {
                        $("#b8").removeClass("orange");
                        zone[8] = 0;
                        $("#b9").removeClass("vert");
                        $("#b12").removeClass("vert");
                    }

                    var laici = "#b4";
                    mouv(situation, laici);

                }
            });


            $("#b5").click(function() {
                var haut = zone[1];
                var droite = zone[6];
                var bas = zone[9];
                var gauche = zone[4];

                if ((haut == 1) || (droite == 1) || (bas == 1) || (gauche == 1)) {

                    var situation = zone[5];

                    if (haut == 1) {
                        $("#b1").removeClass("orange");
                        zone[1] = 0;
                        $("#b0").removeClass("vert");
                        $("#b2").removeClass("vert");
                    } else if (droite == 1) {
                        $("#b6").removeClass("orange");
                        zone[6] = 0;
                        $("#b2").removeClass("vert");
                        $("#b7").removeClass("vert");
                        $("#b10").removeClass("vert");
                    } else if (bas == 1) {
                        $("#b9").removeClass("orange");
                        zone[9] = 0;
                        $("#b8").removeClass("vert");
                        $("#b10").removeClass("vert");
                        $("#b13").removeClass("vert");
                    } else if (gauche == 1) {
                        $("#b4").removeClass("orange");
                        zone[4] = 0;
                        $("#b0").removeClass("vert");
                        $("#b8").removeClass("vert");
                    }

                    var laici = "#b5";
                    mouv(situation, laici);

                }
            });


            $("#b6").click(function() {
                var haut = zone[2];
                var droite = zone[7];
                var bas = zone[10];
                var gauche = zone[5];

                if ((haut == 1) || (droite == 1) || (bas == 1) || (gauche == 1)) {

                    var situation = zone[6];

                    if (haut == 1) {
                        $("#b2").removeClass("orange");
                        zone[2] = 0;
                        $("#b1").removeClass("vert");
                        $("#b3").removeClass("vert");
                    } else if (droite == 1) {
                        $("#b7").removeClass("orange");
                        zone[7] = 0;
                        $("#b3").removeClass("vert");
                        $("#b11").removeClass("vert");
                    } else if (bas == 1) {
                        $("#b10").removeClass("orange");
                        zone[10] = 0;
                        $("#b9").removeClass("vert");
                        $("#b11").removeClass("vert");
                        $("#b14").removeClass("vert");
                    } else if (gauche == 1) {
                        $("#b5").removeClass("orange");
                        zone[5] = 0;
                        $("#b1").removeClass("vert");
                        $("#b4").removeClass("vert");
                        $("#b9").removeClass("vert");
                    }

                    var laici = "#b6";
                    mouv(situation, laici);

                }
            });


            $("#b7").click(function() {
                var haut = zone[3];
                var bas = zone[11];
                var gauche = zone[6];

                if ((haut == 1) || (bas == 1) || (gauche == 1)) {

                    var situation = zone[7];

                    if (haut == 1) {
                        $("#b3").removeClass("orange");
                        zone[3] = 0;
                        $("#b2").removeClass("vert");
                    } else if (bas == 1) {
                        $("#b11").removeClass("orange");
                        zone[11] = 0;
                        $("#b10").removeClass("vert");
                        $("#b15").removeClass("vert");
                    } else if (gauche == 1) {
                        $("#b6").removeClass("orange");
                        zone[6] = 0;
                        $("#b2").removeClass("vert");
                        $("#b5").removeClass("vert");
                        $("#b10").removeClass("vert");
                    }

                    var laici = "#b7";
                    mouv(situation, laici);

                }
            });


            $("#b8").click(function() {
                var haut = zone[4];
                var droite = zone[9];
                var bas = zone[12];

                if ((haut == 1) || (droite == 1) || (bas == 1)) {

                    var situation = zone[8];

                    if (haut == 1) {
                        $("#b4").removeClass("orange");
                        zone[4] = 0;
                        $("#b0").removeClass("vert");
                        $("#b5").removeClass("vert");
                    } else if (droite == 1) {
                        $("#b9").removeClass("orange");
                        zone[9] = 0;
                        $("#b5").removeClass("vert");
                        $("#b10").removeClass("vert");
                        $("#b13").removeClass("vert");
                    } else if (bas == 1) {
                        $("#b12").removeClass("orange");
                        zone[12] = 0;
                        $("#b13").removeClass("vert");
                    }

                    var laici = "#b8";
                    mouv(situation, laici);

                }
            });


            $("#b9").click(function() {
                var haut = zone[5];
                var droite = zone[10];
                var bas = zone[13];
                var gauche = zone[8];

                if ((haut == 1) || (droite == 1) || (bas == 1) || (gauche == 1)) {

                    var situation = zone[9];

                    if (haut == 1) {
                        $("#b5").removeClass("orange");
                        zone[5] = 0;
                        $("#b4").removeClass("vert");
                        $("#b6").removeClass("vert");
                        $("#b1").removeClass("vert");
                    } else if (droite == 1) {
                        $("#b10").removeClass("orange");
                        zone[10] = 0;
                        $("#b6").removeClass("vert");
                        $("#b11").removeClass("vert");
                        $("#b14").removeClass("vert");
                    } else if (bas == 1) {
                        $("#b13").removeClass("orange");
                        zone[13] = 0;
                        $("#b12").removeClass("vert");
                        $("#b14").removeClass("vert");
                    } else if (gauche == 1) {
                        $("#b8").removeClass("orange");
                        zone[8] = 0;
                        $("#b4").removeClass("vert");
                        $("#b12").removeClass("vert");
                    }

                    var laici = "#b9";
                    mouv(situation, laici);

                }
            });


            $("#b10").click(function() {
                var haut = zone[6];
                var droite = zone[11];
                var bas = zone[14];
                var gauche = zone[9];

                if ((haut == 1) || (droite == 1) || (bas == 1) || (gauche == 1)) {

                    var situation = zone[10];

                    if (haut == 1) {
                        $("#b6").removeClass("orange");
                        zone[6] = 0;
                        $("#b2").removeClass("vert");
                        $("#b5").removeClass("vert");
                        $("#b7").removeClass("vert");
                    } else if (droite == 1) {
                        $("#b11").removeClass("orange");
                        zone[11] = 0;
                        $("#b7").removeClass("vert");
                        $("#b15").removeClass("vert");
                    } else if (bas == 1) {
                        $("#b14").removeClass("orange");
                        zone[14] = 0;
                        $("#b13").removeClass("vert");
                        $("#b15").removeClass("vert");
                    } else if (gauche == 1) {
                        $("#b9").removeClass("orange");
                        zone[9] = 0;
                        $("#b5").removeClass("vert");
                        $("#b8").removeClass("vert");
                        $("#b13").removeClass("vert");
                    }

                    var laici = "#b10";
                    mouv(situation, laici);

                }
            });


            $("#b11").click(function() {
                var haut = zone[7];
                var bas = zone[15];
                var gauche = zone[10];

                if ((haut == 1) || (bas == 1) || (gauche == 1)) {

                    var situation = zone[11];

                    if (haut == 1) {
                        $("#b7").removeClass("orange");
                        zone[7] = 0;
                        $("#b3").removeClass("vert");
                        $("#b6").removeClass("vert");
                    } else if (bas == 1) {
                        $("#b15").removeClass("orange");
                        zone[15] = 0;
                        $("#b14").removeClass("vert");
                    } else if (gauche == 1) {
                        $("#b10").removeClass("orange");
                        zone[10] = 0;
                        $("#b6").removeClass("vert");
                        $("#b9").removeClass("vert");
                        $("#b14").removeClass("vert");
                    }

                    var laici = "#b11";
                    mouv(situation, laici);

                }
            });


            $("#b12").click(function() {
                var haut = zone[8];
                var droite = zone[13];

                if ((droite == 1) || (haut == 1)) {

                    var situation = zone[12];

                    if (droite == 1) {
                        $("#b13").removeClass("orange");
                        zone[13] = 0;
                        $("#b9").removeClass("vert");
                        $("#b14").removeClass("vert");
                    } else if (haut == 1) {
                        $("#b8").removeClass("orange");
                        zone[8] = 0;
                        $("#b4").removeClass("vert");
                        $("#b9").removeClass("vert");
                    }

                    var laici = "#b12";
                    mouv(situation, laici);

                }
            });


            $("#b13").click(function() {
                var droite = zone[14];
                var gauche = zone[12];
                var haut = zone[9];

                if ((droite == 1) || (gauche == 1) || (haut == 1)) {

                    var situation = zone[13];

                    if (droite == 1) {
                        $("#b14").removeClass("orange");
                        zone[14] = 0;
                        $("#b10").removeClass("vert");
                        $("#b15").removeClass("vert");
                    } else if (gauche == 1) {
                        $("#b12").removeClass("orange");
                        zone[12] = 0;
                        $("#b8").removeClass("vert");
                    } else if (haut == 1) {
                        $("#b9").removeClass("orange");
                        zone[9] = 0;
                        $("#b5").removeClass("vert");
                        $("#b8").removeClass("vert");
                        $("#b10").removeClass("vert");
                    }

                    var laici = "#b13";
                    mouv(situation, laici);

                }
            });


            $("#b14").click(function() {
                var droite = zone[15];
                var gauche = zone[13];
                var haut = zone[10];

                if ((droite == 1) || (gauche == 1) || (haut == 1)) {

                    var situation = zone[14];

                    if (droite == 1) {
                        $("#b15").removeClass("orange");
                        zone[15] = 0;
                        $("#b11").removeClass("vert");
                    } else if (gauche == 1) {
                        $("#b13").removeClass("orange");
                        zone[13] = 0;
                        $("#b9").removeClass("vert");
                        $("#b12").removeClass("vert");
                    } else if (haut == 1) {
                        $("#b10").removeClass("orange");
                        zone[10] = 0;
                        $("#b6").removeClass("vert");
                        $("#b9").removeClass("vert");
                        $("#b11").removeClass("vert");
                    }

                    var laici = "#b14";
                    mouv(situation, laici);

                }
            });


            $("#b15").click(function() {
                var haut = zone[11];
                var gauche = zone[14];

                if ((gauche == 1) || (haut == 1)) {

                    var situation = zone[15];

                    if (gauche == 1) {
                        $("#b14").removeClass("orange");
                        zone[14] = 0;
                        $("#b10").removeClass("vert");
                        $("#b13").removeClass("vert");
                    } else if (haut == 1) {
                        $("#b11").removeClass("orange");
                        zone[11] = 0;
                        $("#b7").removeClass("vert");
                        $("#b10").removeClass("vert");
                    }

                    var laici = "#b15";
                    mouv(situation, laici);

                }
            });

            /*
                définition des boutons "your pocket" (lapoche) et "life points" (lavie)

                pour lapoche ... affichage de la "potion" et du "scroll" si quantité supérieure à 0
                -> execution de la fonction lapoche()

                pour lavie ... affichage des points de vie
                -> execution de la fonction lavie()

            */

            $("#lapoche").click(function() {
                potion = divers[1];
                scroll = divers[2];
                lapoche(potion, scroll);
            });

            $("#lavie").click(function() {
                lavie();
            });

            /*
                Que se passe t-il quand on clique sur l'icone potion (ipot) ?
                1/ prise de la potion si la vie est supérieure à 0
                -> texte
                -> vie +30
                -> mise à jour de la variable vie
                -> potion en moins
                2/ si la vie est égale à 0
                -> pas possible de prendre la potion, vous êtes mort
            */

            $("body").on("click", "#ipot", function() {
                vie = divers[0];
                if (vie > 0) {
                    $("#remplir").text("You drank the red potion and you feel better, really better.");
                    vie += 30;
                    divers[0] = vie;
                    divers[1] = 0;
                } else {
                    $("h2").text("Sorry... you are dead!");
                    $("#remplir").text(" ");
                }
            });

            /*
            Que se passe t-il quand on clique sur l'icone scroll (iscr) ?

            -> bilan de la situation selon la case en utilisant laici du tableau jesuis[]
            -> exécution de la fonction situa() qui construit une phrase selon la position et la situation
            */

            $("body").on("click", "#iscr", function() {
                var laici = jesuis[0];
                var situa1 = 0,
                    situa2 = 0,
                    situa3 = 0,
                    situa4 = 0;

                if (laici === "#b0") {
                    situa1 = zone[1];
                    var situatext1 = situa("right", situa1);
                    situa2 = zone[4];
                    var situatext2 = situa("bottom", situa2);
                    var situaglobal = situatext1 + situatext2;
                } else if (laici === "#b1") {
                    situa1 = zone[0];
                    var situatext1 = situa("left", situa1);
                    situa2 = zone[2];
                    var situatext2 = situa("right", situa2);
                    situa3 = zone[5];
                    var situatext3 = situa("bottom", situa3);
                    var situaglobal = situatext1 + situatext2 + situatext3;
                } else if (laici === "#b2") {
                    situa1 = zone[1];
                    var situatext1 = situa("left", situa1);
                    situa2 = zone[3];
                    var situatext2 = situa("right", situa2);
                    situa3 = zone[6];
                    var situatext3 = situa("bottom", situa3);
                    var situaglobal = situatext1 + situatext2 + situatext3;
                } else if (laici === "#b3") {
                    situa1 = zone[2];
                    var situatext1 = situa("left", situa1);
                    situa2 = zone[7];
                    var situatext2 = situa("bottom", situa2);
                    var situaglobal = situatext1 + situatext2;
                } else if (laici === "#b4") {
                    situa1 = zone[0];
                    var situatext1 = situa("top", situa1);
                    situa2 = zone[5];
                    var situatext2 = situa("right", situa2);
                    situa3 = zone[8];
                    var situatext3 = situa("bottom", situa3);
                    var situaglobal = situatext1 + situatext2 + situatext3;
                } else if (laici === "#b5") {
                    situa1 = zone[1];
                    var situatext1 = situa("top", situa1);
                    situa2 = zone[6];
                    var situatext2 = situa("right", situa2);
                    situa3 = zone[9];
                    var situatext3 = situa("bottom", situa3);
                    situa4 = zone[4];
                    var situatext4 = situa("left", situa4);
                    var situaglobal = situatext1 + situatext2 + situatext3 + situatext4;
                } else if (laici === "#b6") {
                    situa1 = zone[2];
                    var situatext1 = situa("top", situa1);
                    situa2 = zone[7];
                    var situatext2 = situa("right", situa2);
                    situa3 = zone[10];
                    var situatext3 = situa("bottom", situa3);
                    situa4 = zone[5];
                    var situatext4 = situa("left", situa4);
                    var situaglobal = situatext1 + situatext2 + situatext3 + situatext4;
                } else if (laici === "#b7") {
                    situa1 = zone[3];
                    var situatext1 = situa("top", situa1);
                    situa3 = zone[11];
                    var situatext3 = situa("bottom", situa3);
                    situa4 = zone[6];
                    var situatext4 = situa("left", situa4);
                    var situaglobal = situatext1 + situatext3 + situatext4;
                } else if (laici === "#b8") {
                    situa1 = zone[4];
                    var situatext1 = situa("top", situa1);
                    situa2 = zone[9];
                    var situatext2 = situa("right", situa2);
                    situa3 = zone[12];
                    var situatext3 = situa("bottom", situa3);
                    var situaglobal = situatext1 + situatext2 + situatext3;
                } else if (laici === "#b9") {
                    situa1 = zone[5];
                    var situatext1 = situa("top", situa1);
                    situa2 = zone[10];
                    var situatext2 = situa("right", situa2);
                    situa3 = zone[13];
                    var situatext3 = situa("bottom", situa3);
                    situa4 = zone[8];
                    var situatext4 = situa("left", situa4);
                    var situaglobal = situatext1 + situatext2 + situatext3 + situatext4;
                } else if (laici === "#b10") {
                    situa1 = zone[6];
                    var situatext1 = situa("top", situa1);
                    situa2 = zone[11];
                    var situatext2 = situa("right", situa2);
                    situa3 = zone[14];
                    var situatext3 = situa("bottom", situa3);
                    situa4 = zone[9];
                    var situatext4 = situa("left", situa4);
                    var situaglobal = situatext1 + situatext2 + situatext3 + situatext4;
                } else if (laici === "#b11") {
                    situa1 = zone[7];
                    var situatext1 = situa("top", situa1);
                    situa3 = zone[15];
                    var situatext3 = situa("bottom", situa3);
                    situa4 = zone[10];
                    var situatext4 = situa("left", situa4);
                    var situaglobal = situatext1 + situatext3 + situatext4;
                } else if (laici === "#b12") {
                    situa1 = zone[8];
                    var situatext1 = situa("top", situa1);
                    situa2 = zone[13];
                    var situatext2 = situa("right", situa2);
                    var situaglobal = situatext1 + situatext2;
                } else if (laici === "#b13") {
                    situa1 = zone[9];
                    var situatext1 = situa("top", situa1);
                    situa2 = zone[14];
                    var situatext2 = situa("right", situa2);
                    situa4 = zone[12];
                    var situatext4 = situa("left", situa4);
                    var situaglobal = situatext1 + situatext2 + situatext4;
                } else if (laici === "#b14") {
                    situa1 = zone[10];
                    var situatext1 = situa("top", situa1);
                    situa2 = zone[15];
                    var situatext2 = situa("right", situa2);
                    situa4 = zone[13];
                    var situatext4 = situa("left", situa4);
                    var situaglobal = situatext1 + situatext2 + situatext4;
                } else if (laici === "#b15") {
                    situa1 = zone[11];
                    var situatext1 = situa("top", situa1);
                    situa4 = zone[14];
                    var situatext4 = situa("left", situa4);
                    var situaglobal = situatext1 + situatext4;
                }
                $("h2").text("You are on the orange square");
                $("#remplir").html(situaglobal);

                divers[2] = 0;
            });

            function situa(direction, situat) {
                if (direction === "right") {
                    dirTexte = "- On your right";
                } else if (direction === "bottom") {
                    dirTexte = "- At your bottom";
                } else if (direction === "left") {
                    dirTexte = "- On your left";
                } else if (direction === "top") {
                    dirTexte = "- At your top";
                }

                if (situat == 3) {
                    sitTexte = "there is a monster and he is awake!!<br>";
                } else if (situat == 2) {
                    sitTexte = "you can find the stairs...<br>";
                } else if (situat == 0) {
                    sitTexte = "there is nothing.<br>";
                } else {
                    sitTexte = "problème<br>";
                }

                return dirTexte + ", " + sitTexte;
            }


            /*
                LES FONCTIONS
            */

            function entierAleatoire(min, max) {
                return Math.floor(Math.random() * (max - min + 1)) + min;
            }

            function mort() {
                $("h2").text("You are dead - Life Points = " + divers[0]);
                $("#remplir").html("You face the monster. He points his spear at you. He skewers you several times. You are dead.<br><br><img class='centreIMG' src='img/rip.png'><br>You can <a href='index.php'>RESTART DUNGEONJS</a> or <a href='level4.php'>RESTART THE LEVEL</a>.");
            }

            function evasion() {
                $("h2").text("Stairs... maybe a way to leave this weird place!");
                $("#remplir").html("You escape the room by going down the stairs.<br><br><img class='centreIMG' src='img/stairs.png'><br>You can <a href='index.php'>RESTART DUNGEONJS</a> or PLAY THE NEXT LEVEL.");
            }

            function tresor() {
                $("h2").text("You crush an old book ...");
                $("#remplir").html("In this all-black room, you destroy an old book by stepping on it. While digging around, you notice that a leaf of the book is intact and, unbelievably, you manage to read it.<br><br><img class='centreIMG' src='img/scroll.png'><br>It is a magical scroll that allows you to see into the squares around you.<br><br>You can use it once and you put it in your pocket!");
                divers[2] = 1;
            }

            function blessure() {
                $("h2").text("You are not yet dead - Life Points = " + divers[0]);
                $("#remplir").html("You fight the monster with the sword you found on the stairs. You kill him ... but he hit you several times and you end up with multiple wounds.<br><br><img class='centreIMG' src='img/sword.png'><br>");
            }

            function lavie() {
                $("h2").text("Life Points = " + divers[0]);
                $("#remplir").text(" ");
            }

            function lapoche(potion, scroll) {
                $("h2").text("You have in your pockets ...");
                var ppot = " ",
                    pscr = " ";
                if (potion > 0) {
                    var ppot = "<a href='#' id='ipot'><img class='centreIMG' src='img/redpotion.png'></a>";
                }
                if (scroll > 0) {
                    var pscr = "<a href='#' id='iscr'><img class='centreIMG' src='img/scroll.png'></a>";
                }
                $("#remplir").html(ppot + pscr);
            }

            /*
            attente(laici) permet de supprimer les couleurs ajoutées en fonction de la
            constante DECOMPTE

            mouv(situation, laici) gère ce qui se passe en fonction de la situation (définie par le
            paramète situation) sur la case où se trouve le héros (paramètre laici)

            */


            function attente(laici) {
                $(laici).removeClass("rose bleu");
            }

            function mouv(situation, laici) {
                if (situation == 0) {
                    place(laici);
                } else if (situation == 2) {
                    $(laici).removeClass("vert").addClass("jaune");
                    evasion();
                } else if (situation == 3) {
                    vie = divers[0];
                    if (vie > 10) {
                        place(laici);
                        $(laici).addClass("rose");
                        divers[0] = vie - 10;
                        blessure();
                        setTimeout(attente, DECOMPTE, laici);
                    } else {
                        $(laici).removeClass("vert").addClass("rouge");
                        divers[0] = 0;
                        mort();
                    }
                } else if (situation == 4) {
                    place(laici);
                    $(laici).addClass("bleu");
                    tresor();
                    setTimeout(attente, DECOMPTE, laici);

                }
                jesuis[0] = laici;
            }

            /*
                place(laici) - la fonction place permet de définir en fonction de votre case
                (laici) les possibilités de déplacement.
            */

            function place(laici) {
                if (laici === "#b1") {
                    $("#b1").removeClass("vert").addClass("orange");
                    $("#b0").addClass("vert");
                    $("#b2").addClass("vert");
                    $("#b5").addClass("vert");
                    zone[1] = 1;
                } else if (laici === "#b2") {
                    $("#b2").removeClass("vert").addClass("orange");
                    $("#b1").addClass("vert");
                    $("#b3").addClass("vert");
                    $("#b6").addClass("vert");
                    zone[2] = 1;
                } else if (laici === "#b3") {
                    $("#b3").removeClass("vert").addClass("orange");
                    $("#b2").addClass("vert");
                    $("#b7").addClass("vert");
                    zone[3] = 1;
                } else if (laici === "#b4") {
                    $("#b4").removeClass("vert").addClass("orange");
                    $("#b0").addClass("vert");
                    $("#b5").addClass("vert");
                    $("#b8").addClass("vert");
                    zone[4] = 1;
                } else if (laici === "#b5") {
                    $("#b5").removeClass("vert").addClass("orange");
                    $("#b1").addClass("vert");
                    $("#b4").addClass("vert");
                    $("#b6").addClass("vert");
                    $("#b9").addClass("vert");
                    zone[5] = 1;
                } else if (laici === "#b6") {
                    $("#b6").removeClass("vert").addClass("orange");
                    $("#b2").addClass("vert");
                    $("#b5").addClass("vert");
                    $("#b7").addClass("vert");
                    $("#b10").addClass("vert");
                    zone[6] = 1;
                } else if (laici === "#b7") {
                    $("#b7").removeClass("vert").addClass("orange");
                    $("#b3").addClass("vert");
                    $("#b6").addClass("vert");
                    $("#b11").addClass("vert");
                    zone[7] = 1;
                } else if (laici === "#b8") {
                    $("#b8").removeClass("vert").addClass("orange");
                    $("#b4").addClass("vert");
                    $("#b9").addClass("vert");
                    $("#b12").addClass("vert");
                    zone[8] = 1;
                } else if (laici === "#b9") {
                    $("#b9").removeClass("vert").addClass("orange");
                    $("#b5").addClass("vert");
                    $("#b10").addClass("vert");
                    $("#b13").addClass("vert");
                    $("#b8").addClass("vert");
                    zone[9] = 1;
                } else if (laici === "#b10") {
                    $("#b10").removeClass("vert").addClass("orange");
                    $("#b6").addClass("vert");
                    $("#b9").addClass("vert");
                    $("#b11").addClass("vert");
                    $("#b14").addClass("vert");
                    zone[10] = 1;
                } else if (laici === "#b11") {
                    $("#b11").removeClass("vert").addClass("orange");
                    $("#b7").addClass("vert");
                    $("#b10").addClass("vert");
                    $("#b15").addClass("vert");
                    zone[11] = 1;
                } else if (laici === "#b12") {
                    $("#b12").removeClass("vert").addClass("orange");
                    $("#b8").addClass("vert");
                    $("#b13").addClass("vert");
                    zone[12] = 1;
                } else if (laici === "#b13") {
                    $("#b13").removeClass("vert").addClass("orange");
                    $("#b9").addClass("vert");
                    $("#b12").addClass("vert");
                    $("#b14").addClass("vert");
                    zone[13] = 1;
                } else if (laici === "#b14") {
                    $("#b14").removeClass("vert").addClass("orange");
                    $("#b10").addClass("vert");
                    $("#b13").addClass("vert");
                    $("#b15").addClass("vert");
                    zone[14] = 1;
                } else if (laici === "#b15") {
                    $("#b15").removeClass("vert").addClass("orange");
                    $("#b11").addClass("vert");
                    $("#b14").addClass("vert");
                    zone[15] = 1;
                }
            }


        });
    </script>

</body>

</html>