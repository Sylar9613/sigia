<?php

namespace App\Controller;

use App\Entity\Implicado;
use App\Entity\Log;
use App\Entity\Phd;
use App\Form\PhdType;
use App\Repository\PhdRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/phd")
 */
class PhdController extends AbstractController
{
    /**
     * @Route("/", name="phd_index", methods={"GET"})
     */
    public function index(PhdRepository $phdRepository, Request $request): Response
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());
        return $this->render('phd/index.html.twig', [
            'phds' => $phdRepository->findAll(),
            'entidades' => $this->getDoctrine()->getManager()->getRepository('App\Entity\Entidad')->findBy(array('activo'=>1)),
            'acciones' => $this->getDoctrine()->getManager()->getRepository('App\Entity\TipoAccion')->findBy(array('activo'=>1)),
            'url' => 'planificacion',
            'routes' => ['Planificación', 'Daños', 'PHD'],
        ]);
    }

    /**
     * @Route("/to_xls", name="phd_to_xls")
     */
    public function exportXls(Request $request)
    {
        /**
         * @var Phd $item
         */
        $em = $this->getDoctrine()->getManager()->getRepository('App\Entity\Phd')->findAll();
        $fecha = date("d-m-Y H:i:s");
        $filename = 'PHD_'.$fecha.'.xls';

        //Para CSV
        //header('Content-Type: text/csv; charset=utf-8');
        //Para excel
        header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
        header("Content-Disposition: attachment; filename=$filename");
        header("Pragma: no-cache");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: private", false);
        $content = '
        <!DOCTYPE html>
        <html lang="esp" >
            <head>
                <meta charset="utf-8"/>
            </head>
            <body>';
        foreach ($em as $item){
            $content.='
            <div style="font-family: \'Agency FB\', Arial, Helvetica, sans-serif;">
                    <table><tr></tr></table>
                    <table style="text-align: center;font-size: 12px;" border="1" cellpadding="2" cellspacing="0" width="100%">
                    <caption>Situaci&oacute;n de los Presuntos Hechos Delictivos</caption>
                    <thead>
                        <tr>
                            <th colspan="2" style="border-right-style: none;">Provincia: Matanzas</th>
                            <th colspan="2" style="border-right-style: none;border-left-style: none;">Unidad organizativa: '.$item->getUnidadOrganizativa().'</th>
                            <th colspan="2" style="border-right-style: none;border-left-style: none;">Per&iacute;odo que informa: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                        </tr>
                        <tr>
                            <th style="background-color: #006622;color:white;">Orden de trabajo</th>
                            <th style="background-color: #006622;color:white;">Entidad</th>
                            <th style="background-color: #006622;color:white;">No. Expediente</th>
                            <th style="background-color: #006622;color:white;">No. Causa</th>
                            <th style="background-color: #006622;color:white;">Tipo de acci&oacute;n</th>
                            <th style="background-color: #006622;color:white;">Situaci&oacute;n</th>
                            <th style="background-color: #006622;color:white;">Causa / Condici&oacute;n</th>
                            <th style="background-color: #006622;color:white;">Fecha</th>
                        </tr>
                    </thead>
                    ';

            $content.= '<tbody>
                                            <tr>
                                                <td style="text-align: center;">';
            if ($item->getAccionControl()){
                $content.=$item->getAccionControl()->getOrdenTrabajo().'</td>';
            }
            else{
                $content.='-</td>';
            }
            $content.='
                                                <td style="text-align: center;">'.$item->getEntidad()->getNombre().'</td>
                                                <td style="text-align: center;">'.$item->getNumeroExpediente().'</td>
                                                <td style="text-align: center;">'.$item->getNumeroCausa().'</td>
                                                <td style="text-align: center;">'.$item->getTipoAccion()->getNombre().'</td>
                                                <td style="text-align: center;">'.$item->getSituacion()->getNombre().'</td>
                                                <td style="text-align: center;">'.$item->getCausaCondicion()->getNombre().'</td>
                                                <td style="text-align: center;">'.$item->ToString($item->getFecha()).'</td>
                                            </tr>
                                            </tbody>
                    </table><br/><br/>';

            $content.='<table style="text-align: center;font-size: 12px;" cellpadding="2" cellspacing="0" width="100%">
                        <th rowspan="10" style="border: 0.05em solid;">S&iacute;ntesis</th>
                        <td rowspan="10" style="border: 0.05em solid;text-align: justify;vertical-align: middle;">'
                .$item->getSintesis().'
                        </td>    
                        <thead>
                            <tr>
                                <th></th>
                                <th></th>
                                <th colspan="3" style="border: 0.05em solid;">Da&ntilde;os Econ&oacute;micos</th>
                            </tr>
                            <tr>
                                <th></th>
                                <th></th>
                                <th style="background-color: #006622;color:white;border: 0.05em solid;">CUP</th>
                                <th style="background-color: #006622;color:white;border: 0.05em solid;">CUC</th>
                                <th style="background-color: #006622;color:white;border: 0.05em solid;">Otras monedas</th>
                            </tr>
                        </thead>';
            $content.= '<tbody>
                            <tr>
                                <td></td>
                                <td></td>
                                <td style="text-align: center;border: 0.05em solid;">';
            if ($item->getDanoEconomicoCup()){
                $content.=$item->getDanoEconomicoCup().'</td>';
            }
            else{
                $content.='&nbsp;</td>';
            }
            $content.='
                                <td style="text-align: center;border: 0.05em solid;">&nbsp;</td>
                                <td style="text-align: center;border: 0.05em solid;">';
            if ($item->getDanoEconomicoOtraMoneda()){
                $content.=$item->getDanoEconomicoOtraMoneda().'</td>';
            }
            else{
                $content.='&nbsp;</td>';
            }
            $content.='
                            </tr>
                        </tbody>
            </table><br/>';

            $content.='<table><td></td><td><p><b><u>IMPLICADOS</u></b></p></td></table>';
            foreach ($item->getImplicados() as $implicado){
                $content.='<table style="text-align: center;font-size: 12px;" cellpadding="2" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th></th>
                                <th style="background-color: #006622;color:white;border: 0.05em solid;">Nombres y apellidos</th>
                                <th style="background-color: #006622;color:white;border: 0.05em solid;">Cargo</th>
                                <th style="background-color: #006622;color:white;border: 0.05em solid;">Categor&iacute;a ocupacional</th>
                                <th style="background-color: #006622;color:white;border: 0.05em solid;">Escolaridad</th>
                                <th style="background-color: #006622;color:white;border: 0.05em solid;">PCC</th>
                            </tr>
                        </thead>';
                $content.= '<tbody>
                            <tr>
                                <td></td>
                                <td style="text-align: center;border: 0.05em solid;">'.$implicado->getNombre().'</td>
                                <td style="text-align: center;border: 0.05em solid;">'.$implicado->getCargo().'</td>
                                <td style="text-align: center;border: 0.05em solid;">'.$implicado->getCategoriaOcupacional().'</td>
                                <td style="text-align: center;border: 0.05em solid;">'.$implicado->getEscolaridad().'</td>
                                <td style="text-align: center;border: 0.05em solid;">';
                if ($implicado->getPcc()=='1'){
                    $content.='X</td>';
                }
                else{
                    $content.='&nbsp;</td>';
                }
                $content.='
                                            </tr>
                                        </tbody>
                            </table><br/><br/>';
            }

            $content.='<div></div><table style="text-align: center;font-size: 12px;" cellpadding="2" cellspacing="0" width="100%">  
                        <thead>
                            <tr>
                                <th></th>
                                <th style="background-color: #006622;color:white;border: 0.05em solid;">Aprobado por:</th>
                                <th style="background-color: #006622;color:white;border: 0.05em solid;">Aprobado por:</th>
                            </tr>
                        </thead>';
            $content.= '<tbody>
                            <tr style="border-right: 0.05em solid;border-left: 0.05em solid;">
                                <td></td>
                                <td>Nombres y apellidos: </td>
                                <td>Nombres y apellidos: </td>
                            </tr>
                            <tr style="border-right: 0.05em solid;border-left: 0.05em solid;">
                                <td></td>
                                <td>Cargo: </td>
                                <td>Cargo: </td>
                            </tr>
                            <tr style="border-right: 0.05em solid;border-left: 0.05em solid;">
                                <td></td>
                                <td>Fecha: </td>
                                <td>Fecha: </td>
                            </tr>
                            <tr style="border-bottom: 0.05em solid; border-right: 0.05em solid;border-left: 0.05em solid;">
                                <td style="border-bottom: none;"></td>
                                <td>Firma: </td>
                                <td>Firma: </td>
                            </tr>
                        </tbody>
                </table><br/><br/><br/></div>';
        }
        $content.='</body></html>';
        echo $content;
        exit();
    }

    /**
     * Filter all hcs by entidades and/or municipios
     *
     * @Route("/", name="phd_filter", methods={"GET", "POST"})
     */
    public function filterAction(Request $request, PhdRepository $phdRepository)
    {
        $ident = $request->request->get('filtrar_ent');
        $idtipoAccion = $request->request->get('filtrar_tipo_accion');

        $em = $this->getDoctrine()->getManager();

        if($ident=='todas')
        {
            if ($idtipoAccion=='todos')
            {
                return $this->redirectToRoute("phd_index");
            }
            else
            {
                $phds = $phdRepository->findBy(array('tipoAccion'=>$idtipoAccion));
            }
        }
        else
        {
            if ($idtipoAccion=='todos')
            {
                $phds = $phdRepository->findBy(array('entidad'=>$ident));
            }
            else
            {
                $phds = $phdRepository->findBy(array('entidad'=>$ident, 'tipoAccion'=>$idtipoAccion));
            }
        }

        $bitacora = new Log();
        $bitacora->setFecha(new \DateTime('now'))
            ->setUsuario($this->getUser())
            ->setIp($request->getClientIp())
            ->setAccion('Filtrar PHD');
        $em->persist($bitacora);
        $em->flush();

        return $this->render("phd/index.html.twig", array(
            'phds' => $phds,
            'entidades' => $em->getRepository('App\Entity\Entidad')->findBy(array('activo'=>1)),
            'acciones' => $em->getRepository('App\Entity\TipoAccion')->findBy(array('activo'=>1)),
            'url' => 'planificacion',
            'routes' => ['Planificación', 'Daños', 'PHD'],
        ));
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/new", name="phd_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());
        $phd = new Phd();

        $form = $this->createForm(PhdType::class, $phd);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $bitacora = new Log();
            $bitacora->setFecha(new \DateTime('now'))
                ->setUsuario($this->getUser())
                ->setIp($request->getClientIp())
                ->setAccion('Insertar PHD');
            $entityManager->persist($bitacora);
            $entityManager->persist($phd);
            $entityManager->flush();

            $this->addFlash(
                'notice',
                'PHD registrado!'
            );
            return $this->redirectToRoute('phd_index');
        }

        return $this->render('phd/new.html.twig', [
            'phd' => $phd,
            'form' => $form->createView(),
            'url' => 'planificacion',
            'routes' => ['Planificación', 'Daños', 'PHD', 'Nuevo'],
        ]);
    }

    /**
     * @Route("/{id}", name="phd_show", methods={"GET"})
     */
    public function show(Phd $phd, Request $request): Response
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());
        return $this->render('phd/show.html.twig', [
            'phd' => $phd,
            'url' => 'planificacion',
            'routes' => ['Planificación', 'Daños', 'PHD', 'Ver'],
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/{id}/edit", name="phd_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Phd $phd): Response
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());
        $form = $this->createForm(PhdType::class, $phd);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bitacora = new Log();
            $bitacora->setFecha(new \DateTime('now'))
                ->setUsuario($this->getUser())
                ->setIp($request->getClientIp())
                ->setAccion('Modificar PHD: '.$phd->getId());
            $this->getDoctrine()->getManager()->persist($bitacora);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'notice',
                'PHD actualizado!'
            );

            return $this->redirectToRoute('phd_index');
        }

        return $this->render('phd/edit.html.twig', [
            'phd' => $phd,
            'form' => $form->createView(),
            'url' => 'planificacion',
            'routes' => ['Planificación', 'Daños', 'PHD', 'Editar'],
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/{id}/activate", name="phd_activate", methods={"GET","POST"})
     */
    public function activate(Request $request, Phd $phd): Response
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());
        $phd->setActivo(true);
        $bitacora = new Log();
        $bitacora->setFecha(new \DateTime('now'))
            ->setUsuario($this->getUser())
            ->setIp($request->getClientIp())
            ->setAccion('Activar PHD: '.$phd->getId());
        $this->getDoctrine()->getManager()->persist($bitacora);
        $this->getDoctrine()->getManager()->flush();

        $this->addFlash(
            'notice',
            'PHD activado!'
        );
        return $this->redirectToRoute('phd_index');
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/{id}/deactivate", name="phd_deactivate", methods={"GET","POST"})
     */
    public function deactivate(Request $request, Phd $phd)
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());

        $implicado = $phd->getImplicado();

        if (count($implicado) > 0)
        {
            $this->addFlash(
                'error',
                'No se puede eliminar este PHD porque está asociado a algún implicado!'
            );

            return true;
        }
        else
        {
            $phd->setActivo(false);
            $bitacora = new Log();
            $bitacora->setFecha(new \DateTime('now'))
                ->setUsuario($this->getUser())
                ->setIp($request->getClientIp())
                ->setAccion('Desactivar PHD: '.$phd->getId());
            $this->getDoctrine()->getManager()->persist($bitacora);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'notice',
                'PHD desactivado!'
            );

            return false;
        }
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/{id}", name="phd_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Phd $phd): Response
    {
        if ($this->isCsrfTokenValid('delete'.$phd->getId(), $request->request->get('_token'))) {
            if ($this->deactivate($request, $phd))
            {
                return $this->redirectToRoute('phd_show', array('id'=>$phd->getId()));
            }
        }

        return $this->redirectToRoute('phd_index');
    }

    /**
     * Filter all auditores by vence R.N.A.
     *
     * @Route("_download_pdf", name="phd_download_pdf", methods={"GET","POST"})
     */
    public function pdf(Request $request)
    {
        $conn = $this->getDoctrine()->getConnection();
        $auditors = $conn
            ->fetchAll('SELECT phd.id,phd.activo,numero_expediente,unidad_organizativa,accion_control.orden_trabajo,numero_causa,phd.fecha,dano_economico_cup,dano_economico_otra_moneda,entidad.nombre AS entidad,tipo_accion.nombre AS accion,situacion.nombre AS situacion,causa_condicion.nombre AS causa  
                    FROM phd,entidad,tipo_accion,situacion,causa_condicion,accion_control 
                    WHERE phd.entidad_id=entidad.id 
                    AND phd.tipo_accion_id=tipo_accion.id
                    AND phd.situacion_id=situacion.id
                    AND phd.causa_condicion_id=causa_condicion.id
                    AND phd.accion_control_id=accion_control.id
                    ');

        $reporte_name = 'Reporte de los P.H.D. - '.date('Y/m/d h:i:s a');

        require_once(__DIR__.'/../../vendor/tcpdf/tcpdf.php');

        $pdf = new \TCPDF('P', 'mm', 'A4', true, 'ASCII', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Arián Castellanos');
        $pdf->SetTitle($reporte_name);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetMargins(10, 20, 10, false);
        $pdf->SetAutoPageBreak(true, 10);
        $pdf->setPageOrientation('L');
        $pdf->addPage();

        $content = '';

        $content .= '
		<div class="row">
        	<div class="col-md-12">
            	<h1 style="text-align:center;">'.$reporte_name.'</h1>
            	<h2 style="text-align:center;">Consta de '.count($auditors).' P.H.D.</h2>
           	</div>
        </div>
    <table border="0" cellpadding="3">
        <thead>
          <tr style="color: whitesmoke; background-color: #223444;">';
        $content .= '
            <th>N<sup>o</sup> Orden Trab.</th>
            <th>N<sup>o</sup> Exp.</th>
            <th>N<sup>o</sup> Causa</th>
            <th>Udad. Org.</th>
            <th>Entidad</th>
            <th>Tipo acci&oacute;n</th>
            <th>Situaci&oacute;n</th>
            <th>Causa</th>
            <th>Fecha</th>
            <th>Da&ntilde;os CUP</th>
            <th>Da&ntilde;os otra mon.</th>
            ';
        $content .= '
          </tr>
        </thead>
	';

        foreach ($auditors as $index => $user) {
            if($user['activo']=='1'){  $color= '#3f51b5'; }else{ $color= '#ff4081'; }
            $content .= '
		<tr style="color: #FFFFFF;" bgcolor="'.$color.'">
		    <td>'.$user['orden_trabajo'].'</td>
		    <td>'.$user['numero_expediente'].'</td>
		    <td>'.$user['numero_causa'].'</td>
            <td>'.$user['unidad_organizativa'].'</td>
            <td>'.$user['entidad'].'</td>
            <td>'.$user['accion'].'</td>
            <td>'.$user['situacion'].'</td>
            <td>'.$user['causa'].'</td>
            <td>'.$user['fecha'].'</td>
            <td>'.$user['dano_economico_cup'].'</td>
            <td>'.$user['dano_economico_otra_moneda'].'</td>
        </tr>';
        }

        $content .= '</table>';

        $content .= '
		<div class="row padding">
        	<div class="col-md-12" style="text-align:center;">
            	<span>Pdf Creator </span><a href="http://www.redecodifica.com">By Arián Castellanos</a>
            </div>
        </div>
    	
	';
        $pdf->SetFont('Helvetica', '', 7);
        $pdf->writeHTML($content, true, 0, true, 0);

        $pdf->lastPage();
        $pdf->output('Reporte P.H.D. STA '.date('Y/m/d').'.pdf', 'D');

        $em = $this->getDoctrine()->getManager();
        $bitacora = new Log();
        $bitacora->setFecha(new \DateTime('now'))
            ->setUsuario($this->getUser())
            ->setIp($request->getClientIp())
            ->setAccion('Crear reporte PHD');

        $em->persist($bitacora);
        $em->flush();

        return $this->redirectToRoute('phd_index');
    }
}
