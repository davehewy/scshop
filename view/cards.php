<div class="breaker">Select Payment Method</div>
<div class="pad10">
<table border="0" class="cards" style="margin:10px auto;">
	<tr>
		<td><label for="radio1"><img style="cursor:pointer;" src="<?=$header['asset']?>images/paypal.png"></label></td>
		<td><label for="radio2"><img style="cursor:pointer;" src="<?=$header['asset']?>images/visa.png"></label></td>
		<td><label for="radio3"><img style="cursor:pointer;" src="<?=$header['asset']?>images/mastercard.png"></label></td>
		<td><label for="radio4"><img style="cursor:pointer;" src="<?=$header['asset']?>images/visadebit.png"></label></td>
	</tr>
	<tr>
		<td align="center"><label for="radio1">PayPal</label></td>
		<td align="center"><label for="radio2">Visa</label></td>
		<td align="center"><label for="radio3">MasterCard</label></td>
		<td align="center"><label for="radio4">Visa Debit</label></td>
	</tr>
	<tr>
		<td align="center" class="pad10bottom"><input type="radio" id="radio1" value="1" name="type" <?php echo set_radio('type', '1'); ?>></td>
		<td align="center" class="pad10bottom"><input type="radio" id="radio2" value="2" name="type" <?php echo set_radio('type', '2'); ?>></td>
		<td align="center" class="pad10bottom"><input type="radio" id="radio3" value="3" name="type" <?php echo set_radio('type', '3'); ?>></td>
		<td align="center" class="pad10bottom"><input type="radio" id="radio4" value="4" name="type" <?php echo set_radio('type', '4'); ?>></td>
	</tr>
	<tr>
		<td><label for="radio5"><img style="cursor:pointer;" src="<?=$header['asset']?>images/maestro.png"></label></td>
		<td><label for="radio6"><img style="cursor:pointer;" src="<?=$header['asset']?>images/moneybookers.png"></label></td>
		<td><label for="radio7"><img style="cursor:pointer;" src="<?=$header['asset']?>images/banktransfer.png"></label></td>
		<td><label for="radio8"><img style="cursor:pointer;" src="<?=$header['asset']?>images/mobilepay.png"></label></td>
	</tr>
	<tr>
		<td align="center"><label for="radio5">Maestro</label></td>
		<td align="center"><label for="radio6">Moneybookers</label></td>
		<td align="center"><label for="radio7">Bank Transfer</label></td>
		<td align="center"><label for="radio8">Phone / SMS</label></td>
	</tr>
	<tr>
		<td align="center"><input type="radio" id="radio5" value="5" name="type" <?php echo set_radio('type', '5'); ?>></td>
		<td align="center"><input type="radio" id="radio6" value="6" name="type" <?php echo set_radio('type', '6'); ?>></td>
		<td align="center"><input type="radio" id="radio7" value="7" name="type" <?php echo set_radio('type', '7'); ?>></td>
		<td align="center"><input type="radio" id="radio8" value="8" name="type" <?php echo set_radio('type', '8'); ?>></td>
	</tr>
	<tr>
		<td colspan="4" align="center" style="padding-top:8px;"><input type="submit" value="" name="pay" class="bigblack" id="paybutton" /></td>
	</tr>
</table>
</div>

</form>
