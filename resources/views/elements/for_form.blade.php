<!-- bootstrap datepicker -->
<link rel="stylesheet" href="/plugins/datepicker/datepicker3.css">

<!-- bootstrap datepicker -->
<script src="/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="/plugins/datepicker/locales/bootstrap-datepicker.ja.js"></script>


<!-- daterange picker -->
<link rel="stylesheet" href="/plugins/daterangepicker/daterangepicker.css">
<!-- date-range-picker -->
<script src="/plugins/daterangepicker/daterangepicker.js"></script>

<script>
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

    //１日選択の上、半休の場合、片方のチェックボックスがチェックされたらもう片方を選択不可にする
    turnOverChkBox('#from_am', '#from_pm');
    turnOverChkBox('#from_pm', '#from_am');

    function turnOverChkBox(checked, turnOver) {
        $(checked).change(function () {
            if ($(this).is(':checked')) {
                $(turnOver).prop('disabled', true);
            } else {
                $(turnOver).prop('disabled', false);
            }
        });
    }

    function calcAndSetVal(start, end) {
        var usedDays = end.diff(start, 'days') + 1; //消化する日数計算
        $('#sum').text(usedDays); //消化日数をp要素に出力

        //消化日数が１日か複数日かで表示する半休チェックを入れ替える			
        displayChkBox(usedDays);

        //hiddenで値を渡す
        $('#from').attr('value', start.format('YYYY-MM-DD'));
        $('#until').attr('value', end.format('YYYY-MM-DD'));
        $('#used_days').attr('value', usedDays);
    }

    function displayChkBox(usedDays, defaultForEdit = false) {
        if (defaultForEdit === false) {
            if (usedDays <= 1) {
                $('#single').removeClass("cant-use").addClass("can-use").find('input').prop("checked", false).prop('disabled', false);
                $('#plural').removeClass("can-use").addClass("cant-use").find('input').prop("checked", false);
            } else {
                $('#single').removeClass("can-use").addClass("cant-use").find('input').prop("checked", false);
                $('#plural').removeClass("cant-use").addClass("can-use").find('input').prop("checked", false).prop('disabled', false);
            }
        } else {
            if (usedDays <= 1) {
                $('#single').removeClass("cant-use").addClass("can-use").find('input').prop('disabled', false);
                $('#plural').removeClass("can-use").addClass("cant-use").find('input').prop("checked", false);
            } else {
                $('#single').removeClass("can-use").addClass("cant-use").find('input').prop("checked", false);
                $('#plural').removeClass("cant-use").addClass("can-use").find('input').prop('disabled', false);
            }
        }
    }

</script>