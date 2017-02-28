<?php

namespace akerbel\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use akerbel\BlogBundle\Entity\Message;
use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\HttpKernel\Log\DebugLoggerInterface;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('akerbelBlogBundle:Default:index.html.twig');
    }
    
    public function postAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();

        $message = new Message();

        $message->setTitle($request->get('title'));
        $message->setText($request->get('text'));

        $em->persist($message);
        $em->flush();

        return new JsonResponse(
            [
                'result' => 'success',
                'message_id' => $message->getId(),
            ],
            Response::HTTP_OK
        );
    }
    
    public function listAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $messages = $em->getRepository('akerbelBlogBundle:Message')->findBy([], [], $request->get('limit'), $request->get('offset'));
        if (!count($messages)) {
            throw new \Exception('The blog haven`t had any messages yet', 404);
        }

        $result = [];
        foreach ($messages as $message){
            $result[] = $message->toArray();
        }

        return new JsonResponse(
            [
                'result' => 'success',
                'messages' => $result,
            ],
            Response::HTTP_OK
        );
    }
    
    public function getAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $message = $em->getRepository('akerbelBlogBundle:Message')->find($id);
        if (!$message) {
            throw new \Exception("The message #$id isnot found", 404);
        }

        return new JsonResponse(
            [
                'result' => 'success',
                'message' => $message->toArray(),
            ],
            Response::HTTP_OK
        );
    }

    public function patchAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $message = $em->getRepository('akerbelBlogBundle:Message')->find($id);
        if (!$message) {
            throw new \Exception("The message #$id isnot found", 404);
        }

        if (null !== $request->get('title')) {
            $message->setTitle($request->get('title'));
        }
        if (null !== $request->get('text')) {
            $message->setText($request->get('text'));
        }

        $em->flush();

        return new JsonResponse(
            [
                'result' => 'success',
            ],
            Response::HTTP_OK
        );
    }

    public function deleteAction($id)
    {

        $em = $this->getDoctrine()->getManager();

        $message = $em->getRepository('akerbelBlogBundle:Message')->find($id);
        if (!$message) {
            throw new \Exception("The message #$id isnot found", 404);
        }

        $em->remove($message);

        $em->flush();

        return new JsonResponse(
            [
                'result' => 'success',
            ],
            Response::HTTP_OK
        );
    }

    public function exceptionAction(Request $request, FlattenException $exception, DebugLoggerInterface $logger = null)
    {
        return new JsonResponse(
            [
                'result' => 'error',
                'error_code' => $exception->getCode(),
                'error_text' => $exception->getMessage(),
            ],
            ($exception->getCode() ? $exception->getCode() : 500)
        );
    }
    
}
