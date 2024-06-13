<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Frankfurter allgemeine</title>
    <link rel="stylesheet" href="sportPolitika.css">
</head>
<?php
include 'connect.php';
define('UPLPATH', 'img/');
?>
<body>
    <div class="main">
        <header>
            <div class="links">
                <a href="index.php">HOME</a>
                <a href="politika.php">POLITIKA</a>
                <a href="#">SPORT</a>
                <a href="unos.php">UNOS</a>
                <a href="administracija.php">ADMINISTRACIJA</a>
                <a href="registracija.php">REGISTRACIJA</a>
            </div>
            <hr>
            <h1>Frankfurter Allgemeine</h1>
        </header>


        <section class="dis">
            <div class="category"><hr> Sport</div>
        <?php
            $query = "SELECT * FROM vijest WHERE arhiva=0 AND kategorija='sport'";
            $result = mysqli_query($dbc, $query);
            $i=0;
            while($row = mysqli_fetch_array($result)) {
            echo '<article>';
            echo'<div class="article">';
            echo '<div class="iimg">';
            echo '<img src="' . UPLPATH . $row['slika'] . '"';
            echo '</div>';
            echo '<div class="media_body">';
            echo '<h4 class="title">';
            echo '<a href="clanak.php?id='.$row['id'].'">';
            echo $row['naslov'];
            echo '</a></h4>';
            echo '<a href="clanak.php?id='.$row['id'].'">';
            echo $row['sazetak'];
            echo '</a>';
            echo '</div></div>';
            echo '</article>';
            }?>
            
        </section>
    </div>

    <footer>
    <h1>Frankfurter Allgemeine</h1>
    </footer>
</body>
</html>