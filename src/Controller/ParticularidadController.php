<?php

namespace App\Controller;

use App\Entity\Log;
use App\Entity\Particularidad;
use App\Form\ParticularidadType;
use App\Repository\ParticularidadRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/particularidad")
 */
class ParticularidadController extends AbstractController
{
    /**
     * @Route("/", name="particularidad_index", methods={"GET"})
     */
    public function index(ParticularidadRepository $particularidadRepository, Request $request): Response
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());
        return $this->render('particularidad/index.html.twig', [
            'particularidads' => $particularidadRepository->findAll(),
            'url' => 'planificacion',
            'routes' => ['Planificación', 'Acciones', 'Particularidades'],
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/new", name="particularidad_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());
        $particularidad = new Particularidad();
        $form = $this->createForm(ParticularidadType::class, $particularidad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $bitacora = new Log();
            $bitacora->setFecha(new \DateTime('now'))
                ->setUsuario($this->getUser())
                ->setIp($request->getClientIp())
                ->setAccion('Insertar particularidad');
            $entityManager->persist($bitacora);
            $entityManager->persist($particularidad);
            $entityManager->flush();

            $this->addFlash(
                'notice',
                'Particularidad registrada!'
            );
            return $this->redirectToRoute('particularidad_index');
        }

        return $this->render('particularidad/new.html.twig', [
            'particularidad' => $particularidad,
            'form' => $form->createView(),
            'url' => 'planificacion',
            'routes' => ['Planificación', 'Acciones', 'Particularidades', 'Nueva'],
        ]);
    }

    /**
     * @Route("/{id}", name="particularidad_show", methods={"GET"})
     */
    public function show(Particularidad $particularidad, Request $request): Response
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());
        return $this->render('particularidad/show.html.twig', [
            'particularidad' => $particularidad,
            'url' => 'planificacion',
            'routes' => ['Planificación', 'Acciones', 'Particularidades', 'Ver'],
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/{id}/edit", name="particularidad_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Particularidad $particularidad): Response
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());
        $form = $this->createForm(ParticularidadType::class, $particularidad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bitacora = new Log();
            $bitacora->setFecha(new \DateTime('now'))
                ->setUsuario($this->getUser())
                ->setIp($request->getClientIp())
                ->setAccion('Modificar particularidad: '.$particularidad->getId());
            $this->getDoctrine()->getManager()->persist($bitacora);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'notice',
                'Particularidad actualizada!'
            );

            return $this->redirectToRoute('particularidad_index');
        }

        return $this->render('particularidad/edit.html.twig', [
            'particularidad' => $particularidad,
            'form' => $form->createView(),
            'url' => 'planificacion',
            'routes' => ['Planificación', 'Acciones', 'Particularidades', 'Editar'],
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/{id}/activate", name="particularidad_activate", methods={"GET","POST"})
     */
    public function activate(Request $request, Particularidad $particularidad): Response
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());
        $particularidad->setActivo(true);
        $bitacora = new Log();
        $bitacora->setFecha(new \DateTime('now'))
            ->setUsuario($this->getUser())
            ->setIp($request->getClientIp())
            ->setAccion('Activar particularidad: '.$particularidad->getId());
        $this->getDoctrine()->getManager()->persist($bitacora);
        $this->getDoctrine()->getManager()->flush();

        $this->addFlash(
            'notice',
            'Particularidad activada!'
        );
        return $this->redirectToRoute('particularidad_index');
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/{id}/deactivate", name="particularidad_deactivate", methods={"GET","POST"})
     */
    public function deactivate(Request $request, Particularidad $particularidad)
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());

        //Aqui van las acciones
        $accion = $particularidad->getId();

        if (count($accion) > 0)
        {
            $this->addFlash(
                'error',
                'No se puede eliminar esta particularidad porque existe una relación con alguna Acción de control!'
            );

            return true;
        }
        else
        {
            $particularidad->setActivo(false);
            $bitacora = new Log();
            $bitacora->setFecha(new \DateTime('now'))
                ->setUsuario($this->getUser())
                ->setIp($request->getClientIp())
                ->setAccion('Desactivar particularidad: '.$particularidad->getId());
            $this->getDoctrine()->getManager()->persist($bitacora);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'notice',
                'Particularidad desactivada!'
            );

            return false;
        }
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/{id}", name="particularidad_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Particularidad $particularidad): Response
    {
        if ($this->isCsrfTokenValid('delete'.$particularidad->getId(), $request->request->get('_token'))) {
            if ($this->deactivate($request, $particularidad))
            {
                return $this->redirectToRoute('particularidad_show', array('id'=>$particularidad->getId()));
            }
        }

        return $this->redirectToRoute('particularidad_index');
    }
}
