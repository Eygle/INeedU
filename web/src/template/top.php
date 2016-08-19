<?php
/** @var User $user */
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title><?php echo $pageTitle;?></title>

    <!-- Bootstrap -->
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <link rel="stylesheet" href="../css/common.css" />
    <link rel="stylesheet" href="../css/login.css" />

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>

<!-- Fixed navbar -->
<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#"><?php echo I18n::get("title");?></a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li <?php if ($page == "home") echo "class=\"active\"";?>><a href="/"><?php echo I18n::get("home");?></a></li>
                <li <?php if ($page == "demands") echo "class=\"active\"";?>><a href="demandes"><?php echo I18n::get("demands");?></a></li>
                <li <?php if ($page == "offers") echo "class=\"active\"";?>><a href="offres"><?php echo I18n::get("offers");?></a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <?php if ($user->isLogged()) { ?>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo I18n::get("my_account");?> <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="#"><?php echo I18n::get("my_profil");?></a></li>
                        <li><a href="#"><?php echo I18n::get("parameters");?></a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="#"><?php echo I18n::get("deconnect");?></a></li>
                    </ul>
                </li>
                <?php } else { ?>
                <li><a href="login"><button class="btn btn-primary"><?php echo I18n::get("connexion");?></button></a></li>
                <?php } ?>
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</nav>