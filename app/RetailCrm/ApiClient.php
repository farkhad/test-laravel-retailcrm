<?php
namespace App\RetailCrm;

use RetailCrm\Api\Interfaces\ClientExceptionInterface;
use RetailCrm\Api\Enum\CountryCodeIso3166;
use RetailCrm\Api\Enum\Customers\CustomerType;
use RetailCrm\Api\Factory\SimpleClientFactory;
use RetailCrm\Api\Interfaces\ApiExceptionInterface;
use RetailCrm\Api\Model\Entity\Orders\Delivery\OrderDeliveryAddress;
use RetailCrm\Api\Model\Entity\Orders\Delivery\SerializedOrderDelivery;
use RetailCrm\Api\Model\Entity\Orders\Items\Offer;
use RetailCrm\Api\Model\Entity\Orders\Items\OrderProduct;
use RetailCrm\Api\Model\Entity\Orders\Items\PriceType;
use RetailCrm\Api\Model\Entity\Orders\Items\Unit;
use RetailCrm\Api\Model\Entity\Orders\Order;
use RetailCrm\Api\Model\Entity\Orders\Payment;
use RetailCrm\Api\Model\Entity\Orders\SerializedRelationCustomer;
use RetailCrm\Api\Model\Request\Orders\OrdersCreateRequest;
use RetailCrm\Api\Enum\Customers;
use RetailCrm\Api\Model\Entity\CustomersCorporate\CustomerCorporate;
use RetailCrm\Api\Model\Filter\Customers\CustomerFilter;
use RetailCrm\Api\Model\Filter\Store\ProductFilterType;
use RetailCrm\Api\Model\Request\Customers\CustomersRequest;
use RetailCrm\Api\Model\Request\Store\ProductsRequest;
use RetailCrm\Api\Model\Response\References\StoresResponse;

class ApiClient
{
    public $client;

    public function __construct()
    {
        $this->client = SimpleClientFactory::createClient(
            env('RETAILCRM_API_URL'),
            env('RETAILCRM_API_KEY')
        );
    }

    /**
     * Find customers by name. Customer name is represented as First Name, Last Name, Middle Name.
     * RetailCrm searches for name in each of these fields.
     *
     * @param string $customerName
     *
     * @return array
     * @throws \RetailCrm\Api\Interfaces\ApiExceptionInterface
     * @throws \RetailCrm\Api\Interfaces\ClientExceptionInterface
     */
    public function findCustomersByName(string $customerName = ''): array
    {
        $customersResponse = new CustomersRequest();
        $customersResponse->filter = new CustomerFilter();
        $customersResponse->filter->name = $customerName;

        $customersResponse = $this->client->customers->list($customersResponse);

        return $customersResponse->customers;
    }

    /**
     * Find "active" products by manufacturer (article & manufacturer).
     *
     * @param array $filter
     *
     * @return array
     * @throws \RetailCrm\Api\Interfaces\ApiExceptionInterface
     * @throws \RetailCrm\Api\Interfaces\ClientExceptionInterface
     */
    public function findProducts(array $filter = []): array
    {
        $productsResponse = new ProductsRequest();
        $productsResponse->filter = new ProductFilterType();

        foreach ($filter as $k => $v) {
            // filter[name] -- Название/артикул товара либо артикул/штрихкод торгового предложения
            $productsResponse->filter->$k = $v;
        }

        $productsResponse = $this->client->store->products($productsResponse);

        return $productsResponse->products;
    }

    /**
     *
     * @param array $orderData
     *
     * @return int
     * @throws \RetailCrm\Api\Interfaces\ApiExceptionInterface
     * @throws \RetailCrm\Api\Interfaces\ClientExceptionInterface
     */
    public function placeOrder(array $orderData = []): int
    {
        $ordersRequest = new OrdersCreateRequest();
        $order = new Order();

        // Attach product item to new order
        $products = $this->findProducts($orderData['productFilter']);
        if (count($products) > 0 && count($products[0]->offers) > 0) {
            $offer = $products[0]->offers[0];

            if (empty($offer->displayName)) {
                $offer->displayName = $offer->name;
            }

            $item = new OrderProduct();

            $item->offer = $offer;

            $item->priceType = count($offer->prices) > 0
                ? new PriceType($offer->prices[0]->priceType)
                : new PriceType('base');

            $item->quantity = 1;
            $item->purchasePrice = $offer->purchasePrice;

            $order->items = [$item];
        }

        $order->orderType = $orderData['orderType'];
        $order->orderMethod = $orderData['orderMethod'];
        $order->countryIso = CountryCodeIso3166::RUSSIAN_FEDERATION;

        $customers = $this->findCustomersByName($orderData['customerName']);
        if (count($customers) > 0) {
            $order->customer = SerializedRelationCustomer::withIdAndType($customers[0]->id, CustomerType::CUSTOMER);
        }

        $customerNameParts = explode(" ", $orderData['customerName']);
        if (!empty($customerNameParts[1])) {
            $order->firstName = $customerNameParts[1];
            $order->lastName = $customerNameParts[0];
            $order->patronymic = @$customerNameParts[2];
        } else {
            $order->firstName = $orderData['customerName'];
        }

        $order->status = $orderData['status'];
        $order->customerComment = $orderData['customerComment'];

        $order->number = $orderData['number'];

        $ordersRequest->order = $order;
        $ordersRequest->site = $orderData['site'];

        $ordersResponse = $this->client->orders->create($ordersRequest);

        return $ordersResponse->order->id;
    }

    /**
     *
     * @return void
     */
    public function printLatestOrders()
    {
        try {
            $ordersResponse = $this->client->orders->list();
        } catch (ApiExceptionInterface | ClientExceptionInterface $exception) {
            echo $exception; // Every ApiExceptionInterface instance should implement __toString() method.
            exit(-1);
        }

        echo "<ol>";
        foreach ($ordersResponse->orders as $order) {
            echo "<li style=\"margin-bottom:20px;border:1px solid black;width:50%\"><div style=\"padding:4px;height:100px;overflow-x:hidden;overflow-y:auto\">";
            echo "<pre style=\"white-space: pre-wrap\">";
            print_r($order);
            echo "</pre></div></li>";
        }
        echo "</ol>";
    }
}
