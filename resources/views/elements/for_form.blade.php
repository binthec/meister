<!-- bootstrap datepicker -->
<link rel="stylesheet" href="/plugins/datepicker/datepicker3.css">

<!-- bootstrap datepicker -->
<script src="/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="/plugins/datepicker/locales/bootstrap-datepicker.ja.js"></script>

<script>
    $(function () {
        $(".use_datepicker").datepicker({
            language: "ja",
            format: "yyyy年m月d日",
            autoclose: true,
            orientation: "bottom left"
        });
    });
</script>


<!-- daterange picker -->
<link rel="stylesheet" href="/plugins/daterangepicker/daterangepicker.css">

<!-- date-range-picker -->
<script src="/plugins/daterangepicker/daterangepicker.js"></script>

<script>
    //Date range picker	
    $(function () {
        $(".use_daterange").daterangepicker({
            drops: "up",
            applyClass: "btn-primary",
            locale: {
                format: "YYYY年MM月DD日",
                separator: " 〜 ",
                applyLabel: "反映",
                cancelLabel: "取消",
            }
        },
                function (start, end) {
                    var used_days = end.diff(start, 'days') + 1; //消化する日数計算
                    $('#sum').text(used_days); //消化日数をp要素に出力

                    //消化日数が１日か複数日かで表示する半休チェックを入れ替える
                    if (used_days == 1) {
                        $('#single').removeClass("cant-use").addClass("can-use");
                        $('#plural').removeClass("can-use").addClass("cant-use").find('input').prop("checked", false);
                    } else {
                        $('#single').removeClass("can-use").addClass("cant-use").find('input').prop("checked", false);
                        $('#plural').removeClass("cant-use").addClass("can-use");
                    }

                    //hiddenで値を渡す
                    $('#from').attr('value', start.format('YYYY-MM-DD'));
                    $('#until').attr('value', end.format('YYYY-MM-DD'));
                    $('#used_days').attr('value', used_days);
                });

        //午前・午後の計算
        $('.half').change(function () {
            if ($(this).is(':checked')) {
                var sum = Number($('#sum').text()) - Number(0.5);
                $('#sum').text(sum);
                $('#used_days').attr('value', sum);
            } else {
                var sum = Number($('#sum').text()) + Number(0.5);
                $('#sum').text(sum);
                $('#used_days').attr('value', sum);
            }
        });
    });
</script>