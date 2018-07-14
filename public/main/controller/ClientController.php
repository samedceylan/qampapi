<?php

use EF2\Core\Controller;
use ResultResponse as Result;
use EF2\Http\Request;
use FormValidation as SValidation;

class ClientController extends Controller
{

    public function actionState(Request $request, Result $result, SValidation $validation)
    {

        if($request->header->token!="e9e4ed801f4fe9d06a15cb890b15e4fb")
            $result->response(-1,"token");

        $validate = $request->validate([
            ["clientId", "clientId", "required"]
        ]);

        if (count($validate->getErrors()) > 0) {
            $result->response(-1, "error", $validate->getReadableErrors(true));
        }
        $client=new Clients;
        $res=$client->add([
            "clientId"=>$request->post->clientId
        ]);

        if($res["result"]==1)
          $result->response(1,"add");
        else if($res["result"]==2)
            $result->response(2,"no panik");
        else if($res["result"]==3)
            $result->response(3,"popup");
        else if($res["result"]==4)
            $result->response(4,"popup pause music");
    }
}