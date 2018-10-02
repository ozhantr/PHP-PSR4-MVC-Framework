<?php

namespace Core;

use mysqli;

/**
 * DB Class.
 *
 * @author  Ozhan Duran <ozhan@hotmail.com>
 *
 * @version 1.0.0
 */
class DB
{
    private static $instance = null;

    private $db = null;

    private function __construct()
    {
        $this->db = new mysqli('127.0.0.1', 'dbUser', 'dbPassword', 'test_app');

        /* Check connection. */
        if ($this->db->connect_errno) {
            printf("Connect failed: %s\n", $this->db->connect_error);
            exit();
        }

        $this->db->set_charset('utf8mb4');
    }

    public static function getInstance()
    {
        if (null == self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function query($sql)
    {
        return $this->db->query($sql);
    }

    public function safe($input)
    {
        $input = "'" . $this->db->real_escape_string($input) . "'";

        return $input;
    }

    public function freeResult()
    {
        while ($this->db->more_results()) {
            $this->db->next_result();
            if ($result = $this->db->store_result()) {
                $result->free();
            }
        }
    }

    /**
     * Prevent duplication of connection.
     */
    private function __clone()
    {
    }
}
