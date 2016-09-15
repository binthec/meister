@extends('layouts/master')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>
		ユーザ管理
	</h1>
</section>

<!-- Main content -->
<section class="content">

	<div class="box box-primary">
		<div class="box-header with-border">
			<h3 class="box-title">ユーザ一覧</h3>
			<a href="{{ url('user/update') }}" class="btn btn-danger pull-right">全ユーザDB更新</a>
		</div>
		<!-- /.box-header -->
		<div class="box-body">
			<table id="users" class="table table-bordered table-striped">
                <thead>
					<tr>
						<th>名前</th>
						<th>メールアドレス</th>
						<th>入社日</th>
						<th>有給残日数</th>
						<th>操作</th>
					</tr>
                </thead>
                <tbody>
					@foreach($users as $user)
					<tr>
						<td>{{ $user->last_name }} {{ $user->first_name }}</td>
						<td>{{ $user->email }}</td>
						<td>{{ App\User::getJaDate($user->date_of_entering) }}</td>
						<td class="font18">{{ $user->getSumRemainingDays() }}</td>
						<td><a href="{{ url('user/profile', $user->id) }}" class="btn btn-primary">詳細表示</a></td>
					</tr>
					@endforeach
                </tbody>
                <tfoot>
					<tr>
						<th>名前</th>
						<th>メールアドレス</th>
						<th>入社日</th>
						<th>有給残日数</th>
						<th>操作</th>
					</tr>
                </tfoot>
			</table>
		</div><!-- /.box-body -->
	</div><!-- /.box -->

</section>
@endsection

@section('js')
@include('elements.for_datatables')
<script>
    $(function () {
        $("#users").DataTable({
            "stateSave": true, // 状態を保存する機能をつける
            "order": [[2, 'asc']], //初期表示の際はメアドの昇順
            "columnDefs": [{
                    "orderable": false,
                    "targets": [4] //操作カラムのソートを不可に設定
                }],
        });
    });
</script>
@endsection