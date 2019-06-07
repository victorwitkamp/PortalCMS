<?php

class Product
{
    public static function getProductById($Id) {
        $stmt = DB::conn()->prepare("SELECT * FROM products WHERE id = ? LIMIT 1");
        $stmt->execute([$Id]);
        if (!$stmt->rowCount() == 1) {
            return false;
        } else {
            return $stmt->fetch();
        }
    }
    public static function getAllProducts()
    {
        $stmt = DB::conn()->prepare("SELECT * FROM Products");
        $stmt->execute();
        if (!$stmt->rowCount() > 0) {
            return false;
        } else {
            return $stmt->fetchAll();
        }
    }
    public static function new()
    {
        $name = Request::post('name', true);
        $price = Request::post('price', true);
        $type = Request::post('type', true);

        $stmt = DB::conn()->prepare("SELECT id FROM products WHERE name = ?");
        $stmt->execute([$name]);
        if (!$stmt->rowCount() == 0) {
            Session::add('feedback_negative', "Productnaam bestaat al.");
        } else {
            if (!self::addProductAction($name, $price, $type)) {
                Session::add('feedback_negative', "Toevoegen van product mislukt.");
            } else {
                Session::add('feedback_positive', "Product toegevoegd.");
                Redirect::to("rental/products/");
            }
        }
    }

    public static function addProductAction($name, $price, $type) {
        $stmt = DB::conn()->prepare("INSERT INTO products(id, name, price, type) VALUES (NULL,?,?,?)");
        $stmt->execute([$name, $price, $type]);
        if (!$stmt) {
            return false;
        }
        return true;
    }

    public static function deleteProduct($id)
    {
        $stmt = DB::conn()->prepare("SELECT * FROM products where id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $count = count($result);
        if ($count > 0) {
            $stmt = DB::conn()->prepare("DELETE FROM products WHERE id = ?");
            if (!$stmt->execute([$id])) {
                Session::add('feedback_negative', "Verwijderen van product mislukt.");
                return false;
            } else {
                Session::add('feedback_positive', "Product verwijderd.");
                return true;
            }
        } else {
            Session::add('feedback_negative', "Verwijderen van product mislukt.<br>Product bestaat niet.");
            return false;
        }
    }
}