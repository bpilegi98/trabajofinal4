<?php namespace DAOJson;

use Models\CreditCard as CreditCard;
use DAO\IRepository as IRepository;

class CreditCardRepository implements IRepository
{
    private $creditCardList = array ();

    public function __constructor(){

    }

    public function Add($creditCard){ 

        $this->retrieveData();
    
        if (empty($this->creditCardList)) {
            $newId = 1;
        } else {
            $lastElement = end($this->creditCardList);
            $newId = $lastElement->getId() + 1;
        }
        $creditCard->setId($newId);
        $creditCard->setStatus(true);

        array_push($this->creditCardList, $creditCard);

        $this->saveData();
    }

    public function read($id)
    {
        $this->retrieveData();
        $flag=false;
        $creditCardReturn = new CreditCard();
        foreach($this->creditCardList as $cc)
        {
            if(!$flag)
            {
                if($id==$cc->getId())
                {
                    $flag=true;
                    $creditCardReturn=$cc;
                }
            }
        }
        return $creditCardReturn;
    }

    public function getAll(){

        $this->retrieveData();

        return $this->creditCardList;
    }

    public function saveData(){

        $arrayToJson = array();

        foreach($this->creditCardList as $creditCard){

            //id?
            $valuesArray["id"] = $creditCard->getId();
            $valuesArray["company"] = $creditCard->getCompany();
            $valuesArray["number"] = $creditCard->getNumber();
            $valuesArray["status"] = $creditCard->getStatus();
            $valuesArray["idUser"] = $creditCard->getIdUser();
           

            array_push($arrayToJson, $valuesArray);
        }

        $jsonContent = json_encode($arrayToJson, JSON_PRETTY_PRINT);

        file_put_contents('Data/creditCards.json', $jsonContent);
    }


    public function retrieveData(){

        $this->creditCardList = array ();
       
        if(file_exists('Data/creditCards.json')){

            $jsonContent = file_get_contents('Data/creditCards.json');
    
            $arrayToDecode= ($jsonContent) ? json_decode($jsonContent, true) : array();
         
            foreach($arrayToDecode as $valuesArray){

                $creditCard = new CreditCard();
                
                $creditCard->setId($valuesArray["id"]);
                $creditCard->setCompany($valuesArray["company"]);
                $creditCard->setNumber($valuesArray["number"]);
                $creditCard->setStatus($valuesArray["status"]);
                $creditCard->setIdUser($valuesArray["idUser"]);
              

                array_push($this->creditCardList, $creditCard);
            }
        }
    }

    public function edit($creditCard)
    {

        $this->retrieveData();

        foreach($this->creditCardList as $values){

            if($values->getId() == $creditCard->getId()){
                $values->setId($creditCard->getId());
                $values->setCompany($creditCard->getCompany());
                $values->setNumber($creditCard->getNumber());
                $values->setStatus($creditCard->getStatus());
                $values->setIdUser($creditCard->getIdUser());
            break;
            }
        }
        $this->saveData();

    }

    public function delete($id) 
    {   
    
        //no pusimos status en credit card asi que sera fisica cuando la hagamos XD
        
    }


  //funciones extras

    public function getCreditCards($id_user)
    {
        $this->retrieveData();
        $aux = array();
        $flag = false;

        foreach($this->creditCardList as $values)
        {
            if($id_user == $values->getIdUser()){
                array_push($aux,$values);   
            $flag = true;         
        }
        }

        if($flag){
            return $aux;
        }
        else{
            return $flag;
        }
    }

    public function getId($creditCard)
    {
      $this->retrieveData();
      foreach($this->creditCardList as $values)
      {
           if($values->getNumber() == $creditCard->getNumber())
           {
                $creditCard->setId($values->getId());
           }
      }

      return $creditCard;
    }
    
    //devuelve un arreglo de purchases que coinciden con el id de creditCard
    public function getPurchasesById($listadoP, $listadoCC)
    {
        $result = array ();

        if($listadoCC != null)
        {
            if(is_array($listadoCC))
            {
                foreach($listadoCC as $creditCard)
                {
                    if(is_array($listadoP))
                    {
                        foreach($listadoP as $purchase)
                        {
                            if($creditCard->getId() == $purchase->getIdCreditCard())
                            {
                                array_push($result, $purchase);
                            }
                        }
                    }
                    else
                    {
                        if($creditCard->getId() == $listadoP->getIdCreditCard())
                        {
                            array_push($result, $listadoP);
                        }
                    }
                }
            }
            else
            {
                if(is_array($listadoP))
                {
                    foreach($listadoP as $purchase)
                    {
                        if($listadoCC->getId() == $purchase->getIdCreditCard())
                        {
                            array_push($result, $purchase);
                        }
                    }
                }
                else
                {
                    if($listadoCC->getId() == $listadoP->getIdCreditCard())
                    {
                        array_push($result, $listadoP);
                    }
                }
            }
        }
        else
        {
            $result = $listadoP;
        }

        return $result;
    }
}
