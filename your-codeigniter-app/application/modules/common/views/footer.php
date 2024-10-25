  </div>
    <!-- /.container -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <div class="container">
      <div class="pull-right hidden-xs">
        <b>Version</b> 9.0.0
      </div>
      <strong>Copyright &copy; <?= date('Y'); ?>.</strong> All rights reserved.
    </div>
<!--
    <a href="#">BADGE</a>
-->
    <!-- /.container -->
  </footer>
</div>
<!-- ./wrapper -->

<!-- jQuery 3 -->
<script src="<?=base_url('assets');?>/bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="<?=base_url('assets');?>/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="<?=base_url('assets');?>/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="<?=base_url('assets');?>/bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="<?=base_url('assets');?>/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?=base_url('assets');?>/dist/js/demo.js"></script>
<!-- DataTables -->
<script src="<?=base_url('assets');?>/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?=base_url('assets');?>/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="https://npmcdn.com/masonry-layout@4.0/dist/masonry.pkgd.js"></script>
<script src="//platform.twitter.com/widgets.js" charset="utf-8"></script>
<!-- Select2 -->
<script src="<?=base_url('assets');?>/bower_components/select2/dist/js/select2.full.min.js"></script>
<script src="<?=base_url('assets');?>/dist/js/pages/ajax.js?v=2"></script>
<script src="//cdn.datatables.net/plug-ins/1.10.21/pagination/input.js" charset="utf-8"></script>
<?php # if($vSerial!=''){echo "<span class='hidden' style=' visibility:hidden'>/".$vSerial."</span>";}?>
<script>
function fetchCustomerBedge(vFacility){
  var vSerial = $('#vAssigned_vSerial').val();
    $.ajax({
        url:"<?php echo base_url(); ?>admin/Customers/fetch_BadgeSerial/" + vSerial,
        method:"POST",
        data:{vFacility:vFacility},
        success:function(data)
        {
         $('#Badge').html(data);
        }
    });
}
	$(document).ready(function(){
	 $('#events_').DataTable( {
		"processing": true,
        "serverSide": true,
		"pageLength": 10,
		'serverMethod': 'post',
        "ajax": "<?php echo base_url(); ?>admin/AjaxController/fetchEventForDatatables",
        "columns": [
            { "data": "iId"},
			      // {
            //     "orderable":      false,
            //     "data":           null,
            //     "defaultContent": ''
            // },
			      // {
            //     "orderable":      false,
            //     "data":           null,
            //     "defaultContent": ''
            // },
			      // {
            //     "orderable":      false,
            //     "data":           null,
            //     "defaultContent": ''
            // },
			      { "data": "vBadge_serial"},
			      { "data": "vReader_serial"},
            { "data": "iEvent_num"},
            { "data": "vEvent_type"},
            // {
            //     "orderable":      false,
            //     "data":           null,
            //     "defaultContent": ''
            // },
            // {
            //     "orderable":      false,
            //     "data":           null,
            //     "defaultContent": ''
            // },
			      { "data": "tTimestamp" },
			      { "data": "tRaw_save_time" },
          ],
		      "order": [ 0, 'desc' ]
        });
    
	$('#prox_events').DataTable( {
		"processing": true,
        "serverSide": true,
		"pageLength": 10,
		'serverMethod': 'post',
        "ajax": "<?php echo base_url(); ?>admin/AjaxController/fetchProxForDatatables",
        "columns": [
            { "data": "id"},
			{"data": "time_stamp"},
			{"data": "peer_id"},
			{"data": "event_type"},
			{ "data": "badge_serial"},
			{ "data": "created_at"},
            {"data": "updated_at"}
        ],
		"order": [ 0, 'desc' ]
    });
	
;
  if(vFacility != '') {
    var vFacility = $('#vFacility').val();
    fetchCustomerBedge(vFacility);
  }
 
  
//  $('#vFacility').on('change',function(){
//   var vFacility = $('#vFacility').val();
//   if(vFacility != '')
//   {
// 	  console.log(vFacility);
//    $.ajax({
//     url:"<?php// echo base_url(); ?>admin/Customers/fetch_BadgeSerial/$vSerial",
//     method:"POST",
//     data:{vFacility:vFacility},
//     success:function(data)
//     {
//      $('#Badge').html(data);
//     }
//    });
//   }
//   else
//   {
//    $('#Badge').html('<option value="" disabled selected>-Select Badge Serial-</option>');
//   }
//  });

});
	
  $(function () {
   
   $('.select2').select2();

    $(".alert").fadeTo(2000, 500).slideUp(500, function(){
   $(".alert").slideUp(1000);
    });   
    $('#example1').DataTable(

    	);
    $('.datatable').DataTable();


    var url = window.location.pathname, 
        urlRegExp = new RegExp(url.replace(/\/$/,'') + "$"); // create regexp to match current url pathname and remove trailing slash if present as it could collide with the link in navigation in case trailing slash wasn't present there
        // now grab every link from the navigation
         var current = location.pathname;
      $('.navbar-nav a').each(function(){
          var $this = $(this);
          // if the current path is like this link, make it active
          if($this.attr('href').indexOf(current) !== -1){
              $this.parent('li').addClass('active');
          }
      });

       // Twitter widget is loading.
           window.twttr = function (d, s, id) {
           var t, js, fjs = d.getElementsByTagName(s)[0];
           if (d.getElementById(id)) return; js = d.createElement(s); js.id = id;
           js.src = "https://platform.twitter.com/widgets.js";
           fjs.parentNode.insertBefore(js, fjs);
           return window.twttr || (t = { _e: [], ready: function (f) { t._e.push(f) } });
           }(document, 'script', 'twitter-wjs');
          // window.twttr.widgets.load();
         
         // When widget is ready, run masonry
         var $container='';
         twttr.ready(function (twttr) {
           twttr.events.bind('loaded', function (event) {
           $container= $('.grid').masonry({
               "itemSelector" : '.grid-item',
                "columnWidth": ".grid-sizer",
               "transitionDuration": 0, 
              "percentPosition": true, 
               "gutter": 20
             });
           });
         });

         $("#btn_updt").click(function(){
          $(this).css('display','none');
        
          $('img.loadImage').css('display','block');
         });
         $("#btn_upd_pref").click(function(){
          $(this).css('display','none');
          $('img.loadImages').css('display','block');
         });

         $("#loadmore").click(function(event_ref){
            event_ref.preventDefault();
            var since_id = $('#since_id').val();
              $(".alert-2").hide(1000);
            //var url = "<?=base_url('customers/dashboard/getMoreTweets')?>";
            var url = "<?=base_url('customers/dashboard/getMoreTweetsFromDB')?>";
               
            
             $("#loadImage").css("display","block");
                $("#loadmore").css("display","none");

               $.ajax({
                   url: url,
                   data: {'since_id':since_id, '<?php echo $this->security->get_csrf_token_name(); ?>': $('#csrfHash').val()},
                   type: 'post',
                   dataType: 'JSON',
                   success:function(data){
                       
                       if(data)
                       {
                        $('#csrfHash').val(data.csrfHash);
                        $('#since_id').val(data.since_id);
                        var html=data.resultData;
                                              
                        console.log(html);
                         
                        var $moreBlocks = $(html);
                        $($moreBlocks).hide().appendTo($container).fadeIn(1000);
                        
                          $container.masonry( 'appended', $moreBlocks );
                          window.twttr.widgets.load();
                          $("#loadImage").css("display","none");
                          $("#loadmore").css("display","inline-block");
                           
                       }
                   }
               });
               });
          $('.editvScreenName').click(function(){
              var iId=$(this).attr('data-iId');
               $.ajax({
                  url: "<?=base_url('admin/preference/getHandle/');?>"+iId,
                  type: "get",
                  dataType:'json',
                  success: function (response) {
                    $('#edit_iId').val(response.iId);
                    $('#edit_vScreenName').val(response.vScreenName);
                    $('#edit_iStateId').val(response.iStateId);
                    $('#edit_handles').modal('show');
                  },
                  error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                  }


              });
          });
          $('.editvStateName').click(function(){
              var iId=$(this).attr('data-iId');
              console.log(iId);
               $.ajax({
                  url: "<?=base_url('admin/preference/getState/');?>"+iId,
                  type: "get",
                  dataType:'json',
                  success: function (response) {
                    console.log(response);
                    $('#edit_state_iId').val(response.iId);
                    $('#edit_vStateName').val(response.vStateName);
                  
                    $('#edit_state').modal('show');
                  },
                  error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                  }


              });
          });

 });

