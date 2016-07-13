@extends('layouts/master')

@section('content')
<div class="row">
	<div class="col col-md-offset-2 col-md-8">
		<h2>ユーザ情報編集</h2>

		<form method="post" action="/user/edit/{{ $user->id }}" class="form-horizontal well">
			{{-- CSRF対策--}}
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
			<div class="form-group">
				<label for="ID" class="col-md-2 control-label">ID</label>
				<div class="col-md-8 form-control-static">{{ $user->id }}</div>
			</div>
			<div class="form-group">
				<label for="ID" class="col-md-2 control-label">名前</label>
				<div class="col-md-8 form-inline">
					<input type="text" class="form-control" placeholder="姓" name="family_name" id="family_name" value="{{ $user->family_name }}"></input>
					<input type="text" class="form-control" placeholder="名" name="first_name" id="first_name" value="{{ $user->first_name }}"></input>
				</div>
			</div>
			<div class="form-group">
				<label for="ID" class="col-md-2 control-label">E-Mailアドレス</label>
				<div class="col-md-6">
					<input type="text" class="form-control" placeholder="メールアドレス" name="email" id="email" value="{{ $user->email }}"></input>
				</div>
			</div>

			<div class="form-group">
				<label for="ID" class="col-md-2 control-label">権限</label>
				<div class="col-md-2">
					<select class="form-control">
						@foreach($roleLabel as $role)
						<option>{{ $role }}</option>
						@endforeach
					</select>
				</div>
				<span class="label label-danger">必須</span>
			</div>

			<div class="form-group">
				<label for="ID" class="col-md-2 control-label">パスワード</label>
				<div class="col-md-4">
					<input type="password" class="form-control" name="password" id="password"></input>
					※変更の場合のみ入力してください
				</div>
			</div>

			<hr>

			<div class="form-group">
				<label for="ID" class="col-md-2 control-label">入社日</label>
				<div class="col-md-4">
					<input type="date" class="form-control" name="date_of_entering" id="date_of_entering" value="{{ $user->date_of_entering }}"></input>
				</div>
			</div>
			<div class="form-group">
				<label for="ID" class="col-md-2 control-label">起算日</label>
				<div class="col-md-6">
					<p class="form_txt" id="base_date_text">
						@if(!empty($user->base_date))
						{{ $user->base_date }}
						@else
						入社日を入力すると自動で算出されます。
						@endif
					</p>
				<!--<input type="date" class="form-control" name="base_date" id="base_date" value="{{ $user->base_date }}"></input>-->
				</div>
			</div>
			<div class="form-group">
				<label for="ID" class="col-md-2 control-label">備考</label>
				<div class="col-md-8">
					<textarea class="form-control" name="memo" id="memo" value="{{ $user->memo }}">{{ $user->memo }}</textarea>
				</div>
			</div>

			<input type="hidden" name="base_date" id="base_date" value="{{ $user->base_date }}"></input>
			<input type="hidden" name="user_id" id="user_id" value="{{ $user->id }}"></input>

			<button type="submit" class="btn btn-success col-md-offset-2">決定</button>
		</form>
	</div>
</div>

{{-- DatePickerと起算日計算のJS--}}
<script src="/js/calc_base_date.js"></script>

@endsection