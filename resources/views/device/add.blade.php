@extends('layouts/master')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>デバイス管理</h1>
</section>

<!-- Main content -->
<section class="content">
	<div class="box">
		<div class="box-header with-border">
			<h3 class="box-title">デバイス新規登録</h3>
		</div>
		<div class="box-body">

			{!! Form::open(['url' => '/device/add', 'method' => 'POST', 'class' => 'form-horizontal']) !!}
			{{ csrf_field() }}


			<div class="form-group{{ $errors->has('category') ? ' has-error' : '' }}">
				<label for="category" class="col-md-2 control-label">分類</label>
				<div class="col-md-3">
					{!! Form::select('category', App\Device::$deviceCategories, 1,['class' => 'form-control']) !!}
				</div>
				<span class="label label-danger">必須</span>
			</div>

			<div class="form-group">
				<label for="os" class="col-md-2 control-label">OS</label>
				<div class="col-md-3">
					{!! Form::select('os', App\Device::$osLabels, 1,['class' => 'form-control']) !!}
				</div>
				<span class="label label-danger">必須</span>
			</div>

			<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
				<label for="name" class="col-md-2 control-label">機器名</label>
				<div class="col-md-8">
					{!! Form::text('name', '', ['class' => 'form-control']) !!}
					@if($errors->has('name'))
					<span class="help-block">
						<strong class="text-danger">{{ $errors->first('name') }}</strong>
					</span>
					@endif
				</div>
			</div>

			<div class="form-group{{ $errors->has('serial_id') ? ' has-error' : '' }}">
				<label for="serial_id" class="col-md-2 control-label">シリアルID</label>
				<div class="col-md-8">
					{!! Form::text('serial_id', '', ['class' => 'form-control']) !!}
					@if($errors->has('serial_id'))
					<span class="help-block">
						<strong class="text-danger"">{{ $errors->first('serial_id') }}</strong>
					</span>
					@endif
				</div>
				<div class="label label-danger">必須</div>
			</div>

			<div class="form-group">
				<label for="bought_at" class="col-md-2 control-label">購入日</label>
				<div class="col-md-8">
					{!! Form::text('bought_at', '', ['class' => 'form-control use_datepicker']) !!}
				</div>
			</div>

			<div class="form-group">
				<label for="user_id" class="col-md-2 control-label">使用者</label>
				<div class="col-md-8">
					{!! Form::select('user_id', $users, '', ['class' => 'form-control', 'placeholder' => 'なし']) !!}
				</div>
			</div>

			<hr>
			<h4><i class="fa fa-wrench"></i> スペック</h4>

			<div class="form-group{{ $errors->has('core') ? ' has-error' : '' }}">
				<label for="core" class="col-md-2 control-label">コア数</label>
				<div class="col-md-2">
					{!! Form::text('core', '', ['class' => 'form-control']) !!}
					@if($errors->has('core'))
					<span class="help-block">
						<strong class="text-danger">{{ $errors->first('core') }}</strong>
					</span>
					@endif
				</div>
			</div>

			<div class="form-group{{ $errors->has('memory') ? ' has-error' : '' }}">
				<label for="memory" class="col-md-2 control-label">メモリ</label>
				<div class="col-md-8 form-inline">
					{!! Form::text('memory', '', ['class' => 'form-control']) !!}
					<span class="form-control-static"> GB</span>
					@if($errors->has('memory'))
					<span class="help-block">
						<strong class="text-danger">{{ $errors->first('memory') }}</strong>
					</span>
					@endif
				</div>
			</div>

			<div class="form-group{{ $errors->has('size') ? ' has-error' : '' }}">
				<label for="size" class="col-md-2 control-label">サイズ</label>
				<div class="col-md-8  form-inline">
					{!! Form::text('size', '', ['class' => 'form-control']) !!}
					<span class="form-control-static"> インチ</span>
					@if($errors->has('size'))
					<span class="help-block">
						<strong class="text-danger">{{ $errors->first('size') }}</strong>
					</span>
					@endif
				</div>
			</div>


			<div class="box-footer">
				<button type="submit" class="btn btn-primary col-md-offset-2">決定</button>
			</div>

			{!! Form::close() !!}

		</div>
	</div>
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