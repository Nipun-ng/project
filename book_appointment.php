<?php
	session_start();
	$con=new mysqli ('localhost', 'root', '','project_final');
    if ($con -> connect_errno) {
        echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
        exit();
      }
      else {
        echo "db connected";
      }

      
      $query = "select * from doctors";
      $statement = mysqli_query($con,$query);
     
?>

<?php include 'header.php'; ?>

<HEADER><h2>Doctor Availability</h2></HEADER>

<form action=" " method="post" >

<table id="book">
<tr> 
<th> Doctor Name:</th> 
<th>Time :</th> 
<th>Day:</th> 
<th>Select:</th> 
<th>Notes:</th> 

</tr>
 
<?php
$query2 = "select Time , Day, doctors.doctorName , registration.lname, registration.fname from Availability 
INNER JOIN doctors
ON Availability.medicalLicence = doctors.medicalLicence 
INNER JOIN registration ON Availability.healthNo = registration.healthNo and Status = 'Available' ";
$statement = mysqli_query($con,$query2);
 $array1 = array();
 $array2 = array();
 $count=0;
while($row = $statement->fetch_assoc()) 
{ 
    $doctor = $row['doctorName'];
    $patient = $row['fname'];
    $time = $row['Time'];
    $array1[$count]=$doctor;
    $array2[$count]=$time;
   
    echo"<tr>" ; 

    echo"<td>" . $row['doctorName'] . "</td>"; 
    echo"<td>" . $row['Time'] . "</td>"; 
    echo "<td>" . $row['Day'] . "</td>"; 
    echo"<td>" ."<input type='checkbox' id='check' name='check' value='$count'>"."</td>" ;
    echo "<td>"."<a href='lab_result_submission_form.php'><button type='button' class='back_btn'>Upload Notes"."</td>";
    echo"</tr>";
    $count++;
}
?>
</table><br>
<span>
<label for='patientCard'>Enter Patient Health Card Number : </label>
<input type='text' name='patientCard' required/>
</span>
<input name='book'  type='submit' value ='Book' >



<?php 

   if(isset($_POST['book'])){
    $checked =$_POST['check'];
    $patientno =$_POST['patientCard'];
    $doctorNam = $array1[$checked];
    $timing = $array2[$checked];
      $query3 = "update Availability set Status = 'Reserved' , healthNo='$patientno' where Time ='$timing' and medicalLicence in 
      (select Availability.medicalLicence from Availability 
      INNER JOIN doctors ON Availability.medicalLicence = doctors.medicalLicence and doctors.doctorName = '$doctorNam' ); ";
       $statement2 = mysqli_query($con,$query3);

       if($statement2){
        echo '<script type="text/javascript">alert("Appointment Booked ")</script>';
     }
  
    }
?>
	</body>


<?php include 'footer.php'; ?>
