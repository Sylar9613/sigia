<?php

namespace App\Controller;

use App\Entity\HC;
use App\Entity\Log;
use App\Entity\Phc;
use App\Form\HCType;
use App\Repository\HCRepository;
use App\Service\HtmlToDoc;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/hecho_de_corrupcion")
 */
class HCController extends AbstractController
{
    /**
     * @Route("/", name="hc_index", methods={"GET"})
     */
    public function index(HCRepository $hCRepository, Request $request): Response
    {
       return $this->render('hc/index.html.twig', [
            'hcs' => $hCRepository->findAll(),
            'entidades' => $this->getDoctrine()->getManager()->getRepository('App\Entity\Entidad')->findBy(array('activo'=>1)),
            'municipios' => $this->getDoctrine()->getManager()->getRepository('App\Entity\Municipio')->findBy(array('activo'=>1)),
            'url' => 'planificacion',
            'routes' => ['Planificación', 'Daños', 'HC'],
        ]);
    }

    /**
     * @Route("/to_word", name="hc_to_word")
     */
    public function hcToMSWord(Request $request)
    {
        $htd = new HtmlToDoc();
        $phc = new Phc();
        /**
         * @var HC $item
         */
        $em = $this->getDoctrine()->getManager()->getRepository('App\Entity\HC')->findAll();
        $htmlContent = '';
        foreach ($em as $item){
            $htmlContent .= '
            <div style="font-size: 16px; font-family: Arial, Helvetica, sans-serif;">
            <h2>Anexo I</h2>
            <h3 style="text-align:right;">C&oacute;digo de la CGR: <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u></h3><br/>
            <h2>Reporte hecho de corrupci&oacute;n administrativa</h2>
            <p>OACE/Entidad Nacional/CAP: <u>'.$item->getPhc()->getCategoria().'</u>&nbsp;&nbsp;&nbsp;N&uacute;mero: <u>'.$item->getNumeroExpediente().'</u></p>
            <p>Nombre de la entidad: <u>'.$item->getPhc()->getEntidad()->getNombre().'</u></p>
            <p>Radicada en: provincia: <u>'.$item->getPhc()->getProvincia().'</u>, Municipio: <u>'.$item->getPhc()->getMunicipio()->getNombre().'</u></p>
            <p>Organizaci&oacute;n superior a la que se subordina: <u>'.$item->getPhc()->getEntidad()->getOsde()->getNombre().'</u></p>
            <p>S&iacute;ntesis del objeto social de la entidad:</p>
            <p style="text-align: justify;">'.$item->getObjetoSocialEntidad().'</p>
            <p>Fuente o v&iacute;a por la que se detect&oacute; el caso: <u>'.$item->getPhc()->getFuente().'</u></p>
            <p>Fecha de detecci&oacute;n: <u>'.$phc->ToString($item->getPhc()->getFechaDeteccion()).'</u></p>
            <p>Fecha de ocurrencia: <u>'.$phc->ToString($item->getPhc()->getFechaOcurrencia()).'</u></p>
            <p><b>Resumen del hecho reportado:</b></p>
            <p style="text-align: justify;">'.$item->getResumen().'</p>
            <p><b>S&iacute;ntesis de las causas y condiciones que lo propiciaron:</b></p>
            <p>Total de implicados: <u>'.$item->getTotalImplicados().'</u> de la entidad: <u>'.$item->getTotalImplicadosEntidad().'</u> de otras entidades: <u>'.$item->getTotalImplicadosOtras().'</u></p>
            <br/>
            <b>Relaci&oacute;n de implicados de la entidad:</b>
            <table cellpadding="2" cellspacing="0" style="border: solid 1px; font-size: 16px; font-family: Arial, Helvetica, sans-serif;">
                <thead>
                    <tr style="color: whitesmoke; background-color: #3f51b5;">
                        <th style="border: solid 1px;text-align: center;">No.</th>
                        <th style="border: solid 1px;text-align: center;">Nombre y apellidos</th>
                        <th style="border: solid 1px;text-align: center;">Cargo</th>
                        <th style="border: solid 1px;text-align: center;">Categor&iacute;a ocupacional</th>
                        <th style="border: solid 1px;text-align: center;">Nivel de direcci&oacute;n</th>
                        <th style="border: solid 1px;text-align: center;">Nivel escolar</th>
                        <th style="border: solid 1px;text-align: center;">PCC</th>
                        <th style="border: solid 1px;text-align: center;">UJC</th>
                        <th style="border: solid 1px;text-align: center;">Edad</th>
                        <th style="border: solid 1px;text-align: center;">Sexo</th>
                    </tr>
                </thead>    
                <tbody>
                ';
            $contador = 1;
            foreach ($item->getImplicados() as $implicado){
                $htmlContent.='<tr>';
                $htmlContent .= '<td style="border: solid 1px;text-align: center;">'.$contador++.'</td>';
                $htmlContent .= '<td style="border: solid 1px;text-align: center;">'.$implicado->getNombre().'</td>';
                $htmlContent .= '<td style="border: solid 1px;text-align: center;">'.$implicado->getCargo().'</td>';
                $htmlContent .= '<td style="border: solid 1px;text-align: center;">'.$implicado->getCategoriaOcupacional().'</td>';
                $htmlContent .= '<td style="border: solid 1px;text-align: center;">'.$implicado->getNivelDireccion().'</td>';
                $htmlContent .= '<td style="border: solid 1px;text-align: center;">'.$implicado->getEscolaridad().'</td>';
                $htmlContent .= '<td style="border: solid 1px;text-align: center;">';
                if ($implicado->getPcc()==0){
                    $htmlContent.='-</td>';
                }
                else{
                    $htmlContent.='S&iacute;</td>';
                }
                $htmlContent .= '<td style="border: solid 1px;text-align: center;">';
                if ($implicado->getUjc()==0){
                    $htmlContent.='-</td>';
                }
                else{
                    $htmlContent.='S&iacute;</td>';
                }
                $htmlContent .= '<td style="border: solid 1px;text-align: center;">'.$implicado->getEdad().'</td>';
                $htmlContent .= '<td style="border: solid 1px;text-align: center;">'.$implicado->getSexo().'</td>';
                $htmlContent.='</tr>';
            }
            $htmlContent.='
                </tbody>
            </table>
            <br/>
            <p><b>Medidas disciplinarias aplicadas, especificar por implicados:</b></p>
            <p><b>Relaci&oacute;n de responsables colaterales</b></p>
            <table cellpadding="2" cellspacing="0" style="border: solid 1px; font-size: 16px; font-family: Arial, Helvetica, sans-serif;">
                <thead>
                    <tr style="color: whitesmoke; background-color: #3f51b5;">
                        <th style="border: solid 1px;text-align: center;">No.</th>
                        <th style="border: solid 1px;text-align: center;">Nombre y apellidos</th>
                        <th style="border: solid 1px;text-align: center;">Cargo</th>
                        <th style="border: solid 1px;text-align: center;">Nivel de direcci&oacute;n</th>
                        <th style="border: solid 1px;text-align: center;">Medidas aplicadas</th>
                        <th style="border: solid 1px;text-align: center;">Total Med.</th>
                        <th style="border: solid 1px;text-align: center;">Med. Pendt.</th>
                        <th style="border: solid 1px;text-align: center;">PCC</th>
                        <th style="border: solid 1px;text-align: center;">UJC</th>
                    </tr>
                </thead>   
                <tbody>
                ';
            $contadorMed = 1;
            foreach ($item->getImplicados() as $implicado) {
                $htmlContent.='<tr>';
                $htmlContent .= '<td style="border: solid 1px;text-align: center;">'.$contadorMed++.'</td>';
                $htmlContent .= '<td style="border: solid 1px;text-align: center;">'.$implicado->getNombre().'</td>';
                $htmlContent .= '<td style="border: solid 1px;text-align: center;">'.$implicado->getCargo().'</td>';
                $htmlContent .= '<td style="border: solid 1px;text-align: center;">'.$implicado->getNivelDireccion().'</td>';
                $htmlContent .= '<td style="border: solid 1px;text-align: center;">'.$implicado->getResponsabilidad()->getMedidaDisciplinaria()->getNombre().'</td>';
                $htmlContent .= '<td style="border: solid 1px;text-align: center;">'.$implicado->getResponsabilidad()->getMedidasTotal().'</td>';
                $htmlContent .= '<td style="border: solid 1px;text-align: center;">'.$implicado->getResponsabilidad()->getMedidasPendientes().'</td>';
                $htmlContent .= '<td style="border: solid 1px;text-align: center;">';
                if ($implicado->getPcc()==0){
                    $htmlContent.='-</td>';
                }
                else{
                    $htmlContent.='S&iacute;</td>';
                }
                $htmlContent .= '<td style="border: solid 1px;text-align: center;">';
                if ($implicado->getUjc()==0){
                    $htmlContent.='-</td>';
                }
                else{
                    $htmlContent.='S&iacute;</td>';
                }
                $htmlContent.='</tr>';
            }
            $htmlContent.='
                </tbody>    
            </table>
            <br/>
            <p style="text-align: justify"><b>
                Tanto para implicados como para los responsables colaterales especificar la 
                situaci&oacute;n de las medidas pendientes y en el caso de no aplicación de 
                medida disciplinaria, explicar las causas:
            </b></p>
            <p>
                <b>Afectaci&oacute;n econ&oacute;mica:</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <b>Recuperado:</b><br/>
                <b>CUP</b>&nbsp;&nbsp;<u>'.$item->getAfectacionEconomicaCUP().'</u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <b>CUP</b>&nbsp;&nbsp;<u>'.$item->getRecuperadoCUP().'</u><br/>
                <b>CUC</b>&nbsp;&nbsp;<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <b>CUC</b>&nbsp;&nbsp;<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u>
            </p>
            <p><b>(Si es en otra moneda convertirla a CUC)</b></p>
            <br/>
            <p><b>Observaciones:</b></p>
            <br/>
            <p><b>Reportado por:</b></p>
            <table cellpadding="2" cellspacing="0" style="border: solid 1px; font-size: 16px; font-family: Arial, Helvetica, sans-serif;">
                <thead>
                    <tr>
                        <th style="border: solid 1px;text-align: center;">Nombre y apellidos</th>
                        <th style="border: solid 1px;text-align: center;">Cargo</th>
                        <th style="border: solid 1px;text-align: center;">Firma</th>
                        <th style="border: solid 1px;text-align: center;">Fecha</th>
                    </tr>
                </thead> 
                <tbody>
                    <tr>
                        <td style="border: solid 1px;"></td>
                        <td style="border: solid 1px;"></td>
                        <td style="border: solid 1px;"></td>
                        <td style="border: solid 1px;"></td>
                    </tr>
                </tbody>
            </table>
            <br/>
            <p><b>Revisado por CGR:</b></p>
            <table cellpadding="2" cellspacing="0" style="border: solid 1px; font-size: 16px; font-family: Arial, Helvetica, sans-serif;">
                <thead>
                    <tr>
                        <th style="border: solid 1px;text-align: center;">Nombre y apellidos</th>
                        <th style="border: solid 1px;text-align: center;">Cargo</th>
                        <th style="border: solid 1px;text-align: center;">Firma</th>
                        <th style="border: solid 1px;text-align: center;">Fecha</th>
                    </tr>
                </thead> 
                <tbody>
                    <tr>
                        <td style="border: solid 1px;"></td>
                        <td style="border: solid 1px;"></td>
                        <td style="border: solid 1px;"></td>
                        <td style="border: solid 1px;"></td>
                    </tr>
                </tbody>
            </table>
            </div>
            <br/><br/><br/><br/><br/><br/><br/><br/><br/>
        ';
        }

        $htd->createDoc($htmlContent,'HC',1);
        exit();
    }

