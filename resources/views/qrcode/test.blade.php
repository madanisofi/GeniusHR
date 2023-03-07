<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('assets/libs/select2/dist/css/select2.min.css') }}">
    <title>Document</title>
</head>
<body>

    <select name="pilih" id="pilih" class="select2" style="width: 200px">
        <option value="">Ana</option>
        <option value="">Andra</option>
        <option value="">Dini</option>
        <option value="">Shinta</option>
        <option value="">Rio</option>
        <option value="">Johan</option>
    </select>
    
</body>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="{{ asset('assets/libs/select2/dist/js/select2.min.js') }}"></script>
    
    <script>
        $(document).ready(function () {
            $('.select2').select2();
    });
    </script>
</html>