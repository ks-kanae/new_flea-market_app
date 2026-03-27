<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>coachtech flea market app</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    @yield('css')
</head>

<body>
    <header class="header">
        <div class="header-inner">
            <div class="header-utilities">
                <a href="{{ route('home') }}">
                    <img class="header-logo" src="{{ asset('img/COACHTECHヘッダーロゴ.png') }}">
                </a>

                @unless (request()->routeIs('login', 'register'))
                <form class="header-search" action="{{ route('home') }}" method="GET">
                    <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="なにをお探しですか？">
                </form>
                <ul class="header-nav">
                    @auth
                    <li class="header-nav-item">
                        <form class="form" action="/logout" method="post">
                        @csrf
                            <button class="header-nav-button">ログアウト</button>
                        </form>
                    </li>
                    @else
                    <li class="header-nav-item">
                        <a class="header-nav-link" href="{{ route('login') }}">ログイン</a>
                    </li>
                    @endauth
                    <li class="header-nav-item">
                        <a class="header-nav-link" href="/mypage">マイページ</a>
                    </li>
                    <li class="header-nav-item">
                        <a href="/sell" class="header-nav-button-sell">出品</a>
                    </li>
                </ul>
                @endunless
            </div>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <script src="https://yubinbango.github.io/yubinbango/yubinbango.js"></script>
</body>

</html>
