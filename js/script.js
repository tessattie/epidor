$(document).ready(function() {

	var access = '<select class="form-client" width="100%" name="access"><option value="none" disabled> -- Accès -- </option><option value="SA">Super-admin</option><option value="A" selected>Admin</option></select>';

	var status = '<select class="form-client" name="status"><option value="none" disabled> -- Status -- </option><option value="1" selected>Actif</option><option value="0">Inactif</option></select>';

	var access_types = [];
	
	var status_types = [];


	// prepare select categories
	var category_first = '<select class="form-client" name="category_id" >';
	var category = '';
	var category_last = '</select>';

	access_types['Admin'] = 'A';
	access_types['Super-admin'] = "SA";
	

	status_types['Actif'] = "1";
	status_types['Inactif'] = "0";

	$(".datepicker").datepicker({dateFormat : 'yy-mm-dd', maxDate: 0});
	$(".dailydatepicker").datepicker({dateFormat : 'yy-mm-dd', maxDate: 0});

	$('#productsTable').DataTable({
		"lengthMenu": [ [-1, 5, 10, 25, 50], ["All", 5, 10, 25, 50] ]
	});
	$('#clientsTable').DataTable({
		"lengthMenu": [ [-1, 5, 10, 25, 50], ["All", 5, 10, 25, 50] ]
	});
	$('#retraitsTable').DataTable({
		"lengthMenu": [ [-1, 5, 10, 25, 50], ["All", 5, 10, 25, 50] ]
	});
	$('#journaliere').DataTable({
		"lengthMenu": [ [-1, 5, 10, 25, 50], ["All", 5, 10, 25, 50] ]
	});
	$('#fichestable').DataTable({
		"lengthMenu": [ [-1, 5, 10, 25, 50], ["All", 5, 10, 25, 50] ]
	});

	$('.newElement').click(function(event){
		event.preventDefault();
		if($('.modification').css('display') == "none")
		{
			$('.modification').fadeIn();
		}
		else
		{
			$('.modification').fadeOut();
		}
	})

	$('.setpass').click(function(event){
		if($('.newpass').val() == $('.newpass2').val())
		{
			$('.errorDiv').empty();
			$('.newpass').css('border', 'none');
			$('.newpass2').css('border', 'none');
			$('#setpassform').submit();
		}
		else
		{
			$('.errorDiv').empty();
			$('.errorDiv').append('<p class="bg-danger">Both passwords must match</p>');
			$('.newpass').css('border', '1px solid red');
			$('.newpass2').css('border', '1px solid red');
			event.preventDefault();
		}
	})

	$('.dateInput').change(function(){
		if($(this).attr('id') == 'to')
		{
			$.ajax({
            url: DIRECTORY_NAME+"/home/setTo",
            type:"POST",
            data: { to : $(this).val() },
            success:function(data){
            	console.log(data) 
            }
        })
		}
		if($(this).attr('id') == 'from')
		{
			$.ajax({
            url:DIRECTORY_NAME+"/home/setFrom",
            type:"POST",
            data: { from : $(this).val() },
            success:function(data){
            	console.log(data) 
            }
        })
		}
	})

	$('.monthlyFormInput').change(function(){
		$('#monthlyForm').submit();
	})

	$('.dailyInput').parent().removeClass('form-group');

	$('.dailyInput').change(function(){
		$('#dailyForm').submit();
	})

	$('.pardateFormInput').change(function(){
		$('#parDateForm').submit();
	})

	$('.clientInputMain').keyup(function(){
		if($(this).val().length > 1)
		{
			$.ajax({
            url:DIRECTORY_NAME+"/clients/choose",
            type:"POST",
            data: { client : $(this).val() },
            success:function(data){
            	data = JSON.parse(data);
                console.log(data[0][0]);
                $('.clientMainDiv').empty();
                $('.clientMainDiv').show();
                for(var i = 0; i < data.length; i++)
                {
                	 $('.clientMainDiv').append("<div class='row'><a href='"+DIRECTORY_NAME+"/clients/setClientChoice/"+data[i]['id']+"' class='setClientChoice'><div class='col-md-5'>"+data[i]['firstname']+" "+data[i]['lastname']+"</div><div class='col-md-4'>NIF : "+data[i]['NIF']+"</div><div class='col-md-3'>ID : "+data[i]['id']+"</div></a></div>");
                }
               $('.clientMainDiv').append("<div class='close'>x</div>");
               $('.close').click(function(){
					$('.clientMainDiv').empty();
					$('.clientMainDiv').hide();
				}); 
            }
        })
		}
		else
		{
			$('.clientMainDiv').hide();
		}
	});

$(".chosen-select").change(function(){
	var cats = $(this).val();
	$('.tableProduits').each(function(){
		$(this).hide();
	})
	for(var i=0;i<cats.length;i++)
	{
		$('#'+cats[i]).show();
	}
})

$('.addToFiche').click(function(){
	var name = $(this).find(".name").text();
	var ident = $(this).attr('id');
	var price = $(this).find(".price").text();
	var quantity = 1;
	var total = price * quantity;
	var totalFiche = 0;
	$('.qty').each(function(){
		if($(this).text().length == 0)
		{
			var prix = parseInt($(this).parent().find('.price').text());
			$(this).text("1");
			$(this).parent().find('.total').text(prix+".00");
		}
		$(this).removeClass("qtyBorder");
	})
	if($('.ficheBody').find('.'+ident).length > 0)
	{
		var newqty = parseInt($('.ficheBody').find('.'+ident+" > .qty").text());
		if(newqty)
		{

		}else
		{
			newqty = 0;
		}
		quantity = newqty + 1;
		total = parseInt(price) * quantity;
		$('.ficheBody').find('.'+ident+" > .qty").text(quantity);
		$('.ficheBody').find('.'+ident+" > .name > input.qtyInput").val(quantity);
		$('.ficheBody').find('.'+ident+" > .qty").addClass("qtyBorder");
		$('.ficheBody').find('.'+ident+" > .total").html(total+".00");
	}
	else
	{
		$('.ficheBody').append("<tr class='"+ident+"'><td class='tddanger removeFiche' id='removeFiche'>-</td><td class='name'>"+name+"<input type='hidden' class='qtyInput' name='quantity[]' value='"+quantity+"'><input type='hidden' class='idInput' name='id[]' value='"+ident+"'></td><td class = 'price'><input type='hidden' class='priceInput' name='price[]' value='"+price+"'><span id='pricevalue'>"+price+"</span></td><td class='qty changeqty qtyBorder'>"+quantity+"</td><td class='total'>"+total+".00</td></tr>")
		$('#removeFiche').click(function(){
			$(this).parent().remove();
		})
		$('.removeFiche').click(function(){
			$(this).parent().remove();
		})
	}

	$(".qty").click(function(){
		$('.qty').each(function(){
			$(this).removeClass("qtyBorder");
		})
		$(this).addClass("qtyBorder");
	})

	$('.ficheBody > tr').each(function(){
		var price = parseInt($(this).find(".price").text());
		var qty = parseInt($(this).find(".qty").text());
		$(this).find(".name > input.qtyInput").val(qty);
		totalFiche = totalFiche + price*qty;
	})
	$('#ficheTotal').html(totalFiche+".00");
})

$('.tdsuccess').click(function(){
	$('.qty').each(function(){
		if($(this).text().length == 0)
		{
			$(this).text("1");
		}
		$(this).removeClass("qtyBorder");
	})
})

$('#validerFiche').click(function(){
	$('#ficheForm').submit();
})

$('#removeFiche').click(function(){
	$(this).parent().remove();
})

$('.removeFiche').click(function(){
	$(this).parent().remove();
})

$(".qty").click(function(){
	$('.qty').each(function(){
		$(this).removeClass("qtyBorder");
	})
	$(this).addClass("qtyBorder");
})

$('.tddanger').click(function(){
	console.log('here');
	var total = 0;
	$('.qtyBorder').each(function(){
		var price = $(this).parent().find(".price").text();
		if($(this).text().length == 0)
		{
			$(this).text("1");
		}
		if($(this).text().length == 1)
		{
			nouveau = 0;
		}
		else
		{
			var nouveau = $(this).text().substr(0, ($(this).text().length - 1));
		}
		var nouveau = $(this).text().substr(0, ($(this).text().length - 1));
		var total = price * nouveau;
		if(nouveau == 0)
		{
			nouveau = "";
		}
		$(this).text(nouveau);
		$(this).parent().find(".total").text(total+".00");
	})
	$('.ficheBody > tr').each(function(){
		var price = parseInt($(this).find(".price").text());
		if($(this).find(".qty").text() > 0)
		{
			var qty = parseInt($(this).find(".qty").text());
		}
		else
		{
			var qty = 0;
		}
		$(this).find(".name > input.qtyInput").val(qty);
		total = total + price*qty;
	})
	$('#ficheTotal').html(total+".00");
})

$('.number').click(function(){
	var chiffre = $(this).html();
	var total = 0;
	$('.qtyBorder').each(function(){
		var nouveau = $(this).html() + chiffre;
		var price = $(this).parent().find("#pricevalue").text();
		$(this).html(nouveau);
		var total = price * nouveau;
		$(this).html(nouveau);
		$(this).parent().find(".total").html(total+".00");
	})

	$('.ficheBody > tr').each(function(){
		var price = parseInt($(this).find("#pricevalue").html());
		if($(this).find(".qty").text() > 0)
		{
			var qty = parseInt($(this).find(".qty").html());
		}
		else
		{
			var qty = 0;
		}
		$(this).find(".name > input.qtyInput").val(qty);
		total = total + price*qty;
	})
	$('#ficheTotal').html(total+".00");
})

$('.rechercheCatalogueFiltre').keyup(function(){
	var value = $(this).val();
	$('.name').each(function(){
		if(!$(this).text().toLowerCase().match(value.toLowerCase()))
		{
			$(this).parent().hide();
		}
		else
		{
			$(this).parent().show();
		}
	})
	$('.catalogueTable').each(function(){
		var erase = true;
		$(this).find('table > tbody > tr').each(function(){
			if($(this).css("display") == "none" && erase == true)
			{

			}
			else
			{
				erase = false;
			}
		})
		if(erase)
		{
			$(this).hide();
		}
		else
		{
			$(this).show();
		}
	})
})

$('.editCat').dblclick(function(){
	var value = $(this).attr('id');
	for(var item in categories)
	{
		if(categories[item] == value)
		{
			category = category + "<option value='"+categories[item]+"' selected = 'selected'>"+item+"</option>";
		}
		else
		{
			category = category + "<option value='"+categories[item]+"'>"+item+"</option>";
		}
	}
	$(this).html(category_first + category + category_last);
	category='';
	$(this).find('select').change(function(){
					edit($(this), $(this).parent().parent().attr('id'), $(this).val(), 'category_id', $(this).parent().parent().attr('class'));
				})
})

	$("a.FaireChoix").on("click", function(event){
	    if ($(this).is("[disabled]")) {
	        event.preventDefault();
	        alert("Choississez d'abord un client en utilisant le champ de recherche du menu principal.")
	    }
	});

	$('.edit').dblclick(function(){
		var value = $(this).text();
		$(this).parent().removeClass('even');
		$(this).parent().removeClass('odd');
		if($(this).attr('id') == "user_status")
		{
			$(this).html(status);
			$(this).find('select > option[value="'+status_types[value]+'"]').attr('selected', true);
			$(this).find('select').addClass('editable');
			$(this).find('select').change(function(){
				edit($(this), $(this).parent().parent().attr('id'), $(this).val(), 'status', $(this).parent().parent().attr('class'));
			})
		}
		else
		{
			if($(this).attr('id') == "access")
			{
				$(this).html(access);
				$(this).find('select > option[value="'+access_types[value]+'"]').attr('selected', true);
				$(this).find('select').addClass('editable');
				$(this).find('select').change(function(){
					edit($(this), $(this).parent().parent().attr('id'), $(this).val(), 'access', $(this).parent().parent().attr('class'));
				})
			}
			else
			{
				if($(this).attr('id') == "telephone")
				{
					value = value.replace("+509 ", "");
				}
				if($(this).attr('id') == "plafond")
				{
					res = value.replace(" ", "");
					$(this).html("<input type='text' value='"+res+"' width='100%' class='form-client'>");
				}
				if($(this).attr('id') == "amount")
				{
					res = value.replace(" ", "");
					$(this).html("<input type='text' value='"+res+"' width='100%' class='form-client'>");
				}
				else
				{
					$(this).html("<input type='text' value='"+value+"' width='100%' class='form-client'>");
				}
				$(this).find('input').addClass('editable');
				$(this).find('input').change(function(){
					edit($(this), $(this).parent().parent().attr('id'), $(this).val(), $(this).parent().attr('id'), $(this).parent().parent().attr('class'));
				})
			}
		}
	})

	$('.commentSubmit').click(function(){
		$('body').append('<div style="position:absolute;background:black;opacity:0.3;top: 0;z-index: 1000;width: 100%;height: 100%;text-align: center;padding-top:210px" id="loadingbox"><img src="'+DIRECTORY_NAME+'/img/loading.gif" style="z-index:10000;"></div>')
		$.ajax({
            url:DIRECTORY_NAME+"/home/saveComment",
            type:"POST",
            data: { ident : $('#comment_id').val(), 
	            	firstname : $('#comment_firstname').val(), 
	            	lastname : $('#comment_lastname').val(), 
	            	url : $('#comment_url').val(),
	            	comment : $('#comment_comment').val() },
            success:function(data){
            	console.log(data);
            	$('#comment_comment').val('');
            	$('#comment_comment').attr('placeholder', "Votre commentaire a bien été sauvegardé...");
             	$('body').find('#loadingBox').remove();
            }
        })
	})

	function edit(element, id, valeur, champ, modele)
	{
		$('body').append('<div style="position:absolute;background:black;opacity:0.3;top: 0;z-index: 1000;width: 100%;height: 100%;text-align: center;padding-top:210px" id="loadingbox"><img src="'+DIRECTORY_NAME+'/img/loading.gif" style="z-index:10000;"></div>')
		$.ajax({
            url:DIRECTORY_NAME+"/"+modele+"/update",
            type:"POST",
            data: { ident : id, value : valeur, field : champ, model : modele },
            success:function(data){
            	// console.log(data);
            	if(champ == "telephone")
                {
                	element.parent().html("+509 "+valeur);
                }
                if(champ == "amount")
                {
                	var solde = parseFloat($('.soldeRetrait').html());
                	if(data != "false")
                	{
                		$('.soldeRetrait').html(data);
                		element.parent().html(valeur);

                	}
                	else
                	{
                		alert("Le montant que vous avez indiquez est supérieur à la balance du client")
                	}
                	
                }
                if(champ == "access")
                {
                	if(element.find('option[value="'+element.val()+'"]').text() == " -- Accès -- ")
					{
						element.parent().html("Admin");
					}
					else
					{
						element.parent().html(element.find('option[value="'+element.val()+'"]').html());
					}
                }
                if(champ == "status")
                {
                	if(element.find('option[value="'+element.val()+'"]').text() == "-- Statut --")
					{
						element.parent().html("Actif");
					}
					else
					{
						element.parent().html(element.find('option[value="'+element.val()+'"]').html());
					}
                }
                if(champ == "category_id")
                {
                	element.parent().html(element.find('option[value="'+element.val()+'"]').text());
                }
             	$('body').find('#loadingBox').remove();
            }
        })
	}

	$('.supprimerclient').click(function(event){
		if(!confirm("Etes vous sur de vouloir supprimer "+ $(this).parent().parent().find(".firstname").text() + " " + $(this).parent().parent().find(".lastname").text() + " de cette liste ?"))
		{
			event.preventDefault();
		}
	})

	$('.closeInputs').click(function(event){
		event.preventDefault();
		$('.edit > input.form-client').each(function(){
			$(this).parent().html($(this).val());
		});
		$('.edit > select.form-client').each(function(){
			$(this).parent().html($(this).find('option[selected="selected"]').text());
		});
	})

	$('.transactions').click(function(event){
		event.preventDefault();
		$('.table-transactions').show();
		$('.table-achats').hide();
		$(this).addClass('active');
		$('.achats').removeClass('active');
	})

	$('.achats').click(function(event){
		event.preventDefault();
		$('.table-transactions').hide();
		$('.table-achats').show();
		$(this).addClass('active');
		$('.transactions').removeClass('active');
	})

	$('.bg-danger').click(function(){
		$(this).fadeOut();
	})

	$('.bg-success').click(function(){
		$(this).fadeOut();
	})

	if($("#lastname").val().length > 0 || $("#firstname").val().length > 0)
	{
		$('.modification').show();
	}
} );