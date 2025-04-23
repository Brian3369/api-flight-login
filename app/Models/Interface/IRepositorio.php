<?php
namespace App\Models\Interface;

interface IRepositorio{
    public function get_all($page);
    public function get_by_id($id);
    public function create();
    public function update();
    public function delete();
}