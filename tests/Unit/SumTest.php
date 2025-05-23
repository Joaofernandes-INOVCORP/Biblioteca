<?php

function sum ($a, $b){
    return $a + $b;
}

describe('sum', function(){
    
    it('may sum integers', function(){
        $result = sum(1, 2);

        expect($result)->toBe(3);
    });

    it('may sum floats', function(){
        $result = sum(1.5, 2.5);

        expect($result)->toBe(4.0);
    });
});

