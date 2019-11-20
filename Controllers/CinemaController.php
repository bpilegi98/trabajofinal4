<?php

namespace Controllers;

use Controllers\MovieTheaterController as MovieTheaterController;
use Controllers\ShowController as ShowController;
use Models\Cinema as Cinema;

use DAOJson\CinemaDAO as CinemaDAO;
// use DAO\CinemaRepository as CinemaDAO;


class CinemaController
{

    private $cinemaDAO;
    private $showController;

    public function __construct()
    {
        $this->cinemaDAO = new CinemaDAO();
        $this->showController = new ShowController();
    }

    public function getCinemaIdByName($cinemaName)
    {

        $cinemaList = $this->cinemaDAO->getAll();

        $result = null;
        if(is_array($cinemaList)){
            foreach ($cinemaList as $cinema) {
                if ($cinema->getName() == $cinemaName) {
                    $result = $cinema->getId();
                    break;
                }
            }
        }
        else{
            if ($cinemaList->getName() == $cinemaName) {
                $result = $cinemaList->getId();
            }
        }
        return $result;
    }

    public function getShowsByCinemaList($cinemaList = array())
    {

        $result = array();
        if(is_array($cinemaList)){ 
        foreach ($cinemaList as $cinema) 
        {
            $showsList = $this->showController->getShowListByCinema($cinema);
            if($showsList != false)
            {
                if(is_array($showsList)){
                    foreach ($showsList as $show) {
                        array_push($result, $show);
                    }
                }
                else{
                    array_push($result, $showList);
                }
            }
        }
        }
        else
        {
            $showsList = $this->showController->getShowListByCinema($cinemaList);
            if($showsList != false)
            {
                if(is_array($showsList)){
                    foreach ($showsList as $show) {
                        array_push($result, $show);
                    }
                }
                else{
                    array_push($result, $showList);
                }
            }   
        }

        return $result;
    }

    public function getCinemaList($idMovieTheater)
    {

        $result = array();
        $cinemasArray = $this->cinemaDAO->getAll();
        if($cinemasArray !=null){
            if(is_array($cinemasArray)){ 
                foreach ($cinemasArray as $cinema) {
                    if ($cinema->getIdMovieTheater() == $idMovieTheater)
                        array_push($result, $cinema);
                }       
            }   
            else{
                if ($cinemasArray->getIdMovieTheater() == $idMovieTheater)
                array_push($result, $cinemasArray);
             }
    }

        return $result;
    }

    public function getListIdCinema($idMovieTheater)
    {

        $result = array();

        foreach ($this->cinemaDAO->getAll() as $cinema) {
            if ($cinema->getIdMovieTheater() == $idMovieTheater)
                array_push($result, $cinema->getId());
        }

        return $result;
    }







    public function createCinema($name, $seatsNumber, $price, $idMovieTheater)
    {
        $cinema = new Cinema();
        $cinema->setName($name);
        $cinema->createSeats($seatsNumber);
        $cinema->setTicketPrice($price);
        $cinema->setIdMovieTheater($idMovieTheater);
        $cinema->setStatus(true);

        $this->cinemaDAO->Add($cinema);
    }
    public function modifyCinema($id, $status, $name, $seatsNumber, $idMovieTheater)
    {
        $cinemaList = $this->cinemaDAO->getAll();
        $cinemaAux = new Cinema();
        foreach ($cinemaList as $cinema) {
            if ($cinema->getId() == $id) {
                $cinemaAux->setId($id);
                $cinemaAux->setStatus($status);
                $cinemaAux->setName($name);
                $cinemaAux->createSeats($seatsNumber);
                $cinemaAux->setIdMovieTheater($idMovieTheater);
                break;
            }
        }
        $this->cinemaDao->edit($cinemaAux);
    }
    

    public function deleteAllCinemaById($idCinemaList = array())
    {
        foreach ($idCinemaList as $idCinema) {
            $this->deleteCinemaById($idCinema);
        }
    }

    public function deleteCinemaById($id)
    {
        //aca pongo showdelete
        $this->showController->deleteAllShowsById($id);
        $this->cinemaDAO->deleteFisico($id);
    }

    

    public function deleteCinemaByMovieTheaterId($movieTheaterId)
    {
        $cinemaList = $this->cinemaDAO->getAll();
        foreach ($cinemaList as $cinema) {
            if ($cinema->getIdMovieTheater() == $movieTheaterId) {
                $this->deleteCinemaById($cinema->getId());
            }
        }
    }




    public function deleteCinemaViewThree($id, $movieTheaterName)
    {
        $movieTheaterController = new MovieTheaterController();
        $this->deleteCinemaById($id);
        $movieTheaterController->viewCreateMovieTheaterThree($movieTheaterName);
    }

    public function getSeats($idCinemaName)
    {

        $cinemaList = $this->cinemaDAO->getAll();

        $result = null;

        foreach ($cinemaList as $cinema) {
            if ($cinema->getId() == $idCinemaName) {
                $result = $cinema->countSeats();
                break;
            }
        }
        return $result;
    }

    public function getCinemaById($id){
        $cinemaList = $this->cinemaDAO->getAll();
        $result = null;
        foreach($cinemaList as $cinema){
            if($cinema->getId() == $id){
                $result = $cinema;
            break;
            }
        }
        return $result;
    }
}
