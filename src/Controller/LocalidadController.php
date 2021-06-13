<?php

namespace App\Controller;

use App\Entity\Localidad;
use App\Entity\Log;
use App\Form\LocalidadType;
use App\Repository\LocalidadRepository;
use App\Repository\MunicipioRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/localidad")
 */
class LocalidadController extends AbstractController
{
    /**
     * @Route("/", name="localidad_index", methods={"GET"})
     */
    public function index(LocalidadRepository $localidadRepository, MunicipioRepository $municipioRepository, Request $request): Response
    {
        return $this->render('localidad/index.html.twig', [
            'localidads' => $localidadRepository->findAll(),
            'municipios' => $municipioRepository->findBy(array('activo'=>1)),
            'url' => 'sistema',
            'routes' => ['Ubicación', 'Localidad'],
        ]);
    }

    /**
     * Filter all localidades by municipios
     *
     * @Route("/", name="loc_filter", methods={"GET", "POST"})
     */
    public function filterAction(Request $request, LocalidadRepository $localidadRepository, MunicipioRepository $municipioRepository)
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());
        $idmun = $request->request->get('filtrar');
        if($idmun=='todos')
        {
            return $this->redirectToRoute("localidad_index");
        }
        else
        {
            $em = $this->getDoctrine()->getManager();
            $bitacora = new Log();
            $bitacora->setFecha(new \DateTime('now'))
                ->setUsuario($this->getUser())
                ->setIp($request->getClientIp())
                ->setAccion('Filtrar localidad');
            $em->persist($bitacora);
            $em->flush();

            return $this->render("localidad/index.html.twig", array(
                'localidads' => $localidadRepository->findBy(array('municipio' => $idmun)),
                'municipios' => $municipioRepository->findBy(array('activo'=>1)),
                'url'=>'sistema',
                'routes' => ['Ubicación', 'Localidad'],
            ));
        }
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/new", name="localidad_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());
        $localidad = new Localidad();
        $form = $this->createForm(LocalidadType::class, $localidad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $bitacora = new Log();
            $bitacora->setFecha(new \DateTime('now'))
                ->setUsuario($this->getUser())
                ->setIp($request->getClientIp())
                ->setAccion('Insertar localidad');
            $entityManager->persist($bitacora);
            $entityManager->persist($localidad);
            $entityManager->flush();

            $this->addFlash(
                'notice',
                'Localidad registrada!'
            );

            return $this->redirectToRoute('localidad_index');
        }

        return $this->render('localidad/new.html.twig', [
            'localidad' => $localidad,
            'form' => $form->createView(),
            'url' => 'sistema',
            'routes' => ['Ubicación', 'Localidad', 'Nueva'],
        ]);
    }

    /**
     * @Route("/{id}", name="localidad_show", methods={"GET"})
     */
    public function show(Localidad $localidad, Request $request): Response
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());
        return $this->render('localidad/show.html.twig', [
            'localidad' => $localidad,
            'url' => 'sistema',
            'routes' => ['Ubicación', 'Localidad', 'Ver'],
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/{id}/edit", name="localidad_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Localidad $localidad): Response
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());
        $form = $this->createForm(LocalidadType::class, $localidad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bitacora = new Log();
            $bitacora->setFecha(new \DateTime('now'))
                ->setUsuario($this->getUser())
                ->setIp($request->getClientIp())
                ->setAccion('Modificar localidad: '.$localidad->getId());
            $this->getDoctrine()->getManager()->persist($bitacora);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'notice',
                'Localidad actualizada!'
            );

            return $this->redirectToRoute('localidad_index');
        }

        return $this->render('localidad/edit.html.twig', [
            'localidad' => $localidad,
            'form' => $form->createView(),
            'url' => 'sistema',
            'routes' => ['Ubicación', 'Localidad', 'Editar'],
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/{id}/activate", name="localidad_activate", methods={"GET","POST"})
     */
    public function activate(Request $request, Localidad $localidad): Response
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());
        $localidad->setActivo(true);
        $bitacora = new Log();
        $bitacora->setFecha(new \DateTime('now'))
            ->setUsuario($this->getUser())
            ->setIp($request->getClientIp())
            ->setAccion('Activar localidad: '.$localidad->getId());
        $this->getDoctrine()->getManager()->persist($bitacora);
        $this->getDoctrine()->getManager()->flush();

        $this->addFlash(
            'notice',
            'Localidad activada!'
        );
        return $this->redirectToRoute('localidad_index');
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/{id}/deactivate", name="localidad_deactivate", methods={"GET","POST"})
     */
    public function deactivate(Request $request, Localidad $localidad)
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());

        $auditor = $localidad->getAuditores();

        if (count($auditor) > 0)
        {
            $this->addFlash(
                'error',
                'No se puede eliminar esta localidad porque existe algún auditor en ella!'
            );

            return true;
        }
        else
        {
            $localidad->setActivo(false);
            $bitacora = new Log();
            $bitacora->setFecha(new \DateTime('now'))
                ->setUsuario($this->getUser())
                ->setIp($request->getClientIp())
                ->setAccion('Desactivar localidad: '.$localidad->getId());
            $this->getDoctrine()->getManager()->persist($bitacora);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'notice',
                'Localidad desactivada!'
            );

            return false;
        }
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/{id}", name="localidad_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Localidad $localidad): Response
    {
        if ($this->isCsrfTokenValid('delete'.$localidad->getId(), $request->request->get('_token'))) {
            if ($this->deactivate($request, $localidad))
            {
                return $this->redirectToRoute('localidad_show', array('id'=>$localidad->getId()));
            }
        }

        return $this->redirectToRoute('localidad_index');
    }
}
