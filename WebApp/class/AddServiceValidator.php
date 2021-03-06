<?php

class AddServiceValidator extends Form
{
    private array $fields =
        ['category', 'serviceName', 'price', 'serviceDescription'];

    public function __construct(array $post, ?array $file = null)
    {
        parent::__construct($post, $file, 'images/services/');
    }

    public function validateInputs(): void
    {
        foreach ($this->fields as $field)
        {
            if (empty($this->data[$field]))
            {
                $this->addError("{$field} is empty", $field);
            } else
            {
                $this->addValid($field);
            }
        }
        if (!empty($this->error))
        {
            $_SESSION['error'] = $this->error;
            $_SESSION['valid'] = $this->valid;
            return;
        }
        $this->validateName('serviceName');
        $this->validateLength('serviceName');
        $this->validateLength('serviceDescription');
        $this->uniqueServiceName();
        $this->checkPrice();
        if ($this->validateImage)
        {
            if ($this->checkImages() === false)
            {
                $_SESSION['valid'] = $this->valid;
                $_SESSION['error'] = $this->error;
                return;
            }
        }
        if (!empty($this->error))
        {
            $_SESSION['error'] = $this->error;
            $_SESSION['valid'] = $this->valid;
            return;
        }
        $error = $this->validateImage->uploadImage();
        if ($error !== 'ok')
        {
            $this->addError($error, 'image');
            $_SESSION['error'] = $this->error;
            $_SESSION['valid'] = $this->valid;
        }
        if (!empty($this->error))
        {
            return;
        }
        $this->checkCategory();

        $_SESSION['error'] = $this->error;
        $_SESSION['valid'] = $this->valid;
    }


    private function uniqueServiceName(): void
    {
        $q = $this->dbManager->query('select name from service where name= ?', [$this->data['serviceName']]);
        $res = $q->fetch();
        if (empty($res))
        {
            $this->addValid('serviceName');
            return ;
        }
        $this->addError('the service already exist','serviceName');
    }



    private function checkCategory():void {
        $this->data['category'] = ucfirst(strtolower($this->data['category']));
        $q = $this->dbManager->query('select idCategory from categoryservice where idCategory = ?', [$this->data['category']]);
        $res = $q->fetch();
        if (!empty($res)){
            $this->addDataService();
            return;
        }
            $this->addError("this category doesn't exist {$this->data['category']}", 'category');

    }
    private function checkPrice():void
    {
        if (is_numeric($this->data['price']) && $this->data['price'] > 0){
            $this->addValid('price');
            return;
        }
        $this->addError('the price can only be a number non negative','price' );
    }
    private function addDataService(): void
    {
        $demo = isset($this->data['demo']);
        $q = $this->dbManager->query('INSERT INTO service (idService,category,name, price, image, demo,description)
             VALUES (:idService,:idCategory,:service,:price,:image,:demo,:description)',
            [
                ':idService' => DbManager::v4(),
                ':idCategory' => $this->data['category'],
                ':service' => $this->data['serviceName'],
                ':price' => $this->data['price'],
                ':image' => $this->validateImage->getFullpath(),
                ':demo' => $demo,
                ':description' => $this->data['serviceDescription']
            ]
        );

        if($q)
        {
            $this->valid['request'] = 'Service Successfully Added';
            return;
        }
        $this->error['request'] = 'There is a technical problem try later';

    }

    private function validateLength($field):void
    {
        if (strlen($this->data[$field])>150){
                $this->addError("the {$field} too long",$field);
            return;
        }
        $this->addValid($field);
    }
}
