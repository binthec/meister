@extends('layouts/master')
@section('title', 'dashboard')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>Dashboard</h1>
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
				<a href="{{ action('UserController@show', Auth::user()->id) }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
			</div>
		</div><!-- ./col -->

		<div class="col-md-2 col-md-offset-4 text-center">
			<a href="/user/reset/{{ Auth::user()->id }}" class="btn btn-danger col-md-12"type="button">リセット</a>
			<span class="text-sm">※本番リリース時にこのボタンは削除します</span>
		</div>
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
			<p class="font18 calc-symbol">＋</p>
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
			<p class="font18 calc-symbol">＝</p>
		</div>

		<div class="col-lg-4">
			<div class="box box-success">
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

	@if(Auth::user()->getUsedAdvancedDays())
	<div class="row">
		<div class="col-lg-4 col-lg-offset-8">
			<div class="box box-danger">
				<div class="box-header with-border text-center">
					<i class="fa fa-comments-o"></i>
					<h3 class="box-title">前借り日数</h3>
				</div>
				<div class="box-body text-center font18 text-danger">
					{{ Auth::user()->getUsedAdvancedDays() }} 日
				</div>
			</div>
		</div>
	</div><!-- /.row -->
	@endif

</section><!-- /.content -->


<section class="content">
	<div class="box box-primary">
		<div class="box-header with-border">
			<h3 class="box-title">登録済有給一覧</h3>
			<a href="{{ url('vacation/create') }}" class="btn btn-primary pull-right">有給消化新規登録</a>
		</div>
		<div class="box-body">

			@if($usedDays->count() > 0)
			<table class="table table-bordered">
				<thead class="bg-primary">
				<th width="5%">№</th><th>期間</th><th>日数</th><th width="15%">操作</th>
				</thead>
				<tbody>

					<?php $i = ($usedDays->currentPage() - 1) * App\UsedDays::PAGE_NUM + 1 ?>
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
						<td>
							<a type="button" class="btn btn-primary btn-sm" name="edit" href="{{ action('VacationController@edit', $usedDay->id) }}">編集</a>
							&ensp;
							<button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal{{ $usedDay->id }}">削除</button>

							<!-- deleteModalWindow -->
							<div class="modal fade" id="deleteModal{{ $usedDay->id }}" tabindex="-1" role="dialog" aria-labelledby="ModalLabel">
								<div class="modal-dialog" role="document">
									<div class="modal-content">
										<div class="modal-header bg-red">
											<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
											<h4 class="modal-title" id="ModalLabel">登録済有給の削除</h4>
										</div>
										<div class="modal-body">
											<p>選択した登録済有給を削除します。よろしいですか？</p>
											<p>
												▼削除する有給<br>
												<span class="font18">{{ App\User::getJaDate($usedDay->from) }} 〜 {{ App\User::getJaDate($usedDay->until) }}</span> 
											</p>
											<p>
												▼有給日数<br>
												<span class="font18">{{ $usedDay->used_days }} 日分</span>
											</p>
											<p class="text-red pull-right">&ensp;<i class="fa fa-warning"></i> この処理は取り消せません</p><br>
										</div>

										<div class="modal-footer">
											{!! Form::open(['method' => 'delete', 'action' => ['VacationController@destroy', $usedDay->id]]) !!}
											<button type="button" class="btn btn-default" data-dismiss="modal">キャンセル</button>
											{!! Form::submit('削除実行', ['class' => 'btn btn-danger btn-sm']) !!}
											{!! Form::close() !!}
										</div>
									</div>
								</div>
							</div><!-- /deleteModalWindow -->
						</td>
						<?php $i += 1 ?>
					</tr>
					@endforeach

				</tbody>
			</table>

			@else
			<p>登録済みの有給はありません。</p>
			@endif
		</div>

		@if($usedDays->count() > 0)
		<div class="box-footer text-center">
			{!! $usedDays->render() !!}
		</div>
		@endif

	</div>
</section>
@endsection
