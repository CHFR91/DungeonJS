$(document).ready(function () {
  // Génération du niveau

  const NIVEAU = 9;

  var zone = new Array();

  for (let i = 0; i < NIVEAU; i++) {
    zone[i] = 0;
  }

  zone[0] = 1;

  let hasard2 = entierAleatoire(1, NIVEAU - 1);
  zone[hasard2] = 2;

  let hasard3 = entierAleatoire(1, NIVEAU - 1);
  while (hasard3 == hasard2) {
    let hasard3 = entierAleatoire(1, NIVEAU - 1);
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

  $("#b0").click(function () {
    let droite = zone[1];
    let bas = zone[3];

    if (droite == 1 || bas == 1) {
      let situation = zone[0];

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

  $("#b1").click(function () {
    let gauche = zone[0];
    let droite = zone[2];
    let bas = zone[4];

    if (droite == 1 || gauche == 1 || bas == 1) {
      let situation = zone[1];

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

  $("#b2").click(function () {
    let gauche = zone[1];
    let bas = zone[5];

    if (gauche == 1 || bas == 1) {
      let situation = zone[2];

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

  $("#b3").click(function () {
    let haut = zone[0];
    let droite = zone[4];
    let bas = zone[6];

    if (haut == 1 || droite == 1 || bas == 1) {
      let situation = zone[3];

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

  $("#b4").click(function () {
    let haut = zone[1];
    let droite = zone[5];
    let bas = zone[7];
    let gauche = zone[3];

    if (haut == 1 || droite == 1 || bas == 1 || gauche == 1) {
      let situation = zone[4];

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

  $("#b5").click(function () {
    let haut = zone[2];
    let bas = zone[8];
    let gauche = zone[4];

    if (haut == 1 || bas == 1 || gauche == 1) {
      let situation = zone[5];

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

  $("#b6").click(function () {
    let haut = zone[3];
    let droite = zone[7];

    if (droite == 1 || haut == 1) {
      let situation = zone[6];

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

  $("#b7").click(function () {
    let droite = zone[8];
    let gauche = zone[6];
    let haut = zone[4];

    if (droite == 1 || gauche == 1 || haut == 1) {
      let situation = zone[7];

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

  $("#b8").click(function () {
    let haut = zone[5];
    let gauche = zone[7];

    if (gauche == 1 || haut == 1) {
      let situation = zone[8];

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
    $("h2").text("No luck, you are dead!");
    $("#remplir").html(
      "You face the monster. He points his spear at you. He skewers you several times. You are dead.<br><br><img class='centreIMG' src='img/rip.png'><br>You can <a href='index.html'>RESTART DUNGEONJS</a> or <a href='level3.html'>RESTART THE LEVEL</a>."
    );
  }

  function evasion() {
    $("h2").text("Stairs... maybe a way to leave this weird place!");
    $("#remplir").html(
      "You escape the room by going down the stairs.<br><br><img class='centreIMG' src='img/stairs.png'><br>You can <a href='index.html'>RESTART DUNGEONJS</a> or <a href='level4.html'>PLAY THE NEXT LEVEL</a>."
    );
  }
});
