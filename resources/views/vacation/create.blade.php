@extends('layouts/master')
@section('title', '有給消化登録')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>有給消化登録</h1>
</section>

<!-- Main content -->
<section class="content">
	<div class="box box-primary">
		<div class="box-header with-border">
			<h3 class="box-title">新規登録</h3>
		</div>
		<div class="box-body">

			{!! Form::open(['url' => '/vacation', 'method' => 'POST', 'class' => 'form-horizontal']) !!}
			{{ csrf_field() }}

			<div class="form-group">
				<label for="name" class="col-md-2 control-label">名前</label>
				<div class="col-md-8 form-control-static">
					<p>{{ Auth::user()->last_name }} {{ Auth::user()->first_name }} さん</p>
				</div>
			</div>

			<div class="form-group request_remaining_day">
				<label for="validPaidVacation" class="col-md-2 control-label">有給残日数</label>
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

			<div class="form-group{{ $errors->has('daterange') ? ' has-error' : '' }}">
				<label for="daterange" class="col-md-2 control-label">有給消化登録期間</label>
				<div class="col-md-6">
					{!! Form::date('daterange', '', ['class' => 'form-control use_daterange font18', 'placeholder' => '日付を選択してください']) !!}
					@if($errors->has('daterange'))
					<span class="help-block">
						<strong class="text-danger">{{ $errors->first('daterange') }}</strong>
					</span>
					@endif
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

			<div class="form-group{{ $errors->has('memo') ? ' has-error' : '' }}">
				<label for="memo" class="col-md-2 control-label">連絡事項</label>
				<div class="col-md-8">
					{!! Form::textarea('memo', '', ['class' => 'form-control', 'rows' => 5]) !!}
					@if($errors->has('memo'))
					<span class="help-block">
						<strong class="text-danger">{{ $errors->first('memo') }}</strong>
					</span>
					@endif
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

	<div class="box box-primary">
		<div class="box-header with-border">
			<h3 class="box-title">登録済有給の一覧</h3>
		</div>
		<div class="box-body">

			@if($usedDays->count() > 0)
			<table class="table table-bordered">
				<thead class="bg-primary">
				<th width="5%">№</th><th>期間</th><th>日数</th>
				</thead>
				<tbody>

					{{-- 通し番号を付ける --}}
					<?php $i = ($usedDays->currentPage() - 1) * App\User::PAGINATION + 1 ?>
					{{-- dd(Auth::user()->usedDays) --}}
					@foreach ($usedDays as $usedDay)
					<tr>
						<td class="middle">{{ $i }}</td>
						<td>
							@if($usedDay->used_days <= 1)
							{{ App\User::getJaDate($usedDay->from) }}
							&ensp;
							{{ ($usedDay->from_am == 1)? '午前半休': '' }}
							{{ ($usedDay->from_pm == 1)? '午後半休': '' }}
							@else
							{{ App\User::getJaDate($usedDay->from) }} 
							{{ ($usedDay->from_pm == 1)? '午後': '' }}
							&ensp;〜&ensp;
							{{ App\User::getJaDate($usedDay->until) }}
							{{ ($usedDay->until_am == 1)? '午前': '' }}
							@endif
						</td>
						<td>{{ $usedDay->used_days }} 日間</td>
						<?php $i += 1 ?>
					</tr>
					@endforeach
				</tbody>
			</table>
			@else
			<p>登録済みの有給はありません。</p>
			@endif
			{!! $usedDays->render() !!}
		</div><!-- /.box-body -->
	</div><!-- /,box -->

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