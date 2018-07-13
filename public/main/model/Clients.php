<?php

use EF2\Core\Model;
use EF2\Db\Criteria;

class Clients extends Model
{
    protected $table = "clients";

    public function add($params)
    {
        if (!$this->has($params["clientId"])) {
            $model = new $this;
            $model->client_id = $params["clientId"];
            $model->last_at = date("Y-m-d H:i:s");
            $model->save();

            return [
                "result"=>1
            ];
        }else{
            $this->updateLast($params["clientId"]);
            $client=$this->get($params["clientId"]);

            $k=time()-strtotime($client->created_at);
            if($k>0 && $k<=60*60*24*7)
            {
                return [
                    "result"=>2,
                    "second"=> $k
               ];
            }else if($k>60*6*24*7 && $k<=60*60*24*14){
                return [
                    "result"=>3,
                    "second"=> $k
                ];
            }else{
                return [
                    "result"=>4,
                    "second"=> $k
                ];
            }

        }


    }


    public function has($clientid)
    {
        $criteria = new Criteria;
        $criteria->condition("client_id=:clientId", [":clientId" => $clientid]);

        $count = self::model()->count($criteria);

        if ($count > 0) {
            return true;
        }

        return false;
    }


    public function updateLast($clientid)
    {
        $criteria = new Criteria;
        $criteria->condition("client_id=?", [$clientid]);

        self::model()->update($criteria,["last_at"=>date("Y-m-d H:i:s")]);


    }

    public function get($clientid)
    {
        $criteria = new Criteria;
        $criteria->condition("client_id=:clientId", [":clientId" => $clientid]);

        return self::model()->find($criteria);
    }
}