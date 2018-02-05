@extends('layouts/master')
@section('title', 'お知らせ詳細')

@section('content')
    <section class="content-header">
        <h1>お知らせ詳細</h1>
    </section>

    <section class="content">
        <div class="row">

            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h4><i class="fa fa-info-circle"></i> {{ $info->title }}</h4>
                    </div>
                    <div class="box-body">
                        {!! nl2br(htmlspecialchars($info->body)) !!}
                    </div>
                    <div class="box-footer">
                        <a href="{{ url('/dashboard') }}">← Dashboardへ戻る</a>
                    </div>
                </div><!-- /.box -->
            </div>
        </div>
    </section>

@endsection