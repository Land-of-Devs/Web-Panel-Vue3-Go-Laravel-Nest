<?php
namespace App\Interfaces;

interface ApiTicketInterface {
    public function all();
    public function setStatus($ids);
    public function delete($ids);
}