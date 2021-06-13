<?php

namespace App\Controller;

use App\Entity\AccionControl;
use App\Entity\Datos;
use App\Entity\Entidad;
use App\Entity\HC;
use App\Entity\Log;
use App\Entity\Phc;
use App\Entity\Phd;
use App\LogGenerator;
use App\Service\HtmlToDoc;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class AdminController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index(Request $request)
    {
        $session = $request->getSession();
        $path = $session->get('path');
        $userExist = $session->get('userExist');
        $session->set('path', $request->getPathInfo());
        $aux = false;

        if (preg_match('/login/', $path)){
            $aux = true;
        }

        if ($aux && !$userExist){
            $log = new Log();
            $entityManager = $this->getDoctrine()->getManager();
            $log->setUsuario($this->getUser())
                ->setIp($request->getClientIp())
                ->setAccion('Login')
                ->setFecha(new \DateTime('now', new \DateTimeZone('America/Havana')));
            $entityManager->persist($log);
            $entityManager->flush();
        }

        return $this->render('admin/index.html.twig', [
            'url' => 'home',
        ]);
    }

    /**
     * @IsGranted("ROLE_SUPER_ADMIN")
     * @Route("/admin", name="admin", methods={"GET"})
     */
    public function admin()
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN', null, 'User tried to access a page without having ROLE_SUPER_ADMIN');
        return $this->render('admin/admin.html.twig', [
            'controller_name' => 'AdminController',
            'url' => 'database',
            'routes' => ['Admin page'],
            'datos' => $this->getDoctrine()->getManager()->getRepository('App\Entity\Datos')->findAll(),
        ]);
    }

    /**
     * @Route("/access_denied", name="access_denied", methods={"GET"})
     */
    public function accessDeniedAction()
    {
        /*$em = $this->getDoctrine()->getManager();
        $bitacora = new Bitacora();
        $bitacora->setFecha(new \DateTime('now'))
            ->setUsuario($this->getUser())
            ->setIp($request->getClientIp())
            ->setAccion('Acceso prohibido.');
        $em->persist($bitacora);
        $em->flush();*/

        $response = new Response('Access Forbidden',Response::HTTP_FORBIDDEN);

        return $this->render('bundles/TwigBundle/Exception/error403.html.twig', array(
            'response'=>$response
        ));
    }

    /**
     * @Route("/tema", name="tema")
     */
    public function temaAction(Request $request)
    {
        $session = $request->getSession();
        $tema = $session->get('tema');
        if ($tema==='black')
        {
            $session->set('tema', 'white');
        }
        else{
            $session->set('tema', 'black');
        }

        $path = $request->getUriForPath($session->get('path'));
        return $this->redirect($path);
    }

    /**
     * @Route("/salva", name="salva")
     */
    public function salva(Request $request)
    {
        /* Usuario para la conexion a Mysql. */
        $usuario = "root";
        /* Password para la conexion a Mysql. */
        $passwd = "";
        /* Host para la conexion a Mysql. */
        $host = "localhost";
        /* Base de Datos que se seleccionará. */
        $bd = "sigia";
        /* Nombre del fichero que se descargará. */
        $fecha = new \DateTime('now');
        $aux = new AccionControl();
        $nombre = "database".$aux->DateTimeToString($fecha).".sql";
        $fullName = "uploads/database/database".$aux->DateTimeToString($fecha).".sql";
        /* Determina si la tabla será vaciada (si existe) cuando  restauremos la tabla. */
        $drop = true;
        /*
        * Array que contiene las tablas de la base de datos que seran resguardadas.
        * Puede especificarse un valor false para resguardar todas las tablas
        * de la base de datos especificada en  $bd.
        *
        * Ejs.:
        * $tablas = false;
        *    o
        * $tablas = array("tabla1", "tabla2", "tablaetc");
        *
        */
        //$tablas = array("auditor");
        $tablas = array("datos", "accion_control", "auditor", "cargo", "causa_condicion", "combustible", "entidad", "hc", "implicado", "localidad", "log", "medida_disciplinaria", "migration_versions", "municipio", "nivel", "organismo", "osde", "particularidad", "phc", "phd", "plaza", "responsabilidad", "situacion", "tipo_accion", "user");

        /* Conexion y eso*/
        $conexion = mysqli_connect($host, $usuario, $passwd)
        or die("No se puede conectar con el servidor MySQL: ".mysqli_error($conexion));
        mysqli_select_db($conexion, $bd)
        or die("No se pudo seleccionar la Base de Datos: ". mysqli_error($conexion));


        /* Se busca las tablas en la base de datos */
        if ( empty($tablas) ) {
            $consulta = "SHOW TABLES FROM `$bd`;";
            $respuesta = mysqli_query($conexion, $consulta)
            or die("No se pudo ejecutar la consulta: ".mysqli_error($conexion));
            while ($fila = mysqli_fetch_array($respuesta, MYSQLI_NUM)) {
                $tablas[] = $fila[0];
            }
        }

        /* Se crea la cabecera del archivo */
        $info['dumpversion'] = "1.1b";
        $info['fecha'] = date("d-m-Y");
        $info['hora'] = date("h:m:s");
        $info['mysqlver'] = mysqli_get_server_info($conexion);
        $info['phpver'] = phpversion();
        ob_start();
        print_r($tablas);
        $representacion = ob_get_contents();
        ob_end_clean ();
        preg_match_all('/(\[\d+\] => .*)\n/', $representacion, $matches);
        //$info['tablas'] = implode(";  ", $matches[1]);
        $cantidad_tablas = count($matches[1]);

        $dump = <<<EOT
-- Por Arian {$info['dumpversion']}
-- Generado el {$info['fecha']} a las {$info['hora']} por el usuario '$usuario'
-- Servidor: {$_SERVER['HTTP_HOST']}
-- MySQL Version: {$info['mysqlver']}
-- PHP Version: {$info['phpver']}
-- Base de datos: '$bd'
-- Tablas: '$cantidad_tablas'
     
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE="NO_AUTO_VALUE_ON_ZERO" */;

DELIMITER $$
--
-- Funciones
--
CREATE DEFINER=`root`@`localhost` FUNCTION `buscar_string` (`nombre_o_apellido` VARCHAR(120), `fuente` VARCHAR(120)) RETURNS INT(3) begin

RETURN if(locate(nombre_o_apellido,fuente)=0,0,1);

end$$

CREATE DEFINER=`root`@`localhost` FUNCTION `devolver_anno` (`fecha_vence` VARCHAR(10)) RETURNS INT(2) BEGIN
return IF(EXTRACT(YEAR FROM curdate())-(LEFT(fecha_vence,4)+5)=0,1,0);
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `edad` (`CI` VARCHAR(11)) RETURNS INT(11) BEGIN

return if(substr(curdate(),3,2) > left(CI,2), substr(curdate(),3,2) - left(CI,2),
left(curdate(),4)-1900-left(CI,2));

END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `sexo` (`CI` VARCHAR(11)) RETURNS CHAR(1) CHARSET latin1 BEGIN

return if(substr(CI,10,1)%2=0,'M','F');

END$$

DELIMITER ;


EOT;
        foreach ($tablas as $tabla) {

            $drop_table_query = "";
            $create_table_query = "";
            $insert_into_query = "";

            /* Se halla el query que será capaz vaciar la tabla. */
            if ($drop) {
                $drop_table_query = "DROP TABLE IF EXISTS `$tabla`;";
            } else {
                $drop_table_query = "-- No especificado.";
            }

            /* Se halla el query que será capaz de recrear la estructura de la tabla. */
            $create_table_query = "";
            $consulta = "SHOW CREATE TABLE $tabla;";
            $respuesta = mysqli_query($conexion, $consulta)
            or die("No se pudo ejecutar la consulta: ".mysqli_error($conexion));
            while ($fila = mysqli_fetch_array($respuesta, MYSQLI_NUM)) {
                $create_table_query = $fila[1].";";
                //var_dump($fila);die;
            }

            /* Se halla el query que será capaz de insertar los datos. */
            $insert_into_query = "";
            $consulta = "SELECT * FROM $tabla;";
            $respuesta = mysqli_query($conexion, $consulta)
            or die("No se pudo ejecutar la consulta: ".mysqli_error($conexion));
            if ($respuesta->num_rows!=0){
                $insert_into_query .= "INSERT INTO `$tabla` (";
            }
            $is = true;
            $arreglo_tam = $respuesta->num_rows;
            $j = 0;
            while ($fila = mysqli_fetch_array($respuesta, MYSQLI_ASSOC)) {
                $columnas = array_keys($fila);
                $tam = count($columnas);
                $i = 0;
                foreach ($columnas as $columna) {
                    if ( gettype($fila[$columna]) == "NULL" ) {
                        $values[] = "NULL";
                    } else {
                        $values[] = "'".mysqli_real_escape_string($conexion, $fila[$columna])."'";
                    }
                    if ($is){
                        $insert_into_query.="`$columna`";
                        if ($i!=$tam-1){
                            $insert_into_query.=", ";
                        }
                        else{
                            $insert_into_query.=") VALUES \n";
                            $is = false;
                        }
                        $i++;
                    }
                }
                if ($j!=$arreglo_tam-1){
                    $insert_into_query .= "(".implode(", ", $values)."),\n";
                    //$insert_into_query .= "INSERT INTO `$tabla` VALUES (".implode(", ", $values).");\n";
                }
                else{
                    $insert_into_query .= "(".implode(", ", $values).");\n";
                }
                unset($values);
                $j++;
            }

            //var_dump($insert_into_query);die;
            $dump .= <<<EOT
            
-- 
-- Vaciado de tabla '$tabla'
-- 
$drop_table_query
                        
--
-- Estructura de tabla para la tabla '$tabla'
--

$create_table_query

EOT;
            if ($respuesta->num_rows!=0){
                $dump .= <<<EOT
                
--
-- Volcado de datos para la tabla '$tabla'
--

$insert_into_query

EOT;

            }
        }
        $dump .= <<<EOT
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
EOT;

        /* Envio */
        if ( !headers_sent() ) {
            header("Pragma: no-cache");
            header("Expires: 0");
            header("Content-Transfer-Encoding: binary");

            $fp = @fopen($fullName, 'w');
            if(!is_resource($fp)){
                return false;
            }

            fwrite($fp, $dump);
            fclose($fp);

            $bitacora = new Log();
            $bitacora->setFecha(new \DateTime('now'))
                ->setUsuario($this->getUser())
                ->setIp($request->getClientIp())
                ->setAccion('Salvar Database');

            $database = new Datos();
            $database->setDatabaseFilename($nombre);
            $database->setDate(new \DateTime('now'));
            $this->getDoctrine()->getManager()->persist($bitacora);
            $this->getDoctrine()->getManager()->persist($database);
            $this->getDoctrine()->getManager()->flush();
        } else {
            echo "<b>ATENCION: Probablemente ha ocurrido un error</b><br />\n<pre>\n$dump\n</pre>";
        }
        return $this->redirectToRoute('admin');
    }

    /**
     * @Route("/{id}/delete_database", name="delete_database")
     */
    public function deleteDatabase(Datos $datos)
    {
        $file = 'uploads/database/'.$datos->getDatabaseFilename();
        //var_dump($file);die;
        if (!unlink($file))
        {
            $this->addFlash(
                'error',
                'Error al borrar el archivo!'
            );
        }
        else
        {
            $this->addFlash(
                'notice',
                'Archivo borrado!'
            );
        }
        $this->getDoctrine()->getManager()->remove($datos);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('admin');
    }

    public function exportProductDatabase($productResult){
        $timestamp = time();
        $filename = 'Export_'.$timestamp.'.xls';

        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment; filename=\"$filename\"");

        $isPrintHeader = false;

        foreach ($productResult as $row){
            if (!$isPrintHeader){
                echo implode("\t", array_keys($row))."\n";
                $isPrintHeader = true;
            }
            echo implode("\t", array_values($row))."\n";
        }
        exit();
    }

}
