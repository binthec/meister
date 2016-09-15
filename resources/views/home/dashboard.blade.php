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

		<div class="col-md-2 col-md-offset-4">
			<a href="/user/reset/{{ Auth::user()->id }}" class="btn btn-danger col-md-12"type="button">リセット</a>
			<br><br>
			<a href="{{ url('user/update', Auth::user()->id) }}" class="btn btn-danger col-md-12">DB更新</a>
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
</section><!-- /.content -->



<!-- Main content -->
<section class="content">
	<div class="box">
		<div class="box-header with-border">
			<h3 class="box-title">申請済有給一覧</h3>
			<a href="{{ url('use_request/add') }}" class="btn btn-primary pull-right bg-aqua">有給消化新規登録</a>
		</div>
		<div class="box-body">

			@if($usedDays->count())
			<table class="table table-bordered">
				<thead class="well">
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
							<a type="button" class="btn btn-primary btn-sm" name="edit" href="{{ url('use_request/edit', $usedDay->id) }}">編集</a>
							&ensp;
							<button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal{{ $usedDay->id }}">削除</button>

							<!-- deleteModalWindow -->
							<div class="modal fade" id="deleteModal{{ $usedDay->id }}" tabindex="-1" role="dialog" aria-labelledby="ModalLabel">
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
												削除する有給：{{ $usedDay->from }} 〜 {{ $usedDay->until }}<br>
												有給日数：{{ $usedDay->used_days }} 日分
											</p>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-default" data-dismiss="modal">キャンセル</button>
											<a type="button" class="btn btn-danger btn-sm" name="delete" href="/use_request/delete/{{ $usedDay->id }}">削除実行</a>
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
		</div>
		<div class="box-footer text-center">
			{!! $usedDays->render() !!}
		</div>
	</div>
</section>
@endsection
