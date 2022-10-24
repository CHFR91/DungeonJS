<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DungeonJS - level 1</title>
    <link href="https://fonts.googleapis.com/css2?family=Slabo+27px&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
</head>

<body>

    <div class="corps">

        <h1>DungeonJS - Level 1</h1>

        <p id="haut">
            You are a normal person, nothing special.<br>
            <br>
            On a beautiful autumn Sunday, you are walking in the forest with friends. Friends ? You doubt it now. You've been looking for them for more than 8 hours.<br>
            <br>
            Night has fallen and you are lost. You hear the sounds of wild beasts (wolves?) and they approach you. You run... like you've never run before.<br>
            <br>
            Suddenly, you see in front of you a small wooden cabin. You go inside. You close the door when the wild beasts arrive at the level of the cabin. You use the very end of your battery to observe the inside of it.<br>
            <br>
            And, good news, there is a trap door in front of you.<br>
            The bad news is that your cell phone just died.<br>
            <br>
            You open the heavy trap door, climb down and close it behind you when the door to the hut opens with a crash... the animals have just entered.<br>
            <br>
            All you have to do is climb down the <span class="jaune">LADDER</span> (<em>click on it</em>)...<br>
        </p>

        <div class="grille">
            <div>
                <button id="b0" class="bout1 jaune">The Ladder</button>
            </div>
        </div>


        <div class="texte">
            <h2></h2>
            <p id="remplir"></p>
        </div>
    </div>


    <script>
        $(document).ready(function() {

            $(".bout1").click(function() {

                $("h2").text(escalierTitre);
                $("#remplir").html(escalierTexte);
            });

            var escalierTitre = "The ladder... a way to be safe from those beasts!";
            var escalierTexte = "You escape those animals by climbing down the ladder.<br><br><img class='centreIMG' src='img/ladder.png'><br>You can <a href='index.php'>RESTART DUNGEONJS</a> or <a href='level2.php'>PLAY THE NEXT LEVEL</a>.";

        });
    </script>

</body>

</html>