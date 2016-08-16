
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Navbar Template for Bootstrap</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    
</head>

<body>

<div class="container">
    <nav class="navbar navbar-light bg-faded">
        <button class="navbar-toggler hidden-sm-up" type="button" data-toggle="collapse" data-target="#navbar-header" aria-controls="navbar-header">
            &#9776;
        </button>
        <div class="collapse navbar-toggleable-xs" id="navbar-header">
            <a class="navbar-brand" href="index.php">Pruebas</a>
            <ul class="nav navbar-nav">
                <li class="nav-item active">
                    <a class="nav-link" href="diagrama.php">Diagrama</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="https://github.com/zurcacielos/php_orm" target="_blank">GitHub</a>
                </li>
            </ul>
            <form class="form-inline pull-xs-right">
                <input class="form-control" type="text" placeholder="Buscar">
                <button class="btn btn-outline-success" type="submit">Buscar</button>
            </form>
        </div>
    </nav> <!-- /navbar -->

    <!-- Main component for a primary marketing message or call to action -->
    <div class="jumbotron">
        <?php echo $content; ?>
    </div>

</div> <!-- /container -->


<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.0.0/jquery.min.js" integrity="sha384-THPy051/pYDQGanwU6poAc/hOdQxjnOEXzbT+OuUAFqNqFjL+4IGLBgCJC3ZOShY" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.2.0/js/tether.min.js" integrity="sha384-Plbmg8JY28KFelvJVai01l8WyZzrYWG825m+cZ0eDDS1f7d/js6ikvy1+X+guPIB" crossorigin="anonymous"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>

