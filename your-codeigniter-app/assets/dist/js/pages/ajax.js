$(function () {
    'use strict';

    $('#vAssigned_Facility_ID').on('change', function(){
        $.ajax({
            type: 'GET',
            url: APP_URL+'admin/customers/getCustomersByFacility/'+this.value,
            success: function (data) {
                data = JSON.parse(data);
                if(data.success){
                    var customers = data.customers;
                    // console.log(customers);
                    var users = $('#vAssigned_User_ID');
                    // alert(users.val());
                    users.empty();
                    users.append('<option>-Select User-</option>')
                    for (var i = 0; i < customers.length; i++) {
                        users.append('<option value=' + customers[i].iId + '>' + customers[i].vFirstName + ' ' + customers[i].vLastName + '</option>');
                    }
                    users.change();
                }
            }
        });
    })

    $('#vFacility').on('change',function(){
        fetchCustomerBedge(this.value);
    });

    $('#vFacilityFilter').on('change',function(){
        fetchCustomers();
    });

    $('#vFacilityFilterDevice').on('change',function(){
        fetchDevices();
    });

    $('#vFacilityFilterReader').on('change',function(){
        fetchReaders();
    });

});

function fetchCustomers(hideUnassignedUsers=false) {
    $("#customer-table").dataTable().fnDestroy();
    let facility = $('#vFacilityFilter').val();
    // let hideUnassignedUsers = $('#hideUnassignedUsers').val();
    $('#customer-table').DataTable({
        "processing": true,
        "serverSide": true,
        "pageLength": 10,
        "order": [],
        "ajax": {
            "url": APP_URL+'admin/customers/customerlist_dt/'+facility+'?hideUnassignedUsers='+hideUnassignedUsers,
            "type": "POST"
        },
        "columnDefs": [
            { 
                "targets": [0],
                "orderable": false
            }
        ]
    });
}

function fetchDevices(){
    $("#devices-table").dataTable().fnDestroy();
    let facility = $('#vFacilityFilterDevice').val();
    let deviceTable = $('#devices-table').DataTable({
        "processing": true,
        "serverSide": true,
        "order": [],
        "ajax": {
            "url": APP_URL+'admin/devices/devicelist_dt/'+facility,
            "type": "POST"
        },
        "columnDefs": [
            { 
                "targets": [0],
                "orderable": false
            }
        ]
    });
}

function fetchReaders(){
    $("#readers-table").dataTable().fnDestroy();
    let facility = $('#vFacilityFilterReader').val();
    let readerTable = $('#readers-table').DataTable({
        "processing": true,
        "serverSide": true,
        "order": [],
        "ajax": {
            "url": APP_URL+'admin/beacons/readerslist_dt/'+facility,
            "type": "POST"
        },
        "columnDefs": [
            {
                "targets": [0],
                "orderable": false
            }
        ]
    });
}

function fetchDeviceHistory(){
    $("#history-table").dataTable().fnDestroy();
    let device = $('#deviceSerial').val();
    let historyTable = $('#history-table').DataTable({
        "processing": true,
        "serverSide": true,
        "order": [],
        "ajax": {
            "url": APP_URL+'admin/devices/historylist_dt/'+device,
            "type": "POST"
        },
        "columnDefs": [
            { 
                "targets": [0],
                "orderable": false
            }
        ]
    });
}


function fetchCustDeviceHistory(){
    $("#history-table").dataTable().fnDestroy();
    let user = $('#user_id').val();
    let userTable = $('#history-table').DataTable({
        "processing": true,
        "serverSide": true,
        "order": [],
        "ajax": {
            "url": APP_URL+'admin/customers/historylist_dt/'+user,
            "type": "POST"
        },
        "columnDefs": [
            {
                "targets": [0],
                "orderable": false
            }
        ]
    });
}

function fetchGateways(){
    $("#gateways-table").dataTable().fnDestroy();
    let readerTable = $('#gateways-table').DataTable({
        "processing": true,
        "serverSide": true,
        "order": [],
        "ajax": {
            "url": APP_URL+'admin/gateway/gatewaylist_dt',
            "type": "POST"
        },
        "columnDefs": [
            { 
                "targets": [0],
                "orderable": false
            }
        ]
    });
}
