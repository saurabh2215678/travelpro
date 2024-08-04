<!DOCTYPE html>
<html>
<title>License Activate</title>
<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700" rel="stylesheet">

<link rel="stylesheet" type="text/css"
    href="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.2/css/bootstrap.min.css" />
<script type="text/javascript" src="//code.jquery.com/jquery-1.10.1.min.js"></script>
<script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>

<style>
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    .bodybg {
        height: 100vh;
        font-family: 'Open Sans', sans-serif;
        color: #444550;
        background: url('assets/activate-bg.png');
        background-position: center center;
        background-size: cover;
        background-repeat: no-repeat;
        position: relative;
        z-index: 0;
    }

    .bodybg:before {
        content: '';
        position: absolute;
        background: rgba(0, 0, 0, .6);
        width: 100%;
        height: 100%;
        z-index: -1;
    }

    .boxbg {
        display: flex;
        background: #fff;
        align-items: center;
    }

    .loglogo {
        position: fixed;
        margin: 20px 35px;
        top: 0;
        left: 0;
        float: left;
    }

    .loginmain {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 700px;
        box-sizing: border-box;

        overflow: hidden;
        box-shadow: 0 15px 25px rgb(0 0 0 / 50%);
        border-radius: 10px;
        overflow-y: auto;

    }

    .logbg {
        width: 50%;
        background: #fff;
        color: #2e2e2e;
    }

    .logtitle {
        margin: 0;
        padding: 10px 15px;
        font-weight: 600;
        font-size: 22px;
        color: #c70082;
        border-radius: 3px 3px 0 0;
    }

    .fullwidth {
        width: 100%;
        float: left;
    }

    .formbox {
        padding: 20px 20px;
        /*border: solid 1px #ddd;*/
    }

    .formbox form,
    .formbox div {
        width: 100%;
        float: left;
    }

    .formbox label {
        display: block;
        margin-bottom: 10px;
    }

    .formbox input {
        width: 100%;
        float: left;
        border: 1px solid #dedfe4;
        padding: 10px;
        font-size: 16px;
        height: 40px;
        border-radius: 3px;
    }

    .loginbtn {
        padding: 10px;
        border: none !important;
        width: 100%;
        cursor: pointer;
        text-align: center;
        font-size: 16px;
        height: 46px;
        color: #fff;
        font-weight: 600;
        border-radius: 3px;
        border: none;
        background: linear-gradient(to bottom, #d02881 0%, #a9348c 100%);
    }

    .box2 {
        float: left;
        width: 50%;
        background: #fff;
        padding: 15px;
        position: relative;
        height: 320px;
    }

    .box3 {
        width: 100%;
        background: #fff;
        padding: 15px;
        position: relative;
    }

    .logo1 {
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
        width: auto;
    }

    .logingheading {
        color: #c70082;
        margin-top: 0;
        font-size: 18px;
    }

    .rightimg {
        width: 50%;
    }

    .rimgbox {
        max-height: calc(100vh - 50px);
    }

    .rightimg img {
        width: 100%;
        object-fit: cover;
        object-position: center;
    }
</style>

<body class="bodybg">
  
    <div class="loginmain">
        <div class="boxbg">
            <div class="logbg">
            <h1 class="logtitle text-center">License Activate</h1>

                @include('snippets.flash')

                <div class="fullwidth formbox">

                    <form class="" role="form" method="POST" action="">
                        {!! csrf_field() !!}

                        <div class="form-group{{ $errors->has('license') ? ' has-error' : '' }}">
                            <label class="control-label">License code</label>
                            <div class="">
                                <input type="text" class="form-control" name="license" value="{{ old('license') }}">
                                @if ($errors->has('license'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('license') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('client') ? ' has-error' : '' }}">
                            <label class="control-label">Your name</label>
                            <div class="">
                                <input type="text" class="form-control" name="client">
                                @if ($errors->has('client'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('client') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="">
                                <button type="submit" class="loginbtn">Activate</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>

            <div class="rightimg">
                <img src="{{ url('assets/activate.png') }}">
            </div>

        </div>
    </div>

</body>

</html>
