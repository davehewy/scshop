<center><h1 class="headline">Place order for Credits <?=$fuckaduck['name']?></h1></center>

<?php

	// Work out the membership pricing based on the monthly amount.
	$ten = ($pricing['single']*10);
		
	$costs = array();
	
	for($i=1;$i<22;$i++){
		if($i==1){
			$costs[$i] = number_format(($pricing['single']*($i*10)),2);
		}elseif($i==21){
			$costs[$i] = number_format((75.00/100)*(100-$product->discount),2);
		}else{
			$costs[$i] = number_format(((($pricing['single']*($i*10))/100)*(100-$i)),2);
		}
	}

?>

<div class="indent">
<h1><? echo $product->name; ?> - starting at 10 for $<?=number_format($ten,2);?> <?php if($product->discount>0){ echo ' - <span class="sale">SALE '.$product->discount.'% OFF!</span>';}?></h1>
<?php echo $cat->description; ?>

<?php 
	if(isset($errors)){
		echo $errors; 
	}
?>
</div>
<?php echo form_open_this('order/start/'.strtolower(str_replace(" ","_",$product->name)));?>

<div class="breaker">Account</div>
<div class="indent">
I want these credits to be applied to the account attached to this email address: &nbsp;

<?php
  	$data = array("name"=>"account","value"=>set_value('account'),"class"=>"text","style"=>"width:200px;");
  	if($this->session->_isset('email')){
  		$data['readonly']="readonly";
  		$data['value'] = $this->session->_get('email');
  	}
	echo form_input($data);
	
	if($this->session->_get('email')){
		echo '&nbsp;&nbsp;<a href="#" id="changeuser">change</a>';
	}
?>

</div>
<div class="breaker">Select Quantity</div>

	
<div class="ovfl threequarters centrediv">
	<div class="halfthreequarters fl">
		<span class="big">Order: <span id="credits">10</span> Credits</span>
		<p>Alter amount:&nbsp;
		<select name="selectamount" class="scshop">
		<?php 
		for($i=1;$i<22;$i++){
			if($i==21){ ?>
				<option value="<?=$i?>" <?php echo set_select('selectamount', $i); ?> cost="<?=number_format($costs[$i],2)?>"><?php echo '500 Credits - &pound;'.number_format($costs[$i],2); ?></option>
				
			<?php }else{ ?>
				
				<option value="<?=$i?>" <?php echo set_select('selectamount', $i); ?> cost="<?=number_format($costs[$i],2)?>"><?php echo ($i*10).' Credits - &pound;'.number_format($costs[$i],2); ?></option>
			
			<?php }
		}
		?>
		</select></p>
		
		<?php
		
		if($this->input->post('selectamount')){
			$display_cost =  $costs[$this->input->post('selectamount')];
			$cost_per = round(($display_cost/($this->input->post('selectamount')*10)),3);
		}else{
			$display_cost = $ten;
			$cost_per = number_format($ten/10,3);
		}
		
		?>
		<h1>Amount: <span id="cost">&pound;<?=number_format($display_cost,2)?></span></h1>
		
	</div>
	<div class="halfthreequarters fr">
		Selecting more credits to buy in one go will result in a nice discount per credit.
		<h1>Cost per credit: &pound;<span id="costper"><?=$cost_per?></span></h1>
	</div>
</div>

