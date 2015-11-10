<!DOCTYPE html>
<html lang="">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Login</title>
        <!-- Bootstrap CSS -->
        <link href="{{  asset('css/bootstrap.css') }}" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="{{asset('assets/css/style.css')}}">
    </head>
    <body>
        @include('partial.notifications')
        <div class="container" style="margin-top:40px">
            <div class="row">
                <div class="col-sm-6 col-md-4 col-md-offset-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <strong> Sign in</strong>
                        </div>
                        <div class="panel-body">
                            <form role="form" action="{{route('admin.login')}}" method="POST">
                                {!! csrf_field() !!}
                                <fieldset>
                                    <div class="row">
                                        <div class="center-block">
                                            <img class="profile-img"
                                                src="{{asset('assets/images/avatar_2x.png')}}" alt="">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 col-md-10  col-md-offset-1 ">
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                        <i class="glyphicon glyphicon-user"></i>
                                                    </span> 
                                                    <input class="form-control" placeholder="Email" name="email" type="email" autofocus>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                        <i class="glyphicon glyphicon-lock"></i>
                                                    </span>
                                                    <input class="form-control" placeholder="Password" name="password" type="password" value="">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label">
                                                    <input type="checkbox" name="remember"><small>Remember Me</small>
                                                </label>
                                            </div>
                                            <div class="form-group">
                                                <input type="submit" class="btn btn-lg btn-primary btn-block" value="Sign in">
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- jQuery -->
        <script src="{{  asset('js/jquery-2.1.4.js') }}"></script>
        <!-- Bootstrap JavaScript -->
        <script src="{{  asset('js/bootstrap.js') }}"></script>
    </body>
</html>