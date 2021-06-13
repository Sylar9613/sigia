<?php

namespace App\Controller;

use App\Entity\Auditor;
use App\Entity\Log;
use App\Form\AuditorType;
use App\Repository\AuditorRepository;
use App\Repository\CargoRepository;
use App\Repository\EntidadRepository;
use App\Repository\LocalidadRepository;
use App\Repository\MunicipioRepository;
use App\Repository\NivelRepository;
use App\Repository\OrganismoRepository;
use App\Repository\OsdeRepository;
use App\Repository\PlazaRepository;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


/**
 * @Route("/auditor")
 */
class AuditorController extends AbstractController
{
    /**
     * @Route("/", name="auditor_index", methods={"GET"})
     */
    public function index(AuditorRepository $auditorRepository, Request $request, OrganismoRepository $organismoRepository, OsdeRepository $osdeRepository, EntidadRepository $entidadRepository, CargoRepository $cargoRepository, NivelRepository $nivelRepository, MunicipioRepository $municipioRepository, LocalidadRepository $localidadRepository, PlazaRepository $plazaRepository): Response
    {
        return $this->render('auditor/index.html.twig', [
            'auditors' => $auditorRepository->findAll(),
            'url' => 'auditor',
            'routes' => ['Personal', 'Auditor'],
            'organismos' => $organismoRepository->findBy(array('activo'=>1)),
            'osdes' => $osdeRepository->findBy(array('activo'=>1)),
            'entidads' => $entidadRepository->findBy(array('activo'=>1)),
            'nivels' => $nivelRepository->findBy(array('activo'=>1)),
            'municipios' => $municipioRepository->findBy(array('activo'=>1)),
            'localidads' => $localidadRepository->findBy(array('activo'=>1)),
            /*'aprob' => count($plazaRepository->findAll()),*/
            'aprob' => $plazaRepository->findPlazasAprob()[1],
            'cub' => count($auditorRepository->findPlazasCub()),
            'cargos' => $cargoRepository->findBy(array('activo'=>1)),
        ]);
    }

