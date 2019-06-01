<?php

/**
 * ProductController
 * Controls everything that is product-related
 */
class ProductController extends Controller
{
    public function __construct() {
        if (isset($_POST['saveNewProduct'])) {
            Product::new();
        }
    }
}