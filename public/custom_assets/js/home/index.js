$( document ).ready(function() {

    $('#building-home-table').DataTable({
		"language": {
		    "lengthMenu": "Display _MENU_ records per page",
		    "zeroRecords": "Nothing found - sorry",
		    "info": "Showing page _PAGE_ of _PAGES_",
		    "infoEmpty": "No records available",
		    "infoFiltered": "(filtered from _MAX_ total records)",
			"order":[[ 0, "desc" ]]
		}
    });

    $('#industry-home-table').DataTable({
		"language": {
		    "lengthMenu": "Display _MENU_ records per page",
		    "zeroRecords": "Nothing found - sorry",
		    "info": "Showing page _PAGE_ of _PAGES_",
		    "infoEmpty": "No records available",
		    "infoFiltered": "(filtered from _MAX_ total records)",
			"order":[[ 0, "desc" ]]
		}
    });
    
});