<?php
namespace App\Controller\Component;
use Cake\Controller\Component;

class SearchComponent extends Component {
    var $controller = null;
    public function initialize(array $config){
        $this->controller = $this->getController();
    }
    function getConditions(){
        $conditions = [];
        $query = $this->request->getQuery();
        if(!empty($query)) {
            //pr($query);
            foreach ($query as $param_name => $value) {
                if(!in_array($param_name, ['sort', 'direction', 'page', 'limit'])){
                    $type = $this->controller->{$this->controller->modelClass}->schema()->columnType($param_name);
                    if(isset($type) && !empty($value)){
                        switch($type){
                            case "string":
                                if(in_array($this->controller->modelClass, ['Audits'])){
                                    $conditions[$this->controller->modelClass . "." .$param_name] =  $value;
                                }else{
                                    $conditions[$this->controller->modelClass . "." .$param_name . " LIKE"] = "%".trim($value)."%";
                                }
                                break;
                            case "integer":
                                $conditions[$this->controller->modelClass . "." .$param_name] =  $value;
                                break;
                            case "biginteger":
                                $conditions[$this->controller->modelClass . "." . $param_name] =  $value;
                                break;
                            case "boolean":
                                $conditions[$this->controller->modelClass . "." .$param_name] =  $value - 1;
                                break;
                            case "datetime":
                                $temp = explode(' - ', $value);
                                $conditions[$this->controller->modelClass.".".$param_name." >="] = trim($temp[0].' 00:00:00');
                                $conditions[$this->controller->modelClass.".".$param_name." <="] = trim($temp[1].' 23:59:59');
                                break;
                            case "date":
                                $temp = explode(' - ', $value);
                                $conditions[$this->controller->modelClass.".".$param_name." >="] = trim($temp[0]);
                                $conditions[$this->controller->modelClass.".".$param_name." <="] = trim($temp[1]);
                                break;
                        }
                        $this->request->data[$param_name] = $value;
                    }else{
                        if(!empty($value)){
                            $_name = explode('__', $param_name);
                            $model = $_name[0];
                            $model_param = $_name[1];
                            $type = $this->controller->{$this->controller->modelClass}->{$model}->schema()->columnType($model_param);
                            switch($type){
                                case "string":
                                case "integer":
                                    $conditions[$model . "." .$model_param] =  $value;
                                    break;
                                case "boolean":
                                    $conditions[$model . "." .$model_param] =  $value - 1;
                                    break;
                            }
                        }
                        $this->request->data[$param_name] = $value;
                    }
                }
            }
            //pr($conditions);
            //pr($this->request->getData());
        }
        return $conditions;
    }
}