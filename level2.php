<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DungeonJS - level 2</title>
    <link href="https://fonts.googleapis.com/css2?family=Slabo+27px&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
</head>

<body>

    <div class="corps">

        <h1>DungeonJS - Level 2</h1>

        <p id="haut">
            You are in a 2x2 dungeon room.<br>
            You are at the top left of the room (<span class="orange">ORANGE</span>),
            the room is so dark that you cannot see the next squares.
            You can only move on <span class="vert">GREEN</span> squares.<br>
            In the room, you can find only a <span class="jaune">STAIRCASE</span>
            which is maybe a path to an exit.<br>
            <br>
            You discover a new concept in this tower... LIFE and that you have now LIFE POINTS.<br>
            Going down the stairs, you gained 5 Life and you have now a total of ... 5 L.P.
        </p>

        <div class="grille">
            <div>
                <button id="b0" class="bout2 orange"></button>
                <button id="b1" class="bout2 vert"></button>
            </div>
            <div>
                <button id="b2" class="bout2 vert"></button>
                <button id="b3" class="bout2"></button>
            </div>
        </div>

        <div class="texte">
            <h2></h2>
            <p id="remplir"></p>
        </div>

        <span id="sp0"></span><span id="sp1"></span><br>
        <span id="sp2"></span><span id="sp3"></span><br>
    </div>


    <script>
        $(document).ready(function() {

            // Génération du niveau

            const NIVEAU = 4;

            var zone = new Array();

            for (var i = 0; i < NIVEAU; i++) {
                zone[i] = 0;
            }

            zone[0] = 1;

            var hasard2 = entierAleatoire(1, (NIVEAU - 1));
            zone[hasard2] = 2;

            /*
            $("#sp0").text(zone[0]);
            $("#sp1").text(zone[1]);
            $("#sp2").text(zone[2]);
            $("#sp3").text(zone[3]);
            */


            /*
            Logique :

            avec la boussole (haut, droite, bas, gauche), on définit ceux qui peuvent cliquer sur le bouton

            selon l'origine du joueur, on supprime les anciennes possibilités de placement et la position

            ensuite, selon la situation :
            0 = rien
            1 = héros
            2 = escalier

            on redéfinit les possibilités de déplacement pour le 0 (et le fait d'être présent sur la case)
            et pour le 2, les conséquences

            */




            $("#b0").click(function() {
                var droite = zone[1];
                var bas = zone[2];

                if ((droite == 1) || (bas == 1)) {

                    var situation = zone[0];

                    if (droite == 1) {
                        $("#b1").removeClass("orange");
                        zone[1] = 0;
                        $("#b3").removeClass("vert");
                    } else if (bas == 1) {
                        $("#b2").removeClass("orange");
                        zone[2] = 0;
                        $("#b3").removeClass("vert");
                    }

                    if (situation == 0) {
                        $("#b0").removeClass("vert").addClass("orange");
                        $("#b1").addClass("vert");
                        $("#b2").addClass("vert");
                        zone[0] = 1;
                    }
                }
            });


            $("#b1").click(function() {
                var gauche = zone[0];
                var bas = zone[3];

                if ((gauche == 1) || (bas == 1)) {

                    var situation = zone[1];

                    if (gauche == 1) {
                        $("#b0").removeClass("orange");
                        zone[0] = 0;
                        $("#b2").removeClass("vert");
                    } else if (bas == 1) {
                        $("#b3").removeClass("orange");
                        zone[3] = 0;
                        $("#b2").removeClass("vert");
                    }

                    if (situation == 0) {
                        $("#b1").removeClass("vert").addClass("orange");
                        $("#b0").addClass("vert");
                        $("#b3").addClass("vert");
                        zone[1] = 1;
                    } else if (situation == 2) {
                        $(this).removeClass("vert").addClass("jaune");
                        evasion();
                    }
                }
            });


            $("#b2").click(function() {
                var haut = zone[0];
                var droite = zone[3];

                if ((droite == 1) || (haut == 1)) {

                    var situation = zone[2];

                    if (droite == 1) {
                        $("#b3").removeClass("orange");
                        zone[3] = 0;
                        $("#b1").removeClass("vert");
                    } else if (haut == 1) {
                        $("#b0").removeClass("orange");
                        zone[0] = 0;
                        $("#b1").removeClass("vert");
                    }

                    if (situation == 0) {
                        $("#b2").removeClass("vert").addClass("orange");
                        $("#b0").addClass("vert");
                        $("#b3").addClass("vert");
                        zone[2] = 1;
                    } else if (situation == 2) {
                        $(this).removeClass("vert").addClass("jaune");
                        evasion();
                    }
                }
            });


            $("#b3").click(function() {
                var haut = zone[1];
                var gauche = zone[2];

                if ((gauche == 1) || (haut == 1)) {

                    var situation = zone[3];

                    if (gauche == 1) {
                        $("#b2").removeClass("orange");
                        zone[2] = 0;
                        $("#b0").removeClass("vert");
                    } else if (haut == 1) {
                        $("#b1").removeClass("orange");
                        zone[1] = 0;
                        $("#b0").removeClass("vert");
                    }

                    if (situation == 0) {
                        $("#b3").removeClass("vert").addClass("orange");
                        $("#b1").addClass("vert");
                        $("#b2").addClass("vert");
                        zone[3] = 1;
                    } else if (situation == 2) {
                        $(this).removeClass("vert").addClass("jaune");
                        evasion();
                    }
                }
            });

            function entierAleatoire(min, max) {
                return Math.floor(Math.random() * (max - min + 1)) + min;
            }

            function evasion() {
                $("h2").text(escalierTitre);
                $("#remplir").html(escalierTexte);
            }

            var escalierTitre = "Stairs... maybe a way to leave this weird place!";
            var escalierTexte = "You escape the room by going down the stairs.<br><br><img class='centreIMG' src='img/stairs.png'><br>You can <a href='index.php'>RESTART DUNGEONJS</a> or <a href='level3.php'>PLAY THE NEXT LEVEL</a>.";

        });
    </script>

</body>

</html>