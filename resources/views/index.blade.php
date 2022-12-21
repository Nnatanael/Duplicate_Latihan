<html lang="en">
    <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">


    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" >

    <!-- Datatables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.css">
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.js" ></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" ></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" ></script>

    <!-- Dtatables JS -->
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.js"></script>


    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <title>NALDO DUP</title>
</head>
<body>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-sm-10">
            <div class="card">
                <div class="card-body shadow-lg">
                    <form>
                        <div class="form-row">
                            <div class="col-sm-4">
                                <input type="text" class="form-control" placeholder="Enter name" id="userName">
                            </div>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" placeholder="Enter email" id="userEmail">
                            </div>
                            <div class="col-sm-2" id="saveBtn" style="">
                                <button type="button" class="btn btn-primary btn-block" onclick="saveData()">Save</button>
                            </div>
                            <div class="col-sm-2" id="updateBtn" style="display: none;">
                                <button type="button" class="btn btn-success btn-block" onclick="update()">Update</button>
                            </div>
                            <div class="col-sm-2" id="cancelBtn" style="display: none;">
                                <button type="button" class="btn btn-danger btn-block" onclick="cancel()">Cancel</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid mt-3">
    <div class="row justify-content-center">
        <div class="col-sm-8">
            <div class="card  shadow-lg">
                <div class="card-header bg-primary text-white">
                    List of Data
                </div>
                <div class="card-body">
                    <div>                
                    <table id="tableData" class="table table-sm table-bordered dataTable no-footer" aria-describedby="tableData_info">
                        <thead>
                        <tr>
                            <th class="sorting" tabindex="0" >
                                 Name
                            </th>
                            <th class="sorting" tabindex="0" >
                                 email
                            </th>
                            <th class="sorting" tabindex="0" >
                                Action
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table> 
                </div>
                </div>
            </div>
        </div>
    </div>
</div>





<script>
    const tableData = $('#tableData').DataTable({
        order:false
    });

    const saveBtn = $('#saveBtn');
    const updateBtn = $('#updateBtn');
    const cancelBtn = $('#cancelBtn');
    let globalUserId = '';

    $(document).ready(function () {
        $('#userName').focus()
        viewData();
    });

    ajaxSetup = () => {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    };

    function viewData(){
        saveBtn.show();
        updateBtn.hide();
        cancelBtn.hide();

        $('#userName').attr('disabled', false)
        $('#userName').val('');
        $('#userEmail').val('');

        ajaxSetup()
        $.ajax({
            url: "http://127.0.0.1:8000/api/View",
            method: 'get',
            data: {},
            beforeSend: function () {
                tableData.clear();
            },
            success: function (result) {
                console.log(result)
                if (result.data.length > 0) {
                    for (let i = 0; i < result.data.length; i++) {
                        tableData.row.add(
                            [result.data[i]['nama'].toUpperCase(), result.data[i]['email'], `<button class="btn btn-danger" onclick="deleteData(${result.data[i].id})">Delete</button> <button class="btn btn-success" onclick="editData(${result.data[i].id})">Update</button>` ]).draw();
                    }
                    console.log(tableData)
                }
                
            }, error: function (error) {
                console.log(error);
            }
        })
    }

    function saveData(){
        const userName = $('#userName').val();
        const userEmail = $('#userEmail').val();
        const validRegex = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;

        if(userName.length < 1 || userEmail.length < 1) {
            swal("Warning", "Form can't be empty", 'info');
            $('#userName').focus();
            return false;
        } else if (!userEmail.match(validRegex)){
            swal("Warning", "Invalid email address", 'info');
            $('#userEmail').focus();
            return false;
        }

        ajaxSetup();
        $.ajax({
            url: "http://127.0.0.1:8000/api/Create",
            method: 'post',
            data: {userName, userEmail},
            success: function (result) {
                if(result.status == 200){
                    swal("Success", result.message, 'success')
                } else {
                    swal("Something's wrong!", result.message, 'warning')
                }
                viewData();
                $('#userName').focus()
            }, error: function (error) {
                console.log(error);
            }
        })
    }

    function editData(userId) {
        ajaxSetup();
        $.ajax({
            url: "http://127.0.0.1:8000/api/Find",
            method: 'post',
            data: {userId},
            success: function (result) {
                if(result.status == 200){
                    saveBtn.hide();
                    updateBtn.show();
                    cancelBtn.show();

                    $('#userName').attr('disabled', true)
                    $('#userName').val(result.data['nama']);
                    $('#userEmail').val(result.data['email']);
                    globalUserId = result.data['id'];
                } else {
                    swal("Something's wrong!", result.message, 'warning')
                }
            }, error: function (error) {
                console.log(error);
            }
        })
    }

    function update(){
        const userId = globalUserId
        const userEmail = $('#userEmail').val();
        const validRegex = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;

        if (!userEmail.match(validRegex)){
            swal("Warning", "Invalid email address", 'info');
            $('#userEmail').focus();
            return false;
        }

        swal("Are You Sure Want to Update Data?", {
            icon: 'warning',
            buttons: {
                cancel: {
                    text: "Cancel",
                    value: null,
                    visible: true,
                    className: 'bg-danger text-white',
                    closeModal: true,
                },
                confirm: {
                    text: "Update",
                    value: "confirm",
                    visible: true,
                    className: "",
                }
            },
        }).then((value) => {
            switch (value) {
                case "confirm":
                    ajaxSetup();
                    $.ajax({
                        url: "http://127.0.0.1:8000/api/Update",
                        method: 'post',
                        data: {userId, userEmail},
                        beforeSend: function () {},
                        success: function (result) {
                            if(result.status == 200){
                                swal("Success", result.message, 'success')
                            } else {
                                swal("Something's wrong!", result.message, 'warning')
                            }
                            viewData();
                        }, error: function (error) {
                            console.log(error);
                        }
                    });
                    break;
                default:
                    console.log("Cancel!");
            }
        });
    }

    function cancel(){
        viewData()
    }

    function deleteData(userId){
        swal("Are You Sure Want to Delete Data?", {
            icon: 'warning',
            buttons: {
                cancel: {
                    text: "Cancel",
                    value: null,
                    visible: true,
                    className: 'bg-danger text-white',
                    closeModal: true,
                },
                confirm: {
                    text: "Delete",
                    value: "confirm",
                    visible: true,
                    className: "",
                }
            },
        }).then((value) => {
            switch (value) {
                case "confirm":
                    ajaxSetup();
                    $.ajax({
                        url: "http://127.0.0.1:8000/api/Delete",
                        method: 'post',
                        data: {userId},
                        beforeSend: function () {},
                        success: function (result) {
                            if(result.status == 200){
                                swal("Success", result.message, 'success')
                            } else {
                                swal("Something's wrong!", result.message, 'warning')
                            }
                            viewData();
                        }, error: function (error) {
                            console.log(error);
                        }
                    })
                    break;
                default:
                    console.log("Cancel!");
            }
        });
    }

</script>
</body>
</html>