    /**
     * Filter all hcs by entidades and/or municipios
     *
     * @Route("/", name="hc_filter", methods={"GET", "POST"})
     */
    public function filterAction(Request $request, HCRepository $hcRepository)
    {
        $ident = $request->request->get('filtrar_ent');
        $idmun = $request->request->get('filtrar_mun');

        $em = $this->getDoctrine()->getManager();

        if($ident=='todas')
        {
            if ($idmun=='todos')
            {
                return $this->redirectToRoute("hc_index");
            }
            else
            {
                $phc = $em->getRepository('App\Entity\Phc')->findBy(array('municipio'=>$idmun));
                $hcs = $hcRepository->findBy(array('phc'=>$phc));
            }
        }
        else
        {
            if ($idmun=='todos')
            {
                $phc = $em->getRepository('App\Entity\Phc')->findBy(array('entidad'=>$ident));
                $hcs = $hcRepository->findBy(array('phc'=>$phc));
            }
            else
            {
                $phc = $em->getRepository('App\Entity\Phc')->findBy(array('municipio'=>$idmun, 'entidad'=>$ident));
                $hcs = $hcRepository->findBy(array('phc'=>$phc));
            }
        }

        $bitacora = new Log();
        $bitacora->setFecha(new \DateTime('now'))
            ->setUsuario($this->getUser())
            ->setIp($request->getClientIp())
            ->setAccion('Filtrar HC');
        $em->persist($bitacora);
        $em->flush();

        return $this->render("hc/index.html.twig", array(
            'hcs' => $hcs,
            'entidades' => $em->getRepository('App\Entity\Entidad')->findBy(array('activo'=>1)),
            'municipios' => $em->getRepository('App\Entity\Municipio')->findBy(array('activo'=>1)),
            'url' => 'planificacion',
            'routes' => ['Planificación', 'Daños', 'HC'],
        ));
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/new", name="hc_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());
        $hC = new HC();
        $form = $this->createForm(HCType::class, $hC);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $bitacora = new Log();
            $bitacora->setFecha(new \DateTime('now'))
                ->setUsuario($this->getUser())
                ->setIp($request->getClientIp())
                ->setAccion('Insertar hecho de corrupción');
            $entityManager->persist($bitacora);
            $entityManager->persist($hC);
            $entityManager->flush();

