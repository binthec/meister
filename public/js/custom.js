$(document).ready(function(){

    $(".submenu > a").click(function(e) {
    e.preventDefault();
    var $li = $(this).parent("li");
    var $ul = $(this).next("ul");

    if($li.hasClass("open")) {
      $ul.slideUp(350);
      $li.removeClass("open");
    } else {
      $(".nav > li > ul").slideUp(350);
      $(".nav > li").removeClass("open");
      $ul.slideDown(350);
      $li.addClass("open");
    }
  });

    //フラッシュメッセージを一定時間表示後にフェイドアウトして消す
    $(".flashMsg").fadeIn("slow", function () {
        $(this).delay(1200).fadeOut("slow");
    });
    //せっかちな人用に、フェイドアウト前でも、要素かbodyをクリックすればフラッシュメッセージが消える
    $(".flashMsg, body").on("click", function () {
        $(".flashMsg").hide();
    });
    
});