//  window.onscroll = function() {scrollFunction()};

function scrollFunction() {
  if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
    document.getElementById("myBtn").style.display = "block";
  } else {
    document.getElementById("myBtn").style.display = "none";
  }
}

function topFunction() {
  //document.body.scrollTop = 0;
 // document.documentElement.scrollTop = 0;
   $("html, body").animate({scrollTop: 0}, 1000);
}

// Fatching facility name and badge serial dynamically 


$(document).ready(function(){
    $('#uploaded_users_table').DataTable({
        "processing": true,
        "serverSide": true,
        "order": [],
        "ajax": {
            "url": "<?php echo base_url('admin/customers/uploadedlist_dt/'.@$upload['id']); ?>",
            "type": "POST"
        },
        "columnDefs": [{ 
            "targets": [0],
            "orderable": false
        }]
    });
});

function ingnoreuser(ele){
  let usr = $(ele).attr('id')
  let ignore = 0;
  if($(ele).prop("checked") == true){
      ignore = 1;
  }
  else if($(ele).prop("checked") == false){
      ignore = 0;
  }
  $.ajax({
    url: "<?=base_url('admin/customers/ignoreuploaded');?>",
    type: "post",
    dataType:'json',
    data : {
      user: usr, 
      ignore:ignore
    },
    success: function (response) {
    },
    error: function(jqXHR, textStatus, errorThrown) {
      console.log(textStatus, errorThrown);
    }
  });
}

