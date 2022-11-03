$(document).ready(function () {
  $(".bout1").click(function () {
    $("h2").text("The ladder... a way to be safe from those beasts!");
    $("#remplir").html(
      "You escape those animals by climbing down the ladder.<br><br><img class='centreIMG' src='img/ladder.png'><br>You can <a href='index.html'>RESTART DUNGEONJS</a> or <a href='level2.html'>PLAY THE NEXT LEVEL</a>."
    );
  });
});
