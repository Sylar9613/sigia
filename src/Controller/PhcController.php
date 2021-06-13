<?php

namespace App\Controller;

use App\Entity\HC;
use App\Entity\Log;
use App\Entity\Phc;
use App\Form\PhcType;
use App\Repository\PhcRepository;
use App\Service\HtmlToDoc;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/phc")
 */
class PhcController extends AbstractController
{
    /**
     * @Route("/", name="phc_index", methods={"GET"})
     */
    public function index(PhcRepository $phcRepository, Request $request): Response
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());
        return $this->render('phc/index.html.twig', [
            'phcs' => $phcRepository->findAll(),
            'entidades' => $this->getDoctrine()->getManager()->getRepository('App\Entity\Entidad')->findBy(array('activo'=>1)),
            'municipios' => $this->getDoctrine()->getManager()->getRepository('App\Entity\Municipio')->findBy(array('activo'=>1)),
            'url' => 'planificacion',
            'routes' => ['Planificación', 'Daños', 'PHC'],
        ]);
    }

    /**
     * @Route("/to_word", name="phc_to_word")
     */
    public function toMSWord(Request $request)
    {
        $htd = new HtmlToDoc();
        $phc = new Phc();
        /**
         * @var Phc $item
         */
        $em = $this->getDoctrine()->getManager()->getRepository('App\Entity\Phc')->findAll();
        $htmlContent = '';
        foreach ($em as $item){
            $htmlContent .= '
            <div style="font-size: 16px; font-family: Arial, Helvetica, sans-serif;">
            <h2>Anexo II</h2>
            <h2>Informaci&oacute;n previa del presunto hecho de corrupci&oacute;n (PHC)</h2>
            <p>OACE/Entidad Nacional/CAP: <u>'.$item->getCategoria().'</u>&nbsp;&nbsp;&nbsp;N&uacute;mero: <u>'.$item->getNumero().'</u></p>
            <p>Organizaci&oacute;n superior a la que se subordina: <u>'.$item->getEntidad()->getOsde()->getNombre().'</u></p>
            <p>Nombre de la entidad: <u>'.$item->getEntidad()->getNombre().'</u></p>
            <p>Radicada en: provincia: <u>'.$item->getProvincia().'</u>, Municipio: <u>'.$item->getMunicipio()->getNombre().'</u></p>
            <p>Fuente o v&iacute;a por la que se detect&oacute; el caso: <u>'.$item->getFuente().'</u></p>
            <p>Fecha de detecci&oacute;n: <u>'.$phc->ToString($item->getFechaDeteccion()).'</u></p>
            <p>Fecha de ocurrencia: <u>'.$phc->ToString($item->getFechaOcurrencia()).'</u></p>
            <p><b>Resumen del hecho:</b></p>
            <p style="text-align: justify;">'.$item->getResumen().'</p>
            </div>
            <br/><br/><br/><br/><br/><br/><br/><br/><br/>
        ';
        }

        $htd->createDoc($htmlContent,'PHC',1);
        exit();
    }

    /**
     * Filter all phcs by entidades and/or municipios
     *
     * @Route("/", name="phc_filter", methods={"GET", "POST"})
     */
    public function filterAction(Request $request, PhcRepository $phcRepository)
    {
        $ident = $request->request->get('filtrar_ent');
        $idmun = $request->request->get('filtrar_mun');

        $em = $this->getDoctrine()->getManager();

        if($ident=='todas')
        {
            if ($idmun=='todos')
            {
                return $this->redirectToRoute("phc_index");
            }
            else
            {
                $phcs = $phcRepository->findBy(array('municipio'=>$idmun));
            }
        }
        else
        {
            if ($idmun=='todos')
            {
                $phcs = $phcRepository->findBy(array('entidad'=>$ident));
            }
            else
            {
                $phcs = $phcRepository->findBy(array('entidad'=>$ident, 'municipio'=>$idmun));
            }
        }

        $bitacora = new Log();
        $bitacora->setFecha(new \DateTime('now'))
            ->setUsuario($this->getUser())
            ->setIp($request->getClientIp())
            ->setAccion('Filtrar PHC');
        $em->persist($bitacora);
        $em->flush();

        return $this->render("phc/index.html.twig", array(
            'phcs' => $phcs,
            'entidades' => $em->getRepository('App\Entity\Entidad')->findBy(array('activo'=>1)),
            'municipios' => $em->getRepository('App\Entity\Municipio')->findBy(array('activo'=>1)),
            'url' => 'planificacion',
            'routes' => ['Planificación', 'Daños', 'PHC'],
        ));
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/new", name="phc_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());
        $phc = new Phc();
        $form = $this->createForm(PhcType::class, $phc);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $bitacora = new Log();
            $bitacora->setFecha(new \DateTime('now'))
                ->setUsuario($this->getUser())
                ->setIp($request->getClientIp())
                ->setAccion('Insertar PHC');
            $entityManager->persist($bitacora);
            $entityManager->persist($phc);
            $entityManager->flush();

            $this->addFlash(
                'notice',
                'PHC registrado!'
            );
            return $this->redirectToRoute('phc_index');
        }

        return $this->render('phc/new.html.twig', [
            'phc' => $phc,
            'form' => $form->createView(),
            'url' => 'planificacion',
            'routes' => ['Planificación', 'Daños', 'PHC', 'Nuevo'],
        ]);
    }

    /**
     * @Route("/{id}", name="phc_show", methods={"GET"})
     */
    public function show(Phc $phc, Request $request): Response
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());
        return $this->render('phc/show.html.twig', [
            'phc' => $phc,
            'url' => 'planificacion',
            'routes' => ['Planificación', 'Daños', 'PHC', 'Ver'],
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/{id}/edit", name="phc_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Phc $phc): Response
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());
        $form = $this->createForm(PhcType::class, $phc);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bitacora = new Log();
            $bitacora->setFecha(new \DateTime('now'))
                ->setUsuario($this->getUser())
                ->setIp($request->getClientIp())
                ->setAccion('Modificar PHC: '.$phc->getId());
            $this->getDoctrine()->getManager()->persist($bitacora);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'notice',
                'PHC actualizado!'
            );

            return $this->redirectToRoute('phc_index');
        }

        return $this->render('phc/edit.html.twig', [
            'phc' => $phc,
            'form' => $form->createView(),
            'url' => 'planificacion',
            'routes' => ['Planificación', 'Daños', 'PHC', 'Editar'],
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/{id}/activate", name="phc_activate", methods={"GET","POST"})
     */
    public function activate(Request $request, Phc $phc): Response
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());
        $phc->setActivo(true);
        $bitacora = new Log();
        $bitacora->setFecha(new \DateTime('now'))
            ->setUsuario($this->getUser())
            ->setIp($request->getClientIp())
            ->setAccion('Activar PHC: '.$phc->getId());
        $this->getDoctrine()->getManager()->persist($bitacora);
        $this->getDoctrine()->getManager()->flush();

        $this->addFlash(
            'notice',
            'PHC activado!'
        );
        return $this->redirectToRoute('phc_index');
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/{id}/deactivate", name="phc_deactivate", methods={"GET","POST"})
     */
    public function deactivate(Request $request, Phc $phc)
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());
        $phc->setActivo(false);
        $bitacora = new Log();
        $bitacora->setFecha(new \DateTime('now'))
            ->setUsuario($this->getUser())
            ->setIp($request->getClientIp())
            ->setAccion('Desactivar PHC: '.$phc->getId());
        $this->getDoctrine()->getManager()->persist($bitacora);
        $this->getDoctrine()->getManager()->flush();

        $this->addFlash(
            'notice',
            'PHC desactivado!'
        );

        return false;
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/{id}", name="phc_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Phc $phc): Response
    {
        if ($this->isCsrfTokenValid('delete'.$phc->getId(), $request->request->get('_token'))) {
            if ($this->deactivate($request, $phc))
            {
                return $this->redirectToRoute('phc_show', array('id'=>$phc->getId()));
            }
        }

        return $this->redirectToRoute('phc_index');
    }

    /**
     * Filter all auditores by vence R.N.A.
     *
     * @Route("_download_pdf", name="phc_download_pdf", methods={"GET","POST"})
     */
    public function pdf(Request $request)
    {
        $conn = $this->getDoctrine()->getConnection();
        $auditors = $conn
            ->fetchAll('SELECT phc.id,phc.activo,numero,categoria,fuente,fecha_deteccion,fecha_ocurrencia,entidad.nombre AS entidad,municipio.nombre AS municipio 
                    FROM phc,entidad,municipio 
                    WHERE phc.entidad_id=entidad.id 
                    AND phc.municipio_id=municipio.id');

        $reporte_name = 'Reporte de los Presuntos Hechos de Corrupción(P.H.C.) - '.date('Y/m/d h:i:s a');

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
            	<h2 style="text-align:center;">Consta de '.count($auditors).' P.H.C.</h2>
           	</div>
        </div>
    <table border="0" cellpadding="3">
        <thead>
          <tr style="color: whitesmoke; background-color: #223444;">';
        $content .= '
            <th>N<sup>o</sup></th>
            <th>Entidad</th>
            <th>Municipio</th>
            <th>Categor&iacute;a</th>
            <th>Fuente</th>
            <th>F. det</th>
            <th>F. ocu</th>
            ';
        $content .= '
          </tr>
        </thead>
	';

        foreach ($auditors as $index => $user) {
            if($user['activo']=='1'){  $color= '#3f51b5'; }else{ $color= '#ff4081'; }
            $content .= '
		<tr style="color: #FFFFFF;" bgcolor="'.$color.'">
		    <td>'.$user['numero'].'</td>
            <td>'.$user['entidad'].'</td>
            <td>'.$user['municipio'].'</td>
            <td>'.$user['categoria'].'</td>
            <td>'.$user['fuente'].'</td>
            <td>'.$user['fecha_deteccion'].'</td>
            <td>'.$user['fecha_ocurrencia'].'</td>
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
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->writeHTML($content, true, 0, true, 0);

        $pdf->lastPage();
        $pdf->output('Reporte P.H.C. STA '.date('Y/m/d').'.pdf', 'D');

        $em = $this->getDoctrine()->getManager();
        $bitacora = new Log();
        $bitacora->setFecha(new \DateTime('now'))
            ->setUsuario($this->getUser())
            ->setIp($request->getClientIp())
            ->setAccion('Crear reporte PHC');

        $em->persist($bitacora);
        $em->flush();

        return $this->redirectToRoute('phc_index');
    }
}
