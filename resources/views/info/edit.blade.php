@extends('layouts/master')
@section('title', 'Meister | お知らせ管理')

@section('content')
    <section class="content-header">
        <h1>お知らせ管理</h1>
    </section>

    <section class="content">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">お知らせ{{ $info->id === null ? '新規登録': '編集' }}</h3>
            </div>
            <div class="box-body">

                @if($info->id === null)
                    {!! Form::open(['url' => ['/info', $info->id], 'method' => 'POST', 'class' => 'form-horizontal']) !!}
                @else
                    {!! Form::open(['url' => ['/info', $info->id], 'method' => 'PUT', 'class' => 'form-horizontal']) !!}
                @endif
                {{ csrf_field() }}

                <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                    <label for="title" class="col-md-2 control-label">タイトル <span class="text-danger">*</span></label>
                    <div class="col-md-8">
                        {!! Form::text('title', $info->title, ['class' => 'form-control', 'placeholder' => 'タイトル。これがdashboardに表示されます。']) !!}
                        @if($errors->has('title'))
                            <span class="help-block">
						<strong class="text-danger">{{ $errors->first('title') }}</strong>
					</span>
                        @endif
                    </div>
                </div>

                <div class="form-group{{ $errors->has('body') ? ' has-error' : '' }}">
                    <label for="body" class="col-md-2 control-label">内容</label>
                    <div class="col-md-8">
                        {!! Form::textarea('body', $info->body, ['class' => 'form-control use_datepicker']) !!}
                        @if($errors->has('body'))
                            <span class="help-block">
						        <strong class="text-danger">{{ $errors->first('body') }}</strong>
					        </span>
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <label for="disposal" class="col-md-2 control-label">ステータス <span class="text-danger">*</span></label>
                    <div class="col-md-8">
                        <label class="radio-inline">
                            {!! Form::radio('status', 1, ($info->status === \App\Information::STATUS_OPEN)? true : false) !!} 公開
                        </label>
                        <label class="radio-inline">
                            {!! Form::radio('status', 0, ($info->status === \App\Information::STATUS_CLOSE || $info->id === null )? true : false) !!} 非公開
                        </label>
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