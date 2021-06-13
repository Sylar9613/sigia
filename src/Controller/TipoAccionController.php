<?php

namespace App\Controller;

use App\Entity\Log;
use App\Entity\TipoAccion;
use App\Form\TipoAccionType;
use App\Repository\TipoAccionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/tipo_accion")
 */
class TipoAccionController extends AbstractController
{
    /**
     * @Route("/", name="tipo_accion_index", methods={"GET"})
     */
    public function index(TipoAccionRepository $tipoAccionRepository, Request $request): Response
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());
        return $this->render('tipo_accion/index.html.twig', [
            'tipo_accions' => $tipoAccionRepository->findAll(),
            'url' => 'planificacion',
            'routes' => ['Planificación', 'Parámetros', 'Tipo de acción'],
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/new", name="tipo_accion_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());
        $tipoAccion = new TipoAccion();
        $form = $this->createForm(TipoAccionType::class, $tipoAccion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $bitacora = new Log();
            $bitacora->setFecha(new \DateTime('now'))
                ->setUsuario($this->getUser())
                ->setIp($request->getClientIp())
                ->setAccion('Insertar tipo de acción');
            $entityManager->persist($bitacora);
            $entityManager->persist($tipoAccion);
            $entityManager->flush();

            $this->addFlash(
                'notice',
                'Tipo de acción registrado!'
            );

            return $this->redirectToRoute('tipo_accion_index');
        }

        return $this->render('tipo_accion/new.html.twig', [
            'tipo_accion' => $tipoAccion,
            'form' => $form->createView(),
            'url' => 'planificacion',
            'routes' => ['Planificación', 'Parámetros', 'Tipo de acción', 'Nuevo'],
        ]);
    }

    /**
     * @Route("/{id}", name="tipo_accion_show", methods={"GET"})
     */
    public function show(TipoAccion $tipoAccion, Request $request): Response
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());
        return $this->render('tipo_accion/show.html.twig', [
            'tipo_accion' => $tipoAccion,
            'url' => 'planificacion',
            'routes' => ['Planificación', 'Parámetros', 'Tipo de acción', 'Ver'],
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/{id}/edit", name="tipo_accion_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, TipoAccion $tipoAccion): Response
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());
        $form = $this->createForm(TipoAccionType::class, $tipoAccion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bitacora = new Log();
            $bitacora->setFecha(new \DateTime('now'))
                ->setUsuario($this->getUser())
                ->setIp($request->getClientIp())
                ->setAccion('Modificar tipo de acción: '.$tipoAccion->getId());
            $this->getDoctrine()->getManager()->persist($bitacora);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'notice',
                'Tipo de acción actualizado!'
            );

            return $this->redirectToRoute('tipo_accion_index');
        }

        return $this->render('tipo_accion/edit.html.twig', [
            'tipo_accion' => $tipoAccion,
            'form' => $form->createView(),
            'url' => 'planificacion',
            'routes' => ['Planificación', 'Parámetros', 'Tipo de acción', 'Editar'],
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/{id}/activate", name="tipo_accion_activate", methods={"GET","POST"})
     */
    public function activate(Request $request, TipoAccion $tipoAccion): Response
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());
        $tipoAccion->setActivo(true);
        $bitacora = new Log();
        $bitacora->setFecha(new \DateTime('now'))
            ->setUsuario($this->getUser())
            ->setIp($request->getClientIp())
            ->setAccion('Activar tipo de acción: '.$tipoAccion->getId());
        $this->getDoctrine()->getManager()->persist($bitacora);
        $this->getDoctrine()->getManager()->flush();

        $this->addFlash(
            'notice',
            'Tipo de acción activado!'
        );
        return $this->redirectToRoute('tipo_accion_index');
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/{id}/deactivate", name="tipo_accion_deactivate", methods={"GET","POST"})
     */
    public function deactivate(Request $request, TipoAccion $tipoAccion)
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());

        $phd = $tipoAccion->getPhds();

        if (count($phd) > 0)
        {
            $this->addFlash(
                'error',
                'No se puede eliminar este tipo de acción porque está asociado a algún PHD!'
            );

            return true;
        }
        else
        {
            $tipoAccion->setActivo(false);
            $bitacora = new Log();
            $bitacora->setFecha(new \DateTime('now'))
                ->setUsuario($this->getUser())
                ->setIp($request->getClientIp())
                ->setAccion('Desactivar tipo de acción: '.$tipoAccion->getId());
            $this->getDoctrine()->getManager()->persist($bitacora);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'notice',
                'Tipo de acción desactivado!'
            );

            return false;
        }
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/{id}", name="tipo_accion_delete", methods={"DELETE"})
     */
    public function delete(Request $request, TipoAccion $tipoAccion): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tipoAccion->getId(), $request->request->get('_token'))) {
            if ($this->deactivate($request, $tipoAccion))
            {
                return $this->redirectToRoute('tipo_accion_show', array('id'=>$tipoAccion->getId()));
            }
        }

        return $this->redirectToRoute('tipo_accion_index');
    }
}
