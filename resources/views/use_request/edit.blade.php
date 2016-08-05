@extends('layouts/master')

@section('content')
<div class="col-md-10">

	<div class="row">
		<div class="col-md-12">
			<div class="content-box-large">
				<div class="panel-heading">
					<legend>申請内容変更</legend>
				</div>	
				<div class="panel-body">
					<form method="post" action="/user/request_edit" class="form-horizontal">
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

						<hr>

						<div class="form-group">
							<label for="ID" class="col-md-2 control-label">有給申請期間</label>
							<div class="col-md-3">
								<input type="date" class="form-control" placeholder="開始日" name="from" id="from" value="{{ $requested->from }}"></input>
								<div class="bfh-datepicker" data-format="y-m-d" data-date="today"></div>
							</div>
							<div class="col-md-1 form-control-static text-center">
								〜
							</div>
							<div class="col-md-3">
								<input type="date" class="form-control" placeholder="終了日" name="until" id="until" value="{{ $requested->until }}"></input>
							</div>
							<div class="col-md-3 form-control-static">
								<span id="sum_box">合計： <span id="sum"></span> 日間</span>
							</div>


						</div>
						<div class="form-group">
							<label for="ID" class="col-md-2 control-label">連絡事項</label>
							<div class="col-md-8">
								<textarea class="form-control" name="memo" id="memo" value="">{{ $requested->memo }}</textarea>
							</div>
						</div>


						<input type="hidden" name="used_days" id="used_days" value=""></input>
						<input type="hidden" name="user_id" id="user_id" value="{{ Auth::user()->id }}"></input>
						<input type="hidden" name="id" id="used_days" value="{{ $requested->id }}"></input>

						<button type="submit" class="btn btn-success col-md-offset-2">決定</button>
					</form>
				</div>
			</div>
		</div>
	</div>


	<div class="row">
		<div class="col-md-12">
			<div class="content-box-large">
				<div class="panel-heading">
					<legend>登録済み有給一覧</legend>
				</div>	
				<div class="panel-body">
					<table class="table table-bordered">
						<thead class="well">
						<th width="5%">№</th><th>開始日</th><th>終了日</th><th>日数</th>
						</thead>
						<tbody>

							{{-- 通し番号を付ける --}}
							<?php $i = ($used_days->currentPage() - 1) * 5 + 1 ?>
							{{-- dd(Auth::user()->usedDays) --}}
							@foreach ($used_days as $used_day)
							<tr>
								<td class="middle">{{ $i }}</td>
								<td>{{ $used_day->from }}</td>
								<td>{{ $used_day->until }}</td>
								<td>{{ $used_day->used_days }}</td>
								<?php $i += 1 ?>
							</tr>
							@endforeach
						</tbody>
					</table>
					{!! $used_days->render() !!}
				</div>
			</div>
		</div>
	</div>
</div>



@endsection




@section('js')
{{-- フォームに必要なCSSとJSを読み込み --}}
@include('elements.forms')

{{-- DatePickerのJS--}}
<script>
    $(function () {
        //datepicker
        $("#from, #until").datepicker({
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
        $('#from').change(function () {
            $('#sum').text(1); //消化日数をp要素に出力
            var used_days = 1;
            $('#used_days').attr('value', used_days); //hiddenで値を渡す
        });
        //申請日数の計算
        $('#until').change(function () {
            //$('#use_finish_date').text(''); //入社日が変更されたらデフォルト文言は空にする
            var from = moment($('#from').val()); //入力された開始日を取得
            var until = moment($('#until').val()); //入力された終了日を取得
            var used_days = until.diff(from, 'days') + 1; //消化する日数計算

            $('#sum').text(used_days); //消化日数をp要素に出力
            $('#used_days').attr('value', used_days); //hiddenで値を渡す
        });
    });
</script>
@endsection