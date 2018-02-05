@extends('layouts/master')
@section('title', 'お知らせ一覧')

@section('content')

<section class="content-header">
	<h1>お知らせ管理</h1>
</section>

<section class="content">

	<div class="box box-primary">
		<div class="box-header with-border">
			<h3 class="box-title">検索</h3>
		</div>

		{!! Form::open(['url' => '/info', 'method' => 'GET', 'class' => 'form-horizontal']) !!}
		<div class="box-body">
			<div class="form-group">
				<label for="name" class="col-md-2 control-label">名前</label>
				<div class="col-md-3">
					{!! Form::text('name', Request::input('name'), ['class' => 'form-control', 'placeholder' => '氏名の like 検索']) !!}
				</div>
			</div>

			<div class="form-group">
				<label for="disposal" class="col-md-2 control-label">退職済</label>
				<div class="col-md-3">
					<div class="checkbox">
						<label>
							{!! Form::checkbox('searchInactive', 1, Request::input('searchInactive') == 1? true: false) !!} 退職済みも含める
						</label>
					</div>
				</div>
			</div>
		</div><!-- /.box-body -->

		<div class="box-footer">
			<a href="{{ url('/user') }}" class="btn btn-warning">リセット</a>
			<button class="btn btn-primary pull-right" type="submit" name="search"> 検&emsp;索 </button>
		</div>
		{!! Form::close() !!}
	</div>

	<div class="box box-primary">
		<div class="box-header with-border">
			<h3 class="box-title">お知らせ一覧</h3>
			<span class="pull-right"><span class="font18 text-blue">{{ ($informations->count() > 0)? $informations->total() : '0' }}</span> 件</span>
		</div>

		@if($informations->count() > 0)

		<div class="box-body">
			<table id="users" class="table table-bordered table-striped">
				<thead class="bg-primary">
					<tr>
						<th>タイトル</th>
						<th width="10%">ステータス</th>
						<th width="20%">作成日</th>
						<th width="15%">操作</th>
					</tr>
				</thead>
				<tbody>
					@foreach($informations as $info)
					<tr>
						<td>{{ $info->title }}</td>
						<td class="{{ ($info->status == App\Information::STATUS_CLOSE)? 'text-red': '' }}">{{ App\Information::$status[$info->status] }}</td>
						<td>{{ \App\User::getJaDateTime($info->created_at) }}</td>
						<td>
							<a href="{{ action('InformationController@edit', $info->id) }}" class="btn btn-primary">編集</a>&emsp;
							<a href="{{ action('InformationController@destroy', $info->id) }}" class="btn btn-danger">削除</a>
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div><!-- /.box-body -->
		<div class="box-footer text-center">
			{!! $informations->appends(Request::all())->render() !!}
		</div>

		@else
		<div class="box-body">
			<p>お知らせが存在しません。</p>
		</div>
		@endif
	</div><!-- /.box -->

</section>
@endsection