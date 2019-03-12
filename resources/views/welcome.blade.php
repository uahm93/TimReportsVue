<!doctype html>
@if ( session()->get('user') )    
<script>window.location='dashboard';</script>
@endif
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Exipde tu factura</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <style>
        @import url('https://fonts.googleapis.com/css?family=Numans');

            html,body{
            background-image: url('http://127.0.0.1:8000/images/BGCP.JPG');
            background-size: cover;
            background-repeat: no-repeat;
            height: 100%;
            font-family: 'Numans', sans-serif;
            }

            .container{
            height: 100%;
            align-content: center;
            }

            .card{
            height: 340px;
            margin-top: auto;
            margin-bottom: auto;
            width: 400px;
            background-color: rgba(0,0,0,0.5) !important;
            }

            .social_icon span{
            font-size: 60px;
            margin-left: 10px;
            color: #C0C0C0;
            }

            .social_icon span:hover{
            color: white;
            cursor: pointer;
            }

            .card-header h3{
            color: white;
            }

            .social_icon{
            position: absolute;
            right: 20px;
            top: -45px;
            }

            .input-group-prepend span{
            width: 50px;
            background-color: #C0C0C0;
            color: black;
            border:0 !important;
            }

            input:focus{
            outline: 0 0 0 0  !important;
            box-shadow: 0 0 0 0 !important;

            }

            .remember{
            color: white;
            }

            .remember input
            {
            width: 20px;
            height: 20px;
            margin-left: 15px;
            margin-right: 5px;
            }

            .login_btn{
            color: black;
            background-color: #FFA500;
            width: 360px;
            }

            .login_btn:hover{
            color: black;
            background-color: #FFD700;
            }

            .links{
            color: white;
            }

            .links a{
            margin-left: 4px;
            }
        </style>
    </head>
    <body>
    
	<div class="d-flex justify-content-center h-100">
		<div class="card">
			<div class="card-header">
				<center><img src="http://127.0.0.1:8000/images/default_blanco.png" alt="LOGO XPD"></center> 
			</div>
			<div class="card-body">
				<form action="{{ route('login.custom') }}" method="post">
                @csrf
					<div class="input-group form-group">
						<div class="input-group-prepend">
							<span class="input-group-text"><i class="fas fa-user"></i></span>
						</div>
						<input type="text" class="form-control" id="usuario" name="usuario" placeholder="RFC" title="Se necesita un RFC" Required>
						
					</div>
					<div class="input-group form-group">
						<div class="input-group-prepend">
							<span class="input-group-text"><i class="fas fa-key"></i></span>
						</div>
						<input type="password" class="form-control" id="contrasena" name="contrasena" placeholder="Contraseña" Required>
					</div>
					<div class="row align-items-center remember">
						<input type="checkbox">Recordar cuenta
					</div>
					<div class="form-group">
						<input type="submit" value="Ingresar" class="btn float-right login_btn">
					</div>
				</form>
			</div>
		</div>
	</div>

    </body>
</html>
