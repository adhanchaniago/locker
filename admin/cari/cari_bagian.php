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
    $("#code_bagian").change(function() {
		   var bagian = $("#code_bagian").val();
		   if (bagian != "")
		   {
		   $("#tabel_awal").css("display", "none");

			$("#lockerid").html("<img src='../img/loader.gif'/>")
			$.ajax({
			 type:"post",
			 url:"../admin/ambil-data.php",
			 data:"code_bagian="+ bagian,
			 success: function(data){
			 $("#lockerid").css("display", "block");
			  $("#lockerid").html(data);
			  
			 }
			});
		   }
		   else{
		   $("#lockerid").css("display", "none");
		   $("#tabel_awal").css("display", "block");
		   }
		  });
		
		

  });
</script>



</body>
</html>
