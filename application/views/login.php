<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Bioleafy :: Inicar Sesión</title>

    <!-- Bootstrap Core CSS -->
    <link href="<?php assets_url() ?>/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="<?php assets_url() ?>/dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <p class="text-center">
                            <img src="<?php assets_url() ?>img/logobioleafy_small.png" style="max-width: 150px; margin-bottom: 20px" alt="Bioleafy">
                        </p>
                        <h3 class="panel-title text-center">Ingresar al Panel de Control</h3>
                    </div>
                    <div class="panel-body">
                        <form action="<?php echo site_url('login/auth') ?>" method="post" role="form">
                            <fieldset>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Correo Electrónico" name="username" type="email" autofocus>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Contraseña" name="password" type="password" value="">
                                </div>
<!--
                                <div class="checkbox">
                                    <label>
                                        <input name="remember" type="checkbox" value="Remember Me">Remember Me
                                    </label>
                                </div>
-->
                                <input type="hidden" name="url" value="<?php echo $url ?>">
                                <!-- Change this to a button or input when using this as a form -->
                                <button type="submit" class="btn btn-lg btn-primary btn-block">
                                    Iniciar Sesión
                                </button>
                            </fieldset>
                        </form>
                    </div>
                </div>
                <?php if($error) : ?>
                <div class="alert alert-danger">
                    Usuario/Contraseña incorrectos, intente de nuevo.
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>

</html>
