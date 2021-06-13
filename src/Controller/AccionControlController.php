<?php

namespace App\Controller;

use App\Entity\AccionControl;
use App\Entity\Log;
use App\Form\AccionControlType;
use App\Repository\AccionControlRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


/**
 * @Route("/accion_control")
 */
class AccionControlController extends AbstractController
{
    /**
     * @Route("/", name="accion_control_index", methods={"GET"})
     */
    public function index(AccionControlRepository $accionControlRepository): Response
    {
        return $this->render('accion_control/index.html.twig', [
            'accion_controls' => $accionControlRepository->findAll(),
            'entidades' => $this->getDoctrine()->getManager()->getRepository('App\Entity\Entidad')->findBy(array('activo'=>1)),
            'acciones' => $this->getDoctrine()->getManager()->getRepository('App\Entity\TipoAccion')->findBy(array('activo'=>1)),
            'url' => 'planificacion',
            'routes' => ['Planificación', 'Acciones', 'Acción de Control'],
        ]);
    }

    /**
     * @Route("/to_xls", name="ac_to_xls")
     */
    public function exportXls(Request $request)
    {
        /**
         * @var AccionControl $item
         */
        $em = $this->getDoctrine()->getManager()->getRepository('App\Entity\AccionControl')->findAll();
        $fecha = date("d-m-Y H:i:s");
        $filename = 'Plan Anual de Auditoría_'.$fecha.'.xls';

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
        $auditores = 0;
        $dias = 0;
        $auditoresXdia = 0;
        $phd = 0;
        $phc = 0;
        $danoCup = 0;
        $danoCuc = 0;
        $danoOM = 0;
        $contador = 1;
        $content.='
            <div style="font-family: \'Agency FB\', Arial, Helvetica, sans-serif;">
                    <table><tr></tr><tr></tr><tr><td style="text-align: right;"><b>MODELO 001</b></td></tr></table>
                    <table style="text-align: center;font-size: 12px;" border="1" cellpadding="2" cellspacing="0" width="100%">
                    <caption><b>Plan Anual de Auditor&iacute;a, Supervisi&oacute;n y Control</b></caption>
                    <thead>
                        <tr></tr>
                        <tr>
                            <th colspan="13" style="border-right-style: none;">De: </th>
                            <th colspan="13" style="border-right-style: none;border-left-style: none;">Para a&ntilde;o: </th>
                        </tr>
                        <tr>
                            <th rowspan="3" style="background-color: #006622;color:white;">No.</th>
                            <th rowspan="3" style="background-color: #006622;color:white;">Orden de trabajo</th>
                            <th rowspan="3" style="background-color: #006622;color:white;">Entidades a comprobar</th>
                            <th rowspan="3" style="background-color: #006622;color:white;">C&oacute;digo NIT</th>
                            <th rowspan="3" style="background-color: #006622;color:white;">C&oacute;digo REEUP</th>
                            <th rowspan="3" style="background-color: #006622;color:white;">Tipo de acci&oacute;n</th>
                            <th rowspan="3" style="background-color: #006622;color:white;">Directiva(s)</th>
                            <th rowspan="3" style="background-color: #006622;color:white;">Particularidades</th>
                            <th rowspan="2" colspan="2" style="background-color: #006622;color:white;">
                                Cantidad de auditores
                                <th rowspan="2" colspan="2" style="background-color: #006622;color:white;">
                                    D&iacute;as h&aacute;biles
                                </th>
                                <th rowspan="2" colspan="2" style="background-color: #006622;color:white;">
                                    Auditores&nbsp;&frasl;&nbsp;d&iacute;as
                                </th>
                                <th colspan="2" style="background-color: #006622;color:white;">
                                    Planificado
                                </th>
                                <th colspan="8" style="background-color: #006622;color:white;">
                                    Control del cumplimiento
                                </th>
                                <th rowspan="2" colspan="2" style="background-color: #006622;color:white;">
                                    Control de la entrega de los Planes de Medidas
                                </th>
                                <tr>
                                    <th rowspan="2" style="background-color: #006622;color:white;">F. inicio</th>
                                    <th rowspan="2" style="background-color: #006622;color:white;">F. terminaci&oacute;n</th>
                                    <th rowspan="2" style="background-color: #006622;color:white;">F. inicio</th>
                                    <th rowspan="2" style="background-color: #006622;color:white;">F. terminaci&oacute;n</th>
                                    <th rowspan="2" style="background-color: #006622;color:white;">Calificaci&oacute;n</th>
                                    <th rowspan="2" style="background-color: #006622;color:white;">PHD</th>
                                    <th rowspan="2" style="background-color: #006622;color:white;">PHC</th>
                                    <th colspan="3" style="background-color: #006622;color:white;">Da&ntilde;os econ&oacute;micos</th>
                                </tr>
                                <tr>
                                    <th style="background-color: #006622;color:white;">Plan</th>
                                    <th style="background-color: #006622;color:white;">Real</th>
                                    <th style="background-color: #006622;color:white;">Plan</th>
                                    <th style="background-color: #006622;color:white;">Real</th>
                                    <th style="background-color: #006622;color:white;">Plan</th>
                                    <th style="background-color: #006622;color:white;">Real</th>
                                    <th style="background-color: #006622;color:white;">CUP</th>
                                    <th style="background-color: #006622;color:white;">CUC</th>
                                    <th style="background-color: #006622;color:white;">Otras mon.</th>
                                    <th style="background-color: #006622;color:white;">Sí</th>
                                    <th style="background-color: #006622;color:white;">No</th>
                                </tr>                                
                            </th>                            
                        </tr>
                        <tr style="background-color: #006622;color:white;">
                            <th>1</th><th>2</th><th>3</th><th>4</th><th>5</th><th>6</th>
                            <th>7</th><th>8</th><th>9</th><th>10</th><th>11</th><th>12</th>
                            <th>13</th><th>14</th><th>15</th><th>16</th><th>17</th><th>18</th>
                            <th>19</th><th>20</th><th>21</th><th>22</th><th>23</th><th>24</th>
                            <th>25</th><th>26</th>
                        </tr>
                    </thead>
                    ';

            $content.= '<tbody>';
            foreach ($em as $item){
                $content.='
                            <tr>
                                 <td style="text-align: center;">'.$contador++.'</td>   
                                 <td style="text-align: center;">'.$item->getOrdenTrabajo().'</td>                                    
                                 <td style="text-align: center;">'.$item->getEntidad()->getNombre().'</td>
                                 <td style="text-align: center;">'.$item->getEntidad()->getNit().'</td>
                                 <td style="text-align: center;">'.$item->getEntidad()->getReeup().'</td>
                                 <td style="text-align: center;">'.$item->getTipoAccion()->getNombre().'</td>
                                 <td style="text-align: center;">'.$item->getDirectivas().'</td>
                                 <td style="text-align: center;">'.$item->getParticularidad()->getNombre().'</td>
                                 <td style="text-align: center;">'.$item->getAuditorPlan().'</td>
                                 <td style="text-align: center;">'.$item->getAuditorReal().'</td>
                                 <td style="text-align: center;">'.$item->getDiasPlan().'</td>
                                 <td style="text-align: center;">'.$item->getDiasReal().'</td>
                                 <td style="text-align: center;">'.$item->getAuditorXDiaPlan().'</td>
                                 <td style="text-align: center;">'.$item->getAuditorXDiaReal().'</td>
                                 <td style="text-align: center;">'.$item->ToString($item->getFechaInicioPlan()).'</td>
                                 <td style="text-align: center;">'.$item->ToString($item->getFechaFinPlan()).'</td>
                                 <td style="text-align: center;">'.$item->ToString($item->getFechaInicioReal()).'</td>
                                 <td style="text-align: center;">'.$item->ToString($item->getFechaFinReal()).'</td>
                                 <td style="text-align: center;">'.$item->getCalificacion().'</td>
                                 <td style="text-align: center;">'.count($item->getPhds()).'</td>
                                 <td style="text-align: center;">'.count($item->getPhcs()).'</td>
                                 <td style="text-align: center;">'.$item->getDanoCUP().'</td>
                                 <td style="text-align: center;">'.$item->getDanoCUC().'</td>
                                 <td style="text-align: center;">'.$item->getDanoOtraMoneda().'</td>
                                 <td style="text-align: center;">';
                                 if ($item->getPlanMedidas()){
                                    $content.='X</td><td style="text-align: center;"></td>';
                                 }
                                 else{
                                     $content.='</td><td style="text-align: center;">X</td>';
                                 }
                $content.='</tr>';
                $auditores = $auditores + $item->getAuditorPlan();
                $dias = $dias + $item->getDiasPlan();
                $auditoresXdia = $auditoresXdia + $item->getAuditorXDiaPlan();
                $phd = $phd + count($item->getPhds());
                $phc = $phc + count($item->getPhcs());
                $danoCuc = $danoCuc + $item->getDanoCUC();
                $danoCup = $danoCup + $item->getDanoCUP();
                $danoOM = $danoOM + $item->getDanoOtraMoneda();
            }
                $content.='                              
                          </tbody>
                          <tfoot>
                            <tr>
                                <th></th><th></th>
                                <th style="background-color: #006622;color:white;">Total general</th>
                                <th></th><th></th><th></th><th></th><th></th>
                                <th style="background-color: #006622;color:white;">
                                    '.$auditores.'
                                </th> 
                                <th></th>
                                <th style="background-color: #006622;color:white;">
                                    '.$dias.'
                                </th>
                                <th></th>
                                <th style="background-color: #006622;color:white;">
                                    '.$auditoresXdia.'
                                </th>
                                <th></th><th></th><th></th><th></th><th></th><th></th>
                                <th style="background-color: #006622;color:white;">
                                    '.$phd.'
                                </th>
                                <th style="background-color: #006622;color:white;">
                                    '.$phc.'
                                </th>
                                <th style="background-color: #006622;color:white;">
                                    '.$danoCup.'
                                </th>
                                <th style="background-color: #006622;color:white;">
                                    '.$danoCuc.'
                                </th>
                                <th style="background-color: #006622;color:white;">
                                    '.$danoOM.'
                                </th>
                            </tr>
                        </tfoot>
                    </table><br/><br/>';

            $content.='<table style="text-align: center;font-size: 12px;" cellpadding="2" cellspacing="0" width="100%">  
                        <thead>
                            <tr>
                                <th></th>
                                <th></th>
                                <th style="background-color: #006622;color:white;border: 0.05em solid;">Elaborado por:</th>
                                <th style="background-color: #006622;color:white;border: 0.05em solid;">Aprobado por:</th>
                                <th style="background-color: #006622;color:white;border: 0.05em solid;">Aprobado por:</th>
                            </tr>
                        </thead>';
            $content.= '<tbody>
                            <tr style="border-right: 0.05em solid;border-left: 0.05em solid;">
                                <td style="border-bottom: none; border-left: none; border-right: none;"></td>
                                <td style="border-bottom: none; border-left: none;"></td>
                                <td>Nombres y apellidos: </td>
                                <td>Nombres y apellidos: </td>
                                <td>Nombres y apellidos: </td>
                            </tr>
                            <tr style="border-right: 0.05em solid;border-left: 0.05em solid;">
                                <td style="border-bottom: none; border-left: none; border-right: none;"></td>
                                <td style="border-bottom: none; border-left: none;"></td>
                                <td>Cargo: </td>
                                <td>Cargo: </td>
                                <td>Cargo: </td>
                            </tr>
                            <tr style="border-right: 0.05em solid;border-left: 0.05em solid;">
                                <td style="border-bottom: none; border-left: none; border-right: none;"></td>
                                <td style="border-bottom: none; border-left: none;"></td>
                                <td>Fecha: </td>
                                <td>Fecha: </td>
                                <td>Fecha: </td>
                            </tr>
                            <tr style="border-bottom: 0.05em solid; border-right: 0.05em solid;border-left: 0.05em solid;">
                                <td style="border-bottom: none; border-left: none; border-right: none;"></td>
                                <td style="border-bottom: none; border-left: none;"></td>
                                <td>Firma: </td>
                                <td>Firma: </td>
                                <td>Firma: </td>
                            </tr>
                        </tbody>
                </table><br/><br/><br/></div></body></html>';

        echo $content;
        exit();
    }

