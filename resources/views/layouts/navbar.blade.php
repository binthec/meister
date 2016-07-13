<nav class="navbar navbar-default">
    <div class="container">
        <div class="navbar-header">
            <!-- スマホやタブレットで表示した時のメニューボタン -->
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                ...
            </button>

            <!-- ブランド表示 -->
            <a class="navbar-brand" href="/dashboard">U9システム</a>
        </div>

        <!-- メニュー -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

			@if (!Auth::guest())

			<!-- adminユーザのみユーザ一覧を利用可 -->
			@if(Auth::user()->role == 'admin')
            <ul class="nav navbar-nav">
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
						ユーザ管理
						<span class="caret"></span>
					</a>
					<ul class="dropdown-menu" role="menu">
						<li><a href="/user">ユーザ一覧</a></li>
						<li><a href="/auth/register">ユーザ追加</a></li>
					</ul>
				</li>
                <!--<li><a href="/contact">menu</a></li>-->
            </ul>
			@endif

            <!-- 右寄せメニュー -->
            <ul class="nav navbar-nav navbar-right">
				<li><a href="/use_request">有給消化登録</a></li>

				<!-- ドロップダウンメニュー -->
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
						{{ Auth::user()->email }}
						<span class="caret"></span>
					</a>
					<ul class="dropdown-menu" role="menu">
						<li><a href="/user/{{ Auth::user()->id }}">ユーザ情報</a></li>
						<li role="separator" class="divider"></li>
						<li><a href="/logout">ログアウト</a></li>
					</ul>
				</li>
                @endif
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>