<?php
/**
 * Class login
 * handles the user's login and logout process
 */
class Login
{
    /**
     * @var object The database connection
     */
    private $db_connection = null;
    /**
     * @var array Collection of error messages
     */
    public $errors = array();
    /**
     * @var array Collection of success / neutral messages
     */
    public $messages = array();

    /**
     * the function "__construct()" automatically starts whenever an object of this class is created,
     * you know, when you do "$login = new Login();"
     */
    public function __construct()
    {
        // create/read session, absolutely necessary
        session_start();

        // check the possible login actions:
        // if user tried to log out (happen when user clicks logout button)
        if (isset($_GET["logout"])) {
            $this->doLogout();
        }
        // login via post data (if user just submitted a login form)
        elseif (isset($_POST["login"])) {
            $this->dologinWithPostData();
        }
    }

    /**
     * log in with post data
     */
    private function dologinWithPostData()
    {
        // check login form contents
        if (empty($_POST['user_name'])){
            $this->errors[] = "Username field was empty.";            
        } 
        elseif (empty($_POST['user_password'])) {
            $this->errors[] = "Password field was empty.";
        } 
        elseif (!empty($_POST['user_name']) && !empty($_POST['user_password'])){
             try
            {
                 //$user_name = $this->db_connection->real_escape_string($_POST['user_name']);
                //require_once("config/conexionweb.php");
                include './config/conexionweb.php';

                //$query = "SELECT * from USUARIOS where USUARIO='".$_POST['user_name']."' and PASSWORD='".md5(md5($_POST['user_password']))."' and STATUS='A'; ";

                  $query = "SELECT 
                        U.ID,U.NOMBRE,U.CLAVE_CLIENTE_SAE,U.CLAVE_MATRIZ_SAE,TRIM(U.TIPO) as TIPO,U.CVE_VEND,U.TIPOV,
                        (SELECT NUMERO FROM EMPRESAS WHERE ID=A.ID_EMPRESA) AS N_EMPRESA,
                        (SELECT VERSION FROM EMPRESAS WHERE ID=A.ID_EMPRESA) AS V_EMPRESA,
                        (SELECT NOMBRE FROM EMPRESAS WHERE ID=A.ID_EMPRESA) AS NOM_EMPRESA,
                        (SELECT DIRECCION FROM EMPRESAS WHERE ID=A.ID_EMPRESA) AS DIR_EMPRESA,
                        (SELECT NIT FROM EMPRESAS WHERE ID=A.ID_EMPRESA) AS NIT_EMPRESA,
                        (SELECT NRC FROM EMPRESAS WHERE ID=A.ID_EMPRESA) AS NRC_EMPRESA,
                        (SELECT IMPUESTO FROM EMPRESAS WHERE ID=A.ID_EMPRESA) AS IVA,
                        (SELECT TIPO_EMPRESA FROM EMPRESAS WHERE ID=A.ID_EMPRESA) AS TIPO_EMPRESA,
                        (SELECT BONIF FROM EMPRESAS WHERE ID=A.ID_EMPRESA) AS BONIFICACION
                        from USUARIOS as U inner join ACCESO_EMPRESA as A on U.ID=A.ID_USER
                        and a.ID_EMPRESA=(select E.ID from EMPRESAS AS E where E.NOMBRE='".$_POST['nombre_emp']."')
                        where 
    U.USUARIO='".$_POST['user_name']."' and U.PASSWORD='".md5(md5($_POST['user_password']))."' and U.STATUS='A' 
                and U.ID in (select ID_USER from ACCESO_EMPRESA where ID_EMPRESA in (select E.ID from EMPRESAS AS E where E.NOMBRE='".$_POST['nombre_emp']."') ); ";
 
                $result = ibase_query($conn, $query);
                //$row_count = ibase_num_fields($result);

                //echo $query;
                //die();
                /*$usuarios = array();
                $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
                
                $stmt= sqlsrv_prepare($conn, $query, $usuarios,$options);
                $result = sqlsrv_execute($stmt);
                $row_count = sqlsrv_num_rows( $stmt );*/

               // if ($row_count > 0)
               // {              
                    while($row = ibase_fetch_object($result))
                    {
                        $_SESSION['user_id'] = $row->ID;
                        $_SESSION['user_name'] = $row->NOMBRE;
                        $_SESSION['user_cliente'] = $row->CLAVE_CLIENTE_SAE;
                        $_SESSION['user_matriz'] = $row->CLAVE_MATRIZ_SAE;
                        $_SESSION['user_login_status'] = 1;
                        $_SESSION['user_tipo'] = $row->TIPO;
                        $_SESSION['user_cvevend'] = $row->CVE_VEND;
                        $_SESSION['empre_version'] = $row->V_EMPRESA;
                        $_SESSION['empre_numero'] = $row->N_EMPRESA;
                        $_SESSION['empre_nombre'] = $row->NOM_EMPRESA;
                        $_SESSION['empre_direccion'] = $row->DIR_EMPRESA;
                        $_SESSION['nit_direccion'] = $row->NIT_EMPRESA;
                        $_SESSION['nrc_direccion'] = $row->NRC_EMPRESA;
                        $_SESSION['impu'] = $row->IVA;
                        $_SESSION['tv'] = $row->TIPOV;
                        $_SESSION['bonif'] = $row->BONIFICACION;
                        $_SESSION['tipo_empresa'] = $row->TIPO_EMPRESA;

                    }
                //}
               // else
               // {
                //    $this->errors[] = "Usuario y/o contraseña no coinciden.";
                //}
                ## cerramos la conexion
                ibase_close($conn);
            }
            catch (Exception $e)
            {
                echo "Caught Exception ('{$e->getMessage()}')\n{$e}\n";
            }                           
        }        
    }

    /**
     * perform the logout
     */
    public function doLogout()
    {
        // delete the session of the user
        $_SESSION = array();

        unset($_SESSION['user_id']);
        unset($_SESSION['user_name']);
        unset($_SESSION['user_cliente']);
        unset($_SESSION['user_matriz']);
        unset($_SESSION['user_login_status']);
        unset($_SESSION['user_tipo']);
        unset($_SESSION['user_cvevend']);
        unset($_SESSION['empre_version']);
        unset($_SESSION['empre_numero']);
        unset($_SESSION['empre_nombre']);
        unset($_SESSION['empre_direccion']);
        unset($_SESSION['nit_direccion']);
        unset($_SESSION['nrc_direccion']);
        unset($_SESSION['impu']);
        unset($_SESSION['tv']);
        unset($_SESSION['bonif']);
        unset($_SESSION['tipo_empresa']);
        session_destroy();


        // return a little feeedback message
        $this->messages[] = "Has sido desconectado.";

    }

    /**
     * simply return the current state of the user's login
     * @return boolean user's login status
     */
    public function isUserLoggedIn()
    {
        if (isset($_SESSION['user_login_status']) AND $_SESSION['user_login_status'] == 1) {
            return true;
        }
        // default return
        return false;
    }
}

?>
