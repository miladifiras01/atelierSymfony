<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
#[Route('/todo')]
class ToDoController extends AbstractController
{
    #[Route('', name: 'todo')]
    public function index(Request $request): Response
    {
        $session=$request->getSession();
        if(!$session->has('todos')){
            $todos = [
                'achat'=>'acheter clé usb',
                'cours'=>'Finaliser mon cours',
                'correction'=>'corriger mes examens'
            ];
            $session->set('todos',$todos) ;
            $this->addFlash('info','liste de todo vient d\'être initialiser ' );
        }
        return $this->render('to_do/index.html.twig');
    }
    #[Route('/add/{name?test}/{content?pardefaut}',name: 'todo.add')]
    public function addTodo(Request $request,$name,$content){
       $session=$request->getSession() ;
        //verifier si jai mon tab to do dans la session
       if($session->has('todos'))
           //si oui
       {
$todos=$session->get('todos') ;
if(isset($todos[$name])) {
    // si on a un todo avec le mm name erreur
    $this->addFlash('error', "$name existe deja" );
}
else{
    // sinon on lajoute et on affiche un message de succes
        $todos[$name]=$content ;
$session->set('todos',$todos) ;
    $this->addFlash('success', "$name a ete ajoute avec succes" ) ;
}
        }
        else{
            //si non
                //afficher une error et on va rediriger vers le controleur index
            $this->addFlash('error',"liste todo n'est pas encore initialiser " );
    }
        return $this->redirectToRoute('todo') ;
    }
#[Route('/delete/{name}',name: 'todo.delete')]
    public function deleteTodo(Request $request,$name){
    $session=$request->getSession() ;
    //verifier si jai mon tab to do dans la session
    if($session->has('todos'))
        //si oui
    {
        $todos=$session->get('todos') ;
        if(!isset($todos[$name])) {
            $this->addFlash('error', "$name inexistant" );
        }
        else{
            unset($todos[$name]) ;
            $session->set('todos',$todos) ;
            $this->addFlash('success', "$name a ete supprimee avec succes" ) ;
        }
    }else{
        $this->addFlash('error',"liste todo n'est pas encore initialiser " );
    }
    return($this->redirectToRoute('todo')) ;
    }
    #[Route('/update/{name}/{content}', name: 'todo.update')]
    public function updateTodo(Request $request, $name, $content){
        $session = $request->getSession();
        if ($session->has('todos')) {
            $todos = $session->get('todos');
            if (!isset($todos[$name])) {
                $this->addFlash('error', "Le todo d'id $name n'existe pas dans la liste");
            } else {
                $todos[$name] = $content;
                $session->set('todos', $todos);
                $this->addFlash('success', "Le todo d'id $name a été modifié avec succès");
            }
        } else {
            $this->addFlash('error', "La liste des todos n'est pas encore initialisée");
        }
        return $this->redirectToRoute('todo');
    }
}

