@extends('layouts/master')

@section('content')
<div class="row">
	<div class="col col-md-offset-2 col-md-8">
		<h2>有給消化登録</h2>

		<form method="post" action="/user/use_request" class="form-horizontal well">
			{{-- CSRF対策--}}
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
			<div class="form-group">
				<label for="ID" class="col-md-2 control-label">ID</label>
				<div class="col-md-8 form-control-static">{{ Auth::user()->id }}</div>
			</div>
			<div class="form-group">
				<label for="ID" class="col-md-2 control-label">名前</label>
				<div class="col-md-8 form-control-static">
					<p>{{ Auth::user()->family_name }} {{ Auth::user()->first_name }} さん</p>
				</div>
			</div>



			<div class="form-group request_remaining_day">
				<label for="ID" class="col-md-2 control-label">有給残日数</label>
				<div class="col-md-10">

					@foreach(Auth::user()->getValidPaidVacation() as $remaining_day)
					<div class="col-md-3 inline">
						<div class="panel panel-default align-center">
							<div class="panel-heading">{{ $remaining_day->limit_date }}日期限</div>
							<div class="panel-body font13">
								{{ $remaining_day->remaining_days }}
							</div>
						</div>
					</div>
					@endforeach

					<div class="col-md-3">
						<div class="panel panel-default align-center">
							<div class="panel-heading">合計有給残日数</div>
							<div class="panel-body font13">
								{{ Auth::user()->addRemainingDays() }}
							</div>
						</div>
					</div>
				</div>
			</div>

			<hr>

			<div class="form-group">
				<label for="ID" class="col-md-2 control-label">有給申請期間</label>
				<div class="col-md-8 form-inline">
					<input type="date" class="form-control" placeholder="開始日" name="use_start_date" id="use_start_date" value=""></input>
					〜
					<input type="date" class="form-control" placeholder="終了日" name="use_finish_date" id="use_finish_date" value=""></input>
					<span id="sum_box">合計： <span id="sum"></span> 日間</span>
				</div>
			</div>
			<div class="form-group">
				<label for="ID" class="col-md-2 control-label">連絡事項</label>
				<div class="col-md-8">
					<textarea class="form-control" name="memo" id="memo" value=""></textarea>
				</div>
			</div>

			<input type="hidden" name="use_days" id="use_days" value=""></input>
			<input type="hidden" name="user_id" id="user_id" value=""></input>

			<button type="submit" class="btn btn-success col-md-offset-2">決定</button>
		</form>
	</div>
</div>

{{-- DatePickerのJS--}}
<script>
    $(function () {
        //datepicker
        $("#use_start_date, #use_finish_date").datepicker({
            dateFormat: 'yy-mm-dd',
            language: 'ja',
            beforeShow: function (input, inst) { //カレンダー位置の調整
                var calendar = inst.dpDiv; // Datepicker
                setTimeout(function () {
                    calendar.position({
                        my: 'left top', // カレンダーの左下
                        at: 'left bottom', // 表示対象の左上
                        of: input, // 対象テキストボックス
                    });
                }, 1);
            }
        });
        //申請日数の計算
        $('#use_start_date').change(function () {
            $('#sum').text(1); //消化日数をp要素に出力
            $('#use_days').attr('value', use_days); //hiddenで値を渡す
        });
        //申請日数の計算
        $('#use_finish_date').change(function () {
            //$('#use_finish_date').text(''); //入社日が変更されたらデフォルト文言は空にする
            var use_start_date = moment($('#use_start_date').val()); //入力された開始日を取得
            var use_finish_date = moment($('#use_finish_date').val()); //入力された終了日を取得
            var use_days = use_finish_date.diff(use_start_date, 'days') + 1; //消化する日数計算

            $('#sum').text(use_days); //消化日数をp要素に出力
            $('#use_days').attr('value', use_days); //hiddenで値を渡す
        });
    });
</script>
@endsection