    /**
     * Filter all acciones de control by entidades and/or tipos de acciones
     *
     * @Route("/", name="accion_control_filter", methods={"GET", "POST"})
     */
    public function filterAction(Request $request, AccionControlRepository $accionControlRepository)
    {
        $ident = $request->request->get('filtrar_ent');
        $idtipoAccion = $request->request->get('filtrar_tipo_accion');

        $em = $this->getDoctrine()->getManager();

        if($ident=='todas')
        {
            if ($idtipoAccion=='todos')
            {
                return $this->redirectToRoute("accion_control_index");
            }
            else
            {
                $accionControls = $accionControlRepository->findBy(array('tipoAccion'=>$idtipoAccion));
            }
        }
        else
        {
            if ($idtipoAccion=='todos')
            {
                $accionControls = $accionControlRepository->findBy(array('entidad'=>$ident));
            }
            else
            {
                $accionControls = $accionControlRepository->findBy(array('entidad'=>$ident, 'tipoAccion'=>$idtipoAccion));
            }
        }

        $bitacora = new Log();
        $bitacora->setFecha(new \DateTime('now'))
            ->setUsuario($this->getUser())
            ->setIp($request->getClientIp())
            ->setAccion('Filtrar acciones de control');
        $em->persist($bitacora);
        $em->flush();

        return $this->render("accion_control/index.html.twig", array(
            'accionControls' => $accionControls,
            'entidades' => $em->getRepository('App\Entity\Entidad')->findBy(array('activo'=>1)),
            'acciones' => $em->getRepository('App\Entity\TipoAccion')->findBy(array('activo'=>1)),
            'url' => 'planificacion',
            'routes' => ['Planificación', 'Acciones', 'Acción de Control'],
        ));
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/new", name="accion_control_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $accionControl = new AccionControl();
        $form = $this->createForm(AccionControlType::class, $accionControl);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $bitacora = new Log();
            $bitacora->setFecha(new \DateTime('now'))
                ->setUsuario($this->getUser())
                ->setIp($request->getClientIp())
                ->setAccion('Insertar acción de control');
            $entityManager->persist($bitacora);
            $entityManager->persist($accionControl);
            $entityManager->flush();

            $this->addFlash(
                'notice',
                'Acción de control registrada!'
            );

            return $this->redirectToRoute('accion_control_index');
        }

