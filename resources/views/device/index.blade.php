@extends('layouts/master')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>
		デバイス管理
	</h1>
</section>

<!-- Main content -->
<section class="content">

	<div class="box box-primary">
		<div class="box-header with-border">
			<h3 class="box-title">検索</h3>
		</div>

		{!! Form::open(['url' => '/device', 'method' => 'GET', 'class' => 'form-horizontal']) !!}
		{{ csrf_field() }}
		<div class="box-body">

			<div class="form-group">
				<label for="category" class="col-md-2 control-label">分類</label>
				<div class="col-md-3">
					{!! Form::select('category', App\Device::$deviceCategories, $category,['class' => 'form-control', 'placeholder' => '']) !!}
				</div>

				<label for="os" class="col-md-2 control-label">OS</label>
				<div class="col-md-3">
					{!! Form::select('os', App\Device::$osLabels, $os,['class' => 'form-control', 'placeholder' => '']) !!}
				</div>
			</div>

			<div class="form-group">
				<label for="name" class="col-md-2 control-label">機器名</label>
				<div class="col-md-10">
					{!! Form::text('name', $name, ['class' => 'form-control']) !!}
				</div>
			</div>

			<div class="form-group">
				<label for="bought_at" class="col-md-2 control-label">購入日</label>
				<div class="col-md-5 form-inline">
					{!! Form::text('after', ($after)? App\User::getJaDate($after): '', ['class' => 'form-control use_datepicker']) !!}
					&ensp;〜&ensp;
					{!! Form::text('before', ($before)? App\User::getJaDate($before): '', ['class' => 'form-control use_datepicker']) !!}
				</div>
			</div>

			<div class="form-group">
				<label for="user_id" class="col-md-2 control-label">使用者</label>
				<div class="col-md-4">
					{!! Form::select('user_id', $users, '', ['class' => 'form-control', 'placeholder' => '']) !!}
				</div>

				<label for="disposal" class="col-md-2 control-label">廃棄</label>
				<div class="col-md-4">
					<div class="checkbox">
						<label>
							{!! Form::checkbox('disposal', 1, ($disposal == 1)? true: false) !!} 廃棄済み
						</label>
					</div>
				</div>
			</div>
		</div><!-- /.box-body -->

		<div class="box-footer">
			<button class="btn btn-warning" type="submit" name="reset">リセット</button>
			<button class="btn btn-primary pull-right" type="submit" name="search"> 検&emsp;索 </button>
		</div>
		{!! Form::close() !!}
	</div>



	<div class="box box-primary">
		<div class="box-header with-border">
			<h3 class="box-title">デバイス一覧</h3>
		</div>
		<!-- /.box-header -->
		<div class="box-body">
			@if($devices->count() > 0)
			<table id="users" class="table table-bordered table-striped">
				<thead>
					<tr class="bg-blue">
						<th>分類</th>
						<th>OS</th>
						<th>機器名</th>
						<th>購入日</th>
						<th>使用者</th>
						<th>メモリ</th>
						<th>サイズ</th>
						<th>廃棄済</th>
						<th>操作</th>
					</tr>
				</thead>
				<tbody>

					@foreach($devices as $device)
					<tr>
						<td>{{ App\Device::$deviceCategories[$device->category] }}</td>
						<td>{{ App\Device::$osLabels[$device->os] }}</td>
						<td>{{ $device->name }}</td>
						<td>{{ App\User::getJaDate($device->bought_at) }}</td>
						<td>{{ ($device->user_id) ? $users[$device->user_id] :'なし' }}</td>
						<td>{{ $device->memory }} GB</td>
						<td>{{ $device->size }} インチ</td>
						<td class="text-red">{{ ($device->status == 99) ? '廃棄済' : '' }}</td>
						<td>
							<a href="{{ url('device/edit', $device->id) }}" class="btn btn-primary">編集</a>
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
			@else
			該当するデバイスはありません
			@endif
		</div><!-- /.box-body -->

		<div class="box-footer text-center">
			{!! $devices->render() !!}
		</div>
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