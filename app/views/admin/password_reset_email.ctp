<p>Poštovani,</p>
<p>Primili smo zahtev za promenu lozinke za nalog koji je registrovan na Vašoj e-mail adresi.</p>
<p>Ukoliko Vi niste inicirali ovaj zahtev, slobodno ignorišite ovu poruku,<br>
u suprotnom klikom na sledeći link možete započeti proces postavljanja nove lozinke:</p>
<p><?= $this->link("http://".$_SERVER['SERVER_NAME']."/admin/password_reset/{$key}") ?></p>
<p>Ovaj zahtev ističe <?= $expiration ?>.</p>
<p>Hvala,<br>
<?= APP::settings('general.name') ?><br>
<?= $this->link("http://" . $_SERVER['SERVER_NAME']) ?></p>
