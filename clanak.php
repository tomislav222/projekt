<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Frankfurter allgemeine</title>
    <link rel="stylesheet" href="clanak.css">
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
                <a href="sport.php">SPORT</a>
                <a href="unos.php">UNOS</a>
                <a href="administracija.php">ADMINISTRACIJA</a>
                <a href="registracija.php">REGISTRACIJA</a>
            </div>
            <hr>
            <h1>Frankfurter Allgemeine</h1>
        </header>

        <section class="dis">
            
        <?php
            $query = "SELECT * FROM vijest WHERE arhiva=0 AND id='" . $_GET['id'] . "'";
            $result = mysqli_query($dbc, $query);
            $i=0;
            while($row = mysqli_fetch_array($result)) {
            echo '<article>';
            
            echo '<h2>';
            echo $row['naslov'];
            echo '</h2>';
            echo '<h5>';
            echo 'Postavljeno: ';
            echo $row['datum'];
            echo '</h5>';
            echo '<div class="iimg">';
            echo '<img src="' . UPLPATH . $row['slika'] . '" class="responsiveimg"';
            echo '</div>';
            echo '<div class="sazetak_body">';
            echo '<p class="sazetak">';
            echo $row['sazetak'];
            echo '</p>';
            echo '</div>';
            echo '<div class="tekst_body">';
            echo '<p class="tekst">';
            echo $row['tekst'];
            echo '</p>';
            echo '</div>';
            
            echo '</article>';
            }?>
            
        </section>

    
    </div>
    <footer>
    <h1>Frankfurter Allgemeine</h1>
    </footer>
</body>
</html>