            $this->addFlash(
                'notice',
                'Hecho de corrupción registrado!'
            );
            return $this->redirectToRoute('hc_index');
        }

        return $this->render('hc/new.html.twig', [
            'hc' => $hC,
            'form' => $form->createView(),
            'url' => 'planificacion',
            'routes' => ['Planificación', 'Daños', 'HC', 'Nuevo'],
        ]);
    }

    /**
     * @Route("/{id}", name="hc_show", methods={"GET"})
     */
    public function show(HC $hC, Request $request): Response
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());
        return $this->render('hc/show.html.twig', [
            'hc' => $hC,
            'url' => 'planificacion',
            'routes' => ['Planificación', 'Daños', 'HC', 'Ver'],
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/{id}/edit", name="hc_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, HC $hC): Response
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());
        $form = $this->createForm(HCType::class, $hC);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bitacora = new Log();
            $bitacora->setFecha(new \DateTime('now'))
                ->setUsuario($this->getUser())
                ->setIp($request->getClientIp())
                ->setAccion('Modificar hecho de corrupción: '.$hC->getId());
            $this->getDoctrine()->getManager()->persist($bitacora);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'notice',
                'Hecho de corrupción actualizado!'
            );

            return $this->redirectToRoute('hc_index');
        }

        return $this->render('hc/edit.html.twig', [
            'hc' => $hC,
            'form' => $form->createView(),
            'url' => 'planificacion',
            'routes' => ['Planificación', 'Daños', 'HC', 'Editar'],
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/{id}/activate", name="hc_activate", methods={"GET","POST"})
     */
    public function activate(Request $request, HC $hC): Response
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());
        $hC->setActivo(true);
        $bitacora = new Log();
        $bitacora->setFecha(new \DateTime('now'))
            ->setUsuario($this->getUser())
            ->setIp($request->getClientIp())
            ->setAccion('Activar hecho de corrupción: '.$hC->getId());
        $this->getDoctrine()->getManager()->persist($bitacora);
        $this->getDoctrine()->getManager()->flush();

        $this->addFlash(
            'notice',
            'Hecho de corrupción activado!'
        );
        return $this->redirectToRoute('hc_index');
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/{id}/deactivate", name="hc_deactivate", methods={"GET","POST"})
     */
    public function deactivate(Request $request, HC $hC)
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());

        $implicado = $hC->getImplicado();
        $responsabilidad = $hC->getResponsabilidad();

        if (count($implicado) > 0 || count($responsabilidad) > 0)
        {
            $this->addFlash(
                'error',
                'No se puede eliminar este hecho de corrupción porque tiene asociada alguna responsabilidad administrativa!'
            );

            return true;
        }
        else
        {
            $hC->setActivo(false);
            $bitacora = new Log();
            $bitacora->setFecha(new \DateTime('now'))
                ->setUsuario($this->getUser())
                ->setIp($request->getClientIp())
                ->setAccion('Desactivar hecho de corrupción: '.$hC->getId());
            $this->getDoctrine()->getManager()->persist($bitacora);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'notice',
                'Hecho de corrupción desactivado!'
            );

            return false;
        }

    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/{id}", name="hc_delete", methods={"DELETE"})
     */
    public function delete(Request $request, HC $hC): Response
    {
        if ($this->isCsrfTokenValid('delete'.$hC->getId(), $request->request->get('_token'))) {
            if ($this->deactivate($request, $hC))
            {
                return $this->redirectToRoute('hc_show', array('id'=>$hC->getId()));
            }
        }

        return $this->redirectToRoute('hc_index');
    }

    /**
     * Filter all auditores by vence R.N.A.
     *
     * @Route("_download_pdf", name="hc_download_pdf", methods={"GET","POST"})
     */
    public function pdf(Request $request)
    {
        $conn = $this->getDoctrine()->getConnection();
        $auditors = $conn
            ->fetchAll('SELECT hc.id,hc.activo,numero_expediente,categoria,fuente,fecha_deteccion,fecha_ocurrencia,total_implicados_entidad,total_implicados_otras,afectacion_economica_cup,recuperado_cup,entidad.nombre AS entidad,municipio.nombre AS municipio 
                    FROM hc,phc,entidad,municipio 
                    WHERE hc.phc_id=phc.id
                    AND phc.entidad_id=entidad.id 
                    AND phc.municipio_id=municipio.id');

        $reporte_name = 'Reporte de los hechos de corrupción administrativa - '.date('Y/m/d h:i:s a');

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
            	<h2 style="text-align:center;">Consta de '.count($auditors).' hechos de corrupci&oacute;n</h2>
           	</div>
        </div>
    <table border="0" cellpadding="3">
        <thead>
          <tr style="color: whitesmoke; background-color: #223444;">';
        $content .= '
            <th>N<sup>o</sup> Exp.</th>
            <th>Entidad</th>
            <th>Municipio</th>
            <th>Categor&iacute;a</th>
            <th>Fuente</th>
            <th>F. det</th>
            <th>F. ocu</th>
            <th>Imp. entidad</th>
            <th>Imp. otra</th>
            <th>Da&ntilde;os CUP</th>
            <th>Recuperado CUP</th>
            ';
        $content .= '
          </tr>
        </thead>
	';

        foreach ($auditors as $index => $user) {
            if($user['activo']=='1'){  $color= '#3f51b5'; }else{ $color= '#ff4081'; }
            $content .= '
		<tr style="color: #FFFFFF;" bgcolor="'.$color.'">
		    <td>'.$user['numero_expediente'].'</td>
            <td>'.$user['entidad'].'</td>
            <td>'.$user['municipio'].'</td>
            <td>'.$user['categoria'].'</td>
            <td>'.$user['fuente'].'</td>
            <td>'.$user['fecha_deteccion'].'</td>
            <td>'.$user['fecha_ocurrencia'].'</td>
            <td>'.$user['total_implicados_entidad'].'</td>
            <td>'.$user['total_implicados_otras'].'</td>
            <td>'.$user['afectacion_economica_cup'].'</td>
            <td>'.$user['recuperado_cup'].'</td>
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
        $pdf->SetFont('Helvetica', '', 6);
        $pdf->writeHTML($content, true, 0, true, 0);

        $pdf->lastPage();
        $pdf->output('Reporte H.C. STA '.date('Y/m/d').'.pdf', 'D');

        $em = $this->getDoctrine()->getManager();
        $bitacora = new Log();
        $bitacora->setFecha(new \DateTime('now'))
            ->setUsuario($this->getUser())
            ->setIp($request->getClientIp())
            ->setAccion('Crear reporte HC');

        $em->persist($bitacora);
        $em->flush();

        return $this->redirectToRoute('hc_index');
    }
}
