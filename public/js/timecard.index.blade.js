$(function() {

    //フォームのリセット処理（リセットボタンを含むフォームが対象）
    $('.reset_form').on('click', function () {
        let $form = $(this).closest('form');
        /* 入力可能なname属性をもつ要素に絞り込む */
        let $children = $form.find('input,select,textarea').filter('[name]').not('[type="hidden"],[disabled],[readonly]');
        if ($children[0]) {
            /* 値とチェックマークをクリア */
            $children.prop('checked', false).val('');
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

    /*  タブクリック時に、タブにタブ名をフォームに入力（検索後の初期表示のタブに使用） */
    $('.btn_tab').on('click', function () {
        /* タブ名を取得 */
        let tab_name = $(this).find('a').attr('href').replace('#', '');
        /* フォームに表示しているタブ名を入力 */
        $('input[name="focus_tab"]').val( tab_name );

        if (tab_name === 'tab_calendar'){
            loadCalendarResource();
        } else if (tab_name === 'tab_chart'){
            loadChartResource()
        }

    });


    let focus_tab = $('input[name="focus_tab"]').val();
    /* ロード時のタブがカレンダーなら、カレンダー生成・データ読み込みを実行 */
    if ( focus_tab === 'tab_calendar' )
    {
        setFullCalendar();
        loadCalendarResource();
    }
    /* ロード時のタブがカレンダー以外なら、タブクリック時に処理を一度だけ実行 */
    else
    {
        $('.btn_tab_calendar').one('click',function (){
            /* 非表示時ではうまくいかないため、スタイルで表示しておく */
            $('#tab_calendar').css({display : 'block'});
            /* タブの切り替えがされた後にカレンダーを適用する */
            setFullCalendar();
        });
    }
    /* ロード時のタブがグラフなら、グラフ生成・データ読み込みを実行 */
    if ( focus_tab　== 'tab_chart' ) {
        loadChartResource();
    }


    /* タブ中ににカレンダーを生成する（データ未入力） */
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

    /* 検索フォームの値を取得し、連想配列として渡す */
    function getSearchFormRequest(){
        let request = {};
        $('form[name="search_form"] input[name]').each(function(){
            if($(this).val()){
                request[ this.name ] = $(this).val();
            }
        });
        return request
    }

    /* jsonデータから出退勤データを取得し*/
    function loadCalendarResource(){
        getJSON( getSearchFormRequest() );
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


    /* 出退勤データを取得し、総勤務時間データをグラフに適用 */
    function loadChartResource() {

        $.ajax({
            url: '/attendance/json',
            type: 'GET',
            data: getSearchFormRequest(),
            dataType: 'json'
        })
        .then(
            function (json) {

                let usersData = [];
                let startTime=null;
                /* jsonデータからユーザ毎の総勤務時間を計算 */
                for (let i = 0 ;i < json.length; i++){
                    if ( json[i]['status'] === 10 ) {
                        startTime = json[i]['created_at'];
                    } else {

                        if (startTime){
                            let user_id = json[i]['user_id']
                            /* 出勤・退勤時刻の差分を時で取得 */
                            let wokingHours = ( new Date(json[i]['created_at']) - new Date(startTime) )/ 3600000 ;

                            if ( usersData[user_id] ){
                                usersData[user_id] += wokingHours
                            } else {
                                usersData[user_id] = wokingHours;
                            }
                        }
                        startTime=null;
                    }
                }
                console.log(usersData);
                setChartData( usersData );
                return true;
            },
            function () {
                alert('フォームの取得に失敗しました。サイト管理者にお問い合わせ下さい。');
                return false;
            }
        );


    }
    /* 総勤務時間をグラフに適用 */
    function setChartData( usersData ){

        let $canvas = $("#chart");
        let chartData = {
            labels   : [],
            datasets : [
                {
                    data:[],
                    backgroundColor: "#00b0ff"
                }
            ],
        };
        if ( !$canvas[0] ) {
            return false;
        }

        for (let i = 0 ;i < usersData.length; i++){
            if ( usersData[i] ) {
                chartData.labels.push( 'ユーザ' + i );
                chartData.datasets[0].data.push( usersData[i] );
            }
        }

        let ctx = $canvas[0].getContext("2d");
        let chart = new Chart(ctx, {
            type: "bar",
            data:chartData,
            options:{
                scales:{
                    xAxes:[{
                        stacked: true
                    }],
                    yAxes:[{
                        stacked: true,
                        scaleLabel: {              //軸ラベル設定
                            display: true,          //表示設定
                            labelString: '総勤務時間 (時間単位)',  //ラベル
                            fontSize: 18               //フォントサイズ
                        },
                    }]
                }
            }
        });
    }

});