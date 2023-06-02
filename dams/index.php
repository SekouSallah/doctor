<?php
session_start();
//error_reporting(0);
include('doctor/includes/dbconnection.php');
    if(isset($_POST['submit']))
  {
 $name=$_POST['name'];
  $mobnum=$_POST['phone'];
 $email=$_POST['email'];
 $appdate=$_POST['date'];
 $aaptime=$_POST['time'];
 $specialization=$_POST['specialization'];
  $doctorlist=$_POST['doctorlist'];
 $message=$_POST['message'];
 $aptnumber=mt_rand(100000000, 999999999);
 $cdate=date('Y-m-d');

if($appdate<=$cdate){
       echo '<script>alert("La date de rendez-vous doit etre supérieur à la date d\'aujourd\'hui")</script>';
} else {
$sql="insert into tblappointment(AppointmentNumber,Name,MobileNumber,Email,AppointmentDate,AppointmentTime,Specialization,Doctor,Message)values(:aptnumber,:name,:mobnum,:email,:appdate,:aaptime,:specialization,:doctorlist,:message)";
$query=$dbh->prepare($sql);
$query->bindParam(':aptnumber',$aptnumber,PDO::PARAM_STR);
$query->bindParam(':name',$name,PDO::PARAM_STR);
$query->bindParam(':mobnum',$mobnum,PDO::PARAM_STR);
$query->bindParam(':email',$email,PDO::PARAM_STR);
$query->bindParam(':appdate',$appdate,PDO::PARAM_STR);
$query->bindParam(':aaptime',$aaptime,PDO::PARAM_STR);
$query->bindParam(':specialization',$specialization,PDO::PARAM_STR);
$query->bindParam(':doctorlist',$doctorlist,PDO::PARAM_STR);
$query->bindParam(':message',$message,PDO::PARAM_STR);

 $query->execute();
   $LastInsertId=$dbh->lastInsertId();
   if ($LastInsertId>0) {
    echo '<script>alert("Votre demande de rendez-vous a bien été envoyée. Nous vous contacterons bientot")</script>';
echo "<script>window.location.href ='index.php'</script>";
  }
  else
    {
         echo '<script>alert("Impossible d\'envoyer le formulaire. Merci de réessayer plus tard")</script>';
    }
}
}
?>
<!doctype html>
<html lang="fr">
    <head>
        <title>SecureMed || Page d'accueil</title>

        <!-- CSS FILES -->        
        <link rel="preconnect" href="https://fonts.googleapis.com">
        
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">

        <link href="css/bootstrap.min.css" rel="stylesheet">

        <link href="css/bootstrap-icons.css" rel="stylesheet">

        <link href="css/owl.carousel.min.css" rel="stylesheet">

        <link href="css/owl.theme.default.min.css" rel="stylesheet">

        <link href="css/templatemo-medic-care.css" rel="stylesheet">
        <script>
function getdoctors(val) {
  //  alert(val);
$.ajax({

type: "POST",
url: "get_doctors.php",
data:'sp_id='+val,
success: function(data){
$("#doctorlist").html(data);
}
});
}
</script>
    </head>
    
    <body id="top">
    
    <main>
    <section class="section-padding" id="booking">
        <div class="container">
            <div class="booking-form border rounded p-4">
                <h2 class="text-center mb-lg-3 mb-2">Formulaire de prise de rendez-vous</h2>
                <form role="form" method="post">
                    <div class="row">
                        <div class="col-lg-6 col-md-12">
                            <input type="text" name="name" id="name" class="form-control mb-3" placeholder="Nom complet" required>
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <input type="email" name="email" id="email" pattern="[^ @]*@[^ @]*" class="form-control mb-3" placeholder="Adresse email">
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <input type="tel" name="phone" id="phone" class="form-control mb-3" placeholder="Numéro de téléphone" maxlength="10">
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <input type="date" name="date" id="date" value="" class="form-control mb-3">
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <input type="time" name="time" id="time" value="" class="form-control mb-3">
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <select onChange="getdoctors(this.value);" name="specialization" id="specialization" class="form-control mb-3" required>
                                <option value="">Sélectionner un département</option>
                                <?php
                                $sql="SELECT * FROM tblspecialization";
                                $stmt=$dbh->query($sql);
                                $stmt->setFetchMode(PDO::FETCH_ASSOC);
                                while($row =$stmt->fetch()) { 
                                  ?>
                                <option value="<?php echo $row['ID'];?>"><?php echo $row['Specialization'];?></option>
                                <?php }?>
                            </select>
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <select name="doctorlist" id="doctorlist" class="form-control mb-3">
                                <option value="">Sélectionner un docteur</option>
                                <?php
                                $sql="SELECT * FROM tbldoctor";
                                $stmt=$dbh->query($sql);
                                $stmt->setFetchMode(PDO::FETCH_ASSOC);
                                while($row =$stmt->fetch()) { 
                                  ?>
                                <option value="<?php echo $row['ID'];?>"><?php echo $row['FullName'];?></option>
                                <?php }?>
                            </select>
                        </div>
                        <div class="col-12">
                            <textarea class="form-control mb-3" rows="5" id="message" name="message" placeholder="Ajouter un message"></textarea>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6 mx-auto">
                            <button type="submit" class="form-control btn btn-primary" name="submit" id="submit-button">Envoyer</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
</main>

        <?php include_once('includes/footer.php');?>
        <!-- JAVASCRIPT FILES -->
        <script src="js/jquery.min.js"></script>
        <script src="js/bootstrap.bundle.min.js"></script>
        <script src="js/owl.carousel.min.js"></script>
        <script src="js/scrollspy.min.js"></script>
        <script src="js/custom.js"></script>
    </body>
</html>