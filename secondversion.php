
<html>
<head>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
<title> BMG </title>
</head>
<body>
<div class="container">
<?php
echo "<pre>"; print_r($_REQUEST); echo "</pre>"; 
switch (@$_GET['is']){
	//orgenci
	case 'guncelleFormu':guncelleFormu();
	break;
	case 'guncelle': guncelle($_GET['ono'],$_GET['ad'],$_GET['soyad'],$_GET['bno']);
					 ogrenciListele(); 
	break;
	case 'ogrenciSil'	: ogrenciSil($_GET['ono']);
						  ogrenciListele(); 
	break;
	case 'ogrenciListele': ogrenciListele(); 
	break;
	case 'ogrenciEklemeFormu': ogrenciEklemeFormu();  
	break;
	case 'ogrenciEkle': ogrenciEkle($_GET['ono'],$_GET['ad'],$_GET['soyad'],$_GET['bno']); 
						ogrenciListele(); 
	break;
//bolum 
	case 'bolumListele': bolumListele(); 
	break;
	case 'guncelleFormub' : guncelleFormub(); 
	break;
	case 'boulumguncelle' : boulumguncelle($_GET['bno'], $_GET['bolumad']);
							bolumListele();
	break;
	case 'bolumEklemeFormu': bolumEklemeFormu();  
	break;
	case 'bolumEkle': bolumEkle($_GET['bno'], $_GET['bolumad']);  
					  bolumListele();
	break;
	case 'bolumdekiOgrenciler': bolumdekiOgrenciler($_GET['bno']); 
	break;
	case 'bolumSil'	: bolumSil($_GET['bno']);
					  bolumListele(); 
	break;
	default: anaSayfa();
}
exit;

function bolumEkle($bno,$bolumad){
	$bolno = $bno;
	$adi = $bolumad;
	$dosya = fopen("bolum.txt","a");
    fputs($dosya, "$bolno $adi\n");
    fclose($dosya);
}

function bolumEklemeFormu(){
	
	echo"
		<form action=''>
		<input name=is type=hidden value=bolumEkle>
		<h3>Yeni Bolum</h3>
		<table>
		<tr><td>Bolum No</td> <td><input name=bno type=text></td></tr>
		<tr><td>Bolum Adi</td> <td><input name=bolumad type=text></td></tr>
		<tr><td></td> <td><input name=tamam type=submit value=Olustur></td></tr>
		</table>
		</form>";
}

function bolumSil($bno)
{
    $no = $bno;
    $f1 = fopen('bolum.txt', 'r');
    $f2 = fopen('temp.txt', 'w');
    $f11 = fopen('ogrenci.txt', 'r');
    $f22 = fopen('temp1.txt', 'w');
    
    while (!feof($f1) && !feof($f11)) {
        $satir = fgets($f1);
        $satir1 = fgets($f11);
        $words = explode(" ", $satir);
        $words1 = explode(" ", $satir1);
        if ($words[0] != $no && $words1[3] != $no  ) { // aranan degilse
            fputs($f2, $satir);
            fputs($f22, $satir1);
        } 
    }
    fclose($f1);
    unlink("bolum.txt"); // dosya sil
    fclose($f11);
    unlink("ogrenci.txt"); // dosya sil
    fclose($f2);
    fclose($f22);
    rename("temp.txt", "bolum.txt"); // isim degistirme
    rename("temp1.txt", "ogrenci.txt");
}


function bolumListele(){
	
	echo "
	<h1>Bolum listesi</h1> 
	<a href='?is=bolumEklemeFormu'>Yeni</a>
	<a href='?is='>Ana sayfa</a>
		<table class='table'> 
			<thead>
				<tr> 
				<th>No</th> 
				<th>Adi</th> 
				<th>Ogr. Sayisi</th> 
				<th>Sil</th> 
				</tr>
				</thead>
				<tbody>";

	$dosya = fopen("ogrenci.txt", "r");    // dosyayı okumak için ac
	while (!feof($dosya)) {    // dosya'nın sonuna vardık mı?
		$satir = fgets($dosya);    // $dosya dosyasından 1 satır oku
		$veri = explode(" ", $satir); // array olarak dondurur
		$bolno = trim($veri[3]);	
		@ $ogrSayisi[$bolno]++;
	}
	fclose($dosya);
							
	$dosya = fopen("bolum.txt", "r");    // dosyayı okumak için ac
	while (!feof($dosya)) {    // dosya'nın sonuna vardık mı?
		$satir = fgets($dosya);    // $dosya dosyasından 1 satır oku
		$veri = explode(" ", $satir); // array olarak dondurur
		$bolno = $veri[0];
		$adi = $veri[1];	
		print "<tr> 
			<td>{$veri[0]}</td> 
			<td>{$veri[1]}</td> 
			<td>{$ogrSayisi[$veri[0]]}</td> 
			<td> <a href='?is=bolumSil&bno={$veri[0]}'>Sil</a>
			<td> <a href='?is=guncelleFormub&bno={$veri[0]}&ad={$veri[1]}'>Degistir</a>
			<td> <a href='?is=bolumdekiOgrenciler&bno={$veri[0]}'>Ogrenciler</a>
			</td></tr>";
	}
	fclose($dosya);
	print "</tbody></table>";
}

