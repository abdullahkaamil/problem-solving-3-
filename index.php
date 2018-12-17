
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
	case 'guncelleFormu':guncelleFormu();    break;
	case 'guncelle': guncelle();ogrenciListele();   break;
	case 'ogrenciSil'	: 
		ogrenciSil($_GET['ono']);
		ogrenciListele(); 
		break;
	case 'ogrenciListele': ogrenciListele(); break;
	case 'ogrenciEklemeFormu': ogrenciEklemeFormu();  break;
	case 'ogrenciEkle': 
	ogrenciEkle(); 
	ogrenciListele(); 
	break;
//bolum 
	case 'bolumListele': bolumListele(); break;
	case 'guncelleFormub' : guncelleFormub(); break;
	case 'boulumguncelle' : boulumguncelle(); bolumListele(); break;
	case 'bolumEklemeFormu': bolumEklemeFormu();  break;
	case 'bolumEkle': bolumEkle();  bolumListele(); break;
	case 'bolumdekiOgrenciler':bolumdekiOgrenciler($_GET['bno']); break;
	case 'bolumSil'	: 
		bolumSil($_GET['bno']);
		bolumListele(); 
		break;
	default: anaSayfa();//listele();
}
exit;

function bolumEkle(){

	$sql = "INSERT INTO bolum(bno,ad) VALUE(".$_GET['bno'].",'".$_GET['bolumad']."');";
	$baglanti = mysqli_connect('localhost', 'root', '', 'bmg');
	if(! $baglanti)
		exit(mysqli_error($baglanti));
	$sonuc = mysqli_query($baglanti, $sql);
	if(! $sonuc)
		exit(mysqli_error($baglanti));

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

function bolumSil($bno){

	$sql = "DELETE  bolum, ogrenci FROM bolum INNER JOIN ogrenci ON ogrenci.bno = bolum.bno WHERE bolum.bno ='$bno';";
	$baglanti = mysqli_connect('localhost', 'root', '', 'bmg');
	$sonuc = mysqli_query($baglanti, $sql);
	if(! $sonuc)
		exit(mysqli_error($baglanti));
}


function bolumListele(){
	
	echo "<h1>Bolum listesi</h1> 
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

	$baglanti = mysqli_connect('localhost', 'root', '', 'bmg');
	$kayitKumesi = mysqli_query($baglanti, "SELECT bolum.bno, bolum.ad, COUNT(ono) FROM ogrenci , bolum WHERE ogrenci.bno = bolum.bno GROUP BY bno") or exit(mysqli_error($baglanti));
	while($satir = mysqli_fetch_array($kayitKumesi)){
		print "<tr> 
			<td>{$satir[0]}</td> 
			<td>{$satir[1]}</td> 
			<td>{$satir[2]}</td>
 			<td> <a href='?is=bolumSil&bno={$satir[0]}'>Sil</a>
			<td> <a href='?is=guncelleFormub&bno={$satir[0]}&ad={$satir[1]}'>Degistir</a>
			<td> <a href='?is=bolumdekiOgrenciler&bno={$satir[0]}'>Ogrenciler</a>
			</td></tr>";
	}
	print "</tbody></table>";
}

function boulumguncelle(){
	$baglanti = mysqli_connect('localhost', 'root', '', 'bmg');
	$sql = "UPDATE bolum SET ad='{$_GET['bad']}' WHERE bno={$_GET['bno']};";
	$sonuc = mysqli_query($baglanti, $sql);
	mysqli_close($baglanti); 
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
	
	echo "<h1>$bolumNo Bolumdeki  listesi</h1> <a href='?is='>Ana sayfa</a>
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
	$baglanti = mysqli_connect('localhost', 'root', '', 'bmg');
	$sql = "SELECT * FROM ogrenci WHERE bno=$bolumNo;";
	$kayitKumesi = mysqli_query($baglanti, "SELECT * FROM ogrenci WHERE bno=$bolumNo;");
	//echo "$sql<br>";
	while($satir = mysqli_fetch_array($kayitKumesi)){
		print "<tr> 
			<td>{$satir[0]}</td> 
			<td>{$satir[1]}</td> 
			<td>{$satir[2]}</td> 
			<td>{$satir[3]}</td>";
	}
	print "</tbody></table>";
}



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
//orgenci islem
function ogrenciListele(){
	
	echo "<h1>Ogrenci listesi</h1> 
	<a href='?is=ogrenciEklemeFormu'>Yeni</>
	<a href='?is='>Ana sayfa</a>
	<table class='table'> <thead><tr> <th>No</th> <th>Adi</th> <th>Soyadi</th> <th>Bölüm</th> <th>Sil</th> <th>Degistir</th> </tr></thead><tbody>";
	$baglanti = mysqli_connect('localhost', 'root', '', 'bmg');
    $kayitKumesi = mysqli_query($baglanti, "SELECT * FROM ogrenci");
	while($satir = mysqli_fetch_array($kayitKumesi)){
		print "<tr> 
			<td>{$satir[0]}</td> 
			<td>{$satir[1]}</td> 
			<td>{$satir[2]}</td> 
			<td> <a href='?is=bolumdekiOgrenciler&bno={$satir[3]}'>{$satir[3]}</a> </td> 
			<td> <a href='?is=ogrenciSil&ono={$satir[0]}'>Sil</a>
			<td> <a href='?is=guncelleFormu&ono={$satir[0]}&ad={$satir[1]}&soyad={$satir[2]}&bno={$satir[3]}'>Degistir</a>
			</td></tr>";
	}
	print "</tbody></table>";
}

function ogrenciSil($ono){

	$sql = "DELETE FROM ogrenci WHERE ono ='$ono';";
	$baglanti = mysqli_connect('localhost', 'root', '', 'bmg');
	$sonuc = mysqli_query($baglanti, $sql);
	if(! $sonuc)
		exit(mysqli_error($baglanti));
}

function guncelle(){

	$baglanti = mysqli_connect('localhost', 'root', '', 'bmg');
	$sql = "UPDATE ogrenci SET ad='{$_GET['ad']}',soyad='{$_GET['soyad']}',bno='{$_GET['bno']}' WHERE ono={$_GET['ono']};";
	$sonuc = mysqli_query($baglanti, $sql);
	mysqli_close($baglanti); 
	}

	function guncelleFormu(){
	
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

function ogrenciEkle()
{

	$sql = "INSERT INTO ogrenci( ono,ad,soyad,bno) VALUE(".$_GET['ono'].",'".$_GET['ad']."','".$_GET['soyad']."','".$_GET['bno']."');";
	$baglanti = mysqli_connect('localhost', 'root', '', 'bmg');
	if(! $baglanti)
		exit(mysqli_error($baglanti));
	$sonuc = mysqli_query($baglanti, $sql);
	if(! $sonuc)
		exit(mysqli_error($baglanti));
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
