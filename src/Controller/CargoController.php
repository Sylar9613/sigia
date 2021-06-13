<?php

namespace App\Controller;

use App\Entity\Cargo;
use App\Entity\Log;
use App\Form\CargoType;
use App\Repository\CargoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/cargo")
 */
class CargoController extends AbstractController
{
    /**
     * @Route("/", name="cargo_index", methods={"GET"})
     */
    public function index(CargoRepository $cargoRepository): Response
    {
        return $this->render('cargo/index.html.twig', [
            'cargos' => $cargoRepository->findAll(),
            'url' => 'auditor',
            'routes' => ['Personal', 'Cargo'],
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/new", name="cargo_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $cargo = new Cargo();
        $form = $this->createForm(CargoType::class, $cargo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $bitacora = new Log();
            $bitacora->setFecha(new \DateTime('now'))
                ->setUsuario($this->getUser())
                ->setIp($request->getClientIp())
                ->setAccion('Insertar cargo');
            $entityManager->persist($bitacora);
            $entityManager->persist($cargo);
            $entityManager->flush();

            $this->addFlash(
                'notice',
                'Cargo registrado!'
            );
            return $this->redirectToRoute('cargo_index');
        }

        return $this->render('cargo/new.html.twig', [
            'cargo' => $cargo,
            'form' => $form->createView(),
            'url' => 'auditor',
            'routes' => ['Personal', 'Cargo', 'Nuevo'],
        ]);
    }

    /**
     * @Route("/{id}", name="cargo_show", methods={"GET"})
     */
    public function show(Cargo $cargo): Response
    {
        return $this->render('cargo/show.html.twig', [
            'cargo' => $cargo,
            'url' => 'auditor',
            'routes' => ['Personal', 'Cargo', 'Ver'],
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/{id}/edit", name="cargo_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Cargo $cargo): Response
    {
        $form = $this->createForm(CargoType::class, $cargo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bitacora = new Log();
            $bitacora->setFecha(new \DateTime('now'))
                ->setUsuario($this->getUser())
                ->setIp($request->getClientIp())
                ->setAccion('Modificar cargo: '.$cargo->getId());
            $this->getDoctrine()->getManager()->persist($bitacora);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'notice',
                'Cargo actualizado!'
            );
            return $this->redirectToRoute('cargo_index');
        }

        return $this->render('cargo/edit.html.twig', [
            'cargo' => $cargo,
            'form' => $form->createView(),
            'url' => 'auditor',
            'routes' => ['Personal', 'Cargo', 'Editar'],
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/{id}/activate", name="cargo_activate", methods={"GET","POST"})
     */
    public function activate(Request $request, Cargo $cargo): Response
    {
        $cargo->setActivo(true);
        $bitacora = new Log();
        $bitacora->setFecha(new \DateTime('now'))
            ->setUsuario($this->getUser())
            ->setIp($request->getClientIp())
            ->setAccion('Activar cargo: '.$cargo->getId());
        $this->getDoctrine()->getManager()->persist($bitacora);
        $this->getDoctrine()->getManager()->flush();

        $this->addFlash(
            'notice',
            'Cargo activado!'
        );
        return $this->redirectToRoute('cargo_index');
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/{id}/deactivate", name="cargo_deactivate", methods={"GET","POST"})
     */
    public function deactivate(Request $request, Cargo $cargo)
    {
        $auditor = $cargo->getAuditores();
        $plazas = $cargo->getPlazas();

        if (count($auditor) > 0 || count($plazas) > 0)
        {
            $this->addFlash(
                'error',
                'No se puede eliminar este cargo porque existe algún auditor ocupándolo!'
            );

            return true;
        }
        else
        {
            $cargo->setActivo(false);
            $bitacora = new Log();
            $bitacora->setFecha(new \DateTime('now'))
                ->setUsuario($this->getUser())
                ->setIp($request->getClientIp())
                ->setAccion('Desactivar cargo: '.$cargo->getId());
            $this->getDoctrine()->getManager()->persist($bitacora);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'notice',
                'Cargo desactivado!'
            );

            return false;
        }
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/{id}", name="cargo_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Cargo $cargo): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cargo->getId(), $request->request->get('_token'))) {
            if ($this->deactivate($request, $cargo))
            {
                return $this->redirectToRoute('cargo_show', array('id'=>$cargo->getId()));
            }
        }

        return $this->redirectToRoute('cargo_index');
    }
}