        return $this->render('accion_control/new.html.twig', [
            'accion_control' => $accionControl,
            'form' => $form->createView(),
            'url' => 'planificacion',
            'routes' => ['Planificación', 'Acciones', 'Acción de Control', 'Nueva'],
        ]);
    }

    /**
     * @Route("/{id}", name="accion_control_show", methods={"GET"})
     */
    public function show(AccionControl $accionControl): Response
    {
        return $this->render('accion_control/show.html.twig', [
            'accion_control' => $accionControl,
            'url' => 'planificacion',
            'routes' => ['Planificación', 'Acciones', 'Acción de Control', 'Ver'],
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/{id}/edit", name="accion_control_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, AccionControl $accionControl): Response
    {
        $form = $this->createForm(AccionControlType::class, $accionControl);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bitacora = new Log();
            $bitacora->setFecha(new \DateTime('now'))
                ->setUsuario($this->getUser())
                ->setIp($request->getClientIp())
                ->setAccion('Modificar acción de control: '.$accionControl->getId());
            $this->getDoctrine()->getManager()->persist($bitacora);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'notice',
                'Acción de control actualizada!'
            );

            return $this->redirectToRoute('accion_control_index');
        }

        return $this->render('accion_control/edit.html.twig', [
            'accion_control' => $accionControl,
            'form' => $form->createView(),
            'url' => 'planificacion',
            'routes' => ['Planificación', 'Acciones', 'Acción de Control', 'Editar'],
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/{id}/activate", name="accion_control_activate", methods={"GET","POST"})
     */
    public function activate(Request $request, AccionControl $accionControl): Response
    {
        $accionControl->setActivo(true);
        $bitacora = new Log();
        $bitacora->setFecha(new \DateTime('now'))
            ->setUsuario($this->getUser())
            ->setIp($request->getClientIp())
            ->setAccion('Activar acción de control: '.$accionControl->getId());
        $this->getDoctrine()->getManager()->persist($bitacora);
        $this->getDoctrine()->getManager()->flush();

        $this->addFlash(
            'notice',
            'Acción de control activada!'
        );
        return $this->redirectToRoute('accion_control_index');
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/{id}/deactivate", name="accion_control_deactivate", methods={"GET","POST"})
     */
    public function deactivate(Request $request, AccionControl $accionControl)
    {
        $phd = $accionControl->getPhds();
        $phc = $accionControl->getPhcs();

        if (count($phd) > 0 || count($phc) > 0)
        {
            $this->addFlash(
                'error',
                'No se puede eliminar esta acción de control porque existe alguna relación con un PHD o PHC!'
            );

            return true;
        }
        else
        {
            $accionControl->setActivo(false);
            $bitacora = new Log();
            $bitacora->setFecha(new \DateTime('now'))
                ->setUsuario($this->getUser())
                ->setIp($request->getClientIp())
                ->setAccion('Desactivar acción de control: '.$accionControl->getId());
            $this->getDoctrine()->getManager()->persist($bitacora);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'notice',
                'Acción de control desactivada!'
            );

            return false;
        }
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/{id}", name="accion_control_delete", methods={"DELETE"})
     */
    public function delete(Request $request, AccionControl $accionControl): Response
    {
        if ($this->isCsrfTokenValid('delete'.$accionControl->getId(), $request->request->get('_token'))) {
            if ($this->deactivate($request, $accionControl))
            {
                return $this->redirectToRoute('accion_control_show', array('id'=>$accionControl->getId()));
            }
        }

        return $this->redirectToRoute('accion_control_index');
    }

    /**
     * Filter all auditores by vence R.N.A.
     *
     * @Route("_download_pdf", name="accion_control_download_pdf", methods={"GET","POST"})
     */
    public function pdf(Request $request)
    {
        $auditor = $request->request->get('auditor');
        $dias = $request->request->get('dia');
        $auditorXdia = $request->request->get('auditorXdia');
        $fechaInicio = $request->request->get('fechaInicio');
        $fechaFin = $request->request->get('fechaFin');
        /*$conn = $this->getDoctrine()->getConnection();*/
        /*$auditors = $conn
            ->fetchAll('SELECT accion_control.id,accion_control.activo,entidad.nombre AS entidad,municipio.nombre AS municipio 
                    FROM accion_control,entidad,tipo_accion,particularidad,combustible
                    WHERE hc.entidad_id=entidad.id 
                    AND hc.municipio_id=municipio.id');*/
        $auditors = $this->getDoctrine()->getManager()->getRepository('App\Entity\AccionControl')->findAll();
        $reporte_name = 'Reporte de las acciones de control - '.date('Y/m/d h:i:s a');

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
            	<h2 style="text-align:center;">Consta de '.count($auditors).' acciones de control</h2>
           	</div>
        </div>
    <table border="0" cellpadding="3">
        <thead>
          <tr style="color: whitesmoke; background-color: #223444;">';
        $content .= '
            <th>N<sup>o</sup> Orden Trab.</th>
            <th>P.H.D.</th>
            <th>P.H.C.</th>
            <th>Calif.</th>
            <th>Entidad</th>
            <th>T. acci&oacute;n</th>
            <th>Part.</th>
            <th>C.(Eval)</th>
            <th>Directivas</th>
            <th>Cant. auditor</th>
            <th>Cant. d&iacute;as</th>
            <th>Auditor/d&iacute;a</th>
            <th>F. inicio</th>
            <th>F. fin</th>
            <th>D. CUP</th>
            <th>D. CUC</th>
            <th>D. Otras</th>
            ';
        $content .= '
          </tr>
        </thead>
	';
        /**
         * @var AccionControl $index
         */
        foreach ($auditors as $index/* => $user*/) {
            if($index->getActivo()=='1'){  $color= '#3f51b5'; }else{ $color= '#ff4081'; }
            $content .= '
		<tr style="color: #FFFFFF;" bgcolor="'.$color.'">
		    <td>'.$index->getOrdenTrabajo().'</td>
		    <td>'.$index->getPhds()->count().'</td>
		    <td>'.$index->getPhcs()->count().'</td>
		    <td>'.$index->getCalificacion().'</td>
            <td>'.$index->getEntidad()->getNombre().'</td>
            <td>'.$index->getTipoAccion()->getNombre().'</td>
            <td>'.$index->getParticularidad()->getSiglas().'</td>
            <td>'.$index->getCombustible()->getEvaluacion().'</td>
            <td>'.$index->getDirectivas().'</td>';
            $content.='<td>'.(($auditor=="plan") ? $index->getAuditorPlan() : $index->getAuditorReal()).'</td>';
            $content.='<td>'.(($dias=="plan") ? $index->getAuditorPlan() : $index->getDiasReal()).'</td>';
            $content.='<td>'.(($auditorXdia=="plan") ? $index->getAuditorPlan() : $index->getAuditorXDiaReal()).'</td>';
            $content.='<td>'.(($fechaInicio=="plan") ? $index->ToString($index->getFechaInicioPlan()) : $index->ToString($index->getFechaInicioReal())).'</td>';
            $content.='<td>'.(($fechaFin=="plan") ? $index->ToString($index->getFechaFinPlan()) : $index->ToString($index->getFechaFinReal())).'</td>';
            $content.='<td>'.$index->getDanoCUP().'</td>
            <td>'.$index->getDanoCUC().'</td>
            <td>'.$index->getDanoOtraMoneda().'</td>
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
        $pdf->SetFont('Helvetica', '', 5);
        $pdf->writeHTML($content, true, 0, true, 0);

        $pdf->lastPage();
        $pdf->output('Reporte Acciones de control STA '.date('Y/m/d').'.pdf', 'D');

        $em = $this->getDoctrine()->getManager();
        $bitacora = new Log();
        $bitacora->setFecha(new \DateTime('now'))
            ->setUsuario($this->getUser())
            ->setIp($request->getClientIp())
            ->setAccion('Crear reporte acciones de control');

        $em->persist($bitacora);
        $em->flush();

        return $this->redirectToRoute('accion_control_index');
    }
}
