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
        loadCalendarResource();
    });

    /* カレンダーの設置 */
    if ( $('input[name="focus_tab"]').val() === 'tab_calendar' ) {
        setFullCalendar();
        loadCalendarResource();
    } else {
        $('[data-toggle="tab"]').one('click',function (){

            $('#tab_calendar').css({display : 'block'});

            /* タブの切り替えがされた後にカレンダーを適用する */
            setFullCalendar();
            loadCalendarResource();
        });
    }

    /* fullcalendar.jsを要素に適用 */
    function setFullCalendar() {
        $('#calendar').fullCalendar({
            lazyFetching: true,
            // ヘッダーのタイトルとボタン
            header: {
                // title, prev, next, prevYear, nextYear, today
                left: 'prev,next today',
                center: 'title',
                right: 'month agendaWeek agendaDay'
            },
            defaultView: 'agendaWeek',
            allDaySlot: false,
            axisFormat : 'H:mm',
            // 最初の曜日
            firstDay: 1, // 1:月曜日
            // 土曜、日曜を表示
            weekends: true,
            // 週モード (fixed, liquid, variable)
            weekMode: 'fixed',
            // リストの先頭
            minTime: '05:00:00',
            // リストの終わり
            maxTime: '29:00:00',
            scrollTime: '08:00:00',
            // タイトルの書式
            titleFormat: {
                month: 'YYYY年M月',                             // 2013年9月
                week: "YYYY年M月D日", 							// 2013年9月7日 ～ 13日
                day: "YYYY年M月D日('ddd')"                  // 2013年9月7日(火)
            },
            // 月名称
            monthNames: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'],
            // 月略称
            monthNamesShort: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'],
            // 曜日名称
            dayNames: ['日曜日', '月曜日', '火曜日', '水曜日', '木曜日', '金曜日', '土曜日'],
            // 曜日略称
            dayNamesShort: ['日', '月', '火', '水', '木', '金', '土'],
            // ボタン文字列
            buttonText: {
                prev: '<', // <
                next: '>', // >
                prevYear: '<<',  // <<
                nextYear: '>>',  // >>
                today: '今日',
                month: '月',
                week: '週',
                day: '日'
            },

        });
        //スタイルの非表示設定(display:none)を削除
        $('#tab_calendar').css({display:''});
        $('#calendar').fullCalendar('render');
    }

    /* jsonデータから出退勤データを取得し*/
    function loadCalendarResource(){
        let request = {};

        /* 検索条件 */
        $('form[name="search_form"] input[name]').each(function(){
            if($(this).val()){
                request[ this.name ] = $(this).val();
            }
        });

        getJSON(request);
    }

    /* jsonデータで出退勤データを取得する*/
    function getJSON(request){
        $.ajax({
            url: '/attendance/json',
            type: 'GET',
            data: request,
            dataType: 'json'
        })
            .then(
                function(json) {
                    addEventsToCalendor(json);
                    return true;
                },
                function() {
                    alert('フォームの取得に失敗しました。サイト管理者にお問い合わせ下さい。');
                    return false;
                }
            );
    }
    /* jsonデータから出退勤データを取得し、カレンダーに読み込ませる */
    function addEventsToCalendor(json){
        let events = [];
        let startTime=null;

        for(let i = 0 ;i < json.length; i++){
            if ( json[i]['status'] === 10 ) {
                startTime = json[i]['created_at'];
            } else {

                let color='#f16900'
                switch (json[i]['user_id']) {
                    case 1: color="#1ab237";break;
                    case 2: color="#aa3acc";break;
                    case 3: color="#cc0";break;
                    case 5: color="#7a6dcc";break;
                    case 6: color="#cc2c59";break;
                }
                if(startTime){
                    events.push({
                        title: '出勤・退勤',
                        start: startTime,
                        end: json[i]['created_at'],
                        color:color,
                    });
                }

                startTime=null;
            }
        }
        $('#calendar').fullCalendar('removeEvents');
        $('#calendar').fullCalendar('addEventSource',events);
    }

});