<?php

namespace App\Controller;

use App\Entity\Log;
use App\Entity\Responsabilidad;
use App\Form\ResponsabilidadType;
use App\Repository\ResponsabilidadRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/responsabilidad")
 */
class ResponsabilidadController extends AbstractController
{
    /**
     * @Route("/", name="responsabilidad_index", methods={"GET"})
     */
    public function index(ResponsabilidadRepository $responsabilidadRepository, Request $request): Response
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());
        return $this->render('responsabilidad/index.html.twig', [
            'responsabilidads' => $responsabilidadRepository->findAll(),
            'url' => 'planificacion',
            'routes' => ['Planificación', 'Daños', 'Responsabilidades'],
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/new", name="responsabilidad_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());
        $responsabilidad = new Responsabilidad();
        $form = $this->createForm(ResponsabilidadType::class, $responsabilidad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $bitacora = new Log();
            $bitacora->setFecha(new \DateTime('now'))
                ->setUsuario($this->getUser())
                ->setIp($request->getClientIp())
                ->setAccion('Insertar responsabilidad administrativa');
            $entityManager->persist($bitacora);
            $entityManager->persist($responsabilidad);
            $entityManager->flush();

            $this->addFlash(
                'notice',
                'Responsabilidad administrativa registrada!'
            );
            return $this->redirectToRoute('responsabilidad_index');
        }

        return $this->render('responsabilidad/new.html.twig', [
            'responsabilidad' => $responsabilidad,
            'form' => $form->createView(),
            'url' => 'planificacion',
            'routes' => ['Planificación', 'Daños', 'Responsabilidades', 'Nueva'],
        ]);
    }

    /**
     * @Route("/{id}", name="responsabilidad_show", methods={"GET"})
     */
    public function show(Responsabilidad $responsabilidad, Request $request): Response
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());
        return $this->render('responsabilidad/show.html.twig', [
            'responsabilidad' => $responsabilidad,
            'url' => 'planificacion',
            'routes' => ['Planificación', 'Daños', 'Responsabilidades', 'Ver'],
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/{id}/edit", name="responsabilidad_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Responsabilidad $responsabilidad): Response
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());
        $form = $this->createForm(ResponsabilidadType::class, $responsabilidad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bitacora = new Log();
            $bitacora->setFecha(new \DateTime('now'))
                ->setUsuario($this->getUser())
                ->setIp($request->getClientIp())
                ->setAccion('Modificar responsabilidad administrativa: '.$responsabilidad->getId());
            $this->getDoctrine()->getManager()->persist($bitacora);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'notice',
                'Responsabilidad administrativa actualizada!'
            );

            return $this->redirectToRoute('responsabilidad_index');
        }

        return $this->render('responsabilidad/edit.html.twig', [
            'responsabilidad' => $responsabilidad,
            'form' => $form->createView(),
            'url' => 'planificacion',
            'routes' => ['Planificación', 'Daños', 'Responsabilidades', 'Editar'],
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/{id}/activate", name="responsabilidad_activate", methods={"GET","POST"})
     */
    public function activate(Request $request, Responsabilidad $responsabilidad): Response
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());
        $responsabilidad->setActivo(true);
        $bitacora = new Log();
        $bitacora->setFecha(new \DateTime('now'))
            ->setUsuario($this->getUser())
            ->setIp($request->getClientIp())
            ->setAccion('Activar responsabilidad administrativa: '.$responsabilidad->getId());
        $this->getDoctrine()->getManager()->persist($bitacora);
        $this->getDoctrine()->getManager()->flush();

        $this->addFlash(
            'notice',
            'Responsabilidad administrativa activada!'
        );
        return $this->redirectToRoute('responsabilidad_index');
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/{id}/deactivate", name="responsabilidad_deactivate", methods={"GET","POST"})
     */
    public function deactivate(Request $request, Responsabilidad $responsabilidad)
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());
        $responsabilidad->setActivo(false);
        $bitacora = new Log();
        $bitacora->setFecha(new \DateTime('now'))
            ->setUsuario($this->getUser())
            ->setIp($request->getClientIp())
            ->setAccion('Desactivar responsabilidad administrativa: '.$responsabilidad->getId());
        $this->getDoctrine()->getManager()->persist($bitacora);
        $this->getDoctrine()->getManager()->flush();

        $this->addFlash(
            'notice',
            'Responsabilidad administrativa desactivada!'
        );

        return false;
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/{id}", name="responsabilidad_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Responsabilidad $responsabilidad): Response
    {
        if ($this->isCsrfTokenValid('delete'.$responsabilidad->getId(), $request->request->get('_token'))) {
            if ($this->deactivate($request, $responsabilidad))
            {
                return $this->redirectToRoute('responsabilidad_show', array('id'=>$responsabilidad->getId()));
            }
        }

        return $this->redirectToRoute('responsabilidad_index');
    }
}
