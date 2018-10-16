<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>BEAUTY PLUS</title>
    <!-- Styles -->
    <link href="{{ asset('template/css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('template/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('template/css/footer.css') }}" rel="stylesheet">
    <link href="{{ asset('template/css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css" rel="stylesheet">
    <!-- JQuery DataTable Css -->
    <link href="{{ asset('template/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css') }}" 
    rel="stylesheet" media="all">
    <!-- Sweet Alert Notif -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" rel="stylesheet">
    <!-- -->
    <!-- Air DatePicker -->
    <link href="{{ asset('air-datepicker/dist/css/datepicker.min.css') }}" rel="stylesheet" type="text/css">
    
</head>
<body>
    <!-- Fixed navbar -->
    
    <!-- End Fixed navbar -->

    <!-- Content -->
    @yield('content')
    <!-- End Content -->

    
    @include('includes.footer')

    <!-- Placed at the end of the document so the pages load faster -->
    <!--script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script-->

    <!-- jQuery 3 -->
    <script src="{{ asset('template/jquery/dist/jquery.min.js') }}"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="{{ asset('template/jquery-ui/jquery-ui.min.js') }}"></script>

    <!-- Bootstrap 3.3.7 -->
    <script src="{{ asset('template/bootstrap/dist/js/bootstrap.min.js') }}"></script>

    <script src="{{ asset('template/js/retina-1.1.0.js') }}"></script>
    <script src="{{ asset('template/js/jquery.hoverdir.js') }}"></script>
    <script src="{{ asset('template/js/jquery.hoverex.min.js') }}"></script>
    <script src="{{ asset('template/js/jquery.prettyPhoto.js') }}"></script>
    <script src="{{ asset('template/js/jquery.isotope.min.js') }}"></script>
    <script src="{{ asset('template/js/custom.js') }}"></script>

    <!-- Jquery DataTable Plugin Js -->
    <script src="{{ asset('template/jquery-datatable/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('template/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js') }}"></script>
    <script src="{{ asset('template/jquery-datatable/jquery-datatable.js') }}"></script>

    <!-- Sweet Alert Notif -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
    @include('sweet::alert')
    <!-- -->

    <!-- Air DatePicker -->
    <script src="{{ asset('air-datepicker/dist/js/datepicker.min.js') }}"></script>
    <script src="{{ asset('air-datepicker/dist/js/i18n/datepicker.en.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js"></script>    

    <script>
        //for walkin customer
        $('#addHomeReservation').datepicker({
            position: 'bottom center',
            language: 'en',
            minDate: new Date(), // Now can select only dates, which goes after today
        })
    </script>
    
    <!-- DATA TABLES -->
    <script type="text/javascript">

        //Admin
        $('#users-table').dataTable( {
            "pageLength": 5,
            "aLengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
            "order": [[ 0, "desc" ]] // column index, order ex. descending, ascending
        });

        $('#roles-table').dataTable( {
            "pageLength": 5,
            "aLengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
            "order": [[ 2, "desc" ]] // column index, order ex. descending, ascending
        });

        $('#viewAllWalkin-table').dataTable( {
            "pageLength": 5,
            "aLengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
            "order": [[ 0, "desc" ]] // column index, order ex. descending, ascending
        });

        $('#employees-table').dataTable( {
            "pageLength": 5,
            "aLengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
            "order": [[ 0, "desc" ]] // column index, order ex. descending, ascending
        });

        $('#adminViewAllReservations-table').dataTable( {
            "pageLength": 5,
            "aLengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
            "order": [[ 0, "desc" ]] // column index, order ex. descending, ascending
        });

        $('#adminViewAllBilling-table').dataTable( {
            "pageLength": 5,
            "aLengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
            "order": [[ 0, "desc" ]] // column index, order ex. descending, ascending
        });

        $('#adminViewAllPayments-table').dataTable( {
            "pageLength": 5,
            "aLengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
            "order": [[ 4, "asc" ]] // column index, order ex. descending, ascending
        });

        $('#adminViewAllWalkinPayments-table').dataTable( {
            "pageLength": 5,
            "aLengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
            "order": [[ 4, "desc" ]] // column index, order ex. descending, ascending
        });

        $('#adminViewAllServiceTypes-table').dataTable( {
            "pageLength": 5,
            "aLengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
            "order": [[ 2, "desc" ]] // column index, order ex. descending, ascending
        });

        $('#adminViewAllServices-table').dataTable( {
            "pageLength": 5,
            "aLengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
            "order": [[ 4, "desc" ]] // column index, order ex. descending, ascending
        });

        $('#adminPayReservationBilling-table').dataTable( {
            "pageLength": 10,
            "aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "order": [[ 2, "desc" ]] // column index, order ex. descending, ascending
        });

        $('#adminPayWalkinBilling-table').dataTable( {
            "pageLength": 10,
            "aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "order": [[ 2, "desc" ]] // column index, order ex. descending, ascending
        });

        $('#adminViewEmployeeCommissions-table').dataTable( {
            "pageLength": 5,
            "aLengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
            "order": [[ 0, "desc" ]] // column index, order ex. descending, ascending
        });

        $('#adminViewAllExpertise-table').dataTable( {
            "pageLength": 5,
            "aLengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
            "order": [[ 3, "desc" ]] // column index, order ex. descending, ascending
        });

        $('#adminViewEmployeeSalary-table').dataTable( {
            "pageLength": 5,
            "aLengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
            "order": [[ 4, "desc" ]] // column index, order ex. descending, ascending
        });

        //Employees
        $('#employeeViewAllReservations-table').dataTable( {
            "pageLength": 5,
            "aLengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
            "order": [[ 8, "desc" ]] // column index, order ex. descending, ascending
        });

        $('#employeeViewAllCommissions-table').dataTable( {
            "pageLength": 5,
            "aLengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
            "order": [[ 0, "desc" ]] // column index, order ex. descending, ascending
        });

        $('#employeeViewAllInfractions-table').dataTable( {
            "pageLength": 5,
            "aLengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
            "order": [[ 0, "desc" ]] // column index, order ex. descending, ascending
        });

        //Customer
        $('#customerViewAllReservations-table').dataTable( {
            "pageLength": 5,
            "aLengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
            "order": [[ 9, "desc" ]] // column index, order ex. descending, ascending
        });

        $('#customerViewAllPayments-table').dataTable( {
            "pageLength": 5,
            "aLengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
            "order": [[ 3, "desc" ]] // column index, order ex. descending, ascending
        });
    </script>
    
    <!-- USERS CREATE MODULE -->
    <script>
        $('#createUsersRoleID').on('change',function(){
            if( $(this).val()==="3"){
                $("#employeeSalary").show()
                $("#employeeExpertise").show()
                document.getElementById("employee_salary").required = true;
                document.getElementById("expertise_id").required = true;
            } else{
                $("#employeeSalary").hide()
                $("#employeeExpertise").hide()
                document.getElementById("employee_salary").required = false;
                document.getElementById("expertise_id").required = false;
            }
        });
    </script>

    <script type="text/javascript">
        $('#printWalkinReceiptBtn').click(function() {
            var current_time = new Date().toLocaleTimeString();
            var timestamp = new Date().toJSON().slice(0,10);
            document.title = 'BeautyPlusSalon-Receipt-'+timestamp+' '+current_time+'.pdf';
            window.print();
        });

        $('#printReservationReceiptBtn').click(function() {
            var current_time = new Date().toLocaleTimeString();
            var timestamp = new Date().toJSON().slice(0,10);
            document.title = 'BeautyPlusSalon-Receipt-'+timestamp+' '+current_time+'.pdf';
            window.print();
        });
    </script>

    <script>
        $('#selectWalkinEmpId').on('change', function() {
            $('.sample').hide();
            $(this).find('option:selected').each(function() {
                var id = $(this).data("id");
                document.getElementById(id).style.display = "block";
            })
        })

        $('#SelectHomeServiceEmpId').on('change', function() {
            $('.sample1').hide();
            $(this).find('option:selected').each(function() {
                var id1 = $(this).data("id");
                document.getElementById(id1).style.display = "block";
            })
        })

        $('#SelectOnSalonEmpId').on('change', function() {
            $('.sample2').hide();
            $(this).find('option:selected').each(function() {
                var id2 = $(this).data("id");
                document.getElementById(id2).style.display = "block";
            })
        })

        //Customer
        $('#CustomerSelectHomeServiceEmpId').on('change', function() {
            $('.sample3').hide();
            $(this).find('option:selected').each(function() {
                var id3 = $(this).data("id");
                document.getElementById(id3).style.display = "block";
            })
        })

        $('#CustomerSelectOnSalonEmpId').on('change', function() {
            $('.sample4').hide();
            $(this).find('option:selected').each(function() {
                var id4 = $(this).data("id");
                document.getElementById(id4).style.display = "block";
            })
        })
    </script>

</body>
</html>
