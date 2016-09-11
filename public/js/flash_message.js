$(function () {
    //フラッシュメッセージを一定時間表示後にフェイドアウトして消す
    $(".flashMessage").fadeIn("slow", function () {
        $(this).delay(1200).fadeOut("slow");
    });
    //せっかちな人用に、フェイドアウト前でも、要素かbodyをクリックすればフラッシュメッセージが消える
    $(".flashMessage, body").on("click", function () {
        $(".flashMessage").hide();
    });
});
