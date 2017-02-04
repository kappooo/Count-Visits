<?php



$dbh=new PDO('mysql:host=localhost;dbname=counter','root',"",[PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC ]);


date_default_timezone_set('AFRICA/Cairo');


$minutes_to_add = 5;

$time = new DateTime(date('Y-m-d H:i:s'));
$time->add(new DateInterval('PT' . $minutes_to_add . 'M'));

$time=$time->format('Y-m-d H:i:s');


if(!isset($_COOKIE['visit']))
{
    echo 'no cookie';
    $cookieId=uniqid().rand(1000,155489846546);
    $cookieId=md5($cookieId);
    setcookie('visit',$cookieId,time()+3600*24*365*10);
    $stmt=$dbh->prepare('insert into cookie  (cookie_id,last_active)  values (?,?)');

    $stmt->bindParam(1,$cookieId);


    $stmt->bindParam(2,$time);


    $stmt->execute();
}else{

    echo 'update';
    $cookie_id=$_COOKIE['visit'];

    $stmt=$dbh->prepare('update cookie set last_active = ? where cookie_id = ?');


    $stmt->bindParam(1,$time);

    $stmt->bindParam(2,$cookie_id);

    $stmt->execute();


}


$stmt=$dbh->prepare('select * from cookie where last_active > ?');

$currentDate=date('Y-m-d H:i:s');

  $stmt->bindParam(1,$currentDate);

$stmt->execute();

echo '<pre>';
print_r($stmt->fetchAll());
echo '</pre>';


