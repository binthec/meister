@extends('layouts/master')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>
		有給消化登録・一覧
	</h1>
</section>

<!-- Main content -->
<section class="content">
	<div class="box">
		<div class="box-header with-border">
			<h3 class="box-title">登録済有給一覧</h3>
		</div>
		<div class="box-body">

			@if($usedDays->count())
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
							<a type="button" class="btn btn-primary btn-sm" name="edit" href="{{ url('use_request/edit', $usedDay->id) }}">編集</a>
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
											<p class="alert-danger align-center">&ensp;<i class="fa fa-warning"></i> この処理は取り消せません</p>
											<p>選択した登録済有給を削除します。よろしいですか？</p>
											<p>
												削除する有給：{{ App\User::getJaDate($usedDay->from) }} 〜 {{ App\User::getJaDate($usedDay->until) }}<br>
												有給日数：{{ $usedDay->used_days }} 日分
											</p>
										</div>
										<div class="modal-footer">
											{!! Form::open(['method' => 'delete', 'url' => '/use_request']) !!}
											<button type="button" class="btn btn-default" data-dismiss="modal">キャンセル</button>
											{!! Form::submit('削除実行', ['class' => 'btn btn-danger btn-sm']) !!}
											{!! Form::close() !!}
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

			{!! $usedDays->render() !!}
		</div>
	</div>
</section>

@endsection
