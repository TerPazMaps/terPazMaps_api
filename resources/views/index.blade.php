<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TerPazMaps</title>
    <!-- Adicione o link para o arquivo Leaflet.js -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link href="{{ asset('storage/css/estilos.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="icon" href="storage/favicon/favicon.svg" type="image/svg+xml">

</head>

<body>

    @include('components.header') <!-- Inclui o componente de cabeçalho -->

    @include('components.regionContainer')

    <main style="padding-top: 45px;"> <!-- 45px é a altura do seu cabeçalho -->
        <div id="map"></div>
    </main>

    @include('components.lateral')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>

    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script> <!-- Carrega o arquivo Leaflet.js -->

    <script src="{{ asset('storage/js/scripts.js') }}"></script>

    <script src="https://kit.fontawesome.com/bf4bab225b.js" crossorigin="anonymous"></script>

</body>

</html>