function boulumguncelle($bno,$bolumad){
    $no = $bno;
    $yenibolum = $bolumad; 
    $dosya = fopen("bolum.txt", "r");    // dosyayı okumak için ac
    $cikti = fopen("temp.txt", "w");    // dosyayı okumak için ac
    while (!feof($dosya)) {    // dosya'nın sonuna vardık mı?
        $satir = fgets($dosya);    // $dosya dosyasından 1 satır oku
        $veri = explode(' ', $satir); // array olarak dondurur
        $bolno = $veri[0];
        $adi = $veri[1];
        if ($bolno == $no) {
    fputs($cikti, "$bolno $yenibolum\n");
    break;
}
    else
        fputs($cikti, $satir);
        }
        fclose($dosya);
        unlink("bolum.txt"); // dosya sil
        fclose($cikti);
        rename("temp.txt", "bolum.txt");
} 

function guncelleFormub(){
	
	echo "
		<h4>Bolum Guncelleme</h4>
		<form action=''>
		<input type=hidden name=is value=boulumguncelle>
		<input type=hidden name=bno value='{$_GET['bno']}'>
		<table>
		<tr><td>Bolum No</td><td><input disabled name=bno type=text value='{$_GET['bno']}'></td></tr>
		<tr><td>Bolum Ad</td><td><input name=bad type=text value='{$_GET['ad']}'></td></tr>
		<tr><td></td><td><input name=gonder type=submit value=SAKLA></td></tr>
		</table>
		</form>
		";
}


function bolumdekiOgrenciler($bolumNo){
	
	echo "
	<h1>$bolumNo Bolumdeki  listesi</h1> 
	<a href='?is='>Ana sayfa</a>
	<table class='table'> 
	<thead>
	<tr> 
	<th>No</th>
	<th>Adi</th> 
	<th>Soyadi</th> 
	<th>Bölüm</th> 
	</tr>
	</thead>
	<tbody>";
	$bno = $bolumNo;
	$bno = trim($bno);
    $dosya = fopen("ogrenci.txt", "r");    // dosyayı okumak için ac
    while (!feof($dosya)) {    // dosya'nın sonuna vardık mı?
        $satir = fgets($dosya);    // $dosya dosyasından 1 satır oku
        $veri = explode(' ', $satir); // array olarak dondurur
        $ogrno = $veri[0];
        $adi = $veri[1];
        $soyadi = $veri[2];
        $bolumno = $veri[3];
        if ($bolumno == $bno) 
	print "<tr> 
			<td>{$ogrno}</td> 
			<td>{$adi}</td> 
			<td>{$soyadi}</td> 
			<td>{$bolumno}</td>"; 
	}
	print "</tbody></table>";
	fclose($dosya);
	
}

/////////////////////////////anasayfa//////////////

