<?php

interface IUser
{
    /**
     * @return array
     */
    public function getInfo(): array;
}

interface IUserManager
{
    /**
     * @param IUser $user
     * @return string
     */
    public function getUserInfo(IUser $user): string;
}

class UserManager implements IUserManager
{
    /**
     * @param IUser $user
     * @return string
     */
    public function getUserInfo(IUser $user): string
    {
        $infoString = "";
        $infoArray = $user->getInfo();

        foreach($infoArray as $key => $value) {

            $infoString .= "$key: $value\r\n";
        }

        return "<pre>$infoString</pre>";
    }
}

class User implements IUser
{
    /**
     * @var int $id
     */
    protected $id = null;

    /**
     * @var string $name
     */
    protected $name = null;

    /**
     * User constructor.
     * @param int $id
     * @param string $name
     */
    public function __construct(int $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    /**
     * @return array
     */
    public function getInfo(): array
    {
        return [
            'id'    => $this->id,
            'name'  => $this->name,
        ];
    }
}

class Customer extends User
{
    /**
     * @var string USER_TYPE
     */
    const USER_TYPE = 'Customer';

    /**
     * @var string $balance
     */
    protected $balance = null;

    /**
     * @var string $purchase_count
     */
    protected $purchase_count = null;

    /**
     * CustomerUser constructor.
     * @param int $id
     * @param string $name
     * @param string $balance
     * @param string $purchase_count
     */
    public function __construct(int $id, string $name, string $balance, string $purchase_count)
    {
        parent::__construct($id, $name);

        $this->balance = $balance;
        $this->purchase_count= $purchase_count;
    }

    /**
     * @return array
     */
    public function getInfo(): array
    {
        return [
            'user-type'      => self::USER_TYPE,
            'id'             => $this->id,
            'name'           => $this->name,
            'balance'        => $this->balance,
            'purchase-count' => $this->purchase_count,
        ];
    }
}

class Seller extends User
{
    /**
     * @var string USER_TYPE
     */
    const USER_TYPE = 'Seller';

    /**
     * @var string $earnings_balance
     */
    protected $earnings_balance = null;

    /**
     * @var string $product_count
     */
    protected $product_count = null;

    /**
     * SellerUser constructor.
     * @param int $id
     * @param string $name
     * @param string $earnings_balance
     * @param string $product_count
     */
    public function __construct(int $id, string $name, string $earnings_balance, string $product_count)
    {
        parent::__construct($id, $name);

        $this->earnings_balance = $earnings_balance;
        $this->product_count= $product_count;
    }

    /**
     * @return array
     */
    public function getInfo(): array
    {
        return [
            'user-type'        => self::USER_TYPE,
            'id'               => $this->id,
            'name'             => $this->name,
            'earnings-balance' => $this->earnings_balance,
            'product-count'    => $this->product_count,
        ];
    }
}

class Administrator extends User
{
    /**
     * @var string USER_TYPE
     */
    const USER_TYPE = 'Administrator';

    /**
     * @var string $permissions
     */
    protected $permissions = null;

    /**
     * AdministratorUser constructor.
     * @param int $id
     * @param string $name
     * @param string $permissions
     */
    public function __construct(int $id, string $name, string $permissions)
    {
        parent::__construct($id, $name);

        $this->permissions = $permissions;
    }

    /**
     * @return array
     */
    public function getInfo(): array
    {
        return [
            'user-type'   => self::USER_TYPE,
            'id'          => $this->id,
            'name'        => $this->name,
            'permissions' => $this->permissions,
        ];
    }
}

$customer = new Customer(1, 'John', '100.55', '34');
$seller = new Seller(2, 'Alex', '550.87', '781');
$administrator = new Administrator(3, 'Arnold', '{reports, sales, users}');

$userManager = new UserManager();

echo $userManager->getUserInfo($customer);
echo $userManager->getUserInfo($seller);
echo $userManager->getUserInfo($administrator);
