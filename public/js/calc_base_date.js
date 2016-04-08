$(function () {
    //datepicker
    $("#date_of_entering, #base_date").datepicker({
        dateFormat: 'yy-mm-dd',
        language: 'ja',
        beforeShow: function (input, inst) { //カレンダー位置の調整
            var calendar = inst.dpDiv; // Datepicker
            setTimeout(function () {
                calendar.position({
                    my: 'left bottom', // カレンダーの左下を
                    at: 'left top', // 表示対象の左上に乗せる
                    of: input, // 対象テキストボックス
                });
            }, 1);
        }
    });

    //起算日の自動算出 moment.jsを使用
    $('#date_of_entering').change(function () {
        $('#base_date').text(''); //入社日が変更されたらデフォルト文言は空にする
        var date = moment($('#date_of_entering').val()); //入社日でdateインスタンスを生成

        var base_date = date.add(6, 'months').format('YYYY-MM-DD'); //6ヶ月後の日付を計算、代入
        $('#base_date_text').text(base_date); //6ヶ月後の日付を起算日欄 p要素に出力
        $('#base_date').attr('value', base_date); //hiddenで値を渡す
    });

});
