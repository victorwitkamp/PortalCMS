<?php

use PortalCMS\Core\View\Alert;

if (!isset($_SESSION)) {
    session_start();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Error</title>
<link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.1/css/all.css" integrity="sha384-O8whS3fhG2OnA5Kas0Y9l3cfpmYjapjI0E4theH4iuMD+pLhbf6JI0jIMfYcK3yZ" crossorigin="anonymous">
<link rel="stylesheet" type="text/css" href="https://bootswatch.com/4/superhero/bootstrap.min.css">

<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="/includes/css/style.css">



</head>
<body class="bg">
    <header>
        <div class="navbar navbar-dark bg-dark">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="#"><span class="fa fa-info-circle"></span> Help</a>
                </li>
            </ul>
        </div>
    </header>
    <main>
        <div class="container col-md-6 offset-md-3 mt-5">

                <div class="card">
                    <div class="card-header text-center">
                        <h1 class="h3 mb-3 font-weight-normal">Database error</h1>

                    </div>
                    <div class="card-body">

                        <h2 class="h3 mb-3 font-weight-normal">Error</h2><hr>
                        <?php Alert::renderFeedbackMessages(); ?><hr>
                        <a class="btn btn-primary" href="/login/login.php"><span class="fas fa-sync"></span> Opnieuw proberen</a>
                    </div>
                </div>

        </div>
    </main>

</body>
</html>
