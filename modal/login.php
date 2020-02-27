<div id="myModal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Login</h4>
			</div>
			<div class="modal-body">

				<!--<form class="form" role="form" method="post" action="login" accept-charset="UTF-8" id="login-nav">-->
					<form method="post" accept-charset="utf-8" action="login.php" name="loginform" autocomplete="off" role="form" class="form-signin">

					<div class="form-group">
						<label for="sr-only">Usuario</label>
						<label class="sr-only" for="exampleInputEmail2">Email address</label>
						<input type="text" class="form-control" name="user_name" placeholder="Usuario" required>
					</div>

					<div class="form-group">
						<label for="sr-only">Password:</label>
						<label class="sr-only" for="exampleInputPassword2">Password</label>
						<input type="password" class="form-control"  name="user_password" placeholder="Password" required>
					</div>

					<div class='outer_div'></div>

					<div class="form-group">
						   <button type="submit" class="btn btn-lg btn-success btn-block btn-signin" name="login" id="submit">Iniciar Sesión</button>
					</div>
					<!--<div class="alert alert-success alert-dismissible" role="alert">
						    <strong>Aviso!</strong>
					</div>-->
				</form>	

				

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
    	</div>
	</div>
</div>