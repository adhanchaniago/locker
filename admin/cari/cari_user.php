<script src="../js/jquery-3.2.1.min.js"></script>
<script src="../js/materialize.min.js"></script>
<script src="../js/chart.min.js"></script>
<script src="../js/init.js"></script>
<script>
  $(document).ready(function() {
   $('.button-collapse').sideNav();
    // Initialize modal
    $('.modal').modal();

    // Initialize select list
    $('select').material_select();
	$('.datepicker').on('mousedown',function(event){
        event.preventDefault();
    }); 
    // Initialize datepicker
    $('.datepicker').pickadate({
	   
        format: 'yyyy-mm-dd',
        //min: dateToday
		min: 0
      });

    // Hide messagebox after 5 second
    setTimeout(function(){
      $('#msgBox').hide();
    }, 5000);
	
    // Search
    $('#search').on('keyup', function() {
        var value = $(this).val();
        var patt = new RegExp(value, "i");
          $('#myTable').find('tr').each(function() {
            if (!($(this).find('td').text().search(patt) >= 0)) {
              $(this).not('.myHead').hide();
            }
            if (($(this).find('td').text().search(patt) >= 0)) {
              $(this).show();
            }
          });
        });
		
		

  });
</script>
<script>
		
		 $(document).ready(function() {
		  <!-- event textbox keyup
		  $("#txtcari").keyup(function() {
		   var strcari = $("#txtcari").val();
		   if (strcari != "")
		   {
		   $("#tabel_awal").css("display", "none");

			$("#hasil").html("<img src='../img/loader.gif'/>")
			$.ajax({
			 type:"post",
			 url:"../admin/cari_user.php",
			 data:"q="+ strcari,
			 success: function(data){
			 $("#hasil").css("display", "block");
			  $("#hasil").html(data);
			  
			 }
			});
		   }
		   else{
		   $("#hasil").css("display", "none");
		   $("#tabel_awal").css("display", "block");
		   }
		  });
			});
</script>
</body>
</html>
