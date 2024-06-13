
<?php
include 'connect.php';
define('UPLPATH', 'img/');
?>

    <?php
session_start();
include 'connect.php';
$uspjesnaPrijava;
// Putanja do direktorija sa slikama
// Provjera da li je korisnik došao s login forme
if (isset($_POST['prijava'])) {
 // Provjera da li korisnik postoji u bazi uz zaštitu od SQL injectiona
 $prijavaImeKorisnika = $_POST['username'];
 $prijavaLozinkaKorisnika = $_POST['lozinka'];
 $sql = "SELECT korisnicko_ime, lozinka, razina FROM korisnik
 WHERE korisnicko_ime = ?";
 $stmt = mysqli_stmt_init($dbc);
 if (mysqli_stmt_prepare($stmt, $sql)) {
 mysqli_stmt_bind_param($stmt, 's', $prijavaImeKorisnika);
 mysqli_stmt_execute($stmt);
 mysqli_stmt_store_result($stmt);
 }
 mysqli_stmt_bind_result($stmt, $imeKorisnika, $lozinkaKorisnika,
$levelKorisnika);
 mysqli_stmt_fetch($stmt);
 //Provjera lozinke
 if (password_verify($_POST['lozinka'], $lozinkaKorisnika) &&
mysqli_stmt_num_rows($stmt) > 0) {
 $uspjesnaPrijava = true;
 // Provjera da li je admin
 if($levelKorisnika == 1) {
 $admin = true;
 }
 else {
 $admin = false;
 }
 //postavljanje session varijabli
 $_SESSION['$username'] = $imeKorisnika;
 $_SESSION['$level'] = $levelKorisnika;
 } else {
 $uspjesnaPrijava = false;
 }

}
// Brisanje i promijena arhiviranosti
?>

<div class="news">
<?php
 // Pokaži stranicu ukoliko je korisnik uspješno prijavljen i administrator je



 
 if (($uspjesnaPrijava == true && $admin == true) ||
(isset($_SESSION['$username'])) && $_SESSION['$level'] == 1) {
 
        $query = "SELECT * FROM vijest";
$result = mysqli_query($dbc, $query);
while($row = mysqli_fetch_array($result)) {
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

 // Pokaži poruku da je korisnik uspješno prijavljen, ali nije administrator
 } else if ($uspjesnaPrijava == true && $admin == false) {

 echo '<p>Bok ' . $imeKorisnika . '! Uspješno ste prijavljeni, ali
niste administrator.</p>';
 } else if (isset($_SESSION['$username']) && $_SESSION['$level'] == 0) {
 echo '<p>Bok ' . $_SESSION['$username'] . '! Uspješno ste
prijavljeni, ali niste administrator.</p>';
 } else if ($uspjesnaPrijava == false) {
 ?>
 <!-- Forma za prijavu -->
 <script type="text/javascript">

 //javascript validacija forme

 </script>

 <?php
 }
 ?>
 ?>




</div>
    </div>
    <footer>
    <h1>Frankfurter Allgemeine</h1>
    </footer>
</body>
</html>