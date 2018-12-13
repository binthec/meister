@extends('layouts/master')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>
		タイムカード
	</h1>
</section>
<!-- Main content -->
<section class="content">
	<div class="box box-primary">
		<div class="box-header with-border">
			<h3 class="box-title">検索</h3>
		</div>
		{!! Form::open(['url' => '/attendance', 'method' => 'GET', 'class' => 'form-horizontal', 'name' => 'search_form']) !!}
			<div class="box-body">
				<div class="form-group">
					{!! Form::hidden('focus_tab', Request::has('focus_tab')? Request::input('focus_tab'): 'tab_list') !!}
					@if( Auth::user()->role === 1 )
					<label for="user_name" class="col-md-2 control-label">名前</label>
					<div class="col-md-3">
						{!! Form::text('user_name', Request::input('user_name'), ['class' => 'form-control', 'placeholder' => '氏名の like 検索']) !!}
					</div>
					@endif
					<label for="bought_at" class="col-md-2 control-label">日付</label>
					<div class="col-md-3 form-inline">
						{!! Form::text('after', Request::has('after')? Request::input('after'): '', ['class' => 'form-control use_datepicker date']) !!}
						&ensp;〜&ensp;
						{!! Form::text('before', Request::has('before')? Request::input('before'): '', ['class' => 'form-control use_datepicker date']) !!}
					</div>
				</div>

				<div class="form-group">
					<label for="disposal" class="col-md-2 control-label">出勤</label>
					<div class="col-md-3">
						<div class="checkbox">
							<label>
								{!! Form::checkbox('status[start]', App\Attendance::STATUS_STRAT_WORKING, Request::has('status.start') == App\Attendance::STATUS_STRAT_WORKING? true: false) !!}
							</label>
						</div>
					</div>
					<label for="disposal" class="col-md-2 control-label">退勤</label>
					<div class="col-md-3">
						<div class="checkbox">
							<label>
								{!! Form::checkbox('status[end]', App\Attendance::STATUS_END_WORKING, Request::has('status.end') == App\Attendance::STATUS_END_WORKING? true: false) !!}
							</label>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label for="disposal" class="col-md-2 control-label">有給</label>
					<div class="col-md-3">
						<div class="checkbox">
							<label>
								{!! Form::checkbox('status[vaction]', false) !!}
							</label>
						</div>
					</div>
					<label for="disposal" class="col-md-2 control-label">欠勤</label>
					<div class="col-md-3">
						<div class="checkbox">
							<label>
								{!! Form::checkbox('status[absent]', false) !!}
							</label>
						</div>
					</div>
				</div>
			</div><!-- /.box-body -->

			<div class="box-footer">
				<a class="btn btn-warning reset_form">リセット</a>
				<button class="btn btn-primary pull-right" type="submit" name="search"> 検&emsp;索 </button>
			</div>
		{!! Form::close() !!}
	</div>


</section>

@endsection

@section('css')
	<link rel="stylesheet" href="/assets/plugins/fullcalendar/fullcalendar.css" >
	<link rel="stylesheet" href="/assets/plugins/datepicker/datepicker3.css" >
	<link rel="stylesheet" href="/assets/plugins/timepicker/bootstrap-timepicker.css" >
	<style>
		/* カラムの幅調整と超過分省略（…） */
		.text-omit {
			white-space: nowrap;
			text-overflow: ellipsis;
			overflow: hidden;
		}
		.tap_editor{
			border: none;
			background-color: transparent;
		}

	</style>
@endsection

@section('js')

	<script src="/assets/plugins/fullcalendar/fullcalendar.js"></script>
	<script src="/assets/plugins/chartJS/Chart.min.js"></script>
	<script src="/assets/plugins/timepicker/bootstrap-timepicker.js"></script>
	<script src="/plugins/datepicker/bootstrap-datepicker.js"></script>
	<script src="/plugins/datepicker/locales/bootstrap-datepicker.ja.js"></script>
	<script src="/js/timecard.index.blade.js"></script>

@endsection