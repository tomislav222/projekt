<?php
session_start();
include 'connect.php';
// Putanja do direktorija sa slikama
define('UPLPATH', 'img/');
$uspjesnaPrijava = false;
$admin = false;
$msg = '';

// Provjera da li je korisnik došao s login forme
if (isset($_POST['prijava'])) {
    // Provjera da li korisnik postoji u bazi uz zaštitu od SQL injectiona
    $prijavaImeKorisnika = $_POST['username'];
    $prijavaLozinkaKorisnika = $_POST['lozinka'];
    $sql = "SELECT korisnicko_ime, lozinka, razina FROM korisnik WHERE korisnicko_ime = ?";
    $stmt = mysqli_stmt_init($dbc);
    if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, 's', $prijavaImeKorisnika);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        mysqli_stmt_bind_result($stmt, $imeKorisnika, $lozinkaKorisnika, $levelKorisnika);
        mysqli_stmt_fetch($stmt);

        // Provjera lozinke
        if (mysqli_stmt_num_rows($stmt) > 0 && password_verify($prijavaLozinkaKorisnika, $lozinkaKorisnika)) {
            $uspjesnaPrijava = true;
            // Provjera da li je admin
            if ($levelKorisnika == 1) {
                $admin = true;
            }
            // Postavljanje session varijabli
            $_SESSION['username'] = $imeKorisnika;
            $_SESSION['level'] = $levelKorisnika;
        } else {
            $msg = 'Neispravno korisničko ime ili lozinka.';
        }
    } else {
        $msg = 'Greška u pripremi SQL izjave.';
    }
}

// Brisanje i promijena arhiviranosti
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Frankfurter allgemeine</title>
    <link rel="stylesheet" href="administracija.css">
</head>
<body>
<div class="main">
    <header>
        <div class="links">
            <a href="index.php">HOME</a>
            <a href="politika.php">POLITIKA</a>
            <a href="sport.php">SPORT</a>
            <a href="unos.php">UNOS</a>
            <a href="#">ADMINISTRACIJA</a>
            <a href="registracija.php">REGISTRACIJA</a>
        </div>
        <hr>
        <h1>Frankfurter Allgemeine</h1>
    </header>

    <?php if ($uspjesnaPrijava && $admin) : ?>
        <div class="news">
            <?php
            $query = "SELECT * FROM vijest";
            $result = mysqli_query($dbc, $query);
            while($row = mysqli_fetch_array($result)){
            echo '<section>';
             echo '<form enctype="multipart/form-data" action="admin.php" method="POST">
             <div class="form-item">
             <label for="title">Naslov vjesti:</label>
             <div class="form-field">
             <input type="text" name="title" class="form-field-textual"
            value="'.$row['naslov'].'">
             </div>
             </div>
             <div class="form-item">
             <label for="about">Kratki sadržaj vijesti (do 50
            znakova):</label>
             <div class="form-field">
             <textarea name="about" id="" cols="30" rows="10" class="formfield-textual">'.$row['sazetak'].'</textarea>
             </div>
             </div>
             <div class="form-item">
             <label for="content">Sadržaj vijesti:</label>
             <div class="form-field">
             <textarea name="content" id="" cols="30" rows="10" class="formfield-textual">'.$row['tekst'].'</textarea>
             </div>
             </div>
             <div class="form-item">
             <label for="pphoto">Slika:</label>
             <div class="form-field">
            
             <input type="file" class="input-text" id="pphoto"
            value="'.$row['slika'].'" name="pphoto"/> <br><img src="' . UPLPATH .
            $row['slika'] . '" width=100px>
            // pokraj gumba za odabir slike pojavljuje se umanjeni prikaz postojeće slike
             </div>
             </div>
             <div class="form-item">
             <label for="category">Kategorija vijesti:</label>
             <div class="form-field">
             <select name="category" id="" class="form-field-textual"
            value="'.$row['kategorija'].'">
             <option value="sport">Sport</option>
             <option value="politika">Politika</option>
             </select>
             </div>
             </div>
             <div class="form-item">
             <label>Spremiti u arhivu:
             <div class="form-field">';
             if($row['arhiva'] == 0) {
             echo '<input type="checkbox" name="archive" id="archive"/>
            Arhiviraj?';
             } else {
             echo '<input type="checkbox" name="archive" id="archive"
            checked/> Arhiviraj?';
             }
             echo '</div>
             </label>
             </div>
             
             <div class="form-item">
             <input type="hidden" name="id" class="form-field-textual"
            value="'.$row['id'].'">
             <button type="reset" value="Poništi">Poništi</button>
             <button type="submit" name="update" value="Prihvati">
            Izmjeni</button>
             <button type="submit" name="delete" value="Izbriši">
            Izbriši</button>
             
             </form>';
             echo '</section>';
            }
            ?>
        </div>
    <?php elseif ($uspjesnaPrijava && !$admin) : ?>
        <p>Bok <?php echo htmlspecialchars($imeKorisnika); ?>! Uspješno ste prijavljeni, ali niste administrator.</p>
    <?php elseif (isset($_SESSION['username']) && $_SESSION['level'] == 0) : ?>
        <p>Bok <?php echo htmlspecialchars($_SESSION['username']); ?>! Uspješno ste prijavljeni, ali niste administrator.</p>
    <?php else : ?>
        <p><?php echo $msg; ?></p>
        <form action="" method="POST">
            <div>
                <label for="username">Korisničko ime:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div>
                <label for="lozinka">Lozinka:</label>
                <input type="password" id="lozinka" name="lozinka" required>
            </div>
            <div>
                <button type="submit" name="prijava">Prijava</button>
            </div>
        </form>
    <?php endif; ?>
</div>

<footer>
    <h1>Frankfurter Allgemeine</h1>
</footer>
</body>
</html>
