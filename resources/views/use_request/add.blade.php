@extends('layouts/master')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>
		有給消化申請・一覧
	</h1>
</section>

<!-- Main content -->
<section class="content">
	<div class="box">
		<div class="box-header with-border">
			<h3 class="box-title">申請</h3>
		</div>
		<div class="box-body">

			{!! Form::open(['url' => '/use_request/add', 'method' => 'POST', 'class' => 'form-horizontal']) !!}
			{{ csrf_field() }}

			<div class="form-group">
				<label for="ID" class="col-md-2 control-label">ID</label>
				<div class="col-md-8 form-control-static">{{ Auth::user()->id }}</div>
			</div>

			<div class="form-group">
				<label for="ID" class="col-md-2 control-label">名前</label>
				<div class="col-md-8 form-control-static">
					<p>{{ Auth::user()->last_name }} {{ Auth::user()->first_name }} さん</p>
				</div>
			</div>

			<div class="form-group request_remaining_day">
				<label for="ID" class="col-md-2 control-label">有給残日数</label>
				<div class="col-md-2">
					<div class="panel panel-default align-center">
						<div class="panel-heading text-center">
							@if ($validPaidVacations->count() > 0)
							{{ App\User::getJaDate($validPaidVacations->first()->limit_date) }} 期限
							@else
							未確定
							@endif
						</div>
						<div class="panel-body font18 text-center">
							@if ($validPaidVacations->count() > 0)
							{{ $validPaidVacations->first()->remaining_days }} 日
							@else
							0
							@endif
						</div>
					</div>
				</div>

				<div class="col-md-1 text-center">
					<p class="font18">＋</p>
				</div>

				<div class="col-md-2">
					<div class="panel panel-default align-center">
						<div class="panel-heading text-center">
							@if ($validPaidVacations->count() == 2)
							{{ App\User::getJaDate($validPaidVacations->last()->limit_date) }} 期限
							@else
							未確定
							@endif
						</div>
						<div class="panel-body font18 text-center">
							@if ($validPaidVacations->count() == 2)
							{{ $validPaidVacations->last()->remaining_days }} 日
							@else
							0
							@endif
						</div>
					</div>
				</div>

				<div class="col-md-1 text-center">
					<p class="font18">＝</p>
				</div>

				<div class="col-md-2">
					<div class="panel panel-default align-center">
						<div class="panel-heading text-center">合計有給残日数</div>
						<div class="panel-body font18 text-center">
							{{ Auth::user()->getSumRemainingDays() }} 日
						</div>
					</div>
				</div>

			</div>

			<hr>

			<div class="form-group">
				<label for="ID" class="col-md-2 control-label">有給申請期間</label>
				<div class="col-md-6">
					{!! Form::date('daterange', '', ['class' => 'form-control use_daterange font18', 'placeholder' => '日付を選択してください']) !!}
				</div>
				<div class="col-md-4 form-control-static">
					<span class="font18" id="sum_box">合計： <span id="sum"></span> 日間</span>
				</div>
			</div>

			<div class="form-group">
				<div class="col-md-10 col-md-offset-2">

					<!-- １日以下しか休まない場合 -->
					<div id="single" class="cant-use">
						<div class="col-md-3 well">
							<label>半休の選択</label>
							<div class="checkbox">
								<label>
									{!! Form::checkbox('from_am', '', false, ['class' => 'half', 'id' => 'from_am']) !!} 午前半休
								</label>
							</div>
							<div class="checkbox">
								<label>
									{!! Form::checkbox('from_pm', '', false, ['class' => 'half', 'id' => 'from_pm']) !!} 午後半休
								</label>
							</div>
						</div>
					</div>

					<!-- 複数日休む場合 -->
					<div id="plural" class="cant-use">
						<div class="col-md-3 well">
							<label>開始日の半休選択</label>
							<div class="checkbox">
								<label>
									{!! Form::checkbox('from_pm', '', false, ['class' => 'half', 'id' => 'from_pm']) !!} 午後半休
								</label>
							</div>
						</div>

						<div class="col-md-3 well" style="margin-left: 20px;">
							<label>終了日の半休選択</label>
							<div class="checkbox">
								<label>
									{!! Form::checkbox('until_am', '', false, ['class' => 'half', 'id' => 'until_am']) !!} 午前半休
								</label>
							</div>
						</div>
					</div>

				</div>
			</div>

			<div class="form-group">
				<label for="ID" class="col-md-2 control-label">連絡事項</label>
				<div class="col-md-8">
					{!! Form::textarea('memo', '', ['class' => 'form-control', 'rows' => 5]) !!}
				</div>
			</div>

			{!! Form::hidden('from', '',['id' => 'from']) !!}
			{!! Form::hidden('until', '',['id' => 'until']) !!}
			{!! Form::hidden('used_days', '',['id' => 'used_days']) !!}
			{!! Form::hidden('user_id', Auth::user()->id) !!}

			<div class="box-footer">
				<button type="submit" class="btn btn-primary col-md-offset-2">決定</button>
			</div>
			{!! Form::close() !!}

		</div>
	</div>
</section>

@endsection

@section('js')
@include('elements.for_form')

<script>
//Date range picker	
    $(function () {
        $(".use_daterange").daterangepicker({
            locale: {
                format: "YYYY年MM月DD日",
                separator: " 〜 ",
                applyLabel: "反映",
                cancelLabel: "取消",
            },
            drops: "up",
            applyClass: "btn-primary",
        },
                function (start, end) {
                    calcAndSetVal(start, end);
                });
    });
</script>
@endsection