function ingnorereader(ele){
  let rdr = $(ele).attr('id')
  let ignore = 0;
  if($(ele).prop("checked") == true){
      ignore = 1;
  }
  else if($(ele).prop("checked") == false){
      ignore = 0;
  }
  $.ajax({
    url: "<?=base_url('admin/beacons/ignoreuploaded');?>",
    type: "post",
    dataType:'json',
    data : {
      reader: rdr, 
      ignore:ignore
    },
    success: function (response) {
    },
    error: function(jqXHR, textStatus, errorThrown) {
      console.log(textStatus, errorThrown);
    }
  });
}

$(document).ready(function(){
    $('#uploaded_readers_table').DataTable({
        "processing": true,
        "serverSide": true,
        "order": [],
        "ajax": {
            "url": "<?php echo base_url('admin/beacons/uploadedlist_dt/'.@$upload['id']); ?>",
            "type": "POST"
        },
        "columnDefs": [{ 
            "targets": [0],
            "orderable": false
        }]
    });
});

$('#tobodeleted').DataTable({
  "processing": true,
});

$('#bulkDeleteBy').on('change', function() {
    window.location.href = this.value;
});

$('#rserialType').on('change', function() {
    window.location.href = this.value;
});
$('#bserialType').on('change', function() {
    window.location.href = this.value;
});

function ingnoreDeleUser(ele){
  let usr = $(ele).attr('id')
  let ignore = 0;
  if($(ele).prop("checked") == true){
      ignore = 1;
  }else if($(ele).prop("checked") == false){
      ignore = 0;
  }
  $.ajax({
    url: "<?=base_url('admin/customers/ignoredeleteduser');?>",
    type: "post",
    dataType:'json',
    data : {
      user: usr, 
      ignore:ignore
    },
    success: function (response) {
    },
    error: function(jqXHR, textStatus, errorThrown) {
      console.log(textStatus, errorThrown);
    }
  });
}

function ingnoreDeleDevice(ele){
  let dvc = $(ele).attr('id')
  let ignore = 0;
  if($(ele).prop("checked") == true){
      ignore = 1;
  }else if($(ele).prop("checked") == false){
      ignore = 0;
  }
  $.ajax({
    url: "<?=base_url('admin/devices/ignoredeleteddevice');?>",
    type: "post",
    dataType:'json',
    data : {
      device: dvc, 
      ignore:ignore
    },
    success: function (response) {
    },
    error: function(jqXHR, textStatus, errorThrown) {
      console.log(textStatus, errorThrown);
    }
  });
}

function ingnoreDeleEvent(ele){
  let evt = $(ele).attr('id')
  let ignore = 0;
  if($(ele).prop("checked") == true){
      ignore = 1;
  }else if($(ele).prop("checked") == false){
      ignore = 0;
  }
  $.ajax({
    url: "<?=base_url('admin/events/ignoredeletedevent');?>",
    type: "post",
    dataType:'json',
    data : {
      event: evt, 
      ignore:ignore
    },
    success: function (response) {
    },
    error: function(jqXHR, textStatus, errorThrown) {
      console.log(textStatus, errorThrown);
    }
  });
}

$(document).ready(function(){
    $('#uploaded_devices_table').DataTable({
        "processing": true,
        "serverSide": true,
        "order": [],
        "ajax": {
            "url": "<?php echo base_url('admin/devices/uploadedlist_dt/'.@$upload['id']); ?>",
            "type": "POST"
        },
        "columnDefs": [{ 
            "targets": [0],
            "orderable": false
        }]
    });
});

function ingnoredevice(ele){
  let dvc = $(ele).attr('id')
  let ignore = 0;
  if($(ele).prop("checked") == true){
      ignore = 1;
  }
  else if($(ele).prop("checked") == false){
      ignore = 0;
  }
  $.ajax({
    url: "<?=base_url('admin/devices/ignoreuploaded');?>",
    type: "post",
    dataType:'json',
    data : {
      device: dvc, 
      ignore:ignore
    },
    success: function (response) {
    },
    error: function(jqXHR, textStatus, errorThrown) {
      console.log(textStatus, errorThrown);
    }
  });
}

function ignoreunit(ele){
  let unit = $(ele).attr('id')
  let ignore = 0;
  if($(ele).prop("checked") == true){
      ignore = 1;
  }
  else if($(ele).prop("checked") == false){
      ignore = 0;
  }
  $.ajax({
    url: "<?=base_url('admin/units/ignoreuploaded');?>",
    type: "post",
    dataType:'json',
    data : {
      unit: unit,
      ignore:ignore
    },
    success: function (response) {
    },
    error: function(jqXHR, textStatus, errorThrown) {
      console.log(textStatus, errorThrown);
    }
  });
}
$(document).ready(function(){
    $('#uploaded_units_table').DataTable({
        "processing": true,
        "serverSide": true,
        "order": [],
        "ajax": {
            "url": "<?php echo base_url('admin/units/uploadedlist_dt/'.@$upload['id']); ?>",
            "type": "POST"
        },
        "columnDefs": [{ 
            "targets": [0],
            "orderable": false
        }]
    });
});
</script>
</body>
</html>
