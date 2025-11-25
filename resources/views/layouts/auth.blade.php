<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Login &mdash; {{ $pengaturan->name ?? config('app.name') }}</title>
    <style>
        body {
    
        }
    </style>
    @include('includes.style')
</head>

<body style="background-color: #e5e5f7;
background: radial-gradient(circle, transparent 20%, #e5e5f7 20%, #e5e5f7 80%, transparent 80%, transparent), radial-gradient(circle, transparent 20%, #e5e5f7 20%, #e5e5f7 80%, transparent 80%, transparent) 17.5px 17.5px, linear-gradient(#0008a7 1.4000000000000001px, transparent 1.4000000000000001px) 0 -0.7000000000000001px, linear-gradient(90deg, #0008a7 1.4000000000000001px, #e5e5f7 1.4000000000000001px) -0.7000000000000001px 0;
background-size: 35px 35px, 35px 35px, 17.5px 17.5px, 17.5px 17.5px;">
    <div id="app">
        <section class="section">
            <div class="container mt-5">
                <div class="row">
                    <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3">
                        <div class="login-brand">
                            <img src="{{ asset($pengaturan->logo) }}" alt="Logo Sekolah" width="100" class="shadow-lights">
                            <p class="mt-4">{{ $pengaturan->name ?? config('app.name') }}</p>
                        </div>
                        @if(session()->has('info'))
                        <div class="alert alert-primary">
                            {{ session()->get('info') }}
                        </div>
                        @endif
                        @if(session()->has('status'))
                        <div class="alert alert-info">
                            {{ session()->get('status') }}
                        </div>
                        @endif
                        @yield('content')
                        <div class="simple-footer">
                            Copyright &copy; {{ $pengaturan->name ?? config('app.name') }} {{ date('Y') }}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    @include('includes.style')
</body>
</html>
