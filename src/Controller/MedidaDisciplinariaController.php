<?php

namespace App\Controller;

use App\Entity\Log;
use App\Entity\MedidaDisciplinaria;
use App\Form\MedidaDisciplinariaType;
use App\Repository\MedidaDisciplinariaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/medida_disciplinaria")
 */
class MedidaDisciplinariaController extends AbstractController
{
    /**
     * @Route("/", name="medida_disciplinaria_index", methods={"GET"})
     */
    public function index(MedidaDisciplinariaRepository $medidaDisciplinariaRepository, Request $request): Response
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());
        return $this->render('medida_disciplinaria/index.html.twig', [
            'medida_disciplinarias' => $medidaDisciplinariaRepository->findAll(),
            'url' => 'planificacion',
            'routes' => ['Planificación', 'Parámetros', 'Medidas disciplinarias'],
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/new", name="medida_disciplinaria_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());
        $medidaDisciplinarium = new MedidaDisciplinaria();
        $form = $this->createForm(MedidaDisciplinariaType::class, $medidaDisciplinarium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $bitacora = new Log();
            $bitacora->setFecha(new \DateTime('now'))
                ->setUsuario($this->getUser())
                ->setIp($request->getClientIp())
                ->setAccion('Insertar medida disciplinaria');
            $entityManager->persist($bitacora);
            $entityManager->persist($medidaDisciplinarium);
            $entityManager->flush();

            $this->addFlash(
                'notice',
                'Medida disciplinaria registrada!'
            );

            return $this->redirectToRoute('medida_disciplinaria_index');
        }

        return $this->render('medida_disciplinaria/new.html.twig', [
            'medida_disciplinarium' => $medidaDisciplinarium,
            'form' => $form->createView(),
            'url' => 'planificacion',
            'routes' => ['Planificación', 'Parámetros', 'Medidas disciplinarias', 'Nueva'],
        ]);
    }

    /**
     * @Route("/{id}", name="medida_disciplinaria_show", methods={"GET"})
     */
    public function show(MedidaDisciplinaria $medidaDisciplinarium, Request $request): Response
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());
        return $this->render('medida_disciplinaria/show.html.twig', [
            'medida_disciplinarium' => $medidaDisciplinarium,
            'url' => 'planificacion',
            'routes' => ['Planificación', 'Parámetros', 'Medidas disciplinarias', 'Ver'],
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/{id}/edit", name="medida_disciplinaria_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, MedidaDisciplinaria $medidaDisciplinarium): Response
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());
        $form = $this->createForm(MedidaDisciplinariaType::class, $medidaDisciplinarium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bitacora = new Log();
            $bitacora->setFecha(new \DateTime('now'))
                ->setUsuario($this->getUser())
                ->setIp($request->getClientIp())
                ->setAccion('Modificar medida disciplinaria: '.$medidaDisciplinarium->getId());
            $this->getDoctrine()->getManager()->persist($bitacora);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'notice',
                'Medida disciplinaria actualizada!'
            );

            return $this->redirectToRoute('medida_disciplinaria_index');
        }

        return $this->render('medida_disciplinaria/edit.html.twig', [
            'medida_disciplinarium' => $medidaDisciplinarium,
            'form' => $form->createView(),
            'url' => 'planificacion',
            'routes' => ['Planificación', 'Parámetros', 'Medidas disciplinarias', 'Editar'],
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/{id}/activate", name="medida_disciplinaria_activate", methods={"GET","POST"})
     */
    public function activate(Request $request, MedidaDisciplinaria $medidaDisciplinarium): Response
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());
        $medidaDisciplinarium->setActivo(true);
        $bitacora = new Log();
        $bitacora->setFecha(new \DateTime('now'))
            ->setUsuario($this->getUser())
            ->setIp($request->getClientIp())
            ->setAccion('Activar medida disciplinaria: '.$medidaDisciplinarium->getId());
        $this->getDoctrine()->getManager()->persist($bitacora);
        $this->getDoctrine()->getManager()->flush();

        $this->addFlash(
            'notice',
            'Medida disciplinaria activada!'
        );
        return $this->redirectToRoute('medida_disciplinaria_index');
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/{id}/deactivate", name="medida_disciplinaria_deactivate", methods={"GET","POST"})
     */
    public function deactivate(Request $request, MedidaDisciplinaria $medidaDisciplinarium)
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());
        //Arreglar con la Accion de control
        $responsabilidad = $medidaDisciplinarium->getResponsabilidad();

        if (count($responsabilidad) > 0)
        {
            $this->addFlash(
                'error',
                'No se puede eliminar esta medida disciplinaria porque está asociada a alguna responsabilidad administrativa!'
            );

            return true;
        }
        else
        {
            $medidaDisciplinarium->setActivo(false);
            $bitacora = new Log();
            $bitacora->setFecha(new \DateTime('now'))
                ->setUsuario($this->getUser())
                ->setIp($request->getClientIp())
                ->setAccion('Desactivar medida disciplinaria: '.$medidaDisciplinarium->getId());
            $this->getDoctrine()->getManager()->persist($bitacora);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'notice',
                'Medida disciplinaria desactivada!'
            );

            return false;
        }
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/{id}", name="medida_disciplinaria_delete", methods={"DELETE"})
     */
    public function delete(Request $request, MedidaDisciplinaria $medidaDisciplinarium): Response
    {
        if ($this->isCsrfTokenValid('delete'.$medidaDisciplinarium->getId(), $request->request->get('_token'))) {
            if ($this->deactivate($request, $medidaDisciplinarium))
            {
                return $this->redirectToRoute('medida_disciplinaria_show', array('id'=>$medidaDisciplinarium->getId()));
            }
        }

        return $this->redirectToRoute('medida_disciplinaria_index');
    }
}
