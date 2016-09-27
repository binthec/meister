@extends('layouts/master')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>ユーザ管理</h1>
</section>

<!-- Main content -->
<section class="content" id="profile">
	<div class="row">

		<div class="col-lg-4">
			<!-- Profile Image -->
			<div class="box box-primary">
				<div class="box-body box-profile">
					<div class="icon text-center">
						<i class="ion ion-person"></i>
					</div>

					<h3 class="profile-username text-center">{{ $user->last_name }} {{ $user->first_name }}</h3>

					<p class="text-muted text-center">
						{{($user->status)? App\User::$memberStatus[$user->status]: '未設定' }} @ {{ App\User::$departments[$user->department] }}
					</p>

					<ul class="list-group list-group-unbordered">
						<li class="list-group-item">
							<b>{{ $user->email }}</b> <a class="pull-right">Email</a>
						</li>
						<li class="list-group-item">
							<b>{{ App\User::$roleLabels[$user->role] }}</b> <a class="pull-right">U9サイト内権限</a>
						</li>
						<li class="list-group-item">
							<b>{{ App\User::getJaDate($user->date_of_entering) }}</b> <a class="pull-right">入社日</a>
						</li>
						<li class="list-group-item">
							<b>{{ App\User::getJaDate($user->base_date) }}</b> <a class="pull-right">起算日</a>
						</li>
						<li class="list-group-item">
							<a>備考</a>
							<p>{!! nl2br($user->memo) !!}</p>
						</li>
					</ul>

					<div class="row">
						<div class="col-lg-6{{ (Auth::user()->role !== 1)? ' col-lg-offset-3': '' }}">
							<a href="{{ url('user/editProfile', $user->id) }}" class="btn btn-primary btn-block"><b>プロフィール変更</b></a>	
						</div>
						@if(Auth::user()->role === 1)
						<div class="col-lg-6">
							<a href="{{ url('user/editDate', $user->id) }}" class="btn btn-primary btn-block"><b>入社日変更</b></a>
						</div>
						@endif
					</div>
					<div class="row space-top20">
						<div class="col-lg-6 col-lg-offset-3">

						</div>
					</div>

				</div><!-- /.box-body -->
			</div><!-- /.box -->
		</div>

		<div class="col-lg-8">

			<!-- Left col -->
			<div class="box box-primary">
				<div class="box-header">
					<h3 class="box-title">
						<i class="fa fa-calendar"></i> 有給残日数
					</h3>
				</div>
				<div class="box-body">
					<table class="table table-bordered">
						<thead>
							<tr class="well">
								<th class="text-center">有給有効期限</th>
								<th class="text-center">残日数</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>
									@if ($validPaidVacations->count() > 0)
									{{ App\User::getJaDate($validPaidVacations->first()->limit_date) }}
									@else
									未確定
									@endif
								</td>
								<td class="text-center font18">
									@if ($validPaidVacations->count() > 0)
									{{ $validPaidVacations->first()->remaining_days }} 日
									@else
									0
									@endif
								</td>
							</tr>
							<tr>
								<td>
									@if ($validPaidVacations->count() == 2)
									{{ App\User::getJaDate($validPaidVacations->last()->limit_date) }}
									@else
									未確定
									@endif
								</td>
								<td class="text-center font18">
									@if ($validPaidVacations->count() == 2)
									{{ $validPaidVacations->last()->remaining_days }} 日
									@else
									0
									@endif
								</td>
							</tr>
							<tr class="sum">
								<td>
									<span class="pull-right text-bold">合計有給残日数</span>
								</td>
								<td class="text-center font18 text-blue text-bold">
									{{ $user->getSumRemainingDays() }} 日
								</td>
							</tr>

						</tbody>
					</table>
				</div>
			</div><!-- /.box -->


			<div class="box box-primary">
				<div class="box-header">
					<div class="box-title"><i class="fa fa-calendar-check-o"></i> 【申請済】消化予定or消化済 の有給</div>
				</div>
				<div class="box-body">
					@if($usedDays->count())
					<table class="table table-bordered">
						<thead class="well">
						<th width="5%">№</th><th>期間</th><th>日数</th><th>操作</th>
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
			</div><!-- /.box -->

		</div>


	</div>

</section>

@endsection


@section('js')
{{-- DatePickerと起算日計算のJS--}}
<script src="/js/calc_base_date.js"></script>
@endsection