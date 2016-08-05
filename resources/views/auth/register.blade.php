@extends('layouts/master')

@section('content')
<div class="col-md-10">

	<div class="row">
		<div class="col-md-12">
			<div class="content-box-large">
				<div class="panel-heading">
					<legend>新規ユーザ登録</legend>
				</div>
				<div class="panel-body">

					@if (count($errors) > 0)
					<div class="alert alert-danger">
						<strong>！入力項目に不備があります！</strong> <br>
						<ul>
							@foreach ($errors->all() as $error)
							<li>{{ $error }}</li>
							@endforeach
						</ul>
					</div>
					@endif

					<form class="form-horizontal" role="form" method="POST" action="/auth/register">
						{{-- CSRF対策--}}
						<input type="hidden" name="_token" value="{{ csrf_token() }}">

						<div class="form-group">
							<label for="ID" class="col-md-2 control-label">名前</label>
							<div class="col-md-2">
								<input type="text" class="form-control" placeholder="姓" name="family_name" id="family_name" value="">
							</div>
							<div class="col-md-2">
								<input type="text" class="form-control" placeholder="名" name="first_name" id="first_name" value=""></input>
							</div>
							<div class="col-md-2">
								<span class="label label-danger">必須</span>
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-2 control-label">メールアドレス</label>
							<div class="col-md-6">
								<input type="email" class="form-control" name="email" value="{{ old('email') }}">
							</div>
							<span class="label label-danger">必須</span>
						</div>

						<div class="form-group">
							<label for="ID" class="col-md-2 control-label">権限</label>
							<div class="col-md-2">
								<select class="form-control" name="role">
									@foreach($roleLabel as $key => $role)
									<option value="{{ $key }}">{{ $role }}</option>
									@endforeach
								</select>
							</div>
							<span class="label label-danger">必須</span>
						</div>

						<div class="form-group">
							<label for="ID" class="col-md-2 control-label">パスワード</label>
							<div class="col-md-4">
								<input type="password" class="form-control" name="password" id="password"></input>
							</div>
							<span class="label label-danger">必須</span>
						</div>

						<div class="form-group">
							<label class="col-md-2 control-label">パスワード再入力</label>
							<div class="col-md-4">
								<input type="password" class="form-control" name="password_confirmation">
							</div>
							<span class="label label-danger">必須</span>
						</div>

						<hr>

						<div class="form-group">
							<label for="ID" class="col-md-2 control-label">入社日</label>
							<div class="col-md-4">
								<input type="date" class="form-control" name="date_of_entering" id="date_of_entering" value=""></input>
							</div>
							<span class="label label-danger">必須</span>
						</div>
						<div class="form-group">
							<label for="ID" class="col-md-2 control-label">起算日</label>
							<div class="col-md-6">
								<p class="form_txt" id="base_date_text">
									入社日を入力すると自動で算出されます。
								</p>
							</div>
						</div>
						<div class="form-group">
							<label for="ID" class="col-md-2 control-label">備考</label>
							<div class="col-md-8">
								<textarea class="form-control" name="memo" id="memo" value=""></textarea>
							</div>
						</div>

						<input type="hidden" name="base_date" id="base_date" value=""></input>

						<div class="form-group">
							<div class="col-md-4 col-md-offset-2">
								<button type="submit" class="btn btn-primary">　登録　</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection<!-- /content -->

@section('js')
{{-- DatePickerと起算日計算のJS--}}
<script src="/js/calc_base_date.js"></script>
@endsection