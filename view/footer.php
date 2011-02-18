	</div> <!-- Inner -->
	</div> <!-- Content -->
	</div>
	<div id="footer">
		<div id="footer-navis">
		<?php
/*
			<ul><span>Navigation</span>
				<li><?php echo anchor('shop/membership', 'Memberships'); ?></li>
				<li><?php echo anchor('buy/credits/5', 'Credits'); ?></li>
				<li><?php echo anchor('shop/merchandise', 'Merchandise'); ?></li>
				<li><?php echo anchor('shop/packs', 'Special Packs'); ?></li>
				<li><?php echo anchor('buy/credits/5', 'Credits'); ?></li>
			</ul>
*/
		?>
			<ul class="marg-left50"><span>&nbsp;</span>
				<li><a href="<?php echo site_url(array("reasons")); ?>">Why should I become a member?</a></li>
				<li><a href="#">Why go elite?</a></li>
				<li><a href="#">Perks of going premium</a></li>
				<li><a href="http://www.street-crime.com">Back to the game</a></li>
			</ul>
		</div>
		<div class="fr clear">
			&copy; <a href="http://www.bytewire.co.uk" title="See the creators of street crimes site">Bytewire Ltd</a> 2008-<?=date("Y",time())?> all rights reserved.
		</div>
	</div>
</div>
</body>
</html>