<?php

namespace App\Controller;

use App\Entity\Log;
use App\Form\LogType;
use App\Repository\LogRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/log")
 */
class LogController extends AbstractController
{
    /**
     * @Route("/", name="log_index", methods={"GET"})
     */
    public function index(LogRepository $logRepository, Request $request): Response
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());
        return $this->render('log/index.html.twig', [
            'logs' => $logRepository->findAll(),
            'url' => 'secure',
            'routes' => ['Logs']
        ]);
    }

    /**
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     * @Route("/safe", name="log_safe", methods={"GET"})
     */
    public function safe(): Response
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
        $nombre = "logs_".date('Y-m-d').".sql";
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
        $tablas = array("log");
        /*
        * Tipo de compresion.
        * Puede ser "gz", "bz2", o false (sin comprimir)
        */
        $compresion = 'gz';

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
        
--
-- Base de datos: '$bd'
--

DELIMITER $$

DELIMITER ;

-- --------------------------------------------------------
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
            header("Content-Transfer-Encoding: utf8mb4");
            switch ($compresion) {
                case "gz":
                    header("Content-Disposition: attachment; filename=$nombre.gz");
                    header("Content-type: application/x-gzip");
                    echo gzencode($dump, 9);
                    break;
                case "bz2":
                    header("Content-Disposition: attachment; filename=$nombre.bz2");
                    header("Content-type: application/x-bzip2");
                    echo bzcompress($dump, 9);
                    break;
                default:
                    header("Content-Disposition: attachment; filename=$nombre");
                    header("Content-type: application/force-download");
                    echo $dump;
            }
        } else {
            echo "<b>ATENCION: Probablemente ha ocurrido un error</b><br />\n<pre>\n$dump\n</pre>";
        }
        var_dump('safe');die;
        return $this->redirectToRoute('log_index');
    }

    /**
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     * @Route("/clean", name="log_clean", methods={"GET"})
     */
    public function clean(): Response{
        /* Usuario para la conexion a Mysql. */
        $usuario = "root";
        /* Password para la conexion a Mysql. */
        $passwd = "";
        /* Host para la conexion a Mysql. */
        $host = "localhost";
        /* Base de Datos que se seleccionará. */
        $bd = "sigia";
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
        $tablas = array("log");

        /* Conexion y eso*/
        $conexion = mysqli_connect($host, $usuario, $passwd)
        or die("No se puede conectar con el servidor MySQL: ".mysqli_error($conexion));
        mysqli_select_db($conexion, $bd)
        or die("No se pudo seleccionar la Base de Datos: ". mysqli_error($conexion));

        foreach ($tablas as $tabla) {
            /* Se halla el query que será capaz de recrear la estructura de la tabla. */
            $create_table_query = "";
            $consulta = "TRUNCATE $tabla;";
            $respuesta = mysqli_query($conexion, $consulta)
            or die("No se pudo ejecutar la consulta: " . mysqli_error($conexion));
        }
        //var_dump('clean');die;
        return $this->redirectToRoute('log_index');
    }

    public function new($user, $ip, $accion): Response
    {
        $log = new Log();
        $entityManager = $this->getDoctrine()->getManager();
        $log->setUsuario($user)
            ->setIp($ip)
            ->setAccion($accion)
            ->setFecha(new \DateTime('now'));
        $entityManager->persist($log);
        $entityManager->flush();
    }
    /*public function new(Request $request): Response
    {
        $log = new Log();
        $form = $this->createForm(LogType::class, $log);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($log);
            $entityManager->flush();

            return $this->redirectToRoute('log_index');
        }

        return $this->render('log/new.html.twig', [
            'log' => $log,
            'form' => $form->createView(),
        ]);
    }*/

    /**
     * @Route("/{id}", name="log_show", methods={"GET"})
     */
    public function show(Log $log): Response
    {
        return $this->render('log/show.html.twig', [
            'log' => $log,
            'url' => 'secure',
            'routes' => ['Logs', 'Ver']
        ]);
    }

    /*
    /**
     * @Route("/{id}/edit", name="log_edit", methods={"GET","POST"})
     */
    /*public function edit(Request $request, Log $log): Response
    {
        $form = $this->createForm(LogType::class, $log);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('log_index');
        }

        return $this->render('log/edit.html.twig', [
            'log' => $log,
            'form' => $form->createView(),
        ]);
    }*/
    /*
    /**
     * @Route("/{id}", name="log_delete", methods={"DELETE"})
     */
    /*public function delete(Request $request, Log $log): Response
    {
        if ($this->isCsrfTokenValid('delete'.$log->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($log);
            $entityManager->flush();
        }

        return $this->redirectToRoute('log_index');
    }*/
}
