<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Affichage d'un bouton submit sous la forme d'un lien</title>
        <style>
            form.link {
                display: inline;
            }

            form.link input[type="submit"] {
                background:none!important;
                border:none; 
                padding:0!important;
                font: inherit;
                color: blue;
                text-decoration: underline;
                cursor: pointer;
            }
        </style>
    </head>
    <body>
        <h1>Affichage d'un bouton submit sous la forme d'un lien</h1>
        Ceci est un vrai lien HTML qui produit un GET Http :
        <a href="submit_as_link.php?param=123">Lien</a>
        <br><br>
        <form action="submit_as_link.php" method="post">
            Ceci est un bouton submit qui produit un POST Http :
            <input type="submit" value="Bouton">
        </form>
        <br>
        Ceci est un bouton submit transformé en lien et qui produit un POST Http :
        <form class='link' action="submit_as_link.php" method='post'>
            <input type='text' name='param' value='123' hidden>
            <input type="submit" value="Bouton/Lien">
        </form>
        <br><br>
        <h3>
            <?php
            $method = $_SERVER['REQUEST_METHOD'];
            echo "Cette page a été demandée au moyen de la méthode <u>$method</u> ";
            if ($method === "GET" && isset($_GET['param'])) {
                echo "avec le paramètre <u>param=" . $_GET['param'] . "</u> (voir URL).";
            } elseif ($method === "POST" && isset($_POST['param'])) {
                echo "avec le paramètre <u>param=" . $_POST['param'] . "</u> (voir dans les données http).";
            } else {
                echo "sans paramètres.";
            }
            ?>
        </h3>
    </body>
</html>