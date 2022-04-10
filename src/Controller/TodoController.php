<?php

namespace App\Controller;

use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class TodoController extends AbstractController
{
    /**
     * @Route("");
     *
     */
    public function redirecttodo()
    {
        return $this->redirect('/todo');
    }

    #[Route('/todo', name: 'app_todo')]
    public function index(SessionInterface $session): Response
    {
        if (!$session->has("todos")) {
            $todo = [
                "techno web" => "symfony"
            ];
            $session->set("todos", $todo);
            $this->addFlash("welcome", "bienvenu dans votre todo");

        }
        return $this->render(view: 'todo/index.html.twig');
    }

    /**
     * @Route("/todo/add/{nom}/{contenu}");
     */
    public function addtodo($nom, $contenu, SessionInterface $session)
    {
        if (!$session->has('todos')) {
            $this->addFlash("defaut", "il y'a eu une erreur");
        } else {
            $todos = $session->get('todos');
            if (isset($todos[$nom])) {
                $this->addFlash("defaut", "existe deja");
            } else {
                $todos[$nom] = $contenu;
                $session->set('todos', $todos);
                $this->addFlash("success", "done");
            }
        }
        return $this->redirect('/todo');
    }

    /**
     * @Route("/todo/delete/{nom}");
     */
    public function deletetodo($nom, SessionInterface $session)
    {
        if (!$session->has('todos')) {
            $this->addFlash("defaut", "il y'a eu une erreur");
        } else {
            $todos = $session->get('todos');
            if (!isset($todos[$nom])) {
                $this->addFlash("defaut", "n'existe pas ");
            } else {
                unset($todos[$nom]);
                $session->set('todos', $todos);
                $this->addFlash("defaut", "mise a jour effectuer");
            }
        }
        return $this->redirect('/todo');
    }

    /**
     * @Route("/todo/reset");
     */
    public function reset(SessionInterface $session)
    {
        if (!$session->has('todos')) {
            $this->addFlash("defaut", "deja vide");
        } else {
            $session->clear();
        }
        return $this->redirect('/todo');
    }
}
