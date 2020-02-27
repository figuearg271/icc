<section class="hero3">
		<header>
			<div class="wrapper">
				<a href="principal.php" ><img src="img/logo.png" class="logo" alt="ICC" titl="ICC"/></a>
                <a href="#" class="hamburger"></a> &nbsp;

                <h8 style="color: black;">Empresa:&nbsp;&nbsp;<?php echo $_SESSION['empre_nombre'];?></h8>
                <nav>
        
       
       
            <ul class="menu">

            <?php
                
                if ($_SESSION['user_tipo'] == 'V') 
                {?>
                    <li><a href="clientes.php">Clientes</a></li>                
                    <li><a href="pedidos.php">Pedidos</a></li>
                    <?php                   
                }
                elseif ($_SESSION['user_tipo'] == "A" or $tip_usuario == "C") 
                {
                    ?>
                    <li><a href="clientes.php">Clientes</a></li>  
                    <li><a href="clientesAdmin.php">Autorizaciones</a></li>  
                    <li><a href="pedidos.php">Pedidos</a></li>                
                    <?php

                }?>

               
                

                 <li><a href="login.php?logout">Cerrar Session</a></li>
               
                
            </ul>
        </nav>
        <br/>
        <h8 style="color: black;">&nbsp;&nbsp;Usuario:&nbsp;&nbsp;<?php echo $_SESSION['user_name'];?></h8>
			</div>
		</header><!--  end header section  -->

</section>








	