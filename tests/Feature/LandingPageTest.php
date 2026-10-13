<?php

use Inertia\Testing\AssertableInertia as Assert;

// Halaman depan dapat dibuka tamu dan memakai komponen Landing
test('halaman depan menampilkan landing page', function () {
    $this->get('/')
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page->component('Landing'));
});
