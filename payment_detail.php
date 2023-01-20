<!DOCTYPE html>
<html lang="en">
    <?php
    session_start();
    include('admin/db_connect.php');
    ob_start();
        $query = $conn->query("SELECT * FROM system_settings limit 1")->fetch_array();
        foreach ($query as $key => $value) {
        if(!is_numeric($key))
            $_SESSION['system'][$key] = $value;
        }
    ob_end_flush();
    include('header.php');
    ?>
    <style>
    #main-field{
        margin-top: 5rem!important;
    }
    </style>
<?php
$o_id = mysqli_real_escape_string($conn, $_GET['o_id']);
$querybank = "SELECT * FROM tbl_bank";
$rsbank = mysqli_query($conn, $querybank);
?>
<div class="container-fluid">
	
	<div class="col-lg-12">
		<div class="row mb-4 mt-4">
			<div class="col-md-12">
				
			</div>
		</div>
		<div class="row">
			<!-- FORM Panel -->

			<!-- Table Panel -->
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<b>Payment Detail</b>
					</div>
					<div class="card-body">
						<table class="table table-condensed table-bordered table-hover">
							<thead>
								<tr>
									<th class="text-center">#</th>
									<th class=""><center>Product</th>
									<th class=""><center>Name</th>
									<th class=""><center>Amount</th>
									<th class=""><center>Status</th>
								</tr>
							</thead>
							<tbody>
								<?php 
								$i = 1;
								$cat = array();
								$cat[] = '';
								$qry = $conn->query("SELECT * FROM categories ");
								while($row = $qry->fetch_assoc()){
									$cat[$row['id']] = $row['name'];
								}
								$books = $conn->query("SELECT b.*, u.name as uname,p.name,p.bid_end_datetime bdt, b.*,  k.bname, k.bnumber
                                                        FROM bids b 
                                                        inner join users u on u.id = b.user_id 
                                                        inner join products p on p.id = b.product_id  
                                                        inner join tbl_bank k on b.bid = k.bid 
                                                        order by u.id ");
								while($row=$books->fetch_assoc()):
									$get = $conn->query("SELECT * FROM  bids 
                                                            where product_id = {$row['product_id']}    
                                                            order by bid_amount 
                                                            desc limit 1 ");
									$uid = $get->num_rows > 0 ? $get->fetch_array()['user_id'] : 0 ;
								?>
								<tr>
									<td class="text-center"><?php echo $i++ ?></td>
									<td class="">
										<p> <b><?php echo ucwords($row['name']) ?></b></p>
									</td>
									<td class="">
										<p> <b><?php echo ucwords($row['uname']) ?></b></p>
									</td>
									<td class="text-right">
										<p> <b><?php echo number_format($row['bid_amount'],2) ?></b></p>
									</td>
									<td class="text-center">
                                    <?php if($row['status_payment'] == 1): ?>
										<?php if(strtotime(date('Y-m-d H:i')) < strtotime($row['bdt'])): ?>
										<span class="badge badge-secondary">Waiting for Payment</span>
										<?php else: ?>
										<?php if($uid == $row['user_id']): ?>
										<span class="badge badge-success">Waiting for Payment</span>
										<?php else: ?>
										<?php endif; ?>
										<?php endif; ?>
										<?php elseif($row['status_payment'] == 2): ?>
										<span class="badge badge-primary">Confirmed</span>
										<?php else: ?>
										<span class="badge badge-danger">Canceled</span>
										<?php endif; ?>
                                    </td>										
								</tr>
								<?php endwhile; ?>
							</tbody>
						</table>
                        <div class="card-header">
						<b>แสดงรายละเอียดที่ชำระเงิน</b>
                        <div class = "col-sm-6">
                            bank name : <?php echo $row['bname'];?>
                            bank number : <?php echo $row['bnumber'];?><br>
                            Bath : <?php echo $row['slip_total'];?> <br>
                            D/M/Y : <?php echo $row['slip_date'];?> <br>
                            Slip 
                            <br>
                            <img src="admin/imgslip/<?php echo $row['slip'];?>" width="100%">
                        </div>
					    </div>
					</div>
				</div>
			</div>
			<!-- Table Panel -->
		</div>
	</div>	

</div>
<style>
	
	td{
		vertical-align: middle !important;
	}
	td p{
		margin: unset
	}
	img{
		max-width:100px;
		max-height: :150px;
	}
</style>
<div id="preloader"></div>
        <footer class=" py-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8 text-center">
                        <h2 class="mt-0 text-white">Contact us</h2>
                        <hr class="divider my-4" />
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4 ml-auto text-center mb-5 mb-lg-0">
                        <i class="fas fa-phone fa-3x mb-3 text-muted"></i>
                        <div class="text-white"><?php echo $_SESSION['system']['contact'] ?></div>
                    </div>
                    <div class="col-lg-4 mr-auto text-center">
                        <i class="fas fa-envelope fa-3x mb-3 text-muted"></i>
                        <!-- Make sure to change the email address in BOTH the anchor text and the link target below!-->
                        <a class="d-block" href="mailto:<?php echo $_SESSION['system']['email'] ?>"><?php echo $_SESSION['system']['email'] ?></a>
                    </div>
                </div>
            </div>
            <br>
            <div class="container"><div class="small text-center text-muted">Copyright © 2020 - <?php echo $_SESSION['system']['name'] ?> | <a href="https://www.sourcecodester.com/" target="_blank">Sourcecodester</a></div></div>
        </footer>
        
    <?php include('footer.php') ?>
    </body>