    /**
     * Filter all auditores by ai and/or osdes and/or organismos
     *
     * @Route("/", name="auditor_filter", methods={"GET", "POST"})
     */
    public function filterAction(Request $request, AuditorRepository $auditorRepository, PlazaRepository $plazaRepository, EntidadRepository $entidadRepository, OsdeRepository $osdeRepository, OrganismoRepository $organismoRepository, CargoRepository $cargoRepository, NivelRepository $nivelRepository, MunicipioRepository $municipioRepository, LocalidadRepository $localidadRepository)
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());
        $em = $this->getDoctrine()->getManager();

        $menorque = "";
        $mayorque = "";
        if($request->request->get('menorque')!=null && $request->request->get('menorque')!='')
        {
            $menorque = $request->request->get('menorque');
        }
        if($request->request->get('mayorque')!=null && $request->request->get('mayorque')!='')
        {
            $mayorque = $request->request->get('mayorque');
        }

        $idmun = $request->request->get('filtrar_mun');
        $idloc = $request->request->get('filtrar_loc');
        $idorg = $request->request->get('filtrar_org');
        $idosde = $request->request->get('filtrar_osde');
        $ident = $request->request->get('filtrar_ent');
        $idcargo = $request->request->get('filtrar_cargo');
        $idnivel = $request->request->get('filtrar_niv');
        $fea = $request->request->get('filtrar_fea');
        $sexo = $request->request->get('sexofiltrar');

        $conn = $this->getDoctrine()->getConnection();
        /*var_dump($conn);die;*/
        $query = 'SELECT auditor.id,ci,nombres,apellidos,auditor.activo,telefono,correo,direccion,fea,rna,fecha_rna AS fechaRna,localidad.nombre AS localidad,nivel.nombre AS nivel,entidad.nombre AS entidad,cargo.nombre AS cargo,sexo(ci) AS sexo,edad(ci) AS edad
                    FROM auditor,localidad,entidad,cargo,nivel,osde,organismo,municipio 
                    WHERE auditor.localidad_id=localidad.id 
                    AND auditor.entidad_id=entidad.id 
                    AND auditor.cargo_id=cargo.id 
                    AND auditor.nivel_id=nivel.id 
                    AND entidad.osde_id=osde.id 
                    AND osde.organismo_id=organismo.id 
                    AND localidad.municipio_id=municipio.id 
                    AND auditor.activo=1 ';

        if($menorque!=null && $menorque!='')
        {
            $query.='AND edad(ci)<'.$menorque.' ';
        }
        if($mayorque!=null && $mayorque!='')
        {
            $query.='AND edad(ci)>'.$mayorque.' ';
        }
        if($sexo!="todos")
        {
            $query.='AND sexo(ci)=\''.$sexo.'\' ';
        }
        if($fea!="todos")
        {
            $query.='AND fea=\''.$fea.'\'';
        }
        if($idnivel!="todos")
        {
            $query.='AND nivel.id=\''.$idnivel.'\' ';
        }
        if($idcargo!="todos")
        {
            $query.='AND cargo.id=\''.$idcargo.'\' ';
        }
        if($ident!="todas")
        {
            $query.='AND entidad.id=\''.$ident.'\' ';
        }
        if($idosde!="todas")
        {
            $query.='AND osde.id=\''.$idosde.'\' ';
        }
        if($idorg!="todos")
        {
            $query.='AND organismo.id=\''.$idorg.'\' ';
        }
        if($idloc!="todas")
        {
            $query.='AND localidad.id=\''.$idloc.'\' ';
        }
        if($idmun!="todos")
        {
            $query.='AND municipio.id=\''.$idmun.'\' ';
        }

        $auditors = $conn->fetchAll($query);

        $bitacora = new Log();
        $bitacora->setFecha(new \DateTime('now'))
            ->setUsuario($this->getUser())
            ->setIp($request->getClientIp())
            ->setAccion('Filtrar auditores');
        $em->persist($bitacora);
        $em->flush();

        return $this->render('auditor/filtrar.html.twig', array(
            'routes' => ['Personal', 'Auditor'],
            'organismos' => $organismoRepository->findBy(array('activo'=>1)),
            'osdes' => $osdeRepository->findBy(array('activo'=>1)),
            'entidads' => $entidadRepository->findBy(array('activo'=>1)),
            'nivels' => $nivelRepository->findBy(array('activo'=>1)),
            'municipios' => $municipioRepository->findBy(array('activo'=>1)),
            'localidads' => $localidadRepository->findBy(array('activo'=>1)),
            /*'aprob' => count($plazaRepository->findAll()),*/
            'aprob' => $plazaRepository->findPlazasAprob()[1],
            'cub' => count($auditorRepository->findPlazasCub()),
            'cargos' => $cargoRepository->findBy(array('activo'=>1)),
            'auditors' => $auditors,
            'url' => 'auditor',
        ));
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/new", name="auditor_new", methods={"GET","POST"})
     */
    public function new(Request $request, FileUploader $fileUploader): Response
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());
        $auditor = new Auditor();
        $form = $this->createForm(AuditorType::class, $auditor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $auditor->setActivo(true);
            /** @var UploadedFile $imagenFile */
            $imagenFile = $form['imagen']->getData();
            if ($imagenFile) {
                $imagenFileName = $fileUploader->upload($imagenFile);
                $auditor->setImagen($imagenFileName);
            }
            $entityManager = $this->getDoctrine()->getManager();

            $exist = $entityManager->getRepository('App\Entity\Plaza')->findOneBy(array('entidad' => $auditor->getEntidad()->getId(), 'cargo' => $auditor->getCargo()->getId()));

            if ($exist!=null)
            {
                $auditores = $entityManager->getRepository('App\Entity\Auditor')->findBy(array('entidad' => $auditor->getEntidad()->getId(), 'cargo' => $auditor->getCargo()->getId(), 'activo' => 1));
                $cont = count($auditores);

                if($cont==$exist->getPlazas())
                {
                    $this->addFlash(
                        'error',
                        'Las plazas están ya ocupadas!'
                    );

                    return $this->render('auditor/new.html.twig', [
                        'auditor' => $auditor,
                        'form' => $form->createView(),
                        'url' => 'auditor',
                        'routes' => ['Personal', 'Auditor', 'Nuevo'],
                    ]);
                }

                $bitacora = new Log();
                $bitacora->setFecha(new \DateTime('now'))
                    ->setUsuario($this->getUser())
                    ->setIp($request->getClientIp())
                    ->setAccion('Insertar auditor');

                $entityManager->persist($bitacora);
                $entityManager->persist($auditor);
                $entityManager->flush();

                $this->addFlash(
                    'notice',
                    'Auditor registrado!'
                );
                return $this->redirectToRoute('auditor_index');
            }

            $this->addFlash(
                'error',
                'No puede insertar al auditor en esta entidad ni en el cargo porque la(s) plaza(s) no está(n) creada(s)!'
            );
        }

        return $this->render('auditor/new.html.twig', [
            'auditor' => $auditor,
            'form' => $form->createView(),
            'url' => 'auditor',
            'routes' => ['Personal', 'Auditor', 'Nuevo'],
        ]);
    }

    /**
     * @Route("/{id}", name="auditor_show", methods={"GET"})
     */
    public function show(Auditor $auditor, Request $request): Response
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());
        return $this->render('auditor/show.html.twig', [
            'auditor' => $auditor,
            'url' => 'auditor',
            'routes' => ['Personal', 'Auditor', 'Ver'],
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/{id}/edit", name="auditor_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Auditor $auditor, FileUploader $fileUploader): Response
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());
        $form = $this->createForm(AuditorType::class, $auditor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $exist = $this->getDoctrine()->getManager()->getRepository('App\Entity\Plaza')->findOneBy(array('entidad' => $auditor->getEntidad()->getId(), 'cargo' => $auditor->getCargo()->getId()));

            if ($exist!=null)
            {
                $auditores = $this->getDoctrine()->getManager()->getRepository('App\Entity\Auditor')->findBy(array('entidad' => $auditor->getEntidad()->getId(), 'cargo' => $auditor->getCargo()->getId(), 'activo' => 1));
                $pregPorAuditor = $this->getDoctrine()->getManager()->getRepository("App\Entity\Auditor")->findOneBy(array('id'=>$auditor->getId(), 'entidad'=>$auditor->getEntidad()->getId(), 'cargo'=>$auditor->getCargo()->getId(), 'activo' => 1));
                $cont = count($auditores);

                if($cont==$exist->getPlazas() && $pregPorAuditor==null)
                {
                    $this->addFlash(
                        'error',
                        'Las plazas están ya ocupadas!'
                    );

                    return $this->render('auditor/edit.html.twig', [
                        'auditor' => $auditor,
                        'form' => $form->createView(),
                        'url' => 'auditor',
                        'routes' => ['Personal', 'Auditor', 'Editar'],
                    ]);
                }

                $bitacora = new Log();
                $bitacora->setFecha(new \DateTime('now'))
                    ->setUsuario($this->getUser())
                    ->setIp($request->getClientIp())
                    ->setAccion('Modificar auditor '.$auditor->getId());

                /** @var UploadedFile $imagenFile */
                $imagenFile = $form['imagen']->getData();
                if ($imagenFile) {
                    $imagenFileName = $fileUploader->upload($imagenFile);
                    $auditor->setImagen($imagenFileName);
                }
                $this->getDoctrine()->getManager()->persist($bitacora);
                $this->getDoctrine()->getManager()->flush();

                $this->addFlash(
                    'notice',
                    'Auditor actualizado!'
                );

                return $this->redirectToRoute('auditor_index');
            }

            $this->addFlash(
                'error',
                'No puede insertar al auditor en esta entidad ni en el cargo porque la(s) plaza(s) no está(n) creada(s)!'
            );
        }

        return $this->render('auditor/edit.html.twig', [
            'auditor' => $auditor,
            'form' => $form->createView(),
            'url' => 'auditor',
            'routes' => ['Personal', 'Auditor', 'Editar'],
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/{id}/activate", name="auditor_activate", methods={"GET","POST"})
     */
    public function activate(Request $request, Auditor $auditor): Response
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());
        $auditor->setActivo(true);
        $bitacora = new Log();
        $bitacora->setFecha(new \DateTime('now'))
            ->setUsuario($this->getUser())
            ->setIp($request->getClientIp())
            ->setAccion('Activar auditor: '.$auditor->getId());
        $this->getDoctrine()->getManager()->persist($bitacora);
        $this->getDoctrine()->getManager()->flush();

        $this->addFlash(
            'notice',
            'Auditor activado!'
        );
        return $this->redirectToRoute('auditor_index');
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/{id}/deactivate", name="auditor_deactivate", methods={"GET","POST"})
     */
    public function deactivate(Request $request, Auditor $auditor): Response
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());
        $auditor->setActivo(false);
        $bitacora = new Log();
        $bitacora->setFecha(new \DateTime('now'))
            ->setUsuario($this->getUser())
            ->setIp($request->getClientIp())
            ->setAccion('Desactivar auditor: '.$auditor->getId());
        $this->getDoctrine()->getManager()->persist($bitacora);
        $this->getDoctrine()->getManager()->flush();

        $this->addFlash(
            'notice',
            'Auditor desactivado!'
        );

        return $this->redirectToRoute('auditor_index');
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/{id}", name="auditor_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Auditor $auditor): Response
    {
        if ($this->isCsrfTokenValid('delete'.$auditor->getId(), $request->request->get('_token'))) {
            $this->deactivate($request, $auditor);
            /*$entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($auditor);
            $entityManager->flush();*/
        }

        return $this->redirectToRoute('auditor_index');
    }

    /**
     * Filter all auditores by vence R.N.A.
     *
     * @Route("_vence", name="auditor_vence", methods={"GET"})
     */
    public function vencen(Request $request, AuditorRepository $auditorRepository, OrganismoRepository $organismoRepository, OsdeRepository $osdeRepository, EntidadRepository $entidadRepository, CargoRepository $cargoRepository, NivelRepository $nivelRepository, MunicipioRepository $municipioRepository, LocalidadRepository $localidadRepository, PlazaRepository $plazaRepository)
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());
        return $this->render('auditor/vencen.html.twig', [
            'auditors' => $auditorRepository->findAll(),
            'url' => 'auditor',
            'routes' => ['Personal', 'Auditor'],
            'organismos' => $organismoRepository->findBy(array('activo'=>1)),
            'osdes' => $osdeRepository->findBy(array('activo'=>1)),
            'entidads' => $entidadRepository->findBy(array('activo'=>1)),
            'nivels' => $nivelRepository->findBy(array('activo'=>1)),
            'municipios' => $municipioRepository->findBy(array('activo'=>1)),
            'localidads' => $localidadRepository->findBy(array('activo'=>1)),
            /*'aprob' => count($plazaRepository->findAll()),*/
            'aprob' => $plazaRepository->findPlazasAprob()[1],
            'cub' => count($auditorRepository->findPlazasCub()),
            'cargos' => $cargoRepository->findBy(array('activo'=>1)),
        ]);
    }

    /**
     * Filter all auditores by vence R.N.A.
     *
     * @Route("_download_pdf", name="download_pdf", methods={"GET","POST"})
     */
    public function pdf(Request $request)
    {
        $ubicacion = $request->request->get('ubicacion');
        $trabajo = $request->request->get('trabajo');
        $cargo = $request->request->get('cargo');
        $correo = $request->request->get('correo');
        $nivel = $request->request->get('nivel');
        $edad = $request->request->get('edad');
        $direc = $request->request->get('direccion');
        $ci = $request->request->get('ci');
        $sexo = $request->request->get('sexo');
        $telef = $request->request->get('telefono');
        $rna = $request->request->get('rna');

        $conn = $this->getDoctrine()->getConnection();
        $auditors = $conn
            ->fetchAll('SELECT auditor.id,ci,nombres,apellidos,auditor.activo,telefono,correo,direccion,fea,rna,fecha_rna AS fechaRna,localidad.nombre AS localidad,nivel.nombre AS nivel,entidad.nombre AS entidad,cargo.nombre AS cargo,edad(ci) AS edad,sexo(ci) AS sexo,osde.nombre AS osde,organismo.nombre AS organismo,municipio.nombre AS municipio 
                    FROM auditor,localidad,entidad,cargo,nivel,osde,organismo,municipio 
                    WHERE auditor.localidad_id=localidad.id 
                    AND auditor.entidad_id=entidad.id 
                    AND auditor.cargo_id=cargo.id 
                    AND auditor.nivel_id=nivel.id 
                    AND entidad.osde_id=osde.id 
                    AND osde.organismo_id=organismo.id 
                    AND localidad.municipio_id=municipio.id');

        $reporte_name = 'Reporte de los auditores - '.date('Y/m/d h:i:s a');

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
        $contador = 0;

        $content .= '
		<div class="row">
        	<div class="col-md-12">
            	<h1 style="text-align:center;">'.$reporte_name.'</h1>
            	<h2 style="text-align:center;">Consta de '.count($auditors).' auditores</h2>
           	</div>
        </div>
    <table border="0" cellpadding="3">
        <thead>
          <tr style="color: whitesmoke; background-color: #223444;">';
        if ($edad!=null && $edad!='')
        {
            $content .= '<th>Edad</th>';
            $contador++;
        }
        if ($sexo!=null && $sexo!='')
        {
            $content .= '<th>Sexo</th>';
            $contador++;
        }
        if ($ci!=null && $ci!='')
        {
            $content .= '<th>CI</th>';
            $contador++;
        }
        $content .= '<th>Nombres</th>
            <th>Apellidos</th>';
        if ($trabajo=='org'){
            $content .= '<th>Organismo</th>';
        }
        if ($trabajo=='osde'){
            $content .= '<th>OSDE</th>';
        }
        if ($trabajo=='ent'){
            $content .= '<th>Entidad</th>';
        }
        if ($ubicacion=='mun'){
            $content .= '<th>Municipio</th>';
        }
        if ($ubicacion=='loc'){
            $content .= '<th>Localidad</th>';
        }
        if ($direc!=null && $direc!='')
        {
            $content .= '<th>Dirección</th>';
            $contador++;
        }
        if ($correo!=null && $correo!='')
        {
            $content .= '<th>Correo</th>';
            $contador++;
        }
        if ($telef!=null && $telef!='')
        {
            $content .= '<th>Teléfono</th>';
            $contador++;
        }
        if ($cargo!=null && $cargo!='')
        {
            $content .= '<th>Cargo</th>';
            $contador++;
        }
        if ($nivel!=null && $nivel!='')
        {
            $content .= '<th>Nivel</th>';
            $contador++;
        }
        if ($rna!=null && $rna!='')
        {
            $content .= '<th>R.N.A.</th>';
            $contador++;
        }
        $content .= '
          </tr>
        </thead>
	';

        foreach ($auditors as $index => $user) {
            if($user['activo']=='1'){  $color= '#3f51b5'; }else{ $color= '#ff4081'; }
            $content .= '
		<tr style="color: #FFFFFF;" bgcolor="'.$color.'">';
            if ($edad!=null && $edad!='')
            {
                $content .= '<td>'.$user['edad'].'</td>';
            }
            if ($sexo!=null && $sexo!='')
            {
                $content .= '<td>'.$user['sexo'].'</td>';
            }
            if ($ci!=null && $ci!='')
            {
                $content .= '<td>'.$user['ci'].'</td>';
            }
            $content .= '<td>'.$user['nombres'].'</td>
            <td>'.$user['apellidos'].'</td>';
            if ($trabajo=='org'){
                $content .= '<td>'.$user['organismo'].'</td>';
            }
            if ($trabajo=='osde'){
                $content .= '<td>'.$user['osde'].'</td>';
            }
            if ($trabajo=='ent'){
                $content .= '<td>'.$user['entidad'].'</td>';
            }
            if ($ubicacion=='mun'){
                $content .= '<td>'.$user['municipio'].'</td>';
            }
            if ($ubicacion=='loc'){
                $content .= '<td>'.$user['localidad'].'</td>';
            }
            if ($direc!=null && $direc!='')
            {
                $content .= '<td>'.$user['direccion'].'</td>';
            }
            if ($correo!=null && $correo!='')
            {
                $content .= '<td>'.$user['correo'].'</td>';
            }
            if ($telef!=null && $telef!='')
            {
                $content .= '<td>'.$user['telefono'].'</td>';
            }
            if ($cargo!=null && $cargo!='')
            {
                $content .= '<td>'.$user['cargo'].'</td>';
            }
            if ($nivel!=null && $nivel!='')
            {
                $content .= '<td>'.$user['nivel'].'</td>';
            }
            if ($rna!=null && $rna!='')
            {
                $content .= '<td>'.$user['rna'].'</td>';
            }
            $content .= '</tr>';
        }

        $content .= '</table>';

        $content .= '
		<div class="row padding">
        	<div class="col-md-12" style="text-align:center;">
            	<span>Pdf Creator </span><a href="http://www.redecodifica.com">By Arián Castellanos</a>
            </div>
        </div>
    	
	';
        if ($contador == 9 || $contador == 8){
            /*var_dump($contador.' caca');die;*/
            $pdf->SetFont('Helvetica', '', 6);
        }
        elseif ($contador <= 7 && $contador >= 6){
            /*var_dump($contador.' pinga');die;*/
            $pdf->SetFont('Helvetica', '', 7);
        }
        elseif ($contador <= 5 && $contador >= 4){
            /*var_dump($contador.' pinga');die;*/
            $pdf->SetFont('Helvetica', '', 9);
        }
        else{
            $pdf->SetFont('Helvetica', '', 10);
            /*var_dump($contador.' else');die;*/
        }
        $pdf->writeHTML($content, true, 0, true, 0);

        $pdf->lastPage();
        $pdf->output('Reporte Auditores STA '.date('Y/m/d').'.pdf', 'D');

        $em = $this->getDoctrine()->getManager();
        $bitacora = new Log();
        $bitacora->setFecha(new \DateTime('now'))
            ->setUsuario($this->getUser())
            ->setIp($request->getClientIp())
            ->setAccion('Crear reporte');

        $em->persist($bitacora);
        $em->flush();

        return $this->redirectToRoute('auditor_index');
    }
}
