<?php

namespace App\Controller;

use App\Entity\Cargo;
use App\Entity\Entidad;
use App\Entity\Log;
use App\Entity\Plaza;
use App\Form\PlazaType;
use App\Repository\AuditorRepository;
use App\Repository\CargoRepository;
use App\Repository\EntidadRepository;
use App\Repository\PlazaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/plaza")
 */
class PlazaController extends AbstractController
{
    /**
     * @Route("/", name="plaza_index", methods={"GET"})
     */
    public function index(PlazaRepository $plazaRepository, Request $request, EntidadRepository $entidadRepository, CargoRepository $cargoRepository, AuditorRepository $auditorRepository): Response
    {
        return $this->render('plaza/index.html.twig', [
            'plazas' => $plazaRepository->findAll(),
            'entidades' => $entidadRepository->findBy(array('activo'=>1)),
            'cargos' => $cargoRepository->findBy(array('activo'=>1)),
            'url' => 'auditor',
            'routes' => ['Personal', 'Plazas'],
            'aprob' => $plazaRepository->findPlazasAprob()[1],
            'cub' => count($auditorRepository->findPlazasCub()),
        ]);
    }

    /**
     * Filter all plazas by entidades and/or cargos
     *
     * @Route("/", name="plaza_filter", methods={"GET", "POST"})
     */
    public function filterAction(Request $request, PlazaRepository $plazaRepository, EntidadRepository $entidadRepository, CargoRepository $cargoRepository, AuditorRepository $auditorRepository)
    {
        $ident = $request->request->get('filtrar_ent');
        $idcar = $request->request->get('filtrar_car');

        $em = $this->getDoctrine()->getManager();

        if($ident=='todas')
        {
            if ($idcar=='todos')
            {
                return $this->redirectToRoute("plaza_index");
            }
            else
            {
                $plazas = $plazaRepository->findBy(array('cargo'=>$idcar));
            }
        }
        else
        {
            if ($idcar=='todos')
            {
                $plazas = $plazaRepository->findBy(array('entidad'=>$ident));
            }
            else
            {
                $plazas = $plazaRepository->findBy(array('entidad'=>$ident, 'cargo'=>$idcar));
            }
        }

        $bitacora = new Log();
        $bitacora->setFecha(new \DateTime('now'))
            ->setUsuario($this->getUser())
            ->setIp($request->getClientIp())
            ->setAccion('Filtrar plazas');
        $em->persist($bitacora);
        $em->flush();

        return $this->render("plaza/index.html.twig", array(
            'plazas' => $plazas,
            'entidades' => $entidadRepository->findBy(array('activo'=>1)),
            'cargos' => $cargoRepository->findBy(array('activo'=>1)),
            'url'=>'auditor',
            'routes' => ['Personal', 'Entidad'],
            'aprob' => $plazaRepository->findPlazasAprob()[1],
            'cub' => count($auditorRepository->findPlazasCub()),
        ));
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/new", name="plaza_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $plaza = new Plaza();
        $form = $this->createForm(PlazaType::class, $plaza);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            /*
             * $objeto = $request->request->get('plaza');
             * $exist = $entityManager->getRepository('App\Entity\Plaza')
                ->findOneBy(array('entidad'=>$objeto['entidad'],'cargo'=>$objeto['cargo']));

            if ($exist!=null){

                return $this->render('plaza/new.html.twig', array(
                    'plaza' => $plaza,
                    'form' => $form->createView(),
                    'url' => 'clasificador'
                ));
            }*/
            $bitacora = new Log();
            $bitacora->setFecha(new \DateTime('now'))
                ->setUsuario($this->getUser())
                ->setIp($request->getClientIp())
                ->setAccion('Insertar plazas');
            $entityManager->persist($bitacora);
            $entityManager->persist($plaza);
            $entityManager->flush();

            $this->addFlash(
                'notice',
                'Plaza registrada!'
            );
            return $this->redirectToRoute('plaza_index');
        }

