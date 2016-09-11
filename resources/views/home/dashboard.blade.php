@extends('layouts/master')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>
		Dashboard
		<small>Control panel</small>
	</h1>
</section>

<!-- Main content -->
<section class="content">
	<!-- Small boxes (Stat box) -->
	<div class="row">
		<div class="col-lg-6">
			<!-- small box -->
			<div class="small-box bg-blue">
				<div class="inner">
					<h3>{{ Auth::user()->last_name }} {{ Auth::user()->first_name }}</h3>

					<p>入社日：{{ App\User::getJaDate(Auth::user()->date_of_entering) }} &nbsp; / &nbsp; 起算日：{{ App\User::getJaDate(Auth::user()->base_date) }}</p>
				</div>
				<div class="icon">
					<i class="ion ion-person"></i>
				</div>
			</div>
		</div><!-- ./col -->

	</div><!-- /.row -->


	<!-- Main row -->
	<div class="row">

		<legend class="content-header">有給残日数</legend>

		<div class="col-lg-3">
			<!-- Left col -->
			<div class="box box-primary">
				<div class="box-header with-border text-center">
					@if ($validPaidVacations->count() > 0)
					<i class="fa fa-comments-o"></i>
					<h3 class="box-title">{{ App\User::getJaDate($validPaidVacations->first()->limit_date) }}期限</h3>
					@else
					未確定
					@endif
				</div>
				<div class="box-body text-center font18">
					@if ($validPaidVacations->count() > 0)
					{{ $validPaidVacations->first()->remaining_days }} 日
					@else
					0
					@endif
				</div>
			</div><!-- /.box -->
		</div>

		<div class="col-lg-1 text-center">
			<p class="font18">＋</p>
		</div>

		<div class="col-lg-3">
			<!-- Left col -->
			<div class="box box-primary">
				<div class="box-header with-border text-center">
					@if ($validPaidVacations->count() == 2)
					<i class="fa fa-comments-o"></i>
					<h3 class="box-title">{{ App\User::getJaDate($validPaidVacations->last()->limit_date) }}期限</h3>
					@else
					未確定
					@endif
				</div>
				<div class="box-body text-center font18">
					@if ($validPaidVacations->count() == 2)
					{{ $validPaidVacations->last()->remaining_days }} 日
					@else
					0
					@endif
				</div>
			</div><!-- /.box -->
		</div>

		<div class="col-lg-1 text-center">
			<p class="font18">＝</p>
		</div>

		<div class="col-lg-4">
			<div class="box box-primary">
				<div class="box-header with-border text-center">
					<i class="fa fa-comments-o"></i>
					<h3 class="box-title">合計有給残日数</h3>
				</div>
				<div class="box-body text-center font18">
					{{ Auth::user()->getSumRemainingDays() }} 日
				</div>
			</div>
		</div>

	</div><!-- /.row -->
</section>
<!-- /.content -->

<div class="row">
	<div class="col-md-12">
		<div class="content-box-large">

			<div class="panel-body">
				<div class="col-md-4 col-md-offset-4">
					<a href="/user/reset/{{ Auth::user()->id }}" class="btn btn-danger col-md-12"type="button">リセット</a><br><br>
				</div>
			</div>

		</div>
	</div>
</div>
@endsection
