<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="<?php echo e(asset('storage/logo.png')); ?>" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous">
    </script>

    <link href="https://fonts.googleapis.com/css?family=Work+Sans:400,300,700" rel="stylesheet">

    <title> GERAI UMKM MART </title>

    <style>
        * {
            font-family: 'Work Sans', sans-serif;
        }

        body {
            background-color: #D3D3D3;
            min-height: 100vh;
        }

        .navbar {
            background: linear-gradient(135deg, #28a745, #20c997) !important;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-bottom: 3px solid rgba(255, 255, 255, 0.2);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
        }

        .nav-link {
            font-weight: 500;
            transition: all 0.3s ease;
            border-radius: 25px;
            margin: 0 5px;
            padding: 8px 15px !important;
        }

        .nav-link:hover,
        .nav-link.active {
            background-color: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }

        .dropdown-menu {
            border: none;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .dropdown-item {
            padding: 10px 20px;
            transition: all 0.3s ease;
            border-radius: 10px;
            margin: 2px 5px;
        }

        .dropdown-item:hover {
            background-color: #f8f9fa;
            transform: translateX(5px);
        }

        .btn-success {
            background: linear-gradient(135deg, #28a745, #20c997);
            border: none;
            border-radius: 25px;
            font-weight: 600;
            padding: 10px 20px;
            transition: all 0.3s ease;
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(40, 167, 69, 0.3);
        }

        .main-content {
            /* min-height: calc(100vh - 76px); */
            padding: 0;
        }

        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: #28a745;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #20c997;
        }

        .modal-backdrop {
            background-color: rgba(0, 0, 0, 0.3) !important;
            backdrop-filter: none !important;
        }

        body.modal-open {
            overflow: auto !important;
            padding-right: 0 !important;
        }
    </style>
</head>

<body>
    <?php date_default_timezone_set('Asia/jakarta'); ?>
    <?php echo $__env->make('dashboard.partials.navbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <div class="main-content">
        <?php echo $__env->yieldContent('container'); ?>
    </div>

    <script>
        setTimeout(function () {
            $('.alert').fadeOut('slow');
        }, 5000);

        $('form').on('submit', function () {
            $(this).find('button[type="submit"]').html('<i class="bi bi-hourglass-split"></i> Loading...');
        });
    </script>
</body>

</html>
<?php /**PATH C:\Users\LENOVO\Documents\SEMESTER 5\Magang\Gerai-umkm-mart-finalisasi-main\resources\views/dashboard/layouts/main.blade.php ENDPATH**/ ?>