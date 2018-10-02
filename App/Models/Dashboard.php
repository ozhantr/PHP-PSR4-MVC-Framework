<?php

namespace Model;

use Core\BaseModel;

class Dashboard extends BaseModel
{
    public function getCustomer()
    {
        $sql = 'SELECT * FROM test_app.customer';
        $result = $this->db->query($sql);
        $row = $result->fetch_assoc();

        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }

        $this->db->freeResult();

        return $rows;
    }

    public function getTotalCustomer()
    {
        $sql = 'SELECT COUNT(*) AS total FROM test_app.customer';
        $result = $this->db->query($sql);
        $row = $result->fetch_assoc();
        $this->db->freeResult();

        return $row['total'];
    }

    public function getOrder()
    {
        $sql = 'SELECT * FROM test_app.order';
        $result = $this->db->query($sql);

        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }

        $this->db->freeResult();

        return $rows;
    }

    public function getTotalOrder()
    {
        $sql = 'SELECT COUNT(*) AS total FROM test_app.order';
        $result = $this->db->query($sql);
        $row = $result->fetch_assoc();
        $this->db->freeResult();

        return $row['total'];
    }

    public function getTotalRevenue()
    {
        $sql = 'SELECT SUM(price) AS total FROM test_app.order_item';
        $result = $this->db->query($sql);
        $row = $result->fetch_assoc();
        $this->db->freeResult();

        return $row['total'];
    }

    public function getTotalOrderItem()
    {
        $sql = 'SELECT SUM(quantity) AS total FROM test_app.order_item';
        $result = $this->db->query($sql);
        $row = $result->fetch_assoc();
        $this->db->freeResult();

        return $row['total'];
    }

    public function getOrderItem()
    {
        $sql = 'SELECT * FROM test_app.order_item';
        $result = $this->db->query($sql);

        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }

        $this->db->freeResult();

        return $rows;
    }

    public function getOrderReport($startDate, $endDate)
    {
        $startDate = $this->db->safe($startDate);
        $endDate = $this->db->safe($endDate);

        $sql = "SELECT purchase_date AS date, COUNT(*) AS total FROM test_app.order 
                WHERE purchase_date 
                BETWEEN $startDate AND $endDate 
                GROUP BY purchase_date";

        $result = $this->db->query($sql);

        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }

        $this->db->freeResult();

        return $rows;
    }

    public function getCustomerReport($startDate, $endDate)
    {
        $startDate = $this->db->safe($startDate);
        $endDate = $this->db->safe($endDate);

        $sql = "SELECT register_date AS date, COUNT(*) AS total FROM test_app.customer 
                WHERE register_date 
                BETWEEN $startDate AND $endDate 
                GROUP BY register_date";

        $result = $this->db->query($sql);

        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }

        $this->db->freeResult();

        return $rows;
    }
}
