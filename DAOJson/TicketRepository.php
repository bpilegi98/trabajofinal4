<?php namespace DAOJson;

use Models\Ticket as Ticket;
use DAO\IRepository as IRepository;

class TicketRepository implements IRepository
{
    private $ticketList = array ();

    public function __constructor(){

    }

    public function Add($ticket){ 

        $this->getAll();

        array_push($this->ticketList, $ticket);

        $this->saveData();
    }

    public function read($id)
    {
        $this->retrieveData();
        $flag=false;
        $ticketReturn = new Ticket();
        foreach($this->ticketList as $t)
        {
            if(!$flag)
            {
                if($id==$t->getIdTicket())
                {
                    $flag=true;
                    $ticketReturn=$t;
                }
            }
        }
        return $ticketReturn;
    }

    public function getAll(){

        $this->retrieveData();

        return $this->ticketList;
    }

    public function saveData(){

        $arrayToJson = array();

        foreach($this->ticketList as $ticket){

            //id?
            $valuesArray["idPurchase"] = $ticket->getIdPurchase();
           

            array_push($arrayToJson, $valuesArray);
        }

        $jsonContent = json_encode($arrayToJson, JSON_PRETTY_PRINT);

        file_put_contents('Data/tickets.json', $jsonContent);
    }


    public function retrieveData(){

        $this->ticketList = array ();
       
        if(file_exists('Data/tickets.json')){

            $jsonContent = file_get_contents('Data/tickets.json');
    
            $arrayToDecode= ($jsonContent) ? json_decode($jsonContent, true) : array();
         
            foreach($arrayToDecode as $valuesArray){

                $ticket = new Ticket();
                
                //id?
                $ticket->setIdPurchase($valuesArray["idPurchase"]);
              
                array_push($this->ticketList, $ticket);
            }
        }
    }

    public function edit($ticket)
    {

        //se supone que no podes editar una compra

    }

    public function delete($id) 
    {
        
    }


}
