function affichePhoto() {
  if($("#photo").is(":hidden")){
    $("#photo").show();
  }else{
      $("#photo").hide();
  }

}



function afficheApropos() {
    if($("#Apropos").is(":hidden")){
      $("#Apropos").show();
    }else{
        $("#Apropos").hide();
    }


}


function openCity(evt, cityName) {

$(".tabcontent").hide();
$(".tablinks").removeClass("active");
$("#"+cityName).css("display","block");
evt.currentTarget.addClass("active");

}
