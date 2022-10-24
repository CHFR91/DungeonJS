<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DungeonJS - level 3</title>
    <link href="https://fonts.googleapis.com/css2?family=Slabo+27px&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
</head>

<body>

    <div class="corps">

        <h1>DungeonJS - Level 3</h1>

        <p id="haut">
            You are in a 3x3 dungeon room.<br>
            You are at the top left of the room (<span class="orange">ORANGE</span>),
            the room is so dark that you cannot see the next squares.
            You can only move on <span class="vert">GREEN</span> squares.<br>
            In the room, you can find a <span class="jaune">STAIRCASE</span> (maybe a path to an exit)
            and a deadly <span class="rouge">MONSTER</span> because you don't have enough life yet.<br>
            To stay alive, you just need ... luck!<br>
            <br>
            You get 5 more Life and you have now a total of 10 L.P.
        </p>

        <div class="grille">
            <div>
                <button id="b0" class="bout3 orange"></button>
                <button id="b1" class="bout3 vert"></button>
                <button id="b2" class="bout3"></button>
            </div>
            <div>
                <button id="b3" class="bout3 vert"></button>
                <button id="b4" class="bout3"></button>
                <button id="b5" class="bout3"></button>
            </div>
            <div>
                <button id="b6" class="bout3"></button>
                <button id="b7" class="bout3"></button>
                <button id="b8" class="bout3"></button>
            </div>
        </div>

        <div class="texte">
            <h2></h2>
            <p id="remplir"></p>
        </div>

        <span id="sp0"></span><span id="sp1"></span><span id="sp2"></span><br>
        <span id="sp3"></span><span id="sp4"></span><span id="sp5"></span><br>
        <span id="sp6"></span><span id="sp7"></span><span id="sp8"></span><br>
    </div>


    <script>
        $(document).ready(function() {

            // Génération du niveau

            const NIVEAU = 9;

            var zone = new Array();

            for (var i = 0; i < NIVEAU; i++) {
                zone[i] = 0;
            }

            zone[0] = 1;


            var hasard2 = entierAleatoire(1, (NIVEAU - 1));
            zone[hasard2] = 2;


            var hasard3 = entierAleatoire(1, (NIVEAU - 1));
            while (hasard3 == hasard2) {
                var hasard3 = entierAleatoire(1, (NIVEAU - 1));
            }
            zone[hasard3] = 3;

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
            */


            /*
            Logique :

            avec la boussole (haut, droite, bas, gauche), on définit ceux qui peuvent cliquer sur le bouton

            selon l'origine du joueur, on supprime les anciennes possibilités de placement et la position

            ensuite, selon la situation :
            0 = rien
            1 = héros
            2 = escalier
            3 = mort

            on redéfinit les possibilités de déplacement pour le 0 (et le fait d'être présent sur la case)
            et pour les autres (2 et 3), les conséquences

            */




            $("#b0").click(function() {
                var droite = zone[1];
                var bas = zone[3];

                if ((droite == 1) || (bas == 1)) {

                    var situation = zone[0];

                    if (droite == 1) {
                        $("#b1").removeClass("orange");
                        zone[1] = 0;
                        $("#b2").removeClass("vert");
                        $("#b4").removeClass("vert");
                    } else if (bas == 1) {
                        $("#b3").removeClass("orange");
                        zone[3] = 0;
                        $("#b4").removeClass("vert");
                        $("#b6").removeClass("vert");
                    }

                    if (situation == 0) {
                        $("#b0").removeClass("vert").addClass("orange");
                        $("#b1").addClass("vert");
                        $("#b3").addClass("vert");
                        zone[0] = 1;
                    }

                }
            });


            $("#b1").click(function() {
                var gauche = zone[0];
                var droite = zone[2];
                var bas = zone[4];

                if ((droite == 1) || (gauche == 1) || (bas == 1)) {

                    var situation = zone[1];

                    if (gauche == 1) {
                        $("#b0").removeClass("orange");
                        zone[0] = 0;
                        $("#b3").removeClass("vert");
                    } else if (droite == 1) {
                        $("#b2").removeClass("orange");
                        zone[2] = 0;
                        $("#b5").removeClass("vert");
                    } else if (bas == 1) {
                        $("#b4").removeClass("orange");
                        zone[4] = 0;
                        $("#b3").removeClass("vert");
                        $("#b5").removeClass("vert");
                        $("#b7").removeClass("vert");
                    }

                    if (situation == 0) {
                        $("#b1").removeClass("vert").addClass("orange");
                        $("#b0").addClass("vert");
                        $("#b2").addClass("vert");
                        $("#b4").addClass("vert");
                        zone[1] = 1;
                    } else if (situation == 2) {
                        $(this).removeClass("vert").addClass("jaune");
                        evasion();
                    } else if (situation == 3) {
                        $(this).removeClass("vert").addClass("rouge");
                        mort();
                    }

                }
            });


            $("#b2").click(function() {
                var gauche = zone[1];
                var bas = zone[5];

                if ((gauche == 1) || (bas == 1)) {

                    var situation = zone[2];

                    if (gauche == 1) {
                        $("#b1").removeClass("orange");
                        zone[1] = 0;
                        $("#b0").removeClass("vert");
                        $("#b4").removeClass("vert");
                    } else if (bas == 1) {
                        $("#b5").removeClass("orange");
                        zone[5] = 0;
                        $("#b4").removeClass("vert");
                        $("#b8").removeClass("vert");
                    }

                    if (situation == 0) {
                        $("#b2").removeClass("vert").addClass("orange");
                        $("#b1").addClass("vert");
                        $("#b5").addClass("vert");
                        zone[2] = 1;
                    } else if (situation == 2) {
                        $(this).removeClass("vert").addClass("jaune");
                        evasion();
                    } else if (situation == 3) {
                        $(this).removeClass("vert").addClass("rouge");
                        mort();
                    }

                }
            });


            $("#b3").click(function() {
                var haut = zone[0];
                var droite = zone[4];
                var bas = zone[6];

                if ((haut == 1) || (droite == 1) || (bas == 1)) {

                    var situation = zone[3];

                    if (haut == 1) {
                        $("#b0").removeClass("orange");
                        zone[0] = 0;
                        $("#b1").removeClass("vert");
                    } else if (droite == 1) {
                        $("#b4").removeClass("orange");
                        zone[4] = 0;
                        $("#b1").removeClass("vert");
                        $("#b5").removeClass("vert");
                        $("#b7").removeClass("vert");
                    } else if (bas == 1) {
                        $("#b6").removeClass("orange");
                        zone[6] = 0;
                        $("#b7").removeClass("vert");
                    }

                    if (situation == 0) {
                        $("#b3").removeClass("vert").addClass("orange");
                        $("#b0").addClass("vert");
                        $("#b4").addClass("vert");
                        $("#b6").addClass("vert");
                        zone[3] = 1;
                    } else if (situation == 2) {
                        $(this).removeClass("vert").addClass("jaune");
                        evasion();
                    } else if (situation == 3) {
                        $(this).removeClass("vert").addClass("rouge");
                        mort();
                    }

                }
            });


            $("#b4").click(function() {
                var haut = zone[1];
                var droite = zone[5];
                var bas = zone[7];
                var gauche = zone[3];

                if ((haut == 1) || (droite == 1) || (bas == 1) || (gauche == 1)) {

                    var situation = zone[4];

                    if (haut == 1) {
                        $("#b1").removeClass("orange");
                        zone[1] = 0;
                        $("#b0").removeClass("vert");
                        $("#b2").removeClass("vert");
                    } else if (droite == 1) {
                        $("#b5").removeClass("orange");
                        zone[5] = 0;
                        $("#b2").removeClass("vert");
                        $("#b8").removeClass("vert");
                    } else if (bas == 1) {
                        $("#b7").removeClass("orange");
                        zone[7] = 0;
                        $("#b6").removeClass("vert");
                        $("#b8").removeClass("vert");
                    } else if (gauche == 1) {
                        $("#b3").removeClass("orange");
                        zone[3] = 0;
                        $("#b0").removeClass("vert");
                        $("#b6").removeClass("vert");
                    }

                    if (situation == 0) {
                        $("#b4").removeClass("vert").addClass("orange");
                        $("#b1").addClass("vert");
                        $("#b3").addClass("vert");
                        $("#b5").addClass("vert");
                        $("#b7").addClass("vert");
                        zone[4] = 1;
                    } else if (situation == 2) {
                        $(this).removeClass("vert").addClass("jaune");
                        evasion();
                    } else if (situation == 3) {
                        $(this).removeClass("vert").addClass("rouge");
                        mort();
                    }

                }
            });


            $("#b5").click(function() {
                var haut = zone[2];
                var bas = zone[8];
                var gauche = zone[4];

                if ((haut == 1) || (bas == 1) || (gauche == 1)) {

                    var situation = zone[5];

                    if (haut == 1) {
                        $("#b2").removeClass("orange");
                        zone[2] = 0;
                        $("#b1").removeClass("vert");
                    } else if (bas == 1) {
                        $("#b8").removeClass("orange");
                        zone[8] = 0;
                        $("#b7").removeClass("vert");
                    } else if (gauche == 1) {
                        $("#b4").removeClass("orange");
                        zone[4] = 0;
                        $("#b1").removeClass("vert");
                        $("#b3").removeClass("vert");
                        $("#b7").removeClass("vert");
                    }

                    if (situation == 0) {
                        $("#b5").removeClass("vert").addClass("orange");
                        $("#b2").addClass("vert");
                        $("#b4").addClass("vert");
                        $("#b8").addClass("vert");
                        zone[5] = 1;
                    } else if (situation == 2) {
                        $(this).removeClass("vert").addClass("jaune");
                        evasion();
                    } else if (situation == 3) {
                        $(this).removeClass("vert").addClass("rouge");
                        mort();
                    }

                }
            });


            $("#b6").click(function() {
                var haut = zone[3];
                var droite = zone[7];

                if ((droite == 1) || (haut == 1)) {

                    var situation = zone[6];

                    if (droite == 1) {
                        $("#b7").removeClass("orange");
                        zone[7] = 0;
                        $("#b4").removeClass("vert");
                        $("#b8").removeClass("vert");
                    } else if (haut == 1) {
                        $("#b3").removeClass("orange");
                        zone[3] = 0;
                        $("#b0").removeClass("vert");
                        $("#b4").removeClass("vert");
                    }

                    if (situation == 0) {
                        $("#b6").removeClass("vert").addClass("orange");
                        $("#b3").addClass("vert");
                        $("#b7").addClass("vert");
                        zone[6] = 1;
                    } else if (situation == 2) {
                        $(this).removeClass("vert").addClass("jaune");
                        evasion();
                    } else if (situation == 3) {
                        $(this).removeClass("vert").addClass("rouge");
                        mort();
                    }

                }
            });


            $("#b7").click(function() {
                var droite = zone[8];
                var gauche = zone[6];
                var haut = zone[4];

                if ((droite == 1) || (gauche == 1) || (haut == 1)) {

                    var situation = zone[7];

                    if (droite == 1) {
                        $("#b8").removeClass("orange");
                        zone[8] = 0;
                        $("#b5").removeClass("vert");
                    } else if (gauche == 1) {
                        $("#b6").removeClass("orange");
                        zone[6] = 0;
                        $("#b3").removeClass("vert");
                    } else if (haut == 1) {
                        $("#b4").removeClass("orange");
                        zone[4] = 0;
                        $("#b3").removeClass("vert");
                        $("#b5").removeClass("vert");
                        $("#b1").removeClass("vert");
                    }

                    if (situation == 0) {
                        $("#b7").removeClass("vert").addClass("orange");
                        $("#b4").addClass("vert");
                        $("#b6").addClass("vert");
                        $("#b8").addClass("vert");
                        zone[7] = 1;
                    } else if (situation == 2) {
                        $(this).removeClass("vert").addClass("jaune");
                        evasion();
                    } else if (situation == 3) {
                        $(this).removeClass("vert").addClass("rouge");
                        mort();
                    }

                }
            });


            $("#b8").click(function() {
                var haut = zone[5];
                var gauche = zone[7];

                if ((gauche == 1) || (haut == 1)) {

                    var situation = zone[8];

                    if (gauche == 1) {
                        $("#b7").removeClass("orange");
                        zone[7] = 0;
                        $("#b4").removeClass("vert");
                        $("#b6").removeClass("vert");
                    } else if (haut == 1) {
                        $("#b5").removeClass("orange");
                        zone[5] = 0;
                        $("#b2").removeClass("vert");
                        $("#b4").removeClass("vert");
                    }

                    if (situation == 0) {
                        $("#b8").removeClass("vert").addClass("orange");
                        $("#b5").addClass("vert");
                        $("#b7").addClass("vert");
                        zone[8] = 1;
                    } else if (situation == 2) {
                        $(this).removeClass("vert").addClass("jaune");
                        evasion();
                    } else if (situation == 3) {
                        $(this).removeClass("vert").addClass("rouge");
                        mort();
                    }

                }
            });

            function entierAleatoire(min, max) {
                return Math.floor(Math.random() * (max - min + 1)) + min;
            }

            function mort() {
                $("h2").text(mortTitre);
                $("#remplir").html(mortTexte);
            }

            function evasion() {
                $("h2").text(escalierTitre);
                $("#remplir").html(escalierTexte);
            }

            var mortTitre = "No luck, you are dead!";
            var mortTexte = "You face the monster. He points his spear at you. He skewers you several times. You are dead.<br><br><img class='centreIMG' src='img/rip.png'><br>You can <a href='index.php'>RESTART DUNGEONJS</a> or <a href='level3.php'>RESTART THE LEVEL</a>.";
            var escalierTitre = "Stairs... maybe a way to leave this weird place!";
            var escalierTexte = "You escape the room by going down the stairs.<br><br><img class='centreIMG' src='img/stairs.png'><br>You can <a href='index.php'>RESTART DUNGEONJS</a> or <a href='level4.php'>PLAY THE NEXT LEVEL</a>.";

        });
    </script>

</body>

</html>