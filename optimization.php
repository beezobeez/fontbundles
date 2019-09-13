<?php

class DatabaseManager {

    /**
     * @param $query
     * @param array $params
     * @return array
     */
    public function getData($query, array $params): array {

        //Get calling function name
        $ex = new Exception();
        $trace = $ex->getTrace();
        $final_call = $trace[1];

        $results = [];

        switch($final_call['function']) {

            case 'getProducts':
                $results = [
                    ['id'=>1, 'price'=>1.50],
                    ['id'=>2, 'price'=>2.50],
                    ['id'=>3, 'price'=>3.00]
                ];
                break;
            case 'getOrderItemsCount':
                $results = [0, 1, 2, 3, 4];
                break;
            case 'getProductTags':
                $results = [
                    ['tag_name'=>'Christmas']
                ];
                break;
            case 'getTotalUniqueTags':
                $results = [0, 1, 2, 3, 4, 5, 6, 7];
                break;
            default:
                break;
        }

        return $results;
    }
}

class StoreManager {

    /**
     * @var float CHRISTMAS_MULTIPLIER
     */
    const CHRISTMAS_MULTIPLIER = 1.01;

    /**
     * @var float FREE_MULTIPLIER
     */
    const FREE_MULTIPLIER = 0.5;

    /**
     * @var DatabaseManager $dbManager
     */
    protected $dbManager = null;

    /**
     * @param DatabaseManager $dbManager
     */
    public function __construct(DatabaseManager $dbManager)
    {
        $this->dbManager = $dbManager;
    }

    /**
     * @param int $storeId
     *
     * @return float
     */
    public function calculateStoreEarnings(int $storeId): float
    {
        $storeTotal = 0;

        foreach ($this->getProducts($storeId) as $product) {

            $productTotal = 0;
            $productTotal += $this->getOrderItemsCount($product['id']) * $product['price'];

            $tags = $this->getProductTags($product['id']);
            $tagCount = $this->getTotalUniqueTags();
            $productTotal *= (1 + count($tags) / $tagCount);

            foreach ($tags as $tag) {

                switch($tag['tag_name']) {

                    case 'Christmas':
                        $productTotal *= self::CHRISTMAS_MULTIPLIER;
                        break;
                    case 'Free':
                        $productTotal *= self::FREE_MULTIPLIER;
                        break;
                    default:
                        break;
                }
            }

            $storeTotal += $productTotal;
        }

        return round($storeTotal, 2);
    }

    /**
     * @param int $storeId
     *
     * @return array
     */
    protected function getProducts(int $storeId): array
    {
        $query = 'SELECT * FROM Product WHERE store_id = :store';

        return $this->dbManager->getData($query, ['store' => $storeId]);
    }

    /**
     * @param int $productId
     *
     * @return int
     */
    protected function getOrderItemsCount(int $productId): int
    {
        $query = 'SELECT COUNT(DISTINCT name) as count FROM OrderItem WHERE product_id = :product';

        $result = $this->dbManager->getData($query, ['product' => $productId]);

        return count($result);
    }

    /**
     *
     * @param int $productId
     *
     * @return array
     */
    protected function getProductTags(int $productId): array
    {
        $query = 'SELECT * FROM Tag WHERE id IN (SELECT tag_id FROM TagConnect WHERE product_id = :product)';

        return $this->dbManager->getData($query, ['product' => $productId]);
    }

    /**
     * @return int
     */
    protected function getTotalUniqueTags(): int
    {
        $query = 'SELECT COUNT(DISTINCT name) as count FROM Tag';

        $result = $this->dbManager->getData($query, []);

        return count($result);
    }
}

$storeManager = new StoreManager(new DatabaseManager());

//39.77
echo $storeManager->calculateStoreEarnings(1);
