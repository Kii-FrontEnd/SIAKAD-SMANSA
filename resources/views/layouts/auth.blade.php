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

<body style="background-color: #6e6e76;
background: radial-gradient(circle, transparent 20%, #6e6e76 20%, #6e6e76 80%, transparent 80%, transparent), radial-gradient(circle, transparent 20%, #6e6e76 20%, #6e6e76 80%, transparent 80%, transparent) 10px 10px, linear-gradient(#0008a4 0.8px, transparent 0.8px) 0 -0.4px, linear-gradient(90deg, #0008a4 0.8px, #6e6e76 0.8px) -0.4px 0;
background-size: 20px 20px, 20px 20px, 10px 10px, 10px 10px;">
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
