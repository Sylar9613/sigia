<?php

namespace App\Controller;

use App\Entity\Auditor;
use App\Entity\Log;
use App\Entity\Nivel;
use App\Form\NivelType;
use App\Repository\NivelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/nivel")
 */
class NivelController extends AbstractController
{
    /**
     * @Route("/", name="nivel_index", methods={"GET"})
     */
    public function index(NivelRepository $nivelRepository, Request $request): Response
    {
        return $this->render('nivel/index.html.twig', [
            'nivels' => $nivelRepository->findAll(),
            'url' => 'auditor',
            'routes' => ['Personal', 'Nivel escolar'],
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/new", name="nivel_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());
        $nivel = new Nivel();
        $form = $this->createForm(NivelType::class, $nivel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $bitacora = new Log();
            $bitacora->setFecha(new \DateTime('now'))
                ->setUsuario($this->getUser())
                ->setIp($request->getClientIp())
                ->setAccion('Insertar nivel');
            $entityManager->persist($bitacora);
            $entityManager->persist($nivel);
            $entityManager->flush();

            $this->addFlash(
                'notice',
                'Nivel escolar registrado!'
            );

            return $this->redirectToRoute('nivel_index');
        }

        return $this->render('nivel/new.html.twig', [
            'nivel' => $nivel,
            'form' => $form->createView(),
            'url' => 'auditor',
            'routes' => ['Personal', 'Nivel escolar', 'Nuevo'],
        ]);
    }

    /**
     * @Route("/{id}", name="nivel_show", methods={"GET"})
     */
    public function show(Nivel $nivel, Request $request): Response
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());
        return $this->render('nivel/show.html.twig', [
            'nivel' => $nivel,
            'url' => 'auditor',
            'routes' => ['Personal', 'Nivel escolar', 'Ver'],
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/{id}/edit", name="nivel_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Nivel $nivel): Response
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());
        $form = $this->createForm(NivelType::class, $nivel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bitacora = new Log();
            $bitacora->setFecha(new \DateTime('now'))
                ->setUsuario($this->getUser())
                ->setIp($request->getClientIp())
                ->setAccion('Modificar nivel: '.$nivel->getId());
            $this->getDoctrine()->getManager()->persist($bitacora);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'notice',
                'Nivel escolar actualizado!'
            );

            return $this->redirectToRoute('nivel_index');
        }

        return $this->render('nivel/edit.html.twig', [
            'nivel' => $nivel,
            'form' => $form->createView(),
            'url' => 'auditor',
            'routes' => ['Personal', 'Nivel escolar', 'Editar'],
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/{id}/activate", name="nivel_activate", methods={"GET","POST"})
     */
    public function activate(Request $request, Nivel $nivel): Response
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());
        $nivel->setActivo(true);
        $bitacora = new Log();
        $bitacora->setFecha(new \DateTime('now'))
            ->setUsuario($this->getUser())
            ->setIp($request->getClientIp())
            ->setAccion('Activar nivel: '.$nivel->getId());
        $this->getDoctrine()->getManager()->persist($bitacora);
        $this->getDoctrine()->getManager()->flush();

        $this->addFlash(
            'notice',
            'Nivel activado!'
        );
        return $this->redirectToRoute('nivel_index');
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/{id}/deactivate", name="nivel_deactivate", methods={"GET","POST"})
     */
    public function deactivate(Request $request, Nivel $nivel)
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());

        $auditor = $nivel->getAuditores();

        if (count($auditor) > 0)
        {
            $this->addFlash(
                'error',
                'No se puede eliminar este nivel escolar porque existe algún auditor ocupándolo!'
            );

            return true;
        }
        else
        {
            $nivel->setActivo(false);
            $bitacora = new Log();
            $bitacora->setFecha(new \DateTime('now'))
                ->setUsuario($this->getUser())
                ->setIp($request->getClientIp())
                ->setAccion('Desactivar nivel: '.$nivel->getId());
            $this->getDoctrine()->getManager()->persist($bitacora);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'notice',
                'Nivel desactivado!'
            );

            return false;
        }
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/{id}", name="nivel_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Nivel $nivel): Response
    {
        if ($this->isCsrfTokenValid('delete'.$nivel->getId(), $request->request->get('_token'))) {
            if ($this->deactivate($request, $nivel))
            {
                return $this->redirectToRoute('nivel_show', array('id'=>$nivel->getId()));
            }
            /*$entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($nivel);
            $entityManager->flush();*/
        }
        return $this->redirectToRoute('nivel_index');
    }
}
