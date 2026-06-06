<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    // Menambahkan properti ini memberi tahu Laravel untuk otomatis menjalankan DatabaseSeeder setiap kali testing dimulai
    protected $seed = true; 
}