        return $this->render('plaza/new.html.twig', [
            'plaza' => $plaza,
            'form' => $form->createView(),
            'url' => 'auditor',
            'routes' => ['Personal', 'Plazas', 'Nueva'],
        ]);
    }

    /**
     * @Route("/{id}", name="plaza_show", methods={"GET"})
     */
    public function show(Plaza $plaza, Request $request): Response
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());
        return $this->render('plaza/show.html.twig', [
            'plaza' => $plaza,
            'url' => 'auditor',
            'routes' => ['Personal', 'Plazas', 'Ver'],
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/{id}/edit", name="plaza_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Plaza $plaza): Response
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());
        $form = $this->createForm(PlazaType::class, $plaza);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $objeto = $request->request->get('plaza');
            $byEntidadAndCargo = $this->getDoctrine()->getManager()->getRepository('App\Entity\Auditor')
                ->findBy(array('entidad'=>$objeto['entidad'],'cargo'=>$objeto['cargo'],'activo'=>'1'));

            if ($objeto['plazas']<count($byEntidadAndCargo))
            {
                $this->addFlash(
                    'error',
                    'No se puede modificar la cantidad de plazas porque está ocupada la plantilla, para realizar esta acción debe primero eliminar el auditor del cargo o de la entidad en la que se encuetra!'
                );

                return $this->render('plaza/edit.html.twig', array(
                    'plaza' => $plaza,
                    'form' => $form->createView(),
                    'url' => 'auditor',
                    'routes' => ['Personal', 'Plazas', 'Editar'],
                ));
            }
            $bitacora = new Log();
            $bitacora->setFecha(new \DateTime('now'))
                ->setUsuario($this->getUser())
                ->setIp($request->getClientIp())
                ->setAccion('Modificar plaza: '.$plaza->getId());
            $this->getDoctrine()->getManager()->persist($bitacora);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'notice',
                'Plaza actualizada!'
            );

            return $this->redirectToRoute('plaza_index');
        }

        return $this->render('plaza/edit.html.twig', [
            'plaza' => $plaza,
            'form' => $form->createView(),
            'url' => 'auditor',
            'routes' => ['Personal', 'Plazas', 'Editar'],
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/{id}/activate", name="plaza_activate", methods={"GET","POST"})
     */
    public function activate(Request $request, Plaza $plaza): Response
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());
        $plaza->setActivo(true);
        $bitacora = new Log();
        $bitacora->setFecha(new \DateTime('now'))
            ->setUsuario($this->getUser())
            ->setIp($request->getClientIp())
            ->setAccion('Activar plaza: '.$plaza->getId());
        $this->getDoctrine()->getManager()->persist($bitacora);
        $this->getDoctrine()->getManager()->flush();

        $this->addFlash(
            'notice',
            'Plaza activada!'
        );
        return $this->redirectToRoute('plaza_index');
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/{id}/deactivate", name="plaza_deactivate", methods={"GET","POST"})
     */
    public function deactivate(Request $request, Plaza $plaza)
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());

        $entityManager = $this->getDoctrine()->getManager();

        /**
         * @var Cargo $cargo
         */
        $cargo = $plaza->getCargo();
        /**
         * @var Entidad $entidad
         */
        $entidad = $plaza->getEntidad();

        $byEntidadAndCargo = $entityManager->getRepository('App\Entity\Auditor')
            ->findOneBy(array('entidad'=>$entidad->getId(),'cargo'=>$cargo->getId(),'activo'=>'1'));

        if ($byEntidadAndCargo!=null)
        {
            $this->addFlash(
                'error',
                'No se puede eliminar la relación entre esta entidad y el cargo porque existe(n) auditor(es) ocupando esta(s) plaza(s)!'
            );

            return true;
        }
        else
        {
            $plaza->setActivo(false);
            $bitacora = new Log();
            $bitacora->setFecha(new \DateTime('now'))
                ->setUsuario($this->getUser())
                ->setIp($request->getClientIp())
                ->setAccion('Desactivar plaza: '.$plaza->getId());
            $this->getDoctrine()->getManager()->persist($bitacora);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'notice',
                'Plaza desactivada!'
            );

            return false;
        }
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/{id}", name="plaza_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Plaza $plaza): Response
    {
        if ($this->isCsrfTokenValid('delete'.$plaza->getId(), $request->request->get('_token'))) {
            if ($this->deactivate($request, $plaza))
            {
                return $this->redirectToRoute('plaza_show', array('id'=>$plaza->getId()));
            }
        }

        return $this->redirectToRoute('plaza_index');
    }
}