function anaSayfa(){
	echo "<div class='card align-center'>
			<div class='card-header'>AnaSayfa</div> 
				<div class='card-body'>
					<a href=?is=ogrenciListele>OGRENCILER</a> 
					<br/>
					<a href=?is=bolumListele>BOLUMLER</a>
			</div>
		</div>";
}
//////////////////////////orgenci islem//////////////////////
function ogrenciListele(){
	
	echo "<h1>Ogrenci listesi</h1> 
	<a href='?is=ogrenciEklemeFormu'>Yeni</>
	<a href='?is='>Ana sayfa</a>
	<table class='table'> <thead><tr> <th>No</th> <th>Adi</th> <th>Soyadi</th> <th>Bölüm</th> <th>Sil</th> <th>Degistir</th> </tr></thead><tbody>";
	$dosya = fopen("ogrenci.txt", "r");    // dosyayı okumak için ac
    while (!feof($dosya)) {    // dosya'nın sonuna vardık mı?
        $satir = fgets($dosya);    // $dosya dosyasından 1 satır oku
        $veri = explode(" ", $satir); // array olarak dondurur
        $ogrno = $veri[0];
        $adi = $veri[1];
        $soyadi = $veri[2];
        $bolumno = $veri[3];
       // echo "$ogrno $adi $soyadi $bolumno\n";
  
	print "<tr> 
			<td>{$veri[0]}</td> 
			<td>{$veri[1]}</td> 
			<td>{$veri[2]}</td> 
			<td> <a href='?is=bolumdekiOgrenciler&bno={$veri[3]}'>{$veri[3]}</a> </td> 
			<td> <a href='?is=ogrenciSil&ono={$veri[0]}'>Sil</a>
			<td> <a href='?is=guncelleFormu&ono={$veri[0]}&ad={$veri[1]}&soyad={$veri[2]}&bno={$veri[3]}'>Degistir</a>
			</td></tr>";
	}
    fclose($dosya);
	print "</tbody></table>";
}

function ogrenciSil($ono){
	
	$f1 = fopen('ogrenci.txt', 'r');
    $f2 = fopen('temp.txt', 'w');
    while (!feof($f1)) {
        $satir = fgets($f1);
        $words = explode(" ", $satir);
        if ($words[0] != $ono) // aranan degilse
            fputs($f2, $satir);
    }
    fclose($f1);
    unlink("ogrenci.txt"); // dosya sil
    fclose($f2);
    rename("temp.txt", "ogrenci.txt"); // isim degistirme
}

function guncelle($ono,$ad,$soyad,$bno)
{
	$yeniono = $ono;
	$yeniadi = $ad;
	$yenisoyadi = $soyad ;
	$yenibolum = $bno;
	$dosya = fopen("ogrenci.txt", "r");    // dosyayı okumak için ac
    $cikti = fopen("temp.txt", "w");    // dosyayı okumak için ac
    while (!feof($dosya)) {    // dosya'nın sonuna vardık mı?
        $satir = fgets($dosya);    // $dosya dosyasından 1 satır oku
        $veri = explode(' ', $satir); // array olarak dondurur
        $ono = $veri[0];
        $adi = $veri[1];
        $soyadi = $veri[2];
        $bolumno = $veri[3];
        if ($yeniono == $ono) {
    fputs($cikti, "$yeniono $yeniadi $yenisoyadi $yenibolum\n");
    break;
}
    else
        fputs($cikti, $satir);
        }
        fclose($dosya);
        unlink("ogrenci.txt"); // dosya sil
        fclose($cikti);
        rename("temp.txt", "ogrenci.txt");
}

function guncelleFormu()
{	
	echo "
		<h4>Ogrenci Guncelleme</h4>
		<form action=''>
		<input type=hidden name=is value=guncelle>
		<input type=hidden name=ono value='{$_GET['ono']}'>
		<table>
		<tr><td>NO</td><td><input disabled name=ono type=text value='{$_GET['ono']}'></td></tr>
		<tr><td>AD</td><td><input name=ad type=text value='{$_GET['ad']}'></td></tr>
		<tr><td>SOYAD</td><td><input name=soyad type=text value='{$_GET['soyad']}'></td></tr>
		<tr><td>BolumNo</td><td><input name=bno type=text value='{$_GET['bno']}'></td></tr>
		<tr><td></td><td><input name=gonder type=submit value=SAKLA></td></tr>
		</table>
		</form>
		";
	}

function ogrenciEkle($ono,$ad,$soyad,$bno)
{
    $ogrno = $ono;
	$adi =$ad;
	$soyadi = $soyad;
    $bolumno = $bno;
    $dosya = fopen("ogrenci.txt","a");
    fputs($dosya, "$ogrno $adi $soyadi $bolumno\n");
    fclose($dosya);
}


function ogrenciEklemeFormu(){
	
	echo"
		<form action=''>
		<input name=is type=hidden value=ogrenciEkle>
		<h3>Yeni Ogrenci</h3>
		<table>
		<tr><td>No</td> <td><input name=ono type=text></td></tr>
		<tr><td>Adi</td> <td><input name=ad type=text></td></tr>
		<tr><td>Soyadi</td> <td><input name=soyad type=text></td></tr>
		<tr><td>Bolum</td> <td><input name=bno type=text></td></tr>
		<tr><td></td> <td><input name=tamam type=submit value=Olustur></td></tr>
		</table>	
		</form>";
}
?>
</div>
</body>
</html>
