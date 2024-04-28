<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>TerPazMaps</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>

    <div class="container">
        <div class="row justify-content-center ">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header text-center">{{ __('TerPazMaps - Atualização de senha') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('resetPassword') }}">
                            @csrf

                            @if (Session()->has('danger'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{ Session()->get('danger') }}
                                </div>
                            @endif

                            @if (Session()->has('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ Session()->get('success') }}
                                </div>
                            @endif

                            <input type="hidden" name="password_reset" value="{{ $token }}">
                            <input type="hidden" name="email" value="{{ $email }}">

                            <div class="row mb-3">
                                <label for="password"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Senha') }}</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control"
                                        value="{{ old('password') }}" name="password" required
                                        autocomplete="new-password">
                                </div>
                            </div>

                            <div class="row mb-2">
                                <label for="password-confirm"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Confirme a senha') }}</label>

                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" value="{{ old('password_confirmation') }}"  class="form-control"
                                        name="password_confirmation" required autocomplete="new-password">
                                    @if ($errors->any())
                                        <span class="alert text-danger" role="alert">
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li> <strong>{{ $error }}</strong></li>
                                                @endforeach
                                            </ul>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('atualizar senha') }}
                                    </button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>

</html>
