<script src="../admin/js/jquery-3.2.1.min.js"></script>
<script src="..admin/js/materialize.min.js"></script>
<script src="..admin/js/chart.min.js"></script>
<script src="..admin/js/init.js"></script>

<script>
  $(document).ready(function() {
   
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
		
		
$(function () {
	var check_all = $('#toggle_all_checkboxes');
  check_all.on('click', function () {
  	var ul = $(this).parents('.row').find('ul'),
    		li = ul.find('li:not(".disabled")');
        
    $(li).each(function () {
			$(this).trigger('click');
    });
  });

  $('select').material_select();
});
</script>

</body>
</html>
