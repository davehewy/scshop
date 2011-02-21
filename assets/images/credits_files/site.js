Utilities={
	bindLoginForm: function(){
		$("#loginpopup").bind('click',function(){
			$(this).parents("form:first").submit();
		});
	},
	unbindLoginForm: function(){
		$("#loginpopup").unbind('click');
		$.modal.close();
	},
	
	bindsmsclick: function(){
		if($(this).val()==8){
			alert("When using the SMS payment option, prices may vary slightly from those stated above, they will be stated on the SMS payment gateway page individually.");
		}
	}
}

$(function(){
	
	$("input[name=type]").bind('click',Utilities.bindsmsclick);

	$("#changeuser").click(function(e){
		e.preventDefault();
		$("input[name=account]").attr("readonly","").val('').focus();
	});	
		
	$("select[name=selectamount]").change(function(){
		
		var elem = $("select[name=selectamount] option:selected");
		var cost = elem.attr("cost");
		if(elem.val()!=21){
			var credits = $(this).val()*10;
		}else{
			var credits = 500;
		}
		
		$("#credits").html(credits);
		$("#cost").html('$'+cost);
		var cost_per = Math.round((cost/credits)*1000)/1000;
		cost_per = cost_per.toFixed(3);
		$("#costper").html(cost_per);

	});
	
	$("#paybutton").click(function(){
		$("#content-inner").hide();
		$("<div />").css({"padding":"90px 0px 80px 0px","text-align":"center"}).html("Transaction processing.....").appendTo("#content");
		return true;
	});
	
	
	$("#loginbutton").click(function(){
		$.modal("<div><h1>Login to your Street Crime account</h1><p>Login to your Street Crime account to make your browsing experience better when in our shop! Simply login to your existing Street Crime account using the email and password you would normally use. This will store your account details for the entirety of your look around the shop!</p><form action='/scshop/index.php/login' method='post'><table align='center' width='200px' class='login_popup'><tr><td><label class='largetext'>Email:</label></td><td colspan='2'><input type='text' name='emailadd' class='text' /></td></tr><tr><td><label class='largetext'>Password:</label></td><td colspan='2'><input type='password' name='pass' class='text' /></td></tr><tr><td><label class='largetext'>Pin code:</label></td><td><input type='text' name='pincode' class='pincode' maxlength='4' /></td><td>(If you have one else leave it blank)</td></tr><tr><td colspan='3'><a href='#' class='bt-black-homepage fr' id='loginpopup'><span>Login now</span></a></td></tr></table></form></div>",{
		containerCss:{
		height:310,
		width:620
		},
		onShow: Utilities.bindLoginForm,
		onClose: Utilities.unbindLoginForm
		});
	});
	
	if($("#loginpage").length>0){
		$("#loginpage").bind('click',function(){
			$(this).parents("form:first").submit();
		});
	}
	
});