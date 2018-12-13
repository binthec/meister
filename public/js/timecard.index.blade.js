$(function() {

    //フォームのをリセットする（リセットボタンの親要素にあるフォームが対象）
    $('.reset_form').on('click', function () {
        let $form = $(this).closest('form');
        if ($form[0]) {
            $form.find('input[name],select[name],textarea[name]').prop('checked', false).val('');
        }
    });

    /* 日付項目でdatepickerを有効化 */
    $('.use_datepicker').each(function () {
        $(this).datepicker({
            language: "ja",
            format: "yyyy年m月d日",
            autoclose: true,
            orientation: "top left"
        }).datepicker('setDate', this.value)
    });

    /* 時刻項目でtimepickerを有効化 */
    $('.use_timepicker').each(function () {
        $(this).timepicker({
            showMeridian: false,
            showInputs: false,
            minuteStep: 5,
        }).timepicker('setTime', this.value)
    });

    /* 選択したタブの名称をフォームに入力（検索後の初期表示のタブに使用） */
    $('[data-toggle="tab"]').on('click', function () {
        $('input[name="focus_tab"]').val(this.getAttribute('href').replace('#', ''))
        setResource();
    });

});