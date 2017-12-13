@extends('layouts/master')
@section('title', 'ユーザプロフィール')

@section('content')
<section class="content-header">
	<h1>ユーザ管理</h1>
</section>

<section class="content" id="profile">
	<div class="row">

		<div class="col-lg-4">
			<div class="box box-primary">
				<div class="box-body box-profile">
					<div class="icon text-center">
						<i class="ion ion-person"></i>
					</div>

					<h3 class="profile-username text-center">{{ $user->last_name }} {{ $user->first_name }}</h3>

					<p class="text-muted text-center">
						{{($user->status)? App\User::$typeOfEmployments[$user->type_of_employment]: '未設定' }} @ {{ App\User::$departments[$user->department] }}
					</p>

					<ul class="list-group list-group-unbordered">
						<li class="list-group-item">
							<b>{{ $user->email }}</b> <a class="pull-right">Email</a>
						</li>
						<li class="list-group-item">
							<b>{{ App\User::$roleLabels[$user->role] }}</b> <a class="pull-right">サイト内権限</a>
						</li>
						<li class="list-group-item">
							<b>{{ App\User::getJaDate($user->date_of_entering) }}</b> <a class="pull-right">入社日</a>
						</li>
						<li class="list-group-item">
							<b>{{ App\User::getJaDate($user->base_date) }}</b> <a class="pull-right">起算日</a>
						</li>
                        @if($user->status === App\User::RETIRED)
                        <li class="list-group-item">
                            <b class="text-danger">退職済</b> <a class="pull-right">ステータス</a>
                        </li>
                        @endif
						<li class="list-group-item">
							<a>備考</a>
							<p>{!! nl2br($user->memo) !!}</p>
						</li>
					</ul>

					<div class="row">
						<div class="col-lg-6">
							<a href="{{ action('UserController@edit', $user->id) }}" class="btn btn-primary btn-block"><b>プロフィール変更</b></a>	
						</div>
						<div class="col-lg-6">
							<a href="{{ action('UserController@passwordEdit', $user->id) }}" class="btn btn-primary btn-block"><b>パスワード変更</b></a>	
						</div>
					</div><!-- /.row -->

					@if(Auth::user()->role === 1)
					<div class="row space-top10">
						<div class="col-lg-6 col-lg-offset-3">
							<a href="{{ action('UserController@dateEdit', $user->id) }}" class="btn btn-warning btn-block"><b>入社日変更</b></a>
						</div>
					</div>
					@endif

				</div><!-- /.box-body -->
			</div><!-- /.box -->
		</div><!-- /col-lg-4 -->

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
									{{ $validPaidVacations->first()->remaining_days }}
									@else
									0
									@endif
									日
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
									{{ $validPaidVacations->last()->remaining_days }}
									@else
									0
									@endif
									日
								</td>
							</tr>

							@if(Auth::user()->getUsedAdvancedDays())
							<tr>
								<td>前借り日数</td>
								<td class="text-center text-red font18">
									{{ Auth::user()->getUsedAdvancedDays() }} 日
								</td>
							</tr>
							@endif

							<tr class="sum">
								<td>
									<span class="pull-right text-bold">合計有給残日数</span>
								</td>
								<?php $sum = $user->getSumRemainingDays() - Auth::user()->getUsedAdvancedDays() ?>
								<td class="text-center font18 text-bold{{ ($sum >= 0) ? ' text-blue': ' text-red' }}">
									{{ $sum }} 日
								</td>
							</tr>

						</tbody>
					</table>
				</div>
			</div><!-- /.box -->


			<div class="box box-primary">
				<div class="box-header">
					<div class="box-title"><i class="fa fa-calendar-check-o"></i> 【登録済】消化予定or消化済 の有給</div>
				</div>
				<div class="box-body">
					@if($usedDays->count())
					<table class="table table-bordered">
						<thead class="well">
						<th width="5%">№</th><th>期間</th><th>日数</th>{!! (Auth::user()->role === 1)? '<th>操作</th>': '' !!}
						</thead>
						<tbody>

							<?php $i = ($usedDays->currentPage() - 1) * App\UsedDays::PAGE_NUM + 1 ?>
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

								@if(Auth::user()->role === 1)
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
								@endif
							</tr>

							<?php $i += 1 ?>
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