@extends('layouts/master')
@section('title', 'デバイス一覧・検索')

@section('content')

<section class="content-header">
	<h1>ライセンス管理</h1>
</section>

<!-- Main content -->
<section class="content">

	<div class="box box-primary">
		<div class="box-header with-border">
			<h3 class="box-title">検索</h3>
		</div>

		{!! Form::open(['url' => '/license', 'method' => 'GET', 'class' => 'form-horizontal']) !!}
		<div class="box-body">

			<div class="form-group">
				<label for="category" class="col-md-2 control-label">デバイスの分類</label>
				<div class="col-md-3">
					{!! Form::select('category', App\Device::$deviceCategories, Request::input('category'),['class' => 'form-control', 'placeholder' => '---']) !!}
				</div>

				<label for="os" class="col-md-2 control-label">OS</label>
				<div class="col-md-3">
					{!! Form::select('os', App\Device::$osLabels, Request::input('os'),['class' => 'form-control', 'placeholder' => '---']) !!}
				</div>
			</div>

			<div class="form-group">
				<label for="name" class="col-md-2 control-label">機器名</label>
				<div class="col-md-3">
					{!! Form::text('name', Request::input('name'), ['class' => 'form-control', 'placeholder' => '機器名の like 検索']) !!}
				</div>

				<label for="bought_at" class="col-md-2 control-label">購入日</label>
				<div class="col-md-3 form-inline">
					{!! Form::text('after', Request::has('after')? Request::input('after'): '', ['class' => 'form-control use_datepicker']) !!}
					&ensp;〜&ensp;
					{!! Form::text('before', Request::has('before')? Request::input('before'): '', ['class' => 'form-control use_datepicker']) !!}
				</div>
			</div>

			<div class="form-group">
				<label for="user_id" class="col-md-2 control-label">使用者</label>
				<div class="col-md-3">
					{!! Form::text('user_name', Request::input('user_name'), ['class' => 'form-control', 'placeholder' => '使用者名の like 検索']) !!}
				</div>

				<label for="disposal" class="col-md-2 control-label">廃棄</label>
				<div class="col-md-3">
					<div class="checkbox">
						<label>
							{!! Form::checkbox('searchInactive', 1, Request::input('searchInactive') == 1? true: false) !!} 廃棄済みも含める
						</label>
					</div>
				</div>
			</div>

		</div><!-- /.box-body -->

		<div class="box-footer">
			<a href="{{ url('/license') }}" class="btn btn-warning">リセット</a>
			<button class="btn btn-primary pull-right" type="submit" name="search"> 検&emsp;索 </button>
		</div>
		{!! Form::close() !!}
	</div>



	<div class="box box-primary">
		<div class="box-header with-border">
			<h3 class="box-title">ライセンス一覧</h3>
			<span class="pull-right"><span class="font18 text-blue">{{ ($licenses->count() > 0)? $licenses->total() : '0' }}</span> 件</span>
		</div>
		<!-- /.box-header -->
		<div class="box-body">
			@if($licenses->count() > 0)
			<table id="users" class="table table-bordered table-striped">
				<thead>
					<tr class="bg-blue">
						<th>ライセンス名</th>
						<th>メーカー</th>
						<th>プロダクトキー</th>
						<th>ライセンス数</th>
						<th>有効期限</th>
						<th>操作</th>
					</tr>
				</thead>
				<tbody>

					@foreach($licenses as $license)
					<tr>
						<td>{{ $license->name }}</td>
						<td>{{ App\Maker::getNames()[$license->maker_id] }}</td>
						<td>{{ $license->product_key }}</td>
						<td>{{ $license->number }}</td>
						<td>{{ App\User::getJaDate($license->expired_on) }}</td>
						<td>
							<a href="{{ action('LicenseController@edit', $license->id) }}" class="btn btn-primary">編集</a>
							&emsp;
							<a href="#" class="btn btn-default bg-purple" data-toggle="modal" data-target="#detailModal{{ $license->id }}">詳細</a>


						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
			@else
			該当するラインセンスはありません
			@endif
		</div><!-- /.box-body -->

		@if($licenses->count() > 0)
		<div class="box-footer text-center">
			{!! $licenses->appends(Request::all())->render() !!}
		</div>
		@endif
	</div><!-- /.box -->

</section>
@endsection

@section('js')
@include('elements.for_form')
<script>
    $(function () {
        $(".use_datepicker").datepicker({
            language: "ja",
            format: "yyyy年m月d日",
            autoclose: true,
            orientation: "top left"
        });
    });
</script>
@endsection