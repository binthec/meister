@extends('layouts/master')

@section('content')
<div class="col-md-10">

	<div class="row">
		<div class="col-md-12">
			<div class="content-box-large">
				<div class="panel-heading">
					<legend>有給消化登録</legend>
				</div>	
				<div class="panel-body">
					{!! Form::open(['url' => '/use_request', 'method' => 'POST', 'class' => 'form-horizontal']) !!}
					{!! csrf_field() !!}

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
							<input type="date" class="form-control" placeholder="開始日" name="from" id="from" value=""></input>
							<div class="form-group">
								<div class="col-sm-offset-1 col-sm-10">
									<div class="checkbox">
										<label>
											<input type="checkbox" class="half" name="from_am" id="from_am" checked> 午前
										</label>
									</div>
									<div class="checkbox">
										<label>
											<input type="checkbox" class="half" name="from_pm" id="from_pm" checked> 午後
										</label>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-1 form-control-static text-center">
							<p>〜</p>
						</div>
						<div class="col-md-3">
							<input type="date" class="form-control" placeholder="終了日" name="until" id="until" value=""></input>
							<div class="form-group">
								<div class="col-sm-offset-1 col-sm-10">
									<div class="checkbox">
										<label>
											<input type="checkbox" class="half" name="until_am" id="until_pm" checked> 午前
										</label>
									</div>
									<div class="checkbox">
										<label>
											<input type="checkbox" class="half" name="until_pm" id="until_pm" checked> 午後
										</label>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-3 form-control-static">
							<span id="sum_box">合計： <span id="sum"></span> 日間</span>
						</div>


					</div>
					<div class="form-group">
						<label for="ID" class="col-md-2 control-label">連絡事項</label>
						<div class="col-md-8">
							<textarea class="form-control" name="memo" id="memo" value=""></textarea>
						</div>
					</div>

					<input type="hidden" name="used_days" id="used_days" value=""></input>

					<input type="hidden" name="user_id" id="user_id" value="{{ Auth::user()->id }}"></input>

					<button type="submit" class="btn btn-success col-md-offset-2">決定</button>
					{!! Form::close() !!}
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<div class="content-box-large">
				<div class="panel-heading">
					<legend>登録済み有給一覧</legend>
					<!--
					<div class="panel-options">
						<a href="#" data-rel="collapse"><i class="glyphicon glyphicon-refresh"></i></a>
						<a href="#" data-rel="reload"><i class="glyphicon glyphicon-cog"></i></a>
					</div>
					-->
				</div>
				<div class="panel-body">

					@if($used_days->count())
					<table class="table table-bordered">
						<thead class="well">
						<th width="5%">№</th><th>開始日</th><th>終了日</th><th>日数</th><th width="15%">操作</th>
						</thead>
						<tbody>

							<?php $i = ($used_days->currentPage() - 1) * 5 + 1 ?>
							{{-- dd(Auth::user()->usedDays) --}}
							@foreach ($used_days as $used_day)
							<tr>
								<td class="middle">{{ $i }}</td>
								<td>{{ $used_day->from }}</td>
								<td>{{ $used_day->until }}</td>
								<td>{{ $used_day->used_days }}</td>
								<td>
									<a type="button" class="btn btn-success btn-sm" name="edit" href="/use_request/edit/{{ $used_day->id }}'">編集</a>
									<button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModalWindow{{ $used_day->id }}">削除</button>

									<!-- deleteModalWindow -->
									<div class="modal fade" id="deleteModalWindow{{ $used_day->id }}" tabindex="-1" role="dialog" aria-labelledby="ModalLabel">
										<div class="modal-dialog" role="document">
											<div class="modal-content">
												<div class="modal-header">
													<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
													<h4 class="modal-title" id="ModalLabel">申請済有給の削除</h4>
												</div>
												<div class="modal-body">
													<p class="alert-danger align-center">この処理は取り消せません</p>
													<p>選択した申請済有給を削除します。よろしいですか？</p>
													<p>
														削除する有給：{{ $used_day->from }} 〜 {{ $used_day->until }}<br>
														有給日数：{{ $used_day->used_days }} 日分
													</p>
												</div>
												<div class="modal-footer">
													<button type="button" class="btn btn-default" data-dismiss="modal">キャンセル</button>
													<a type="button" class="btn btn-danger btn-sm" name="delete" href="/use_request/delete/{{ $used_day->id }}">削除実行</a>
												</div>
											</div>
										</div>
									</div>
									<!-- /deleteModalWindow -->
								</td>
								<?php $i += 1 ?>
							</tr>
							@endforeach

						</tbody>
					</table>

					@else
					<p>現在、登録済み有給はありません。</p>
					@endif

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
                        my: 'left bottom', // カレンダーの左下
                        at: 'left top', // 表示対象の左上
                        of: input, // 対象テキストボックス
                    });
                }, 1);
            }
        });

        //期間が空でない場合のみ、日数の差分計算をする
        getDiff();

        //申請日数の計算
        $('#from').change(function () {
            //期間が空でない場合のみ、日数の差分計算をする
            getDiff();
            //もしuntilが空の場合、fromと同じ値を入れる
            daysFormat('from', 'until');
            //untilがfromよりも前日付だとエラーを出す
            if (Number($('#sum').text()) <= 0) {
                console.log('errror!');
                if (!$('#from').parent().hasClass('has-error')) {
                    $('#from').parent().addClass('has-error');
                }
            } else {
                if ($('#from').parent().hasClass('has-error')) {
                    $('#from').parent().removeClass('has-error');
                }
            }
        });
        //申請日数の計算
        $('#until').change(function () {
            //期間が空でない場合のみ、日数の差分計算をする
            getDiff();
            //もしfromが空の場合、untilと同じ値を入れる
            daysFormat('until', 'from');
        });
        //午前・午後の計算
        $('.half').change(function () {
            if ($(this).is(':checked')) {
                var sum = Number($('#sum').text()) + Number(0.5);
                $('#sum').text(sum);
            } else {
                var sum = Number($('#sum').text()) - Number(0.5);
                $('#sum').text(sum);
            }
        });
    });

    function daysFormat(own, other) {
        //もしotherが空の場合、ownと同じ値を入れる
        if (!$('#' + other).val()) {
            $('#' + other).val($('#' + own).val());
            $('#sum').text(1); //消化日数をp要素に出力
            $('#used_days').attr('value', 1); //hiddenで値を渡す
        }
    }

    function getDiff() {
        if ($('#from').val() && $('#until').val()) {
            var from = moment($('#from').val()); //入力された開始日を取得
            var until = moment($('#until').val()); //入力された終了日を取得
            var used_days = until.diff(from, 'days') + 1; //消化する日数計算

            $('#sum').text(used_days); //消化日数をp要素に出力
            $('#used_days').attr('value', used_days); //hiddenで値を渡す
        }
    }
</script>
@endsection