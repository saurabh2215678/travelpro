<!DOCTYPE html>
<html>
    <head>
        <title>Be right back.</title>
        <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">

        <style>
            html, body { height: 100%; }
            body {
                margin: 0;
                padding: 0;
                width: 100%;
                color: #B0BEC5;
                display: table;
                font-weight: 100;
                font-family: 'Lato';
            }
            .container {
                text-align: center;
                display: table-cell;
                vertical-align: middle;
            }
            .content {
                text-align: center;
                display: inline-block;
            }
            .title {
                font-size: 72px;
                margin-bottom: 40px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="content">
                <div class="title">419 | PAGE EXPIRED</div>
                <h2>You came to this page due to long time inactivity on your previous page. Please go back and refresh the page and do action.</h2>
                <!-- <h3><a href="{{ url('') }}">Back to Home</a></h3> -->
                <h3><a href="javascript:void(0);history.go(-1);">Back to Previous Page</a></h3>
            </div>
        </div>
    </body>
    <script type="text/javascript">
    setTimeout(function(){
        window.location.href = "{{ url('/') }}";
    },3000);
    </script>
</html>
