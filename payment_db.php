<meta charset="utf-8">
<?php include('admin/db_connect.php');

    $bid = mysqli_real_escape_string($conn, $_POST["bid"]);
    $slip_date = mysqli_real_escape_string($conn, $_POST["slip_date"]);
    $slip_total = mysqli_real_escape_string($conn, $_POST["slip_total"]);
    $o_id = mysqli_real_escape_string($conn, $_POST["o_id"]);

    $date1 = date("Ymd_His"); //วดป เวลา
    $numrand = (mt_rand()); //เลขสุ่ม

    $slip =(isset($_POST['slip']) ? $_POST['slip'] : '');
    $upload = $_FILES['slip']['name'];
    if($upload !=''){
        //โฟลเดอร์ที่เก็บภาพ
        $path = "admin/imgslip/";
        $type = strrchr($_FILES['slip']['name'], ".");
        $newname = 'slip'.$numrand.$date1.$type;
        $path_copy = $path.$newname;
        $path_link = "admin/imgslip/".$newname;

        move_uploaded_file($_FILES['slip']['tmp_name'], $path_copy);
        }else{
            $newname = '';
        }
            
        $sql = " UPDATE bids SET
        bid = '$bid',
        slip_date = '$slip_date',
        slip_total = '$slip_total',
        status_payment = 2,
        slip = '$newname'
        WHERE o_id = $o_id
        ";
        
        $result = mysqli_query($conn, $sql) or die ("Error in query: $sql " 
        .mysqli_error($conn));
            
        mysqli_close($conn);
        
        if($result){
            echo "<script type='text/javascript'>";
            echo "alert('แจ้งชำระเงินสำเร็จ');";
            echo "window.location = 'index.php?page=home';";
            echo "</script>";
            }else{
                echo "<script type='text/javascript'>";
                echo "window.location = 'index.php';";
                echo "</script>";
            }
?>