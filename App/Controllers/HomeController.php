<?php

namespace App\Controllers;

use Core\BaseController;
use Model\Dashboard;

class HomeController extends BaseController
{
    protected $errorCodes = [
        2001 => 'Incorrect Start Date!',
        2002 => 'Incorrect End Date!',
    ];

    /**
     * @description(Default Method)
     * @method("get")
     */
    public function index()
    {
        $request = new Dashboard();
        $data['total_customer'] = $request->getTotalCustomer();
        $data['total_order'] = $request->getTotalOrder();
        $data['total_revenue'] = number_format($request->getTotalRevenue(), 2);
        $data['total_order_item'] = number_format($request->getTotalOrderItem());
        $this->render('Dashboard', $data);
    }

    /**
     * @description(Get Customers);
     * @method("get")
     */
    public function customer()
    {
        $request = new Dashboard();
        $data = $request->getCustomer();
        $this->render('Dashboard', $data);
    }

    /**
     * @description(Get Total Customers);
     * @method("get")
     */
    public function totalCustomer()
    {
        $request = new Dashboard();
        $data = $request->getTotalCustomer();
        $this->render('Dashboard', $data);
    }

    /**
     * @description(Get Order Report - REST API);
     * @method("get")
     */
    public function orderReport($startDate = null, $endDate = null)
    {
        if (null === $startDate && null === $endDate) {
            $startDate = date('Y-m-d', strtotime('-30 day'));
            $endDate = date('Y-m-d');
        }

        if (!$this->checkDate($startDate)) {
            $this->displayError(2001);
        }

        if (!$this->checkDate($endDate)) {
            $this->displayError(2002);
        }

        $request = new Dashboard();
        $data = $request->getOrderReport($startDate, $endDate);
        $this->rest($data);
    }

    /**
     * @description(Get Customer Report - REST API);
     * @method("get")
     */
    public function customerReport($startDate = null, $endDate = null)
    {
        if (null === $startDate && null === $endDate) {
            $startDate = date('Y-m-d', strtotime('-30 day'));
            $endDate = date('Y-m-d');
        }

        if (!$this->checkDate($startDate)) {
            $this->displayError(2001);
        }

        if (!$this->checkDate($endDate)) {
            $this->displayError(2002);
        }

        $request = new Dashboard();
        $data = $request->getCustomerReport($startDate, $endDate);
        $this->rest($data);
    }

    /**
     * @description(Get Chart Data - REST API);
     * @method("get")
     */
    public function chartData($startDate = null, $endDate = null)
    {
        if (null === $startDate && null === $endDate) {
            $startDate = date('Y-m-d', strtotime('-30 day'));
            $endDate = date('Y-m-d');
        }

        if (!$this->checkDate($startDate)) {
            $this->displayError(2001);
        }

        if (!$this->checkDate($endDate)) {
            $this->displayError(2002);
        }

        $request = new Dashboard();
        $data['customer'] = $request->getCustomerReport($startDate, $endDate);

        $tempData = [];
        foreach ($data['customer'] as $val) {
            $tempData[$val['date']]['customer'] = $val['total'];
        }

        $data['order'] = $request->getOrderReport($startDate, $endDate);
        foreach ($data['order'] as $val) {
            $tempData[$val['date']]['order'] = $val['total'];
        }

        $chartData = [];
        foreach ($tempData as $key => $val) {
            $chartData[] = [
                'date' => $key,
                'customer' => $val['customer'] ?? 0,
                'order' => $val['order'] ?? 0,
            ];
        }

        $this->rest($chartData);
    }

    /**
     * @description(Multi Method Test);
     * @method("post", "get");
     */
    public function multiTest()
    {
        echo 'Only POST and GET methods accept!';
    }

    private function checkDate($date, $format = 'Y-m-d')
    {
        $d = \DateTime::createFromFormat($format, $date);

        return $d && $d->format($format) == $date;
    }
}
