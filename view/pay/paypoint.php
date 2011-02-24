<?php

if($ret_info['complete']){ ?>

	<h1>Payment Failed</h1>
	
	<p>We haven't been able to determine why, but your payment to Street Crime has failed. Please wait a few minutes and try again.</p>

<?php }else{ ?>


<h1>Payment Complete</h1>

<p>Congratulations your payment of <span class="bigtext">&pound;<?=$ret_info['pp_vars']['amt']?></span> for <?=$ret_info['pp_vars']['item_name']?> to us is complete.</p>

<p>Your unique transaction reference is <span class="bigtext"><?=$ret_info['pp_vars']['tx_id']?></span></p>

<p>You will receive these items, directly to your account as soon as our servers are notified of the successful transaction (usually instantly).</p>

<?php } 