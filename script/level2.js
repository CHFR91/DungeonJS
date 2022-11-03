$(document).ready(function () {
  // Génération du niveau

  const NIVEAU = 4;

  var zone = new Array();

  for (let i = 0; i < NIVEAU; i++) {
    zone[i] = 0;
  }

  zone[0] = 1;

  let hasard2 = entierAleatoire(1, NIVEAU - 1);
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

  $("#b0").click(function () {
    let droite = zone[1];
    let bas = zone[2];

    if (droite == 1 || bas == 1) {
      let situation = zone[0];

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

  $("#b1").click(function () {
    let gauche = zone[0];
    let bas = zone[3];

    if (gauche == 1 || bas == 1) {
      let situation = zone[1];

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

  $("#b2").click(function () {
    let haut = zone[0];
    let droite = zone[3];

    if (droite == 1 || haut == 1) {
      let situation = zone[2];

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

  $("#b3").click(function () {
    let haut = zone[1];
    let gauche = zone[2];

    if (gauche == 1 || haut == 1) {
      let situation = zone[3];

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
    $("h2").text("Stairs... maybe a way to leave this weird place!");
    $("#remplir").html(
      "You escape the room by going down the stairs.<br><br><img class='centreIMG' src='img/stairs.png'><br>You can <a href='index.html'>RESTART DUNGEONJS</a> or <a href='level3.html'>PLAY THE NEXT LEVEL</a>."
    );
  }
});
