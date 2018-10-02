<?php

namespace Core;

/**
 * CRUD Interface.
 *
 * @author  Ozhan Duran <ozhan@hotmail.com>
 *
 * @version 1.0.0
 *
 * @since 25.12.2017
 */
interface CRUDTemplate
{
    /**
     * Display a listing of the resource.
     * Method: GET.
     */
    public function index();

    /**
     * Show the form for creating a new resource.
     * Method: GET.
     */
    public function create();

    /**
     * Insert a newly created resource in storage.
     * Method: POST.
     */
    public function insert();

    /**
     * Display the ID resource.
     * Method: GET.
     */
    public function show($id);

    /**
     * Show the form for editing the ID resource.
     * Method: GET.
     */
    public function edit($id);

    /**
     * Update the ID resource in storage.
     * Method: PUT.
     */
    public function update($id);

    /**
     * Remove the ID resource from storage.
     * Method: DELETE.
     */
    public function delete($id);
}
