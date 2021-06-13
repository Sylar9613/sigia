<?php

namespace App\Controller;

use App\Entity\Log;
use App\Entity\User;
use App\Form\ChangeRol;
use App\Form\ChangeRolType;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Tests\Encoder\PasswordEncoder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     * @Route("/", name="user_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository, Request $request): Response
    {
        /*$session = $request->getSession();
        $session->set('path', $request->getPathInfo());*/
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
            'routes' => ['Usuario'],
            'url' => 'usuario',
        ]);
    }

    /**
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     * @Route("/new", name="user_new", methods={"GET","POST"})
     */
    public function new(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        // 1) construir el form
        $usuario = new User();
        $form = $this->createForm(UserType::class,$usuario);

        // 2) manipular el submit
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            // 3) encode la password
            $password = $passwordEncoder
                ->encodePassword($usuario, $usuario->getPlainPassword());
            $usuario->setPassword($password);
            $register = $request->request->get('user');
            if (isset($register['roles'])){
                $roles = $register['roles'];
            }
            else{
                $roles = ["ROLE_USER"];
            }
            //$roles = ["ROLE_USUARIO", "ROLE_ADMIN", "ROLE_SUPER_ADMIN"];
            $usuario->setRoles($roles);

            // 4) salvar el usuario
            $em = $this->getDoctrine()->getManager();
            $entidad = $em->getRepository('App\Entity\Entidad')->find(1);
            $usuario->setEntidad($entidad);
            /*$user = $em->getRepository('UsuariosBundle:Usuario')->find($request->request->get('user'));
            $bitacora = new Bitacora();
            $bitacora->setFecha(new \DateTime('now'))
                ->setUsuario($user)
                ->setIp($request->getClientIp())
                ->setAccion('Registrar usuario');

            $em->persist($bitacora);*/
            $usuario->setAvatar($request->request->get('_avatar'));
            $bitacora = new Log();
            $bitacora->setFecha(new \DateTime('now'))
                ->setUsuario($this->getUser())
                ->setIp($request->getClientIp())
                ->setAccion('Registrar usuario: '.$usuario->getId());
            $em->persist($bitacora);
            $em->persist($usuario);
            $em->flush();

            $this->addFlash(
                'notice',
                'Usuario registrado!'
            );

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/new.html.twig', [
            'user' => $usuario,
            'form' => $form->createView(),
            'routes' => ['Usuario', 'Nuevo'],
			'url' => 'usuario',
        ]);
    }

    /**
     * @Route("/{id}", name="user_show", methods={"GET"})
     */
    public function show(User $user, Request $request): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
            'routes' => ['Usuario', 'Ver'],
			'url' => 'usuario',
        ]);
    }

    /**
     * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
     * @Security("usuario.isUser(user)")
     */
    public function edit(Request $request, User $usuario, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $form = $this->createForm(UserType::class, $usuario);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder
                ->encodePassword($usuario, $usuario->getPlainPassword());
            $usuario->setPassword($password);
            $usuario->setAvatar($request->request->get('_avatar'));
            $bitacora = new Log();
            $bitacora->setFecha(new \DateTime('now'))
                ->setUsuario($this->getUser())
                ->setIp($request->getClientIp())
                ->setAccion('Actualizar usuario: '.$usuario->getId());
            $this->getDoctrine()->getManager()->persist($bitacora);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'notice',
                'Usuario actualizado!'
            );
            if (!$this->isGranted("ROLE_SUPER_ADMIN"))
            {
                return $this->redirectToRoute('homepage');
            }
            return $this->redirectToRoute('user_index');
        }

        $response = new Response('Access Forbidden',Response::HTTP_FORBIDDEN);

        return $this->render('user/edit.html.twig', [
            'user' => $usuario,
            'form' => $form->createView(),
            'routes' => ['Usuario', 'Editar'],
			'url' => 'usuario',
            'response' => $response,
        ]);
    }

    /**
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     * @Route("/{id}/activate", name="user_activate", methods={"GET","POST"})
     */
    public function activate(Request $request, User $user): Response
    {
        $user->setIsActive(true);
        $bitacora = new Log();
        $bitacora->setFecha(new \DateTime('now'))
            ->setUsuario($this->getUser())
            ->setIp($request->getClientIp())
            ->setAccion('Activar usuario: '.$user->getId());
        $this->getDoctrine()->getManager()->persist($bitacora);
        $this->getDoctrine()->getManager()->flush();

        $this->addFlash(
            'notice',
            'Usuario activado!'
        );
        return $this->redirectToRoute('user_index');
    }

    /**
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     * @Route("/{id}/deactivate", name="user_deactivate", methods={"GET","POST"})
     */
    public function deactivate(Request $request, User $user): Response
    {
        $user->setIsActive(false);
        $bitacora = new Log();
        $bitacora->setFecha(new \DateTime('now'))
            ->setUsuario($this->getUser())
            ->setIp($request->getClientIp())
            ->setAccion('Desactivar usuario: '.$user->getId());
        $this->getDoctrine()->getManager()->persist($bitacora);
        $this->getDoctrine()->getManager()->flush();

        $this->addFlash(
            'notice',
            'Usuario desactivado!'
        );

        return $this->redirectToRoute('user_index');
    }

    /**
     * @Route("/{id}/change_password", name="change_password", methods={"GET","POST"})
     * @Security("usuario.isUser(user)")
     */
    public function changePass(Request $request, User $usuario, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $form = $this->createForm(UserType::class, $usuario);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder
                ->encodePassword($usuario, $usuario->getPlainPassword());
            $usuario->setPassword($password);
            $usuario->setAvatar($request->request->get('_avatar'));
            $bitacora = new Log();
            $bitacora->setFecha(new \DateTime('now'))
                ->setUsuario($this->getUser())
                ->setIp($request->getClientIp())
                ->setAccion('Actualizar usuario: '.$usuario->getId());
            $this->getDoctrine()->getManager()->persist($bitacora);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'notice',
                'Usuario actualizado!'
            );

            if (!$this->isGranted("ROLE_SUPER_ADMIN"))
            {
                return $this->redirectToRoute('homepage');
            }
            return $this->redirectToRoute('user_index');
        }


        return $this->render('user/change_password.html.twig', [
            'user' => $usuario,
            'routes' => ['Usuario', 'Cambiar contraseña'],
            'url' => 'usuario',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/change_rol", name="change_rol", methods={"GET","POST"})
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     */
    public function changeRol(Request $request, User $usuario): Response
    {
        $form = $this->createForm(ChangeRolType::class, $usuario);
        $form->handleRequest($request);

        if ($form->isSubmitted()/* && $form->isValid()*/) {
            $bitacora = new Log();
            $bitacora->setFecha(new \DateTime('now'))
                ->setUsuario($this->getUser())
                ->setIp($request->getClientIp())
                ->setAccion('Actualizar usuario: '.$usuario->getId());
            $this->getDoctrine()->getManager()->persist($bitacora);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'notice',
                'Usuario actualizado!'
            );

            if (!$this->isGranted("ROLE_SUPER_ADMIN"))
            {
                return $this->redirectToRoute('homepage');
            }
            return $this->redirectToRoute('user_index');
        }


        return $this->render('user/change_rol.html.twig', [
            'user' => $usuario,
            'routes' => ['Usuario', 'Cambiar roles'],
            'url' => 'usuario',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_delete", methods={"DELETE"})
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $this->deactivate($request, $user);
            /*$entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();*/
        }

        return $this->redirectToRoute('user_index');
    }
}
