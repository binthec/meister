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
	<!-- タブ表示 -->
	<div class="box nav-tabs-custom">
		<!-- 各タブの名前を表示 -->
		<ul class="nav nav-tabs">
			<li class="pull-left header"><i class="fa fa-th"></i> タイムカード </li>
			<li class="{{ !Request::has('focus_tab') ? 'active' :  Request::input('focus_tab') === 'tab_list' ? 'active' : ''}}"><a href="#tab_list" data-toggle="tab" aria-expanded="true">一覧</a></li>
			<li class="{{ !Request::has('focus_tab') ? '' :  Request::input('focus_tab') === 'tab_calendar' ? 'active' : ''  }}"><a href="#tab_calendar" data-toggle="tab" aria-expanded="true">カレンダー</a></li>
			<!-- 設定メニュー -->
			<li class="dropdown">
				<a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">　設定 <span class="caret"></span></a>
				<ul class="dropdown-menu">
					<li role="presentation"><a role="menuitem" tabindex="-1" href="{{ url('/attendance/csv/export')  }}">CSVダウンロード</a></li>
					<li role="presentation" class="divider"></li>
					<li role="presentation"><a role="menuitem" tabindex="-1" href="#">スプレッドシート</a></li>
				</ul>
			</li>
			<span class="pull-right" style="margin:9px;" ><span class="font18 text-blue">{{ ($attendances->count() > 0)? $attendances->total() : '0' }}</span> 件</span>
		</ul>
		<!-- 各タブの中身 -->
		<div class="tab-content">
			<!-- 一覧表示 -->
			<div id="tab_list" class="tab-pane {{ !Request::has('focus_tab') ? 'active' :  Request::input('focus_tab') === 'tab_list' ? 'active' : ''}}" >
				<div class="box-body">
					{!! Form::open(['url' => '/attendance/update', 'method' => 'GET', 'class' => 'form-horizontal']) !!}
						<table class="table table-bordered table-striped">
							<thead>
								<tr class="bg-blue">
									<th>#</th>
									<th>名前</th>
									<th>ステータス</th>
									<th>メッセージ</th>
									<th>日付</th>
									<th>時刻</th>
								</tr>
							</thead>
							<tbody>
							@foreach($attendances as $key => $attendance)
								<tr>
									<td class="text-omit">{{ $attendance->id }}</td>
									<td class="text-omit">{{ $attendance->user_id ? App\User::getUserName($attendance->user_id) :'なし' }}</td>
									<td class="text-omit">{{ $attendance->status ? ( $attendance->status ===  \App\Attendance::STATUS_STRAT_WORKING ? '出勤' : ( $attendance->status === \App\Attendance::STATUS_END_WORKING ? '退勤' : '不明' ) ) : '不明' }}</td>
									<td class="text-omit"><input type="text" class="tap_editor" value="{{ $attendance->raw_data }}"></td>
									<td class="">
										<div class="bootstrap-datepicker">
											<div class="input-group">
												<input type="text"  class="form-control use_datepicker" value="{{ $attendance->created_at->format('Y/m/d') }}">
												<div class="input-group-addon"><i class="fa fa-clock-o"></i></div>
											</div>
										</div>
									</td>
									<td class="">
										<div class="bootstrap-timepicker">
											<div class="input-group">
												<input type="text"  class="form-control use_timepicker" value="{{ $attendance->created_at->format('H:i') }}">
												<div class="input-group-addon"><i class="fa fa-clock-o"></i></div>
											</div>
										</div>
									</td>
								</tr>
							@endforeach
							</tbody>
						</table>
						<div class="box-footer">
							<button class="btn btn-primary pull-right" type="submit" name="update"> 更新 </button>
						</div>
					{!! Form::close() !!}
				</div>
				@if($attendances->count() > 0)
				<div class="box-footer text-center">
					{!! $attendances->appends(Request::all())->render() !!}
				</div>
				@endif
			</div>
			<!-- カレンダー表示 -->
			<div id="tab_calendar" class="tab-pane {{ !Request::has('focus_tab') ? '' :  Request::input('focus_tab') === 'tab_calendar' ? 'active' : ''}}">
				<div id="calendar"></div>
			</div>
		</div>

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