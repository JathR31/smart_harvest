<?php

test('the application returns a successful response', function () {
    $response = $this->get('/this-route-does-not-exist');

    $response->assertStatus(404);
});
